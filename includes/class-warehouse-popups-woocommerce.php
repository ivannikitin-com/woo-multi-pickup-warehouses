<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Warehouse_Popups_Woocommerce_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WMW_PLUGIN_VERSION')) {
            $this->version = WMW_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'warehouse-popups-woocommerce';

        if ($this->woo_detect()) {
            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
            $this->define_public_hooks();
			//add_action('plugins_loaded', array( $this, 'define_public_hooks' )); //BMC
            $this->define_helper_hooks();
        }
    }

    public function woo_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('WooCommerce Additional Variation Images Plugin requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-additional-variation-images'), '<a href="http://www.woocommerce.com/" target="_blank">WooCommerce</a>') . '</p></div>';
    }

    private function woo_detect()
    {
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            // Put your plugin code here
            add_action('admin_notices', array($this, 'woo_missing_notice'));
            //add_action('plugins_loaded', 'geoip_detect_defines', 15);
        } else return true;
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Warehouse_Popups_Woocommerce_Loader. Orchestrates the hooks of the plugin.
     * - Warehouse_Popups_Woocommerce_i18n. Defines internationalization functionality.
     * - Warehouse_Popups_Woocommerce_Admin. Defines all hooks for the admin area.
     * - Warehouse_Popups_Woocommerce_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-warehouse-popups-woocommerce-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-warehouse-popups-woocommerce-i18n.php';

        /**
         * The class responsible for defining helper functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-warehouse-popups-woocommerce-helper.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-warehouse-popups-woocommerce-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-warehouse-popups-woocommerce-public.php';

        $this->loader = new Warehouse_Popups_Woocommerce_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Warehouse_Popups_Woocommerce_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Warehouse_Popups_Woocommerce_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the helper functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_helper_hooks()
    {
        $plugin_helper = new Warehouse_Popups_Woocommerce_Helper($this->get_plugin_name(), $this->get_version());
        // $this->loader->add_action( 'init', $plugin_helper, 'wh_get_client_ip_data');
        // $this->loader->add_action( 'init', $plugin_helper, 'wh_get_geoip_data');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Warehouse_Popups_Woocommerce_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_settings_tabs', $plugin_admin, 'woo_add_warehouses_section');
        $this->loader->add_action('woocommerce_settings_warehouses', $plugin_admin, 'warehouse_popups_tab_content');
        $this->loader->add_action('woocommerce_update_options_warehouses', $plugin_admin, 'update_settings');
        $this->loader->add_action('woocommerce_product_options_stock_fields', $plugin_admin, 'woo_inventory_single_stock_content');
        $this->loader->add_action('woocommerce_product_bulk_and_quick_edit', $plugin_admin, 'woo_edit_product_hook');
        $this->loader->add_action('woocommerce_variation_options_inventory', $plugin_admin, 'woo_variable_variation_stock_content', 10, 3);
        $this->loader->add_action('woocommerce_save_product_variation', $plugin_admin, 'woo_edit_variation_hook', 10, 2);
        $this->loader->add_action('wp_ajax_save_api_key', $plugin_admin, 'warehouse_popups_save_api_key', 10, 0);
        $this->loader->add_action('wp_ajax_save_google_api_key', $plugin_admin, 'warehouse_popups_save_google_api_key', 10, 0);
        $this->loader->add_filter('plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 4);
        $this->loader->add_action( 'woocommerce_after_order_itemmeta', $plugin_admin, 'custom_checkout_field_display_admin_order_meta', 10, 3);
        //$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'custom_checkout_field_display_admin_order_meta', 10, 1 );
		
        //$this->loader->add_action( 'admin_enqueue_scripts-wc-settings', $plugin_admin, 'enqueue_styles' ); //BMC
        //$this->loader->add_action( 'admin_enqueue_scripts-wc-settings', $plugin_admin, 'enqueue_scripts' ); //BMC
       	//$this->loader->add_action( 'woocommerce_update_options', $plugin_admin, 'update_settings' ); //BMC
        //$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'setting_links', 10, 2 ); //BMC
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Warehouse_Popups_Woocommerce_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_public, 'handle_switch_form_submit');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_checkout_create_order_line_item', $plugin_public, 'woo_checkout_create_order_line_item', 10, 4);
        $this->loader->add_action('woocommerce_reduce_order_stock', $plugin_public, 'woo_reduce_order_stock', 10, 1);
        $this->loader->add_action('wp_ajax_warehouse_popups_change_shipping_country', $plugin_public, 'warehouse_popups_change_shipping_country', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_warehouse_popups_change_shipping_country', $plugin_public, 'warehouse_popups_change_shipping_country', 10, 0);
        $this->loader->add_filter('woocommerce_product_is_in_stock', $plugin_public, 'wh_popups_is_in_stock', 10, 2);
        $this->loader->add_filter('woocommerce_product_get_stock_quantity', $plugin_public, 'wh_popups_get_stock_quantity', 10, 2);
        $this->loader->add_filter('woocommerce_product_get_backorders', $plugin_public, 'wh_popups_get_backorders', 10, 2);
        $this->loader->add_filter('woocommerce_product_get_stock_status', $plugin_public, 'wh_popups_get_stock_status', 10, 2);
        $this->loader->add_filter('woocommerce_product_variation_get_stock_quantity', $plugin_public, 'wh_popups_get_stock_quantity', 10, 2);
        $this->loader->add_filter('woocommerce_product_variation_get_backorders', $plugin_public, 'wh_popups_get_backorders', 10, 2);
        $this->loader->add_filter('woocommerce_product_variation_get_stock_status', $plugin_public, 'wh_popups_get_stock_status', 10, 2);
        $this->loader->add_filter('woocommerce_add_cart_item_data', $plugin_public, 'wh_popups_add_cart_item_data', 10, 3);
        $this->loader->add_filter('woocommerce_get_item_data', $plugin_public, 'wh_popups_get_item_data', 10, 2);
        $this->loader->add_filter('woocommerce_order_item_quantity', $plugin_public, 'wh_popups_filter_item_quantity', 10, 3);
        $this->loader->add_filter('woocommerce_get_cart_item_from_session', $plugin_public, 'wh_popups_cart_item_from_session', 10, 3);
        $this->loader->add_filter('woocommerce_add_order_item_meta', $plugin_public, 'wh_popups_add_order_item_meta', 10, 3);
        $this->loader->add_filter('woocommerce_currency', $plugin_public, 'wh_popups_override_currency', 10, 1);
        $this->loader->add_filter('woocommerce_currency_symbol', $plugin_public, 'woocommerce_custom_currency_symbol', 10, 2);
        $this->loader->add_filter('woocommerce_available_payment_gateways', $plugin_public, 'wh_popups_hide_denied_gateways', 10, 1);
        $this->loader->add_filter('woocommerce_product_get_price', $plugin_public, 'wh_popups_override_price', 10, 2);
        $this->loader->add_filter('woocommerce_product_get_regular_price', $plugin_public, 'wh_popups_override_price', 10, 2);
        $this->loader->add_filter('woocommerce_product_variation_get_regular_price', $plugin_public, 'wh_popups_override_price', 10, 2);
        $this->loader->add_filter('woocommerce_product_variation_get_price', $plugin_public, 'wh_popups_override_price', 10, 2);
        $this->loader->add_filter('woocommerce_price_filter_widget_min_amount', $plugin_public, 'wh_popups_override_price_filter_min_amount', 10, 1);
        $this->loader->add_filter('woocommerce_price_filter_widget_max_amount', $plugin_public, 'wh_popups_override_price_filter_max_amount', 10, 1);
        $this->loader->add_filter('woocommerce_variation_prices_price', $plugin_public, 'wh_popups_override_variable_price', 10, 3);
        $this->loader->add_filter('woocommerce_variation_prices_regular_price', $plugin_public, 'wh_popups_override_variable_price', 10, 3);
        $this->loader->add_filter('woocommerce_get_variation_prices_hash', $plugin_public, 'wh_popups_override_variable_price_hash', 10, 1);
        $this->loader->add_filter('woocommerce_before_calculate_totals', $plugin_public, 'wh_popups_override_cart_price_totals', 9999, 1);
        $this->loader->add_filter('woocommerce_before_mini_cart', $plugin_public, 'wh_popups_override_before_mini_cart', 9999, 0);
        $this->loader->add_filter('woocommerce_after_mini_cart', $plugin_public, 'wh_popups_override_after_mini_cart', 9999, 0);
        // change number of decimal(round up setting)
        $this->loader->add_filter('wc_get_price_decimals', $plugin_public, 'wh_popups_override_price_decimals', 10, 1);
        // before cart action
        $this->loader->add_filter('woocommerce_before_cart', $plugin_public, 'wh_popups_override_before_cart', 10, 1);
        $this->loader->add_filter('woocommerce_review_order_before_cart_contents', $plugin_public, 'wh_popups_override_before_cart', 10, 0);
        $this->loader->add_short_code('wh_popups_warehouses_switch', $plugin_public, 'woo_switch_content');
        $this->loader->add_short_code( 'wh_popups_warehouses_flybox', $plugin_public, 'woo_switch_flybox_content');
        $this->loader->add_short_code( 'wh_popups_warehouse_client_ip_data', $plugin_public, 'wh_client_ip_data' ); //BMC
        $this->loader->add_action( 'wp_footer', $plugin_public, 'woo_switch_hidden_flybox' ); //BMC
        $this->loader->add_filter(' woocommerce_product_get_stock_quantity', $plugin_public, 'wh_popups_get_stock_quantity', 10, 2 ); //BMC
        $this->loader->add_filter( 'woocommerce_product_variation_get_stock_quantity', $plugin_public, 'wh_popups_get_stock_quantity', 10, 2 ); //BMC
        $this->loader->add_action( 'woocommerce_after_shipping_rate', $plugin_public, 'warehouse_select', 10, 2 );
        $this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'warehouse_checkout_field_process');
        $this->loader->add_filter( 'woocommerce_checkout_posted_data', $plugin_public, 'add_order_custom_fields' );
        $this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'checkout_update_order_meta', 10, 2 );
        $this->loader->add_action( 'woocommerce_reduce_order_stock', $plugin_public, 'reduce_order_selected_stock' );
        $this->loader->add_filter( 'woocommerce_get_order_item_totals', $plugin_public, 'custom_order_meta_to_totals', 25, 2 );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        if (isset($this->loader)) {
            $this->loader->run();
        }
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Warehouse_Popups_Woocommerce_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
