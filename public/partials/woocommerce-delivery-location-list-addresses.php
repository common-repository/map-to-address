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

if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $all_sg_addresses = get_user_meta($user_id, 'sg_user_addresses', false);

    if (isset($all_sg_addresses) && count($all_sg_addresses) > 0) {
        if (isset($all_sg_addresses[0]['addresses']) && count($all_sg_addresses[0]['addresses']) > 0) {
?>
            <div class="addresses col2-set">
                <h3>Other Addresses</h3>
                <input type="hidden" name="" id="sg_del_add_ajax_url" value="<?php echo esc_attr(admin_url('admin-ajax.php')); ?>">
                <?php
                foreach ($all_sg_addresses[0]['addresses'] as $add_id => $address) {
                ?>
                    <div class="sg_remove_address_card sgmb-1">
                        <p class="sgm-0 sg_remove_address_item">
                            <h3 class="sgm-0"><?php echo $address->address_type; ?></h3>
                            <input type="hidden" name="" id="sg_remove_address_id_<?php echo $address->id; ?>" value="<?php echo $address->id; ?>">
                        </p>
                        <?php
                        echo esc_html($address->formatted_address);
                        ?>
                        <a id="sg_remove_address_<?php echo $address->id; ?>" class="button-danger sg_remove_address_action">Delete</a>
                    </div>
                <?php
                }

                ?>
            </div>
            <style>
                .button-danger {
                    color: #ff0000;
                    cursor: pointer;
                    text-decoration: none !important;
                }
                .button-danger:hover {
                    color: #9d0000;
                }
                .sgmb-1 {
                    margin-bottom: 20px;
                }

                .sgm-0 {
                    margin: 0px;
                }

                .sg_remove_address_item a {
                    cursor: pointer;
                }

                .sg_remove_address_card {
                    display: inline-flex;
                    width: calc(90% / 2);
                    flex-wrap: wrap;
                    margin: 15px;
                }

                .sg_remove_address_card:nth-of-type(odd) {
                    margin-left: 0px;
                }

                .sg_remove_address_card:nth-of-type(even) {
                    margin-right: 0px;
                }

                @media only screen and (max-width: 768px) {

                    .sg_remove_address_card {
                        display: inline-block;
                        width: calc(100%);
                        margin-left: 0px;
                        margin-right: 0px;
                    }
                }
            </style>
            <script>
                jQuery(".sg_remove_address_action").click(function() {
                    if (window.confirm('This address will remove permenantly. Are you sure?')) {
                        const address = jQuery(this).parent();
                        console.log(document.getElementById('sg_del_add_ajax_url').value);
                        jQuery.ajax({
                            type: "post",
                            url: document.getElementById('sg_del_add_ajax_url').value,
                            data: {
                                action: 'sgmta_address_remove',
                                id: jQuery(address).children('input').val()
                            },
                            success: function(data) {
                                location.reload();
                            }
                        });
                    } else {
                        alert('cancelled');
                    }
                });
            </script>
<?php
        }
    }
}
?>