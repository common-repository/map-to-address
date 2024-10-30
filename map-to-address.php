<?php

/**

 * @link              https://sevengits.com/plugin/map-to-address-pro/
 * @since             1.0.0
 * @package          Sgmta_Main
 *
 * @wordpress-plugin
 * Plugin Name:       Map to Address
 * Plugin URI:        https://sevengits.com/plugin/map-to-address-pro/
 * Description:       Customers can mark their location  on google map and address will be automatically populated.
 * Version:           1.0.16
 * Author:            Sevengits
 * Author URI:        https://sevengits.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       map-to-address
 * Domain Path:       /languages
 * WC requires at least: 3.0
 * WC tested up to: 	 8.7
 * 
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}
if (!function_exists('get_plugin_data')) {
	require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if (!defined('SGMTA_VERSION')) {

	define('SGMTA_VERSION', get_plugin_data(__FILE__)['Version']);
}
if (!defined('SGMTA_BASE')) {

	define('SGMTA_BASE', plugin_basename(__FILE__));
}

/**
 * Function for ensure hpos compatible
 */
add_action( 'before_woocommerce_init','woom_hpos_check'); 
function woom_hpos_check() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} 

if (!class_exists('\SGMTA\Reviews\Notice')) {
	require plugin_dir_path(__FILE__) . 'includes/packages/plugin-review/notice.php';
}
function dlmp_disabled_plugin_depencies_unavailable()
{

	/**
	 * check dependency plugins are activated
	 */
	$depended_plugins = array(
		array(
			'plugins' => array(
				'WooCommerce' => 'woocommerce/woocommerce.php'
			), 'links' => array(
				'free' => 'https://wordpress.org/plugins/woocommerce/'
			)
		)

	);
	$message = __('The following plugins are required for <b>' . get_plugin_data(__FILE__)['Name'] . '</b> plugin to work. Please ensure that they are activated: ', 'map-to-address');
	$is_disabled = false;
	foreach ($depended_plugins as $key => $dependency) {
		$dep_plugin_name = array_keys($dependency['plugins']);
		$dep_plugin = array_values($dependency['plugins']);
		if (count($dep_plugin) > 1) {
			if (!in_array($dep_plugin[0], apply_filters('active_plugins', get_option('active_plugins'))) && !in_array($dep_plugin[1], apply_filters('active_plugins', get_option('active_plugins')))) {
				$class = 'notice notice-error is-dismissible';
				$is_disabled = true;
				if (isset($dependency['links'])) {
					if (array_key_exists('free', $dependency['links'])) {
						$message .= '<br/> <a href="' . $dependency['links']['free'] . '" target="_blank" ><b>' . $dep_plugin_name[0] . '</b></a>';
					}
					if (array_key_exists('pro', $dependency['links'])) {

						$message .= ' Or <a href="' . $dependency['links']['pro'] . '" target="_blank" ><b>' . $dep_plugin_name[1] . '</b></a>';
					}
				} else {
					$message .= "<br/> <b> $dep_plugin_name[0] </b> Or <b> $dep_plugin_name[1] . </b>";
				}
			}
		} else {
			if (!in_array($dep_plugin[0], apply_filters('active_plugins', get_option('active_plugins')))) {
				$class = 'notice notice-error is-dismissible';
				$is_disabled = true;
				if (isset($dependency['links'])) {
					$message .= '<br/> <a href="' . $dependency['links']['free'] . '" target="_blank" ><b>' . $dep_plugin_name[0] . '</b></a>';
				} else {
					$message .= "<br/><b>$dep_plugin_name[0]</b>";
				}
			}
		}
	}
	if ($is_disabled) {
		if (!defined('DLMP_DISABLED')) {
			define('DLMP_DISABLED', true);
		}
		printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
	}

	/**
	 * plugin review notice 
	 */
	if (class_exists('\SGMTA\Reviews\Notice')) {

		// delete_site_option('prefix_reviews_time'); // FOR testing purpose only. this helps to show message always
		$message = sprintf(__("Hello! Seems like you have been using %s for a while – that’s awesome! Could you please do us a BIG favor and give it a 5-star rating on WordPress? This would boost our motivation and help us spread the word.", 'map-to-address'), "<b>" . get_plugin_data(__FILE__)['Name'] . "</b>");
		$actions = array(
			'review'  => __('Ok, you deserve it', 'map-to-address'),
			'later'   => __('Nope, maybe later I', 'map-to-address'),
			'dismiss' => __('already did', 'map-to-address'),
		);
		$notice = \SGMTA\Reviews\Notice::get(
			'map-to-address',
			get_plugin_data(__FILE__)['Name'],
			array(
				'days'          => 7,
				'message'       => $message,
				'action_labels' => $actions,
				'prefix' => "sgmta"
			)
		);

		// Render notice.
		$notice->render();
	}
}
add_action('admin_notices', 'dlmp_disabled_plugin_depencies_unavailable');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-delivery-location-map-picker-activator.php
 */
if (!function_exists('sgmta_activate')) {
	function sgmta_activate()
	{
		require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-delivery-location-map-picker-activator.php';
		Sgmta_activator::activate();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-delivery-location-map-picker-deactivator.php
 */
if (!function_exists('sgmta_deactivate')) {

	function sgmta_deactivate()
	{
		require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-delivery-location-map-picker-deactivator.php';
		Sgmta_Deactivator::deactivate();
	}
}

register_activation_hook(__FILE__, 'sgmta_activate');
register_deactivation_hook(__FILE__, 'sgmta_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woocommerce-delivery-location-map-picker.php';

require plugin_dir_path(__FILE__) . 'plugin-deactivation-survey/deactivate-feedback-form.php';

add_filter('sgits_deactivate_feedback_form_plugins', 'sgmta_deactivate_feedback');
function sgmta_deactivate_feedback($plugins)
{

	$plugins[] = (object)array(
		'slug'		=> 'map-to-address',
		'version'	=> SGMTA_VERSION
	);
	return $plugins;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if (!function_exists('sgmta_run')) {
	function sgmta_run()
	{
		$plugin = new Sgmta_Main();
		$plugin->run();
	}
}
sgmta_run();
