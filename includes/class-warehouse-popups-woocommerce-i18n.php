<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'warehouse-popups-woocommerce',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
