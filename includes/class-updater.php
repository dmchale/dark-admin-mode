<?php
/**
 * Updater: delivers new versions from GitHub Releases instead of wordpress.org.
 *
 * @package Dark_Admin_Mode
 */

namespace BinaryTemplar\DarkAdminMode;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p7\Vcs\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checks GitHub for the latest Release with Plugin Update Checker (bundled in lib/). The
 * plugin header's `Update URI` keeps core from ever offering a wordpress.org plugin with the
 * same slug as an update.
 */
final class Updater {

	/**
	 * Public GitHub repository that publishes releases.
	 */
	const REPOSITORY = 'https://github.com/dmchale/dark-admin-mode/';

	/**
	 * Release asset to install. GitHub's own source zips unpack to a versioned folder name,
	 * so a release without this asset is ignored rather than installed under the wrong slug.
	 */
	const ASSET_PATTERN = '/^dark-admin-mode\.zip$/';

	/**
	 * Starts the update checker.
	 */
	public static function register() {
		require_once dirname( __DIR__ ) . '/lib/plugin-update-checker/plugin-update-checker.php';

		$checker = PucFactory::buildUpdateChecker( self::REPOSITORY, PLUGIN_FILE, 'dark-admin-mode' );
		$checker->getVcsApi()->enableReleaseAssets( self::ASSET_PATTERN, Api::REQUIRE_RELEASE_ASSETS );
	}
}
