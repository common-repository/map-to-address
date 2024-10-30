<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://sevengits.com
 * @since      1.0.0
 *
 * @package   Sgmta_Main
 * @subpackageSgmta_Main/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package   Sgmta_Main
 * @subpackageSgmta_Main/admin
 * @author     Sevengits <support@sevengits.com>
 */
class Sgmta_Admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sg_Checkout_Location_Picker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sg_Checkout_Location_Picker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/woocommerce-delivery-location-map-picker-admin.css', array(), $this->version, 'all');

        if (!wp_style_is('sgits-admin-settings-sidebar-css', 'enqueued'))
            wp_enqueue_style('sgits-admin-settings-sidebar', plugin_dir_url(__FILE__) . 'css/settings-sidebar.css', array(), $this->version, 'all');

        if (!wp_style_is('sgits-admin-common-css', 'enqueued'))
            wp_enqueue_style('sgits-admin-common', plugin_dir_url(__FILE__) . 'css/common.css', array(), $this->version, 'all');
    }

    /**
     * @since 1.0.0 
     */
    public function sgmta_del_add_settings_tab($settings_tab)
    {

        $settings_tab['sg_del_add_tab'] = esc_html('SG Map to Address Settings');
        return $settings_tab;
    }

    /**
     * @since 1.0.0 
     */
    public function sgmta_del_get_settings($settings, $current_section)
    {

        $custom_settings = array();

        if ('sg_del_add_tab' == $current_section) {
            $custom_settings =  array(
                array(
                    'name'    => __('Helpfull Links', 'map-to-address'),
                    'type'    => 'sgitsSettingsSidebar',
                    'desc'    => __('Helpfull Links for settings page', 'map-to-address'),
                    'desc_tip' => true,
                    'id'      => 'promo-helpfull-links',
                    'options' => array(
                        array(
                            'name' => __("Documentation", 'map-to-address'),
                            'classList' => "dashicons dashicons-media-default sg-icon",
                            'target' => "_blank",
                            'link' => "https://sevengits.com/docs/woocommerce-delivery-location-map-picker/?utm_source=wp&utm_medium=promo-sidebar&utm_campaign=settings_page"
                        ),
                        array(
                            'name' => __("Free Support", 'map-to-address'),
                            'classList' => "dashicons dashicons-groups sg-icon",
                            'target' => "_blank",
                            'link' => "https://wordpress.org/support/plugin/map-to-address/"
                        ),
                        array(
                            'name' => __("Request Customization", 'map-to-address'),
                            'classList' => "dashicons dashicons-sos sg-icon",
                            'target' => "_blank",
                            'link' => "https://sevengits.com/contact/?utm_source=wp&utm_medium=promo-sidebar&utm_campaign=settings_page"
                        ),
                        array(
                            'name' => __("Get Premium", 'map-to-address'),
                            'classList' => "dashicons dashicons-awards sg-icon",
                            'target' => "_blank",
                            'link' => "https://sevengits.com/plugin/woocommerce-delivery-location-map-picker/?utm_source=wp&utm_medium=promo-sidebar&utm_campaign=settings_page"
                        ),
                    )

                ),
                array(
                    'name' => esc_html__('Sg Map to Address', 'map-to-address'),
                    'type' => 'title',
                    'id'   => 'sg_del_tab_main'
                ),

                array(
                    'name'    => esc_html__('Enable map to address', 'map-to-address'),
                    'id'      => 'sg_del_enable_address_picker',
                    'default' => 'disable',
                    'type'    => 'select',
                    'options' => array(
                        'disable'        => esc_attr__('Disable', 'map-to-address'),
                        'enable_for_billing'       => esc_attr__('Enable for billing', 'map-to-address'),
                        'enable_for_shipping'  => esc_attr__('Enable for shipping', 'map-to-address'),
                        'enable_for_both' => esc_attr__('Enable for billing & shipping', 'map-to-address')
                    ),
                ),
                array(
                    'id'      => 'sg_del_script_loading_optimise',
                    'type'    => 'checkbox',
                    'name'    => esc_html__('Optimise loading', 'map-to-address'),
                    'default' => false,
                    'desc' => esc_html__('Load scripts/styles associated with map to address plugin only on checkout page. ', 'map-to-address') . '<a href="' . esc_url('https://sevengits.com/docs/woocommerce-delivery-location-map-picker/?utm_source=wp&utm_medium=promo-sidebar&utm_campaign=settings_page') . '" target="_blank">' . esc_attr__('Learn more', 'map-to-address') . '</a>',
                    'desc_tip' => false,
                ),

                array(
                    'name' => esc_html__('Google Maps API', 'map-to-address'),
                    'type' => 'text',
                    'id' => 'sg_del_gmap_api',
                    'placeholder' => esc_attr__('Enter your google maps api key', 'map-to-address'),
                    'desc_tip' => false,
                    'desc' => esc_html__('For reference ', 'map-to-address') . '<a href="' . esc_url('developers.google.com/maps/documentation/javascript/get-api-key', 'https') . '" target="_blank">' . esc_attr__('Get API', 'map-to-address') . '</a>',
                ),

                array(
                    'name' => esc_html__('Default location latitude', 'map-to-address'),
                    'type' => 'text',
                    'id'    => 'sg_del_default_lat',
                    'placeholder' => esc_attr('Latitude')

                ),

                array(
                    'name' => esc_html__('Default location longitude', 'map-to-address'),
                    'type' => 'text',
                    'id'    => 'sg_del_default_long',
                    'placeholder' => esc_attr('Longitude')

                ),

                array(
                    'name' => esc_html__('Allow user current location', 'map-to-address'),
                    'desc' => esc_html__('Show current user location when map is loading', 'map-to-address'),
                    'desc_tip' => false,
                    'type' => 'checkbox',
                    'id'    => 'sg_del_allow_user_location',
                ),

                array(
                    'name' => esc_html__('New address card button text', 'map-to-address'),
                    'type' => 'text',
                    'id' => 'sg_del_add_new_address_card_btn_text',
                    'placeholder' => esc_attr__('Default is "Choose address"', 'map-to-address'),
                ),

                array(
                    'name' => esc_html__('New address popup title', 'map-to-address'),
                    'type' => 'text',
                    'id' => 'sg_del_add_new_address_form_title',
                    'placeholder' => esc_attr__('Default is "Save delivery address"', 'map-to-address'),
                ),

                array(
                    'name' => esc_html__('New address save button text', 'map-to-address'),
                    'type' => 'text',
                    'id' => 'sg_del_add_new_address_form_btn_text',
                    'placeholder' => esc_attr__('Default is "Save address & proceed"', 'map-to-address'),
                ),

                array(
                    'name' => esc_html__('Custom address title label', 'map-to-address'),
                    'type' => 'text',
                    'id' => 'sg_del_add_title',
                    'placeholder' => esc_attr__("Default is 'Address title'", 'map-to-address'),
                ),

                array(
                    'name' => esc_html__('Enable error for unnamed address', 'map-to-address'),
                    'type' => 'checkbox',
                    'id' => 'sg_del_add_enable_unnamed_error',
                    'desc' => esc_html__('Enable for show error message while address contains unnamed', 'map-to-address'),
                    'desc_tip' => false
                ),

                array(
                    'name' => esc_html__('unnamed address error message', 'map-to-address'),
                    'type' => 'text',
                    'id' => 'sg_del_add_unnamed_error',
                    'default' => esc_attr__('Location is not specified', 'map-to-address'),
                    'placeholder' => esc_attr__("Default is 'Location is not specified'", 'map-to-address'),
                ),

                array(
                    'name' => esc_html__('Additional CSS', 'map-to-address'),
                    'type' => 'textarea',
                    'id' => 'sg_del_address_custom_styles',
                    'placeholder' => esc_attr__('Custom styles enter here...', 'map-to-address'),
                    'custom_attributes' => array('rows' => 10),
                ),

                array(
                    'type' => 'sectionend',
                    'name' => 'end_section',
                    'id' => 'ppw_woo'
                ),

            );

            return $custom_settings;
        } else {

            return $settings;
        }
    }

    /**
     * @since 1.0.0
     */
    public function sgmta_del_add_order_meta_billing($order)
    {
        /**
         * get all the meta data values we need
         */

        $billing_latitude = get_post_meta($order->get_id(), '_billing_address_latitude', true);
        $billing_longitude = get_post_meta($order->get_id(), '_billing_address_longitude', true);
        $maplink_api = 'www.google.com/maps/search/?q=';
        if ($billing_latitude !== '' && $billing_longitude !== '') {

?>
            <div class="address sg-del-address">

                <p><strong><?php echo esc_html('Latitude :'); ?></strong> <?php echo esc_html($billing_latitude); ?></p>
                <p><strong><?php echo esc_html('Longitude :'); ?></strong> <?php echo esc_html($billing_longitude); ?></p>
                <p><a href="<?php echo esc_url($maplink_api . $billing_latitude . ', ' . $billing_longitude, 'https');  ?>" target="_blank" class="link"><?php esc_html_e('View in map', 'map-to-address'); ?></a></p>

            </div>
        <?php
        }
    }

    public function sgmta_del_add_order_meta_shipping($order)
    {

        /**
         * get all the meta data values we need
         */

        $shipping_latitude = get_post_meta($order->get_id(), '_shipping_address_latitude', true);
        $shipping_longitude = get_post_meta($order->get_id(), '_shipping_address_longitude', true);
        $maplink_api = "www.google.com/maps/search/?api=1&query=";
        if ($shipping_latitude !== '' && $shipping_longitude !== '') {

        ?>
            <div class="address sg-del-address">

                <p><strong><?php echo esc_html('Latitude :') ?></strong> <?php echo esc_html($shipping_latitude); ?></p>
                <p><strong><?php echo esc_html('Longitude :') ?></strong> <?php echo esc_html($shipping_longitude); ?></p>
                <p><a href="<?php echo esc_url($maplink_api . $shipping_latitude . ', ' . $shipping_longitude, 'https');  ?>" target="_blank" class="link"><?php esc_html_e('View in map', 'map-to-address'); ?></a></p>

            </div>
        <?php
        }
    }



    /**
     * @since 1.0.0 
     */
    function sgmta_del_add_order_location_link($order, $sent_to_admin, $plain_text, $email)
    {
        if ($email->id == 'new_order') :
            $billing_latitude = get_post_meta($order->get_id(), '_billing_address_latitude', true);
            $billing_longitude = get_post_meta($order->get_id(), '_billing_address_longitude', true);

            $shipping_latitude = get_post_meta($order->get_id(), '_shipping_address_latitude', true);
            $shipping_longitude = get_post_meta($order->get_id(), '_shipping_address_longitude', true);
            $maplink_api = "www.google.com/maps/search/?api=1&query=";
        ?>
            <div class="link-container sg-locationlinks-container">
                <?php
                if ($billing_latitude !== '' && $billing_longitude !== '') {

                ?>
                    <p><a href="<?php echo esc_url($maplink_api . $billing_latitude . ', ' . $billing_longitude, 'https');  ?>" target="_blank" class="link"><?php esc_html_e('Billing location in map', 'map-to-address'); ?></a></p>
                <?php
                }
                if ($shipping_latitude !== '' && $shipping_longitude !== '') {
                ?>
                    <p><a href="<?php echo esc_url($maplink_api . $shipping_latitude . ', ' . $shipping_longitude, 'https');  ?>" target="_blank" class="link"><?php esc_html_e('Shipping location in map', 'map-to-address'); ?></a></p>
                <?php
                }
                ?>
            </div>
        <?php
        endif;
    }

    /**
     * @since 1.0.5
     * 
     * merge exists array of links with new links of array
     * 
     * $position values are start | end
     * 
     */
    function sgmta_merge_links($old_list, $new_list, $position = "end")
    {
        $settings = array();
        foreach ($new_list as $name => $item) {
            $target = (array_key_exists("target", $item)) ? $item['target'] : '';
            $classList = (array_key_exists("classList", $item)) ? $item['classList'] : '';
            $settings[$name] = sprintf('<a href="%s" target="' . $target . '" class="' . $classList . '">%s</a>', $item['link'], $item['name']);
        }
        if ($position !== "start") {
            // push into $links array at the end
            return array_merge($old_list, $settings);
        } else {
            return array_merge($settings, $old_list);
        }
    }

    /**
     * @since 1.0.5
     * 
     * array of new links that shows in plugins page under plugin tite at the begin. (only shows if plugin is installed)
     * 
     */
    function sgmta_links_below_title_begin($links)
    {
        if (!defined('DLMP_DISABLED')) {
            $link_list = array(
                'settings' => array(
                    "name" => esc_html__('Settings', 'map-to-address'),
                    "classList" => "",
                    "link" => esc_url(admin_url('admin.php?page=wc-settings&tab=advanced&section=sg_del_add_tab'), 'map-to-address')
                )
            );
            $links = $this->sgmta_merge_links($links, $link_list, "start");
        }
        return $links;
    }

    /**
     * @since 1.0.5
     * 
     * array of new links that shows in plugins page under plugin tite at the end. (only shows if plugin is installed)
     * 
     */
    function sgmta_links_below_title_end($links)
    {
        $link_list = array(
            'buy-pro' => array(
                "name" => esc_html__('Buy Premium', 'map-to-address'),
                "classList" => "pro-purchase get-pro-link",
                "target" => '_blank',
                "link" => esc_url('https://sevengits.com/plugin/woocommerce-delivery-location-map-picker/?utm_source=plugins&utm_medium=plugins-link&utm_campaign=Free-plugin', 'map-to-address')
            )
        );
        return $this->sgmta_merge_links($links, $link_list, "end");
    }

    /**
     * @since 1.0.5
     * 
     * array of new links that shows in plugins page under plugin description at the end
     * 
     */
    function sgmta_links_below_description_end($links, $file)
    {
        if (strpos($file, 'map-to-address.php') !== false) {
            $new_links = array(
                'pro' => array(
                    "name" => esc_html__('Buy Premium', 'map-to-address'),
                    "classList" => "pro-purchase get-pro-link",
                    "target" => '_blank',
                    "link" => esc_url('https://sevengits.com/plugin/woocommerce-delivery-location-map-picker/?utm_source=plugins&utm_medium=plugins-link&utm_campaign=Free-plugin', 'map-to-address')
                ),
                'docs' => array(
                    "name" => esc_html__('Docs', 'map-to-address'),
                    "target" => '_blank',
                    "link" => esc_url('https://sevengits.com/docs/woocommerce-delivery-location-map-picker/?utm_source=dashboard&utm_medium=plugins-link&utm_campaign=Free-plugin', 'map-to-address')
                ),
                'support' => array(
                    "name" => esc_html__('Free Support', 'map-to-address'),
                    "target" => '_blank',
                    "link" => esc_url('https://wordpress.org/support/plugin/map-to-address/?utm_source=dashboard&utm_medium=plugins-link&utm_campaign=Free-plugin', 'map-to-address')
                ),

            );
            $links = $this->sgmta_merge_links($links, $new_links, "end");
        }

        return $links;
    }

    /**
     * @since 1.0.5
     * sidebar in plugin settings page
     * 
     */
    function sgmta_add_admin_settings_sidebar($links)
    {
        ?>
        <div id="sg-settings-sidebar">
            <div id="<?php echo $links['id']; ?>">
                <h4><?php echo $links['name']; ?></h4>
                <ul>
                    <?php
                    foreach ($links['options'] as $key => $item) {
                        if (is_array($item)) :
                            $target = (array_key_exists("target", $item)) ? $item['target'] : '';
                    ?>
                            <li><span class="<?php echo $item['classList']; ?>"></span><a href="<?php echo $item['link']; ?>" target="<?php echo $target; ?>"><?php echo $item['name']; ?></a></li>
                    <?php
                        endif;
                    }
                    ?>
                </ul>
            </div>
        </div>
<?php
    }
}
