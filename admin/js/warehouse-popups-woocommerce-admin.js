(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function () {
        $(".warehouse-popups-woocommerce-list-save").mousedown(function () {
            //$(window).unbind();
            window.onbeforeunload = null;
        });

        // handle Country / Zone shipping option change
        $('input:checkbox[name=edit-warehouse-shipping-country], input:checkbox[name=new-warehouse-shipping-country]').click(function () {
            if ($(this).prop("checked") == true) {
                $("#shipping-country-mode").removeClass('hidden');
                $("#new-warehouse-shipping-zone,#edit-warehouse-shipping-zone").attr('disabled', 'disabled');
            } else if ($(this).prop("checked") == false) {
                $("#shipping-country-mode").addClass('hidden');
                $("#new-warehouse-shipping-zone,#edit-warehouse-shipping-zone").removeAttr('disabled');
            }
        });

        $('input:checkbox[name=edit-warehouse-shipping-zone], input:checkbox[name=new-warehouse-shipping-zone]').click(function () {
            if ($(this).prop("checked") == true) {
                $("#shipping-zone-mode").removeClass('hidden');
                $("#new-warehouse-shipping-country,#edit-warehouse-shipping-country").attr('disabled', 'disabled');
            } else if ($(this).prop("checked") == false) {
                $("#shipping-zone-mode").addClass('hidden');
                $("#new-warehouse-shipping-country,#edit-warehouse-shipping-country").removeAttr('disabled');
            }
        });

        // handle Country / Zone shipping option change
        $("#warehouse-zone-country").click(function () {
            var selected_zone_country = $(this).val(),
                zip_codes_cont = $("#warehouse-zone-zip-codes").closest('tr');

            if (selected_zone_country == 'GB' || selected_zone_country == 'US' || selected_zone_country == 'CA' || selected_zone_country == 'DE') {
                zip_codes_cont.removeClass('hidden');
            } else {
                zip_codes_cont.addClass('hidden');
            }

            return true;
        });

        // handle Enable checkbox click, just submit parent form
        // if ( $('#wh_popups_warehouses_enabled').length > 0 )
        // {
        //         $('#wh_popups_warehouses_enabled').click(function(){
        //         	var parent_form = $(this).closest("form");
        //         	parent_form.submit();
        // 	});
        // }

        // handle creation of new warehouse
        if ($("#warehouse-popups-woocommerce-list-add-new-cont").length > 0) {
            var add_btn = $("#warehouse-popups-woocommerce-list-add-btn"),
                cancel_btn = $("#warehouse-popups-woocommerce-list-cancel-btn"),
                save_btn = $("#warehouse-popups-woocommerce-list-save"),
                add_new_cont = $("#warehouse-popups-woocommerce-list-add-new-cont"),
                shipping_cont_select = $("#shipping-country-select"),
                shipping_cont_mode = $("#shipping-country-mode");

            // click on add new warehouse
            add_btn.click(function () {
                add_btn.addClass('hidden');
                cancel_btn.removeClass('hidden');
                add_new_cont.removeClass('hidden');
                shipping_cont_select.removeClass('hidden');
                shipping_cont_mode.addClass('hidden');
                save_btn.removeClass('hidden').prop("disabled", false);
                window.onbeforeunload = null;

                return false;
            });

            // click on cancell
            cancel_btn.click(function () {
                add_btn.removeClass('hidden');
                cancel_btn.addClass('hidden');
                add_new_cont.addClass('hidden');
                shipping_cont_select.addClass('hidden');
                shipping_cont_mode.addClass('hidden');
                save_btn.addClass('hidden').prop("disabled", false);

                $('input[type="text"]', add_new_cont).val('');
                window.onbeforeunload = null;

                return false;
            });

            save_btn.click(function () {
                window.onbeforeunload = null;
            });

        }

        if ($('#warehouse-popups-woocommerce-edit-save-btn').length > 0) {
            $('#warehouse-popups-woocommerce-edit-save-btn').click(function (e) {
                window.onbeforeunload = null;
            });
        }

        // show countries list in Woo Warehouses settings
        if ($("#wh-popups-see-country-codes-list").length > 0) {
            $("#wh-popups-see-country-codes-list").click(function () {
                var list_obj = $("#wh-popups-all-countries-codes");
                list_obj.toggleClass('hidden');

                return false;
            });
        }

        $('body').on('change', 'input:radio[name=edit-warehouse-convert-ratio]', function () {
            if ($(this).val().indexOf('cur-convert-') >= 0) {
                $("table.edit-warehouse-cont #currency-api-key").addClass('active');
            } else {
                $("table.edit-warehouse-cont #currency-api-key").removeClass('active');
            }
        });

        $('body').on('change', 'input:radio[name=new-warehouse-convert-ratio]', function () {
            if ($(this).val().indexOf('cur-convert-') >= 0) {
                $("table.new-warehouse-cont #currency-api-key").addClass('active');
            } else {
                $("table.new-warehouse-cont #currency-api-key").removeClass('active');
            }
        });

        $("body").on('click', '#warehouse-popups-woocommerce-manage-inventory', function (e) {
            e.preventDefault();
            $('#apiKey-response-message').text('');
            $("#manage-inventory-popup").addClass('active');
            $("#manage-inventory-popup .inventory-form").addClass('active');
        });

        $("body").on('click', '#manage-inventory-popup .popup-background', function (e) {
            e.preventDefault();

            if (!$("#manage-inventory-popup .inventory-form").hasClass('waiting')) {
                $("#manage-inventory-popup").removeClass('active');
                $("#manage-inventory-popup .inventory-form").removeClass('active');
            }
        });

        $("body").on('click', '#manage-inventory-popup .close-btn', function (e) {
            e.preventDefault();

            $("#manage-inventory-popup").removeClass('active');
            $("#manage-inventory-popup .inventory-form").removeClass('active');
        });

        $("body").on('click', '#manage-inventory-popup .btn-cancel', function (e) {
            e.preventDefault();

            $("#manage-inventory-popup").removeClass('active');
            $("#manage-inventory-popup .inventory-form").removeClass('active');
        });

        $("body").on('click', '#warehouse-popups-woocommerce-inventory-save', function (e) {
            e.preventDefault();
            $("#manage-inventory-popup .inventory-form").addClass('waiting');

            var action_url = $(this).attr("data-action"),
                api_key = $("#venby-api-key").val(),
                data = {
                    'api_key': api_key,
                    'action': 'save_api_key',
                    '_nonce': $("input[name='wh-nonce']").val()
                }

            if (api_key) {
                $.ajax({
                    type: "POST",
                    url: action_url,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        $("#manage-inventory-popup .inventory-form").removeClass('waiting');
                        if (response.type == "success") {
                            $('#apiKey-response-message').text(response.message);
                            $("#manage-inventory-popup").removeClass('active');
                            $("#manage-inventory-popup .inventory-form").removeClass('active');
                        } else {
                            $('#apiKey-response-message').text(response.message);
                        }
                    }
                });
            }
        });

        $("body").on('click', '#warehouse-popups-woocommerce-pro-manage-google', function (e) {
            e.preventDefault();
            $('#apiKey-response-message').text('');
            $("#manage-google-popup").addClass('active');
            $("#manage-google-popup .google-form").addClass('active');
        });

        $("body").on('click', '#manage-google-popup .popup-background', function (e) {
            e.preventDefault();

            if (!$("#manage-google-popup .google-form").hasClass('waiting')) {
                $("#manage-google-popup").removeClass('active');
                $("#manage-google-popup .google-form").removeClass('active');
            }
        });

        $("body").on('click', '#manage-google-popup .close-btn', function (e) {
            e.preventDefault();

            $("#manage-google-popup").removeClass('active');
            $("#manage-google-popup .google-form").removeClass('active');
        });

        $("body").on('click', '#manage-google-popup .btn-cancel', function (e) {
            e.preventDefault();

            $("#manage-google-popup").removeClass('active');
            $("#manage-google-popup .google-form").removeClass('active');
        });

        $("body").on('click', '#warehouse-popups-woocommerce-pro-google-save', function (e) {
            e.preventDefault();
            $("#manage-google-popup .google-form").addClass('waiting');

            var action_url = $(this).attr("data-action"),
                google_api_key = $("#google-api-key").val(),
                data = {
                    'google_api_key': google_api_key,
                    'action': 'save_google_api_key',
                    '_nonce': $("input[name='wh-nonce']").val()
                }

            if (google_api_key) {
                $.ajax({
                    type: "POST",
                    url: action_url,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        $("#manage-google-popup .google-form").removeClass('waiting');
                        if (response.type == "success") {
                            $('#apiKey-response-message').text(response.message);
                            $("#manage-google-popup").removeClass('active');
                            $("#manage-google-popup .google-form").removeClass('active');
                        } else {
                            $('#apiKey-response-message').text(response.message);
							$('#manage-google-popup').removeClass('active');
                        }
                    }
                });
            }
        })
    });
})(jQuery);
