<?php

/**
 *
 * @link              https://matteodefilippis.com
 * @copyright         Copyright (c)2023 Matteo De Filippis
 * @since             0.1.0
 * @package           Csv_To_Acf
 *
 * @wordpress-plugin
 * Plugin Name:       CSV to ACF Importer
 * Plugin URI:        https://matteodefilippis.com/csv-to-acf/
 * Description:       Import CSV data into new pages creating proper ACF fields
 * Version:           0.1.0
 * Author:            Matteo De Filippis
 * Author URI:        https://matteodefilippis.com/
 * License:           GPL v3 and later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       csv-to-acf
 * Domain Path:       /languages
 */

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 */
define('CSV_TO_ACF_VERSION', '0.1.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-csv-to-acf-activator.php
 */
function activate_csv_to_acf()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-csv-to-acf-activator.php';
	Csv_To_Acf_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-csv-to-acf-deactivator.php
 */
function deactivate_csv_to_acf()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-csv-to-acf-deactivator.php';
	Csv_To_Acf_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_csv_to_acf');
register_deactivation_hook(__FILE__, 'deactivate_csv_to_acf');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-csv-to-acf.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_csv_to_acf()
{

	$plugin = new Csv_To_Acf();
	$plugin->run();
}
run_csv_to_acf();
