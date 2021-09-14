<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * The specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 */

/**
 * The specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the specific stylesheet and JavaScript.
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce_Activation
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private static $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        self::$version = $version;

    }

    /**
     * Activation
     *
     */
    public function wh_activation()
    {

    }

    public function wh_deactivation()
    {

    }

    public function wh_schedule_check()
    {

    }

    public static function wh_activation_form()
    {
        ?>
        <h2>Unlock Unlimited Warehouses</h2>
        <form>
            <div class="form-group">
                <input type="text" class="forminp forminp-text" placeholder="Licence Key">
                <button class="button button-primary" type="submit">Unlock</button>
                <button class="button button-secondary" type="button">Buy Licence</button>
            </div>
            <p>Free version can add just one Warehouse. To add Unlimited Warehouse, buy PRO version to get licence. Buy
                the PRO version Licence <a href="https://venby.io">here</a></p>
        </form><br><br>
        <?php
    }
}