(function () {
    'use strict';

    let enabled_section;
    // temp storage of new address data
    let sg_address = {};
    jQuery(document).ready(() => {
        enabled_section = document.getElementById('sg_del_add_status').value;
        jQuery('.sg_del_add_hidden_fields').hide();

        // popup open
        jQuery('.sg-del-add-add-new-opt').on('click', sgmta_address_create_popup_window_open);

        // popup close when click outside
        jQuery('.sg-del-add-overlay').on('click', sgmta_address_create_popup_window_close);

        // popup close when click close button
        jQuery('.sg-popup-close-button').on('click', function () {
            sgmta_address_create_popup_window_close(jQuery(this).attr('data-type'));
        });


        // prevent closing popup when clicked in the form
        jQuery('#sg_delivery_address_billing_popup_panel').on('click', (e) => {
            e.stopImmediatePropagation();
        });
        jQuery('#sg_delivery_address_shipping_popup_panel').on('click', (e) => {
            e.stopImmediatePropagation();
        });

        // trigger form submit
        jQuery('.sg-popup-footer .sg-submit-btn').on('click', (e) => {
            e.stopImmediatePropagation();
            sgmta_add_new_sg_address((e.target.id.includes('billing') ? 'billing' : 'shipping'));
        });

        var req_input_list = [...jQuery('.sg-field input')].filter(field => jQuery(field).attr('required'));
        for (const input of req_input_list) {
            jQuery(input).on('input', function () {
                if (this.value.split(" ").join("").length > 0) {
                    if (jQuery(this).parents('.sg-field').hasClass('has-error')) {
                        jQuery(this).parents('.sg-field').removeClass('has-error');
                    }
                } else {
                    jQuery(this).parents('.sg-field').addClass('has-error');

                }
            });
        }

        // input focus event of popup forms
        jQuery('.sg-field input').on('focus', (e) => {
            jQuery('.sg-field label[for=' + e.target.id + ']').addClass('active');
        });

        // input blur event of popup forms
        jQuery('.sg-field input').on('blur', (e) => {
            if (e.target.value === '') {
                jQuery('.sg-field label[for=' + e.target.id + ']').removeClass('active');
            }
        });
        // address card options menu open
        jQuery(document).on('click', '.sg-menu-option', (event) => {
            if (jQuery(event.target).parents('.sg-dropdown').children('.sg-dropdown-list').hasClass('active')) {
                jQuery('.sg-dropdown-list').removeClass('active');
            } else {
                jQuery('.sg-dropdown-list').removeClass('active');
                jQuery(event.target).parents('.sg-dropdown').children('.sg-dropdown-list').addClass('active');

            }
        });
        // address card options menu close
        document.addEventListener('click', (event) => {
            if (!jQuery(event.target).hasClass('sg-menu-option') && jQuery('.sg-dropdown-list').hasClass('active')) {
                jQuery('.sg-dropdown-list').removeClass('active');

            }
        });
        jQuery('.sg-del-add-selected-address').on('click', sgmta_address_create_popup_window_open);
    });

    // select another address from stored address list
    jQuery(document).on('change', '.addresses-section.sg-del-address-list input.sg-del-add-select', (e) => {
        if (jQuery(e.target).parents('.single-address').hasClass('removed')) {
            sgmta_clear_addresses();
        } else {
            sgmta_get_selected_address(e.target.value, jQuery(e.target).attr('data-type'));
        }

    });

    jQuery(document).on('click', '.addresses-section.sg-del-address-list .available-address .action-container .sg-del-add-select-button', (e) => {
        if (jQuery("input#" + jQuery(e.target).attr('for')).prop('checked') === true) {
            sgmta_get_selected_address(jQuery("input#" + jQuery(e.target).attr('for'))[0].value, jQuery("input#" + jQuery(e.target).attr('for')).attr('data-type'));


        }
    });

    // popup open 
    function sgmta_address_create_popup_window_open() {
        var section = this.id.includes('billing') ? 'billing' : 'shipping';
        jQuery('#sg_delivery_address_' + section + '_popup_window').addClass('show');
        jQuery('#sg_delivery_address_' + section + '_popup_inner').addClass('show');
        jQuery('#sg_delivery_address_' + section + '_popup_window').css('opacity', '1');
        jQuery('body').addClass('has-popup');

        // Iterate through each element
        let bottom = 0;
        document.querySelectorAll('*').forEach(function (element) {
            // Check if the element has a fixed position
            const position = window.getComputedStyle(element).position;
            if (position === 'fixed' && (!element.id || !element.id.includes("sg_delivery_address_"))) {
                bottom += element.clientHeight;
            }
        });
        document.querySelectorAll('*').forEach(function (element) {
            if (element.id.includes("sg_delivery_address_") && element.id.includes("popup_panel") && jQuery(element.parentNode).hasClass('show')) {
                element.style.bottom = bottom + "px";
            }
        });

        // clear input fields
        for (const input of jQuery('#sg_delivery_address_' + section + '_create_form input')) {
            if (!input.id.includes('map') && jQuery(input).attr('data-clear') === 'true') {
                if (input.type === 'radio') {
                    jQuery(input).prop("checked", false);
                } else {
                    input.value = '';
                }
                jQuery('.sg-field label[for=' + input.id + ']').removeClass('active');
            }
        }
        // map initialise
        sgmta_locator_map_init(section);
    }

    // close popup
    function sgmta_address_create_popup_window_close(add_section) {
        let section = '';
        if (this !== undefined) {
            section = this.id.includes('billing') ? 'billing' : 'shipping';
        } else if (section !== undefined) {
            section = add_section;

        }
        jQuery("#sg_delivery_address_" + section + "_popup_inner").removeClass('show');
        jQuery('body').removeClass('has-popup');
        jQuery('#sg_delivery_address_' + section + '_popup_window').css('opacity', '0');
        jQuery('.sg-del-add-' + section + '-container')[0].scrollIntoView();
        setTimeout(() => {
            jQuery('#sg_delivery_address_' + section + '_popup_window').removeClass('show');

        }, 350);
    }

    function sgmta_add_new_sg_address(section) {
        var req_input_list = [...jQuery('#sg_delivery_address_' + section + '_create_form input')];
        if (jQuery('#sg_del_add_unnamed_error_status').val() !== 'no') {
            req_input_list = req_input_list.filter(data => (jQuery(data).attr('required') && (jQuery(data).val().toLowerCase().includes('unnamed') || jQuery(data).val() === '')));
        } else {
            req_input_list = req_input_list.filter(data => (jQuery(data).attr('required') && jQuery(data).val() === ''));
        }
        if (req_input_list.length === 0) {
            sg_address.address_2 = document.getElementById('sg_delivery_address_' + section + '_new_area').value;
            sg_address.door = document.getElementById('sg_delivery_address_' + section + '_new_flat_no').value;
            sg_address.landmark = document.getElementById('sg_delivery_address_' + section + '_new_landmark').value;

            sgmta_update_address_fields(sg_address, section);
            sgmta_address_create_popup_window_close(section);

        } else {
            for (const field of req_input_list) {
                if (field.id === 'sg_delivery_address_' + section + '_new_address') {
                    jQuery(field).parents('.sg-field').addClass('has-error');
                    jQuery(field).siblings('span.sg-error').html(jQuery('#sg_del_add_unnamed_error').val());
                }
            }
        }
    }

    // update field content
    function sgmta_update_address_fields(address, section) {
        let inputs = document.querySelectorAll('input');
        const fields = ['address_1', 'address_2', 'city', 'state', 'postcode', 'door', 'landmark', 'address_type', 'address_latitude', 'address_longitude', 'country'];
        for (const input of inputs) {
            if (input.id.includes(section) && !input.id.includes('sg')) {
                for (const field of fields) {
                    if (input.id.includes(field)) {
                        if (typeof address[field] !== 'string') {
                            if (address[field]) {
                                if (!field.includes('country')) {
                                    input.value = address[field].long_name;
                                } else {

                                    input.value = address[field].short_name;

                                }
                            } else {
                                if (address.position) {

                                    if (field.includes('latitude')) {
                                        input.value = address.position.lat;
                                    } else if (field.includes('longitude')) {
                                        input.value = address.position.lng;
                                    }
                                } else {

                                }

                            }
                        } else {
                            input.value = address[field];
                        }
                    }
                }

            }
        }
        jQuery('body').trigger('update_checkout');
        const selected_address = jQuery('.sg-del-add-' + section + '-container').find('.sg-del-add-selected-address');
        if (address.address_type !== '') {
            jQuery(selected_address).find('.sg-del-add-type').html(address.address_type);
        }
        jQuery(selected_address).find('.sg-del-add-description').html(((address.door !== '') ? address.door + ', ' : '') + address.formatted_address);
        if (address.address_2 !== '') {
            jQuery(selected_address).find('.sg-del-add-area').show();
            jQuery(selected_address).find('.sg-del-add-area span').html(address.address_2);
        } else {
            jQuery(selected_address).find('.sg-del-add-area').hide();
        }
        if (address.landmark !== '') {
            jQuery(selected_address).find('.sg-del-add-landmark').show();
            jQuery(selected_address).find('.sg-del-add-landmark span').html(address.landmark);
        } else {
            jQuery(selected_address).find('.sg-del-add-landmark').hide();
        }
        jQuery('.sg-del-add-' + section + '-container').find('.sg-del-address').slideToggle();

    }


    function sgmta_locator_map_init(section) {
        let map, marker;
        var map_id = document.getElementById('sg_delivery_address_' + section + '_picker_map');
        let position = { lat: parseFloat(document.getElementById('sg_del_add_map_default_lat').value), lng: parseFloat(document.getElementById('sg_del_add_map_default_lng').value) };
        map = new google.maps.Map(map_id, {
            center: position,
            streetViewControl: false,
            fullscreenControl: false,
            mapTypeControl: false,
            // mapTypeId: 'hybrid',
            zoom: 13,
            // styles: map_style
        });

        marker = new google.maps.Marker({
            position: position,
            map: map,
            draggable: true
        });
        if (document.getElementById('sg_del_add_auto_detect_user_location').value === 'yes') {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((event) => {
                    position = {};
                    position = {
                        lat: event.coords.latitude,
                        lng: event.coords.longitude
                    };
                    map.setCenter(position);
                    marker.setPosition(position);
                    sgmta_get_address(position, section);
                }, (error) => {
                    console.log(error.message);
                });

            }
        }
        var locator = new sgmta_user_locater_control(map);
        locator.addEventListener("click", () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((event) => {
                    position = {};
                    position = {
                        lat: event.coords.latitude,
                        lng: event.coords.longitude
                    };
                    map.setCenter(position);
                    marker.setPosition(position);
                    sgmta_get_address(position, section);
                }, (error) => {
                    console.log(error.message);
                });

            }
        });

        sgmta_get_address(position, section);
        marker.addListener('dragend', (m) => {
            sgmta_get_address({ lat: m.latLng.lat(), lng: m.latLng.lng() }, section);
        });

    }

    function sgmta_get_address(pos, section) {

        let geocoder = new google.maps.Geocoder();
        let fields = [...document.querySelectorAll("input")].filter((field) => !field.id.includes('sg_'));
        geocoder.geocode({
            latLng: pos,
        }, (responses) => {
            if (responses && responses.length > 0) {
                if (!responses[0].formatted_address.toLowerCase().includes('unnamed')) {
                    if (jQuery('#sg_delivery_address_' + section + '_new_address').parents('.sg-field').hasClass('has-error')) {
                        jQuery('#sg_delivery_address_' + section + '_new_address').parents('.sg-field').removeClass('has-error');
                        jQuery('#sg_delivery_address_' + section + '_new_address').siblings('span.sg-error').html('');
                    }
                }

                let address_data = [];
                let address_items = {};
                let field;
                address_items.formatted_address = responses[0].formatted_address;
                for (const address of responses[0].address_components) {

                    if (address.types.includes('country')) {
                        field = fields.filter(f => f.id.includes('country') === true && f.id.includes(section) === true);
                        if (field.length > 0) {
                            field = field[0];
                            document.getElementById(field.id).value = address.short_name;
                        }
                        address_items.country = {
                            short_name: address ? address.short_name : '',
                            long_name: address ? address.long_name : ''
                        };
                    } else if (address.types.includes('postal_code')) {
                        address_items.postcode = {
                            short_name: address ? address.short_name : '',
                            long_name: address ? address.long_name : ''
                        };
                    } else if (address.types.includes('administrative_area_level_1')) {
                        address_items.state = {
                            short_name: address ? address.short_name : '',
                            long_name: address ? address.long_name : ''
                        };
                    } else if (address.types.includes('administrative_area_level_3')) {
                        address_items.city = {
                            short_name: address ? address.short_name : '',
                            long_name: address ? address.long_name : ''
                        };
                    } else {
                        address_data.push(address);
                    }
                }
                let long_address = [];
                let short_address = [];
                address_data.filter((a) => long_address.push(a.long_name) && short_address.push(a.long_name));
                long_address = long_address.join(', ');
                short_address = short_address.join(', ');
                address_items.address_1 = {
                    short_name: short_address,
                    long_name: long_address
                }
                sg_address = address_items;
                sg_address.position = pos;
                document.getElementById('sg_delivery_address_' + section + '_new_address').value = sg_address.formatted_address;
                document.getElementById('sg_delivery_address_' + section + '_new_lat').value = pos.lat;
                document.getElementById('sg_delivery_address_' + section + '_new_lng').value = pos.lng;
                document.querySelectorAll('label[for="sg_delivery_address_' + section + '_new_address"]')[0].classList.add("active");
            }
        }, (error) => {
            console.log(error);
        });
    }

    function sgmta_user_locater_control(map) {

        var gm_locateme_control_div = document.createElement("div");
        gm_locateme_control_div.classList = "sg-locateme-control";
        gm_locateme_control_div.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(gm_locateme_control_div);

        // Set CSS for the control border.
        var sg_locateme_btn = document.createElement("div");
        sg_locateme_btn.classList = "sg-gm-user-locator";
        sg_locateme_btn.style.backgroundColor = "#fff";
        sg_locateme_btn.style.border = "2px solid #fff";
        sg_locateme_btn.style.borderRadius = "3px";
        sg_locateme_btn.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
        sg_locateme_btn.style.cursor = "pointer";
        sg_locateme_btn.style.marginBottom = "22px";
        sg_locateme_btn.style.marginRight = "10px";
        gm_locateme_control_div.appendChild(sg_locateme_btn); // Set CSS for the control interior.

        var sg_locateme_img = document.createElement("img");
        sg_locateme_img.style.padding = "5px";
        sg_locateme_img.width = "35";
        sg_locateme_img.height = "35";
        sg_locateme_img.src = document.getElementById('sg_del_add_map_user_locate_icon').value;
        sg_locateme_btn.appendChild(sg_locateme_img);

        return sg_locateme_btn;
    }

})(jQuery);