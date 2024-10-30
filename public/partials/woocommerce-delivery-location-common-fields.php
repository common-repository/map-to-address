<?php
$all_sg_addresses = [];
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $all_sg_addresses = get_user_meta($user_id, 'sg_user_addresses', true);
} else {
    $all_sg_addresses = WC()->session->get('sg_user_addresses');
}
if (!empty($all_sg_addresses)) {
    $default_address_id = $all_sg_addresses['selected'];
} else {
    $default_address_id = '';
}
?>
<div class="sg-del-add-hidden-fields">
    <input type="hidden" name="sg_del_add_map_user_locate_icon" id="sg_del_add_map_user_locate_icon" value="<?php echo esc_attr(plugin_dir_url(__DIR__) . 'img/icons/location-finder-grey.png'); ?>">
    <input type="hidden" name="sg_del_add_map_default_lat" id="sg_del_add_map_default_lat" value="<?php echo esc_attr((get_option('sg_del_default_lat') !== '') ? get_option('sg_del_default_lat', 10.051969) : 10.051969); ?>">
    <input type="hidden" name="sg_del_add_map_default_lng" id="sg_del_add_map_default_lng" value="<?php echo esc_attr((get_option('sg_del_default_long') !== '') ? get_option('sg_del_default_long', 76.315773) : 76.315773); ?>">
    <input type="hidden" name="sg_del_add_auto_detect_user_location" id="sg_del_add_auto_detect_user_location" value="<?php echo esc_attr(get_option('sg_del_allow_user_location', false)); ?>">
    <input type="hidden" name="sg_del_add_default_address_id" id="sg_del_add_default_address_id" value="<?php echo esc_attr($default_address_id); ?>">
    <input type="hidden" name="sg_del_add_ajax_url" id="sg_del_add_ajax_url" value="<?php echo esc_attr(admin_url('admin-ajax.php')); ?>">
    <input type="hidden" name="sg_del_add_status" id="sg_del_add_status" value="<?php echo esc_attr(get_option('sg_del_enable_address_picker', 'disable')); ?>">
    <input type="hidden" name="sg_del_add_unnamed_error_status" id="sg_del_add_unnamed_error_status" value="<?php echo esc_attr(get_option('sg_del_add_enable_unnamed_error', 'no')); ?>">
    <input type="hidden" name="sg_del_add_unnamed_error" id="sg_del_add_unnamed_error" value="<?php echo esc_attr(get_option('sg_del_add_unnamed_error', 'Location is not specified')); ?>">
</div>


<?php
if (get_option('sg_del_address_cards_column') && get_option('sg_del_address_cards_column') !== '') {
    $cards_per_col = get_option('sg_del_address_cards_column', 2);
?>
    <style>
        .addresses-section {
            justify-content: flex-start;
        }

        .addresses-section .single-address.address-inline {

            width: calc(<?php echo esc_html(($cards_per_col === '1') ? "100%" :  "100% /" . $cards_per_col . " - 10px"); ?>);
            margin-right: 2%;
        }

        <?php if ($cards_per_col && $cards_per_col > 3) {

        ?>.addresses-section .single-address .sg-button {
            display: block;
        }

        <?php } 
        ?>.addresses-section .single-address.address-inline:nth-child(<?php echo esc_html($cards_per_col . 'n'); ?>) {
            margin-right: 0px;
        }

        <?php
        echo esc_html(get_option('sg_del_address_custom_styles'));
    }
        ?>
    </style>