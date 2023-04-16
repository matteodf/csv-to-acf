<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://matteodefilippis.com
 * @copyright  Copyright (c)2023 Matteo De Filippis
 * @since      0.1.0
 *
 * @package    Csv_To_Acf
 * @subpackage Csv_To_Acf/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Csv_To_Acf
 * @subpackage Csv_To_Acf/admin
 * @author     Matteo De Filippis
 */

class Csv_To_Acf_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $csv_to_acf    The ID of this plugin.
	 */
	private $csv_to_acf;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $csv_to_acf       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($csv_to_acf, $version)
	{

		$this->csv_to_acf = $csv_to_acf;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Csv_To_Acf_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Csv_To_Acf_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->csv_to_acf, plugin_dir_url(__FILE__) . 'css/csv-to-acf-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->csv_to_acf . '-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Oswald:wght@300;400;600&display=swap', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Csv_To_Acf_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Csv_To_Acf_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->csv_to_acf, plugin_dir_url(__FILE__) . 'js/csv-to-acf-admin.js', array('jquery'), $this->version, true);
	}

	public function add_plugin_admin_menu()
	{
		add_menu_page('CSV to ACF Importer', 'CSV to ACF Importer', 'edit_pages', $this->csv_to_acf, array($this, 'display_plugin_setup_page'), 'dashicons-editor-table');
	}

	public function display_plugin_setup_page()
	{
		include_once('partials/csv-to-acf-admin-display.php');
	}

	public function create_field_group()
	{
		require_once('api/create-field-group.php');
	}


	public function import_data()
	{
		require_once('api/import-data.php');
	}
}
