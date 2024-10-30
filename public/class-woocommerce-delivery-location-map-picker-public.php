<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://sevengits.com
 * @since      1.0.0
 *
 * @package   Sgmta_Woocommerce_Delivery_Location_Map_Picker
 * @subpackageSgmta_Woocommerce_Delivery_Location_Map_Picker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package   Sgmta_Woocommerce_Delivery_Location_Map_Picker
 * @subpackageSgmta_Woocommerce_Delivery_Location_Map_Picker/public
 * @author     Sevengits <support@sevengits.com>
 */
class Sgmta_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Delivery_Location_Map_Picker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Delivery_Location_Map_Picker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if (get_option('sg_del_enable_address_picker') !== 'disable' && is_checkout() && !is_wc_endpoint_url('order-received')) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woocommerce-delivery-location-map-picker-public.min.css', array(), $this->version, 'all');
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Delivery_Location_Map_Picker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Delivery_Location_Map_Picker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if (get_option('sg_del_enable_address_picker') !== 'disable' && (get_option('sg_del_script_loading_optimise', 'no') !== 'yes' || (is_checkout() && !is_wc_endpoint_url('order-received')))) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woocommerce-delivery-location-map-picker-public.min.js', array('jquery'), $this->version, false);
			$api_key = get_option('sg_del_gmap_api', '');
			wp_enqueue_script('sgitsdlmp-googlemap', "https://maps.googleapis.com/maps/api/js?key=$api_key&libraries=places", array('jquery'), $this->version, false);
		}
	}

	function sgmta_woocommerce_after_checkout_billing_form()
	{
		if (get_option('sg_del_enable_address_picker') === 'enable_for_billing' || get_option('sg_del_enable_address_picker') ===  'enable_for_both') {

			$section = "billing";
			include plugin_dir_path(dirname(__FILE__)) . '/public/partials/woocommerce-delivery-location-map-picker-public-display.php';
		}
	}

	function sgmta_woocommerce_after_checkout_shipping_form()
	{
		if (get_option('sg_del_enable_address_picker') === 'enable_for_shipping' || get_option('sg_del_enable_address_picker') ===  'enable_for_both') {

			$section = "shipping";
			include plugin_dir_path(dirname(__FILE__)) . '/public/partials/woocommerce-delivery-location-map-picker-public-display.php';
		}
	}

	function sgmta_del_add_hidden_common_details()
	{
		if (get_option('sg_del_enable_address_picker') !== 'disable') {
			require plugin_dir_path(dirname(__FILE__)) . '/public/partials/woocommerce-delivery-location-common-fields.php';
		}
	}

	function sgmta_manage_billing_address_fields($fields)
	{
		if (get_option('sg_del_enable_address_picker') === 'enable_for_billing' || get_option('sg_del_enable_address_picker') ===  'enable_for_both') {
			$address_type_section = "billing";
			require plugin_dir_path(dirname(__FILE__)) . '/public/partials/woocommerce-delivery-location-customised-fields.php';
		}
		return $fields;
	}

	function sgmta_manage_shipping_address_fields($fields)
	{
		if (get_option('sg_del_enable_address_picker') === 'enable_for_shipping' || get_option('sg_del_enable_address_picker') ===  'enable_for_both') {

			$address_type_section = "shipping";
			require plugin_dir_path(dirname(__FILE__)) . '/public/partials/woocommerce-delivery-location-customised-fields.php';
		}
		return $fields;
	}

	function sgmta_display_addresses()
	{
		require plugin_dir_path(dirname(__FILE__)) . '/public/partials/woocommerce-delivery-location-list-addresses.php';
	}

	function sgmta_remove_delivery_address()
	{
		if (get_option('sg_del_enable_address_picker') !== 'disable') {

			$address_id = $_REQUEST['id'];
			if (is_user_logged_in()) {
				$user_id = get_current_user_id();
				$meta_key = 'sg_user_addresses';
				$addresses = get_user_meta($user_id, $meta_key, true)['addresses'];
			} else {
				$addresses = WC()->session->get('sg_user_addresses')['addresses'];
			}
			foreach ($addresses as $key => $address) {
				if ($address->id === $address_id) {
					unset($addresses[$key]);
				}
			}
			if (is_user_logged_in()) {
				$user_id = get_current_user_id();
				$meta_key = 'sg_user_addresses';
				$new_address_list = get_user_meta($user_id, $meta_key, true);
				$new_address_list['addresses'] = $addresses;
				update_user_meta($user_id, $meta_key, $new_address_list, get_user_meta($user_id, $meta_key, true));
			} else {
				$new_address_list = WC()->session->get('sg_user_addresses');
				$new_address_list['addresses'] = $addresses;
				WC()->session->set('sg_user_addresses', $new_address_list);
			}
		}
	}
}
