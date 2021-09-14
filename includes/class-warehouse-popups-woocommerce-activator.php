<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce_Activator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        if (!function_exists('is_plugin_active_for_network')) {
            include_once(ABSPATH . '/wp-admin/includes/plugin.php');
        }

        if (!current_user_can('activate_plugins')) {
            // Deactivate the plugin.
            deactivate_plugins(plugin_basename(__FILE__));

            $error_message = __('You do not have proper authorization to activate a plugin!', 'warehouse-popups-woocommerce');
            die(esc_html($error_message));
        }

        if (!class_exists('WooCommerce')) {
            // Deactivate the plugin.
            deactivate_plugins(plugin_basename(__FILE__));
            // Throw an error in the WordPress admin console.
            $error_message = __('This plugin requires ', 'warehouse-popups-woocommerce') . '<a href="' . esc_url('https://wordpress.org/plugins/woocommerce/') . '" target="_blank">WooCommerce</a>' . __(' plugin to be active!', 'warehouse-popups-woocommerce');
            die(wp_kses_post($error_message));
        }
    }

}
