<?php

/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Warehouse_Popups_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Multi Pickup Wharehouses
 * Description:       Manages product inventory, currency, prices, & payments across multiple warehouses with a single site WP + Woocommerce install.
 * Version:           2.0.3
 * Author:            Иван Никитин и партнеры
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       warehouse-popups-woocommerce
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WMW_PLUGIN_VERSION', '2.0.3' );
define( 'WMW_PACKAGE_NAME', 'WMW' );

//add_action('plugins_loaded', 'geoip_detect_defines', 50 );

// Countries with enabled shipping zones
$SHIPPING_ZONES_ENABLED = array('GB', 'US', 'CA', 'DE');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-warehouse-popups-woocommerce-activator.php
 */
function activate_wh_popups_warehouses() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-warehouse-popups-woocommerce-activator.php';
	Warehouse_Popups_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-warehouse-popups-woocommerce-deactivator.php
 */
function deactivate_wh_popups_warehouses() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-warehouse-popups-woocommerce-deactivator.php';
	Warehouse_Popups_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wh_popups_warehouses' );
register_deactivation_hook( __FILE__, 'deactivate_wh_popups_warehouses' );

//BMC

//BMC

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-warehouse-popups-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wh_popups_warehouses() {

	$plugin = new Warehouse_Popups_Woocommerce();
	$plugin->run();

}
//run_wh_popups_warehouses();
add_action( 'plugins_loaded', 'run_wh_popups_warehouses', 15 ); //BMC

//add_action('muplugins_loaded', 'geoip_detect_defines', 15);
//add_action('plugins_loaded', 'geoip_detect_defines', 15 );

// The Jquery script
add_action( 'wp_footer', 'custom_popup_script' );
function custom_popup_script() {
    ?>
    <script type="text/javascript">
    jQuery( function($){
        $(document).on('change', 'select[name="billing_country"], #billing_address_1, #billing_city, #billing_address_2_field, #shipping_country, #shipping_address_1, #shipping_city, #shipping_address_2_field ', function(e){
            e.preventDefault();
            const form = $(this).parents('form');
            chage_warehouse_by_address( form );
        });

        function chage_warehouse_by_address( form ){
            const country = $('select[name="billing_country"]').val();
            const warehouse = $('#wh-popups-change-wh-select').val();
            const address = $('select[name="billing_country"]').find(':selected').text() + ' ' +  $('#billing_city').val() + ' ' + $('#billing_address_1').val() + ' ' + $('#billing_address_2_field').val();
            console.log(warehouse);

            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    "country" : country,
                    "address" : address,
                    "warehouse" : warehouse,
                    "action" : 'warehouse_popups_change_shipping_country'
                },
                success: function(data) {
                    if( data.data.to_change ){
                        $('#wh-popups-change-wh-select').val( data.data.new_warehouse_id ).trigger('change');
                    }
                }
            });
        };

    });
    </script>
    <?php
}
