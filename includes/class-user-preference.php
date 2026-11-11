<?php
/**
 * User preference: selects the scheme on activation and gives users their old scheme back on deactivation.
 *
 * @package Dark_Admin_Mode
 */

namespace BinaryTemplar\DarkAdminMode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remembers each user's previous scheme whenever they switch to ours, however they switch
 * (activation or Profile), so deactivating can put it back.
 */
final class User_Preference {

	/**
	 * User meta holding the scheme to restore. Empty means "core default" (no `admin_color` set).
	 */
	const BACKUP_META = '_dark_admin_mode_previous_scheme';

	/**
	 * Hooks the backup into every change of `admin_color`.
	 */
	public static function register() {
		add_filter( 'update_user_metadata', array( self::class, 'remember_previous' ), 10, 4 );
	}

	/**
	 * Before a user's scheme is set to ours, saves what they had. Never short-circuits the update.
	 *
	 * @param null|bool $check    Short-circuit value from earlier filters; passed through.
	 * @param int       $user_id  User being updated.
	 * @param string    $meta_key Meta key being updated.
	 * @param mixed     $value    New meta value.
	 * @return null|bool
	 */
	public static function remember_previous( $check, $user_id, $meta_key, $value ) {
		if ( null !== $check || 'admin_color' !== $meta_key || Color_Scheme::KEY !== $value ) {
			return $check;
		}

		$previous = get_user_meta( $user_id, 'admin_color', true );
		if ( Color_Scheme::KEY !== $previous ) {
			update_user_meta( $user_id, self::BACKUP_META, (string) $previous );
		}

		return $check;
	}

	/**
	 * Activation: selects the scheme for the activating user only. Activating with no user
	 * (e.g. WP-CLI without --user) changes nobody's preference.
	 */
	public static function activate() {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			update_user_meta( $user_id, 'admin_color', Color_Scheme::KEY );
		}
	}

	/**
	 * Deactivation: restores everyone still using the scheme and removes every backup.
	 * `admin_color` is network-wide user meta, so on multisite only a network deactivation
	 * restores: a single site can't tell whether other sites still use the plugin, and a
	 * leftover key is harmless there because core falls back to its default scheme.
	 *
	 * @param bool $network_wide Whether the plugin is being network-deactivated.
	 */
	public static function deactivate( $network_wide = false ) {
		if ( is_multisite() && ! $network_wide ) {
			return;
		}

		$user_ids = get_users(
			array(
				'blog_id'    => 0,
				'fields'     => 'ID',
				'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- one-off on deactivation.
					'relation' => 'OR',
					array(
						'key'   => 'admin_color',
						'value' => Color_Scheme::KEY,
					),
					array(
						'key'     => self::BACKUP_META,
						'compare' => 'EXISTS',
					),
				),
			)
		);

		foreach ( $user_ids as $user_id ) {
			self::restore( (int) $user_id );
		}
	}

	/**
	 * Restores one user's previous scheme if they're still using ours; a later choice of
	 * another scheme is left alone. A missing backup means core's default. A backup that is no
	 * longer registered is restored anyway: core shows its default for it, and the user gets it
	 * back if that scheme returns.
	 *
	 * @param int $user_id User to restore.
	 */
	private static function restore( $user_id ) {
		if ( Color_Scheme::KEY === get_user_meta( $user_id, 'admin_color', true ) ) {
			$previous = (string) get_user_meta( $user_id, self::BACKUP_META, true );

			if ( '' !== $previous ) {
				update_user_meta( $user_id, 'admin_color', $previous );
			} else {
				delete_user_meta( $user_id, 'admin_color' );
			}
		}

		delete_user_meta( $user_id, self::BACKUP_META );
	}
}
