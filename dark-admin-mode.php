<?php
/**
 * Plugin Name: Dark Admin Mode
 * Plugin URI: https://www.binarytemplar.com/dark-admin-mode
 * Description: Adds a dark Administration Color Scheme, selected automatically for the user who activates the plugin. Other users can choose it under Users → Profile.
 * Version: 1.0.0
 * Requires at least: 7.0
 * Requires PHP: 7.4
 * Author: Dave McHale
 * Author URI: https://www.binarytemplar.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dark-admin-mode
 * Update URI: https://github.com/dmchale/dark-admin-mode
 *
 * @package Dark_Admin_Mode
 */

namespace BinaryTemplar\DarkAdminMode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Keep in sync with the Version header above and the Stable tag in readme.txt. Busts the stylesheet cache.
const VERSION     = '1.0.0';
const PLUGIN_FILE = __FILE__;

require_once __DIR__ . '/includes/class-color-scheme.php';
require_once __DIR__ . '/includes/class-user-preference.php';
require_once __DIR__ . '/includes/class-updater.php';

Color_Scheme::register();
User_Preference::register();
Updater::register();

register_activation_hook( __FILE__, array( User_Preference::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( User_Preference::class, 'deactivate' ) );
