<?php
/**
 * Enables JS popups within Divi.
 *
 * @package     Popups_For_Divi
 * @author      Philipp Stracker
 * @copyright   2020 Philipp Stracker
 * @license     GPL-2.0-or-later
 *
 * Plugin Name: Popups for Divi
 * Plugin URI:  https://divimode.com/divi-popup/?utm_source=wpadmin&utm_medium=link&utm_campaign=popups-for-divi
 * Description: Finally, a simple and intuitive way to add custom popups to your Divi pages!
 * Author:      divimode.com
 * Author URI:  https://divimode.com/?utm_source=wpadmin&utm_medium=link&utm_campaign=popups-for-divi
 * Created:     30.12.2017
 * Version:     2.3.6
 * Text Domain: divi-popup
 * Domain Path: /lang
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright (C) 2020 Philipp Stracker
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) || die();

/**
 * A new version value will force refresh of CSS and JS files for all users.
 */
define( 'DIVI_POPUP_VERSION', '2.3.6' );

/**
 * Absolute path and file name of the main plugin file.
 *
 * @var string
 */
define( 'DIVI_POPUP_PLUGIN_FILE', __FILE__ );

/**
 * Basename of the WordPress plugin. I.e., "plugin-dir/plugin-file.php".
 *
 * @var string
 */
define( 'DIVI_POPUP_PLUGIN', plugin_basename( DIVI_POPUP_PLUGIN_FILE ) );

/**
 * Absolute path to the plugin folder, with trailing slash.
 *
 * @var string
 */
define( 'DIVI_POPUP_PATH', plugin_dir_path( DIVI_POPUP_PLUGIN_FILE ) );

/**
 * Absolute URL to the plugin folder, with trailing slash.
 *
 * @var string
 */
define( 'DIVI_POPUP_URL', plugin_dir_url( DIVI_POPUP_PLUGIN_FILE ) );

/**
 * Getter function that returns the main Popups_For_Divi instance.
 *
 * @since 2.0.4
 * @return PFD_App The main application instance.
 */
function divi_popup() {
	if ( empty( $GLOBALS['divi_popup'] ) ) {
		$GLOBALS['divi_popup'] = new PFD_App();
		$GLOBALS['divi_popup']->setup_on( 'plugins_loaded' );
	}

	return $GLOBALS['divi_popup'];
}

/**
 * Instead of using an autoloader that dynamically loads our classes, we have decided
 * to include all dependencies during initialization.
 *
 * We have the following reasons for this:
 *
 * 1. It makes the plugin structure more transparent: We can see all used files here.
 * 2. The number of files is so small that autoloading does not save a lot of
 *    resources.
 * 3. In a production build we want to make sure that files are always loaded in the
 *    same order, at the same time.
 * 4. Every file is treated equal: No different treatment for classes vs function
 *    files.
 *
 * @since 2.2.2
 */
function divi_popup_load_core() {
	// Load helpers.
	require_once DIVI_POPUP_PATH . 'includes/helper/plugin-compatibility.php';

	// Load application modules.
	require_once DIVI_POPUP_PATH . 'includes/core/class-pfd-component.php';
	require_once DIVI_POPUP_PATH . 'includes/core/class-pfd-app.php';
	require_once DIVI_POPUP_PATH . 'includes/core/class-pfd-asset.php';
	require_once DIVI_POPUP_PATH . 'includes/core/class-pfd-editor.php';
	require_once DIVI_POPUP_PATH . 'includes/core/class-pfd-onboarding.php';
}

// Load all dependencies.
divi_popup_load_core();

// Initialize the application.
divi_popup();
