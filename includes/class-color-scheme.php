<?php
/**
 * Color scheme: registers the scheme and the small fixes that make it work smoothly.
 *
 * @package Dark_Admin_Mode
 */

namespace BinaryTemplar\DarkAdminMode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The dark Administration Color Scheme. Core stores each user's choice in their `admin_color`
 * meta and loads the stylesheet only for users who chose it, so the plugin never overrides anyone.
 */
final class Color_Scheme {

	/**
	 * Scheme key: stored in users' `admin_color` meta, so never change it after release.
	 */
	const KEY = 'dark-admin-mode';

	/**
	 * Hooks the scheme into WordPress.
	 */
	public static function register() {
		add_action( 'admin_init', array( self::class, 'register_scheme' ) );
		add_action( 'admin_color_scheme_picker', array( self::class, 'move_picker_option' ), 20 );
	}

	/**
	 * Adds the scheme to core's list (Users → Profile → Administration Color Scheme).
	 */
	public static function register_scheme() {
		wp_admin_css_color(
			self::KEY,
			__( 'Dark Admin Mode', 'dark-admin-mode' ),
			// Core adds its own `ver` (the WordPress version), so a separate argument busts our cache.
			add_query_arg( 'dark-admin-mode-ver', VERSION, plugins_url( 'assets/css/admin.css', PLUGIN_FILE ) ),
			array( '#1b1b1b', '#282828', '#333333', '#8ac7ff' ),
			array(
				'base'    => '#b5b5b5',
				'focus'   => '#8ac7ff',
				'current' => '#ffffff',
			)
		);
	}

	/**
	 * Moves our option to the front of the profile picker. Core sorts the picker itself, so this
	 * runs after it renders; moving the DOM node keeps visual, keyboard and screen-reader order aligned.
	 */
	public static function move_picker_option() {
		$script = <<<'JS'
(function () {
	const input = document.getElementById(%s);
	const picker = document.getElementById('color-picker');
	const option = input && input.closest('.color-option');
	const first = picker && picker.querySelector('.color-option');
	if (option && first && option !== first) {
		picker.insertBefore(option, first);
	}
})();
JS;

		wp_print_inline_script_tag( sprintf( $script, wp_json_encode( 'admin_color_' . self::KEY ) ) );
	}
}
