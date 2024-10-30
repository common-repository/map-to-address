<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://sevengits.com
 * @since      1.0.0
 *
 * @package   Sgmta_Woocommerce_Delivery_Location_Map_Picker
 * @subpackageSgmta_Woocommerce_Delivery_Location_Map_Picker/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="section sg-del-add-section">
    <div class="container sg-del-add-container-outer <?php echo esc_attr('sg-del-add-' . $section . '-container'); ?>">
        <div class="addresses-section sg-del-address sg-del-address-list <?php echo wp_is_mobile() ? esc_attr('mobile-cards') : esc_attr('web-cards'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_list'); ?>">
            <div class="sg-del-address-list-inner">
                <div id="<?php echo esc_attr('sg_delivery_address_' . $section . '_addnew'); ?>" class="sg-del-add-add-new-opt single-address address-inline add-new-address">

                    <div class="action-container">
                        <p class="text-uppercase sg-button button-outline"><?php esc_html_e((get_option('sg_del_add_new_address_card_btn_text') !== '') ? get_option('sg_del_add_new_address_card_btn_text', 'Choose address') : 'Choose address', 'map-to-address'); ?></p>
                    </div>
                </div>
            </div>

        </div>
        <div style="display: none;" class=" sg-del-address">
            <div id="sg_delivery_address_<?php echo $section; ?>_change" class="address-panel sg-del-add-selected-address">
                <p class="change-option"><?php esc_html_e('Change', 'map-to-address'); ?></p>
                <h4 class="sg-del-add-type"><?php esc_html_e('Delivery address', 'map-to-address'); ?></h4>
                <p class="sg-del-add-description"></p>
                <p class="sg-del-add-area"><?php esc_html_e('Area', 'map-to-address'); ?>: <span></span></p>
                <p class="sg-del-add-landmark"><?php esc_html_e('Landmark', 'map-to-address'); ?>: <span></span></p>
            </div>
        </div>
    </div>
</div>
<div class="sg-overlay sg-del-add-overlay <?php echo esc_attr(wp_is_mobile() ? 'is_mobile' : 'is_web'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_popup_window'); ?>">
    <div class="sg-del-add-popup-inner" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_popup_inner'); ?>">
        <div class="<?php echo esc_attr(wp_is_mobile() ? 'sg-bottom-slider' : 'sg-left-slider'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_popup_panel'); ?>">
            <div class="sg-action-container <?php echo esc_attr(wp_is_mobile() ? 'is_mobile' : 'is_web'); ?>">
                <div class="sg-popup-header">
                    <span data-type="<?php echo esc_attr($section); ?>" class="sg-button sg-popup-close-button close-img"><img src="<?php echo plugin_dir_url(__DIR__) . 'img/icons/close.png'; ?>" alt=""></span>
                    <span class="title"><?php esc_html_e((get_option('sg_del_add_new_address_form_title') !== '') ? get_option('sg_del_add_new_address_form_title', 'Save delivery address') : 'Save delivery address', 'map-to-address'); ?></span>
                </div>
                <div id="<?php echo esc_attr('sg_delivery_address_' . $section . '_create_form'); ?>" class="sg-address-generate-from sg-popup-content">

                    <div id="<?php echo esc_attr('sg_delivery_address_' . $section . '_picker_map'); ?>" class="sg-del-add-map-container">
                        <p><?php esc_html_e('pick from map', 'map-to-address'); ?></p>
                    </div>
                    <p class="sg-address-container sg-field">
                        <label for="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_address'); ?>" class="sg-field-label"><?php esc_html_e('Address', 'map-to-address'); ?></label>
                        <input type="text" name="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_address'); ?>" disabled id="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_address'); ?>" required data-clear="true">
                        <input type="hidden" name="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_lat'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_lat'); ?>" data-clear="true">
                        <input type="hidden" name="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_lng'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_lng'); ?>" data-clear="true">
                        <span class="sg-error"></span>
                    </p>

                    <div class="sg-mark-address-container">
                        <div class="sg-option sg-field">
                            <label class="sg-field-label" for="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_area'); ?>"><?php esc_html_e('Area', 'map-to-address'); ?></label>
                            <input type="text" name="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_area'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_area'); ?>" data-clear="true">
                        </div>

                        <div class="sg-option sg-field">
                            <label class="sg-field-label" for="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_flat_no'); ?>"><?php esc_html_e('Door / Flat no.', 'map-to-address'); ?></label>
                            <input type="text" name="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_flat_no'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_flat_no'); ?>" data-clear="true">
                        </div>

                        <div class="sg-option sg-field">
                            <label class="sg-field-label" for="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_landmark'); ?>"><?php esc_html_e('Landmark', 'map-to-address'); ?></label>
                            <input type="text" name="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_landmark'); ?>" id="<?php echo esc_attr('sg_delivery_address_' . $section . '_new_landmark'); ?>" data-clear="true">
                        </div>
                    </div>
                </div>
                <div class="sg-popup-footer">
                    <p id="<?php echo esc_attr('sg_delivery_address_' . $section . '_save_address'); ?>" class="sg-button text-uppercase sg-submit-btn"><?php esc_html_e((get_option('sg_del_add_new_address_form_btn_text') !== '') ? get_option('sg_del_add_new_address_form_btn_text') : 'Save address & proceed', 'map-to-address'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>