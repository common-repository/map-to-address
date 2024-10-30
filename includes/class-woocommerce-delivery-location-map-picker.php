<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://sevengits.com
 * @since      1.0.0
 *
 * @package   Sgmta_Main
 * @subpackageSgmta_Main/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package   Sgmta_Main
 * @subpackageSgmta_Main/includes
 * @author     Sevengits <support@sevengits.com>
 */
class Sgmta_Main
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sgmta_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('SGMTA_VERSION')) {
			$this->version = SGMTA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'map-to-address';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sgmta_Loader. Orchestrates the hooks of the plugin.
	 * - Sgmta_i18n. Defines internationalization functionality.
	 * - Sgmta_Admin. Defines all hooks for the admin area.
	 * - Sgmta_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-delivery-location-map-picker-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woocommerce-delivery-location-map-picker-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woocommerce-delivery-location-map-picker-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woocommerce-delivery-location-map-picker-public.php';

		$this->loader = new Sgmta_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sgmta_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Sgmta_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Sgmta_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');

		$this->loader->add_filter('woocommerce_get_sections_advanced', $plugin_admin, 'sgmta_del_add_settings_tab');
		$this->loader->add_filter('woocommerce_get_settings_advanced', $plugin_admin, 'sgmta_del_get_settings', 10, 2);

		$this->loader->add_action('woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'sgmta_del_add_order_meta_billing');
		$this->loader->add_action('woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'sgmta_del_add_order_meta_shipping');

		$this->loader->add_action('woocommerce_email_customer_details', $plugin_admin, 'sgmta_del_add_order_location_link', 100, 4);

		# below the plugin title in plugins page. add custom links at the begin of list
		$this->loader->add_filter('plugin_action_links_' . SGMTA_BASE, $plugin_admin, 'sgmta_links_below_title_begin');

		# below the plugin title in plugins page. add custom links at the end of list
		$this->loader->add_filter('plugin_action_links_' . SGMTA_BASE, $plugin_admin, 'sgmta_links_below_title_end');

		# below the plugin description in plugins page. add custom links at the end of list
		$this->loader->add_filter('plugin_row_meta', $plugin_admin, 'sgmta_links_below_description_end', 10, 2);

		# sidebar in plugin settings page
		$this->loader->add_action('woocommerce_admin_field_sgitsSettingsSidebar', $plugin_admin, 'sgmta_add_admin_settings_sidebar', 100);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Sgmta_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_action('woocommerce_after_checkout_billing_form', $plugin_public, 'sgmta_woocommerce_after_checkout_billing_form');
		$this->loader->add_action('woocommerce_after_checkout_shipping_form', $plugin_public, 'sgmta_woocommerce_after_checkout_shipping_form');

		$this->loader->add_action('woocommerce_before_order_notes', $plugin_public, 'sgmta_del_add_hidden_common_details');
		$this->loader->add_filter('woocommerce_after_edit_account_address_form', $plugin_public, 'sgmta_display_addresses');
		$this->loader->add_action('wp_ajax_sgmta_address_remove', $plugin_public, 'sgmta_remove_delivery_address');
		$this->loader->add_action('wp_ajax_nopriv_sgmta_address_remove', $plugin_public, 'sgmta_remove_delivery_address');

		$this->loader->add_filter('woocommerce_billing_fields', $plugin_public, 'sgmta_manage_billing_address_fields', 999);
		$this->loader->add_filter('woocommerce_shipping_fields', $plugin_public, 'sgmta_manage_shipping_address_fields', 999);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sgmta_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
