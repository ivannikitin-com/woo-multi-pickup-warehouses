<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require 'vendor/autoload.php';

use GeoIp2\Database\Reader;

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/public
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce_Public
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
    private $version;

    /**
     * The global currency code
     * @var      string $global_currency_code
     */
    private $global_currency_code;

    /**
     * The current currency code
     * @var      string $current_currency_code
     */
    private $current_currency_code;

    /**
     * The currency ratio
     * @var float $currency_ratio
     */
    private $currency_ratio;

    /**
     * The client geoip data
     * @var array $currency_ratio
     */
    private $client_data;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->global_currency_code = 'USD';
        $this->current_currency_code = 'USD';
        $this->currency_ratio = 1.0;
        //$this->client_data = $this->get_geoip_data();
        // $this->helper = $this->wh_load_helper();
    }

    public function wh_load_helper()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-warehouse-popups-woocommerce-helper.php';

        $helper = new Warehouse_Popups_Woocommerce_Helper($this->plugin_name, $this->version);

        return $helper;
    }

    // public function client_data() {
    public static function client_data()
    {
        return self::get_geoip_data();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Warehouse_Popups_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Warehouse_Popups_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/warehouse-popups-woocommerce-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Warehouse_Popups_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Warehouse_Popups_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/warehouse-popups-woocommerce-public.js', array('jquery'), $this->version, false);
    }

    private static function get_selected_warehouse($count_on_default = false)
    {
        if (!$count_on_default) {
            // only alternative warehouses will be counted
            $selected_warehouse = (isset($_COOKIE['wh-popups-selected-warehouse']) && $_COOKIE['wh-popups-selected-warehouse'] != 'default') ? $_COOKIE['wh-popups-selected-warehouse'] : false;
        } else {
            // any warehouse selected, even default will return true
            $selected_warehouse = (isset($_COOKIE['wh-popups-selected-warehouse'])) ? true : false;
        }

        return $selected_warehouse;
    }

    private static function get_selected_warehouse_details()
    {
        $selected_warehouse = self::get_selected_warehouse();
        $alt_warehouses_list = self::get_alt_warehouses_list();

        return (isset($alt_warehouses_list[$selected_warehouse])) ? $alt_warehouses_list[$selected_warehouse] : false;
    }

    private static function get_warehouse_details($warehouse_id)
    {
        if (!$warehouse_id) return false;

        $alt_warehouses_list = self::get_alt_warehouses_list();

        return (isset($alt_warehouses_list[$warehouse_id])) ? $alt_warehouses_list[$warehouse_id] : false;
    }

    private static function get_alt_warehouses_list()
    {
        return (array)json_decode(get_option('warehouse-popups-woocommerce-list'), true);
    }

    public function woo_switch_hidden_flybox()
    {
        $alt_warehouses_list = self::get_alt_warehouses_list();
        $selected_warehouse = self::get_selected_warehouse();
        $countries_obj = new WC_Countries();
        $countries = $countries_obj->__get('countries');
        $base_country = $countries_obj->get_base_country();
        $base_country_title = (isset($countries[$base_country])) ? $countries[$base_country] : $base_country;
        $client_data = self::client_data();

        ?>
        <div class="wh_flybox_popup">
            <div class="wh_flybox_popup_overlay"></div>
            <div class="wh_flybox_popup_content">
                <div class="wh_flybox_close_btn_container" title="Click to Close">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         x="0px" y="0px" viewBox="0 0 22.6 22.6"
                         style="height: 100%; width: 100%; position: absolute; top: 0; left: 0;" xml:space="preserve"
                         preserveAspectRatio="none">
                        <rect x="8.3" y="-1.7" transform="matrix(0.7071 0.7071 -0.7071 0.7071 11.3137 -4.6863)"
                              width="6" height="26"></rect>
                        <rect x="8.3" y="-1.7" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 27.3137 11.3137)"
                              width="6" height="26"></rect>
                    </svg>
                </div>

                <h3><?php echo __('Select Your Location:', 'warehouse-popups-woocommerce'); ?></h3>
                <div class="wh_flybox_warehouses_list">
                    <form method="post">
                        <label class="wh_flybox_button <?php if (!empty($selected_warehouse) && $selected_warehouse === 'default') echo 'checked'; ?>"
                               for="wh-popups-change-default">
                            <input <?php if (!empty($selected_warehouse) && $selected_warehouse === 'default') echo 'checked'; ?>
                                    type="radio" name="wh_popups_change_wh_to" value="default"
                                    id="wh-popups-change-default"><?php echo $base_country_title; ?>
                        </label><?php
                        foreach ($alt_warehouses_list as $one_wh) {
                            $checked_flag = '';
                            if ($selected_warehouse) {
                                if ($selected_warehouse === $one_wh['id']) $checked_flag = 'checked';
                            } else {
                                if (is_array($one_wh['countries'])) {
                                    if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean' && $_REQUEST['wh_popups_change_wh_to'] != 'default') $checked_flag = 'checked';
                                } else {
                                    if ($client_data['name'] === $one_wh['countries']) {
                                        $checked_flag = 'checked';
                                    }
                                }
                            }
                            ?><label class="wh_flybox_button <?php echo $checked_flag; ?>"
                                     for="wh-popups-change-<?php echo $one_wh['id'] ?>">
                            <input <?php echo $checked_flag; ?> type="radio" name="wh_popups_change_wh_to"
                                                                value="<?php echo $one_wh['id'] ?>"
                                                                id="wh-popups-change-<?php echo $one_wh['id'] ?>"><?php echo $one_wh['name'] ?>
                            </label><?php
                        }
                        wp_nonce_field('wh_popups_change_wh', 'wh_popups_change_wh_nonce'); ?>
                        <input type="hidden" name="action" value="wh_popups_warehouse_change">
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    public function woo_switch_flybox_content()
    {
        ob_start();

        $is_wh_popups = get_option('wh_popups_warehouses_enabled');
        //if ( $is_wh_popups == 'yes' ) {
        $alt_warehouses_list = self::get_alt_warehouses_list();
        $selected_warehouse = self::get_selected_warehouse();
        $countries_obj = new WC_Countries();
        $countries = $countries_obj->__get('countries');
        $base_country = $countries_obj->get_base_country();
        $base_country_title = (isset($countries[$base_country])) ? $countries[$base_country] : $base_country;
        $client_data = self::client_data();

        if (empty($selected_warehouse)) {
            if (isset($_REQUEST['wh_popups_change_wh_to']) != 'default') {
                foreach ((array)$alt_warehouses_list as $one_wh) {
                    if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                        $selected_warehouse_title = $one_wh['name'];
                        break;
                    } else {
                        $selected_warehouse_title = $base_country_title;
                    }
                }
            } else {
                $selected_warehouse_title = $base_country_title;
            }
        } else {
            $selected_warehouse_title = $alt_warehouses_list[$selected_warehouse]['name'];
        }

        if (sizeof((array)$alt_warehouses_list) > 0) {
            // hook closing body tag to display hidden flybox
            add_action('wp_footer', array($this, 'woo_switch_hidden_flybox'));
        }

        ?>
        <a href="#" title="<?php echo __('Click to change your warehouse', 'warehouse-popups-woocommerce') ?>"
           class="wh_popups_flybox_switch"><?php echo $selected_warehouse_title; ?></a><?php
        //}

        $flybox_html = ob_get_contents();
        ob_end_clean();

        return $flybox_html;
    }

    public function wh_client_ip_data()
    {
        echo "<div>IP: {$_COOKIE['wh_client_ip_data']} <br>";
        $dataip = json_decode(stripslashes($_COOKIE['wh_geoip_data']), true);
        // echo $dataip['country_name'];
        // echo '<br>';
        // echo $dataip['country_code1'];
        // echo '<br>';
        // echo $dataip['country_code2'];
        // echo '<br>';
        // echo $dataip['region'];
        // echo '<br>';
        // echo $dataip['region_name'];
        // echo '<br>';
        // echo $dataip['city'];
        // echo '<br>';
        // echo $dataip['district'];
        // echo '<br>';
        // echo $dataip['zip'];
        // echo '<br>';
        // echo $dataip['longitude'];
        // echo '<br>';
        // echo $dataip['latitude'];
        // echo '<br>';
        // echo $dataip['timezone'];
        // echo '<br>';
        // echo $dataip['currency'];
        //unset($_COOKIE['wh_geoip_data']);
        echo '</div>';
    }

    public function woo_switch_content()
    {
        //return self::get_alt_warehouses_list();
        ob_start();

        $alt_warehouses_list = self::get_alt_warehouses_list();
        $selected_warehouse = self::get_selected_warehouse();
        $countries_obj = new WC_Countries();
        $countries = $countries_obj->__get('countries');
        $base_country = $countries_obj->get_base_country();
        $base_country_title = (isset($countries[$base_country])) ? $countries[$base_country] : $base_country;
        $client_data = self::client_data();

        if (sizeof($alt_warehouses_list) > 0) {
            ?>
            <div class="wh-popups-wh-switch">
            <form method="post">
                <select name="wh_popups_change_wh_to" id="wh-popups-change-wh-select">
                <option disabled="disabled">Select Warehouse</option>
                    <option value="default" <?php if (!$selected_warehouse) echo 'selected'; ?>><?php echo $base_country_title; ?></option><?php
                    foreach ($alt_warehouses_list as $one_wh) {
                        ?>
                        <option value="<?php echo $one_wh['id'] ?>" <?php
                        if ($selected_warehouse) {
                            if ($selected_warehouse === $one_wh['id']) echo 'selected';
                        } else {
                            if (is_array($one_wh['countries'])) {
                                if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean' && isset($_REQUEST['wh_popups_change_wh_to']) != 'default') echo 'selected';
                            } else {
                                if ($client_data['name'] === $one_wh['country']) echo 'selected';
                            }
                        }
                        ?>
                        ><?php echo $one_wh['name'] ?></option><?php
                    }
                    ?>
                </select><?php wp_nonce_field('wh_popups_change_wh', 'wh_popups_change_wh_nonce'); ?>
                <input type="hidden" name="action" value="wh_popups_warehouse_change">
            </form>
            </div><?php
        }
        //}

        $switch_html = ob_get_contents();
        ob_end_clean();

        return $switch_html;
    }

    // set and unset warehouse cookies
    private static function set_warehouse_cookie($selected_warehouse = null)
    {
        if (!is_null($selected_warehouse)) {
            setcookie("wh-popups-selected-warehouse", $selected_warehouse, time() + 36000, '/');
            $_COOKIE['wh-popups-selected-warehouse'] = $selected_warehouse;
            if (WC() && WC()->session) {
                $alt_warehouses_list = self::get_alt_warehouses_list();
                foreach ($alt_warehouses_list as $one_wh) {
                    WC()->session->set('wh-popups-selected-warehouse', array('warehouse_id' => $one_wh['id'], 'warehouse_email' => $one_wh['email']));
                }
            }
        } else {
            // unset cookies
            setcookie('wh-popups-selected-warehouse', null, -1, '/');
            unset($_COOKIE['wh-popups-selected-warehouse']);
        }
    }

    public static function send_order_email_to_warehouse($recipient, $order)
    {
        $warehouse_email = $order->get_meta('wh-popups-selected-warehouse')['warehouse_email'];

        if ($warehouse_email) {
            $recipient = $recipient . ', ' . $warehouse_email;
        }

        return $recipient;
    }

    function set_order_warehouse($order)
    {
        $warehouse = WC()->session->get('wh-popups-selected-warehouse');

        if (isset($warehouse['warehouse_id'])) {
            $order->update_meta_data('warehouse_id', $warehouse['warehouse_id']);
            $order->update_meta_data('warehouse_email', $warehouse['email']);
        }

        WC()->session->__unset('wh-popups-selected-warehouse'); // Remove session variable
    }

    // automatically detect location and switch warehouse
    private static function auto_warehouse_switch()
    {
        global $SHIPPING_ZONES_ENABLED;

        $found_warehouse = false;

        if (isset($_SERVER['HTTP_GEOIP_COUNTRY_CODE'])) {
            $geo_country_code = trim(strtoupper($_SERVER['HTTP_GEOIP_COUNTRY_CODE']));
        } else {
			/*$geo_url = 'http://www.geoplugin.net/json.gp?ip=' . $_SERVER['REMOTE_ADDR'];
			$geo_json = self::curl_get_contents($geo_url);
			$geo_object = json_decode($geo_json, true);
			$geo_country_code = trim(strtoupper($geo_object['geoplugin_countryCode']));*/
			
			//$geoInfo			= geoip_detect2_get_info_from_current_ip();
			//$geo_country_code	= ( $geoInfo->country->isoCode );
			//$geo_postal_code=($geoInfo->postal->code);
			
			//BMC
			$ip = self::get_client_ip();
			$cache_data = self::_wmw_get_data_from_cache( $ip );
	
			if( $cache_data ){
				$geo_country_code = $cache_data['country_code'];
			}else{
				$json           	= self::_wmw_get_geo_address( $ip ); //BMC
				$result         	= json_decode( $json, true );
				$geo_country_code	= $result['country_code'];
				
				self::_wmw_add_data_to_cache( array( 'country_name' => $result['country_name'], 'country_code' => $result['country_code'] ), $ip );
			}
			//BMC
        }

        $alt_warehouses_list = self::get_alt_warehouses_list();
        if (is_array($alt_warehouses_list) && sizeof($alt_warehouses_list) > 0) {
            //  first check by shipping zone only for countries from zones enabled list
            if (in_array($geo_country_code, $SHIPPING_ZONES_ENABLED)) {
                foreach ($alt_warehouses_list as $one_wh) {
                    $wh_zone_country = trim(strtoupper($one_wh['zone_country']));
                    $wh_zone_zip_codes = (is_array($one_wh['zone_zip_codes']) && sizeof($one_wh['zone_zip_codes']) > 0) ? $one_wh['zone_zip_codes'] : array();

                    if (empty($wh_zone_country)) continue; // this warehouse has no shipping zone

                    if ($wh_zone_country === $geo_country_code && is_array($wh_zone_zip_codes) && sizeof($wh_zone_zip_codes) > 0) {
                        $found_warehouse = $one_wh['id'];
                        break; // warehouse found by country code and postal code stop loop
                    } else if ($wh_zone_country === $geo_country_code) {
                        $found_warehouse = $one_wh['id'];
                        continue; // warehouse found by country code, but continue to search try to find better match with country and postal code
                    }
                }
            }

            // second check by country
            if (!$found_warehouse) {
                foreach ($alt_warehouses_list as $one_wh) {
                    if (!$one_wh['countries']) continue;
                    $wh_countries = $one_wh['countries'];

                    if (is_array($wh_countries) && sizeof($wh_countries) > 0 && in_array($geo_country_code, $wh_countries)) {
                        $found_warehouse = $one_wh['id'];
                        break;
                    }
                }
            }
        }

        if ($found_warehouse !== false) {
            self::set_warehouse_cookie($found_warehouse); // set
        }
    }

    // process select warehouse form submission here
    public static function handle_switch_form_submit()
    {
        // run only when GeoTarget plugin enabled and warehouse was not yet manually selected
        if (!self::get_selected_warehouse(true)) {
            self::auto_warehouse_switch();
        }

        // process switch warehouse form data
        if (isset($_POST['wh_popups_change_wh_nonce']) && wp_verify_nonce($_POST['wh_popups_change_wh_nonce'], 'wh_popups_change_wh')) {
            $selected_warehouse = (isset($_POST['wh_popups_change_wh_to'])) ? sanitize_text_field($_POST['wh_popups_change_wh_to']) : false;

            if ($selected_warehouse && $selected_warehouse === 'default') {
                // setcookie('wh-popups-selected-warehouse', null, -1, '/');
                // unset($_COOKIE['wh-popups-selected-warehouse']);
                //self::set_warehouse_cookie(null); //remove
                self::set_warehouse_cookie($selected_warehouse); // set
            } else if (!empty($selected_warehouse && $selected_warehouse)) {
                // setcookie("wh-popups-selected-warehouse", $selected_warehouse, time()+3600, '/');
                // $_COOKIE['wh-popups-selected-warehouse'] = $selected_warehouse;
                self::set_warehouse_cookie($selected_warehouse); // set
            }
        }
    }

    public static function wh_popups_get_stock_quantity($quantity, $product)
    {
        if ($product->managing_stock()) {
            $selected_warehouse = self::get_selected_warehouse_details();

            if ($selected_warehouse !== false && intval($selected_warehouse['use-default']) === 0) {
                if ($product->is_type('simple')) {
                    //WC_Product_Simple
                    $quantity = intval($product->get_meta('alt_wh_stock_' . $selected_warehouse['id'], true));
                } else if ($product->is_type('variation')) {
                    //WC_Product_Variation;
                    $quantity = intval($product->get_meta('alt_wh_stock_' . $selected_warehouse['id'], true));
                }
            }
        }

        return $quantity;
    }

    public static function wh_popups_get_backorders($backorder, $product)
    {
        if ($product->managing_stock()) {
            $selected_warehouse = self::get_selected_warehouse_details();

            if ($selected_warehouse !== false && intval($selected_warehouse['use-default']) === 0) {
                if ($product->is_type('simple')) {
                    //WC_Product_Simple
                    $backorder = trim($product->get_meta('alt_wh_backorder_' . $selected_warehouse['id'], true));
                } else if ($product->is_type('variation')) {
                    //WC_Product_Variation;
                    $backorder = trim($product->get_meta('alt_wh_backorder_' . $selected_warehouse['id'], true));
                }
            }
        }

        return $backorder;
    }

    public static function wh_popups_is_in_stock($status, $product)
    {
        if ($product->managing_stock()) {
            if ($product->is_on_backorder()) {
                $status = true; // mark it in stock if backorder ON, no matter what qty is
            } else {
                $status = (self::wh_popups_get_stock_quantity($product->get_stock_quantity(), $product) > 0) ? true : false; // if no backorder allowed - check actual qty value for selecte warehouse
            }
        }

        return $status;
    }

    public static function wh_popups_get_stock_status($status, $product)
    {
        if ($product->managing_stock()) {
            $selected_warehouse = self::get_selected_warehouse_details();

            if ($selected_warehouse !== false) {
                $selected_warehouse_stock_qty = self::wh_popups_get_stock_quantity($product->get_stock_quantity(), $product);
                $selected_warehouse_backorders = self::wh_popups_get_backorders($product->get_backorders(), $product);

                if ($selected_warehouse_stock_qty <= get_option('woocommerce_notify_no_stock_amount', 0) && 'no' === $selected_warehouse_backorders) {
                    $status = 'outofstock';

                    // If we are stock managing, backorders are allowed, and we don't have stock, force on backorder status.
                } elseif ($selected_warehouse_stock_qty <= get_option('woocommerce_notify_no_stock_amount', 0) && 'no' !== $selected_warehouse_backorders) {
                    $status = 'onbackorder';

                    // If the stock level is changing and we do now have enough, force in stock status.
                } elseif ($selected_warehouse_stock_qty > get_option('woocommerce_notify_no_stock_amount', 0)) {
                    $status = 'instock';
                }
            }
        }
        return $status;
    }

    // add selected warehouse into cart item data
    public static function wh_popups_add_cart_item_data($cart_item_data, $product_id, $variation_id)
    {
        $selected_warehouse = self::get_selected_warehouse();

        if ($selected_warehouse !== false) {
            $cart_item_data['wh-popups-warehouse-id'] = $selected_warehouse;
        }

        return $cart_item_data;
    }

    // display Warehouse in cart for each item
    public static function wh_popups_get_item_data($item_data, $cart_item)
    {
        if (empty($cart_item['wh-popups-warehouse-id'])) {
            return $item_data;
        }

        $wh_id = wc_clean($cart_item['wh-popups-warehouse-id']);

        if (!empty(get_option('warehouse-popups-woocommerce-list'))) {
            $alt_warehouses_list = self::get_alt_warehouses_list();

            if (is_array($alt_warehouses_list) && $alt_warehouses_list[$wh_id]) {
                $wh_name = $alt_warehouses_list[$wh_id]['name'];

                $item_data[] = array(
                    'key' => __('Warehouse', 'warehouse-popups-woocommerce'),
                    'value' => $wh_name,
                    'display' => '',
                );
            }
        }

        return $item_data;
    }

    // restore cart item warehouse from session
    public static function wh_popups_cart_item_from_session($cart_item, $values)
    {
        if (isset($values['wh-popups-warehouse-id'])) {
            $cart_item['wh-popups-warehouse-id'] = $values['wh-popups-warehouse-id'];
        }

        return $cart_item;
    }

    // save order item warehouse into order meta data to be displayed on order confirmation page
    public static function woo_checkout_create_order_line_item($item, $cart_item_key, $values, $order)
    {
        if (empty($values['wh-popups-warehouse-id'])) return;

        $alt_warehouses_list = self::get_alt_warehouses_list();

        if (is_array($alt_warehouses_list) && $alt_warehouses_list[$values['wh-popups-warehouse-id']]) {
            $item->add_meta_data(__('Warehouse', 'warehouse-popups-woocommerce'), $alt_warehouses_list[$values['wh-popups-warehouse-id']]['name'], true);
        }
    }

    public static function warehouse_select($method, $index) {
        /*Временно привязано к конкретному способу доставки. 
        Нужно вынести в настройки выбор способа, к которому 
        привязывать склады.*/
        if ( 'local_pickup:14' === $method->id) {
            $warehouse_list = (array)json_decode(get_option('warehouse-popups-woocommerce-list'), true);
            $options = array();
            foreach ($warehouse_list as $warehouse) {
                $options[$warehouse['id']] = $warehouse['name'];
            }
            $arr_keys = array_keys($options);
            if (count($options)) {
                echo '<div id="custom_checkout_field">';
     
                woocommerce_form_field( 'wh-popups-warehouse-id', array(
                  'type'          => 'radio',
                  'class'         => array('form-row-wide'),
                  'label'         => '',
                  'options'       => $options,
                  // 'default' => $arr_keys[0]
                  'default'       => ''
                  ));
                /*WC()->checkout->get_value( 'wh-popups-warehouse-id' )*/
             
                echo '</div>';
            }
        }
    }

    public static function add_order_custom_fields($data) { 
        if (isset($_POST['shipping_method']) && strpos($_POST['shipping_method'][0],'local_pickup')!==false && isset($_POST['wh-popups-warehouse-id'])) {
            $data['wh-popups-warehouse-id'] = $_POST['wh-popups-warehouse-id'];
        }
        return $data;
    } 

    // save order item warehouse into post meta field to be used later in reduce stock function
    public static function wh_popups_add_order_item_meta($item_id, $values, $cart_item_key)
    {
        if (empty($values['wh-popups-warehouse-id'])) return;

        add_post_meta($item_id, 'wh-popups-warehouse-id', $values['wh-popups-warehouse-id']);
    }

    public static function warehouse_checkout_field_process() {
        if (isset($_POST['shipping_method']) && strpos($_POST['shipping_method'][0],'local_pickup')!==false) {
            if ( isset($_POST['wh-popups-warehouse-id']) &&  empty($_POST['wh-popups-warehouse-id'])) {
            wc_add_notice(__('Не выбран офис самовывоза.', 'warehouse-popups-woocommerce'), 'error' );
            }
        }
    }

    public static function warehouse_checkout_fields_validation($data, $errors) {
        file_put_contents('checkout_data.txt', json_encode($data).PHP_EOL);
        if (isset($data['shipping_method']) && mb_strpos($data['shipping_method'][0],'local_pickup')!==false) {
            if ( !isset($data['wh-popups-warehouse-id']) || empty($data['wh-popups-warehouse-id'])) {
                $errors->add('validation', __('Не выбран офис самовывоза.', 'warehouse-popups-woocommerce') );
            }
        }
    }

    public static function checkout_update_order_meta( $order_id, $posted ) {
        if( isset($posted['shipping_method']) && isset( $posted['wh-popups-warehouse-id'] ) ) {
            update_post_meta( $order_id, '_wh-popups-warehouse-id', $posted['wh-popups-warehouse-id'] );
        }
        ob_start();
        print_r($posted);
        $output_posted = ob_get_clean();
        file_put_contents('reduce_order_selected_stock.log',$output_posted.PHP_EOL, FILE_APPEND);
    }

    public static function custom_order_meta_to_totals( $rows, $order ) {
        $warehouse_list = (array)json_decode(get_option('warehouse-popups-woocommerce-list'));
        if (count($warehouse_list)) {
            $warehouse_name = $warehouse_list[get_post_meta( $order->get_id(), '_wh-popups-warehouse-id', true )]->name;
            if ($warehouse_name) {
                $new_rows = array();
                foreach($rows as $key=>$value) {
                    $new_rows[$key] = $value;
                    if ($key == 'shipping') {
                        $new_rows[ 'warehouse' ] = array(
                            'label' => __('Пункт самовывоза', 'woocommerce'),
                            'value' => $warehouse_name
                        );
                    }
                }
                $rows = $new_rows;
            }
        }
        return $rows;
    }

    /**
     * Reduce product quantity in order selected stock
     */    
    public static function reduce_order_selected_stock( $order ){
        $order_stock = get_post_meta( $order->get_id(), '_wh-popups-warehouse-id', true);
        file_put_contents('reduce_order_selected_stock.log', 'order_id: '. $order->get_id().PHP_EOL, FILE_APPEND);
        file_put_contents('reduce_order_selected_stock.log', 'order_stock: '. $order_stock.PHP_EOL, FILE_APPEND);
        if ($order_stock != 'default') {
            foreach ( $order->get_items() as $item ) {
                if ( ! $item->is_type( 'line_item' ) ) {
                    continue;
                }
                // Only reduce stock once for each item.
                $product = $item->get_product();
                file_put_contents('reduce_order_selected_stock.log', 'product: '. $product->get_id().PHP_EOL, FILE_APPEND);
                $item_stock_reduced = $item->get_meta( '_reduced_stock', true );

                $item_stock_reduced = false; //Временно для отладки

                if ( $item_stock_reduced || ! $product || ! $product->managing_stock() ) {
                    continue;
                }
                $old_stock = get_post_meta($product->get_id(), 'alt_wh_stock_'.$order_stock, true);                
                $qty  = apply_filters( 'woocommerce_order_item_quantity', $item->get_quantity(), $order, $item );
                $new_stock = $old_stock - $qty;
                update_post_meta($product->get_id(), 'alt_wh_stock_'.$order_stock, $new_stock );
                $changes[] = array(
                    'product' => $product,
                    'from'    => $old_stock,
                    'to'      => $new_stock,
                );
                file_put_contents('reduce_order_selected_stock.log', 'old_stock: ' .$old_stock.PHP_EOL, FILE_APPEND);
                file_put_contents('reduce_order_selected_stock.log', 'new_stock: ' .$new_stock.PHP_EOL, FILE_APPEND);                
                file_put_contents('reduce_order_selected_stock.log', '----------------------------------------'.PHP_EOL, FILE_APPEND);                
            }

            wc_trigger_stock_change_notifications( $order, $changes );
        }
    }
    


    // for items from alt warehouses - return 0 as quantity, means do not reduce main stock quantity value
    // and do not change $qty for default warehouse, return as is
    public static function wh_popups_filter_item_quantity($qty, $order, $item)
    {
        $item_id = $item->get_id();
        $item_selected_warehouse = (!empty(get_post_meta($item_id, 'wh-popups-selected-warehouse', true))) ? get_post_meta($item_id, 'wh-popups-selected-warehouse', true) : false;
        $warehouse_details = self::get_warehouse_details($item_selected_warehouse);

        return ($item_selected_warehouse !== false && intval($warehouse_details['use-default']) != 1) ? null : $qty;
    }

    // here we actually reduce alt warehouses stock qty
    public static function woo_reduce_order_stock($order)
    {
        $items = $order->get_items();

        if (is_array($items) && sizeof($items) > 0) {
            foreach ($items as $one_item) {
                $item_id = $one_item->get_id();
                $item_selected_warehouse = get_post_meta($item_id, 'wh-popups-selected-warehouse', true);

                if (!$item_selected_warehouse || empty($item_selected_warehouse)) continue; // this item is from default warehouse, just skip it

                $was_stock_reduced = get_post_meta($item_id, 'wh-popups-stock-reduced', true);

                if ($was_stock_reduced && $was_stock_reduced === 1) continue; // this item already was reduced, skip it
                

                $item_product = $one_item->get_product();
                $qty = $one_item->get_quantity();

                if ($item_product) {
                    if ($item_product->is_type('simple')) {
                        $qty_before = intval(get_post_meta($item_product->get_id(), 'alt_wh_stock_' . $item_selected_warehouse, true));
                        $qty_after = $qty_before - $qty;
                        update_post_meta($item_product->get_id(), 'alt_wh_stock_' . $item_selected_warehouse, $qty_after);
                        //update_post_meta($item_id, 'wh-popups-stock-reduced', 1);
                    } else if ($item_product->is_type('variation')) {
                        $variation_id = $item_product->get_variation_id();
                        $qty_before = get_post_meta($variation_id, 'alt_wh_stock_' . $item_selected_warehouse, true);
                        $qty_after = $qty_before - $qty;
                        update_post_meta($variation_id, 'alt_wh_stock_' . $item_selected_warehouse, $qty_after);
                        //update_post_meta($item_id, 'wh-popups-stock-reduced', 1);
                    }
                }
            }
        }
    }

    // ovveride woo currency with warehouse currency
    public static function wh_popups_override_currency($currency)
    {
        if (is_admin()) {
            return $currency;
        } else {
            $selected_warehouse = self::get_selected_warehouse();
            if ($selected_warehouse !== false) {
                $alt_warehouses_list = self::get_alt_warehouses_list();
                $currency = (isset($alt_warehouses_list[$selected_warehouse]['currency'])) ? $alt_warehouses_list[$selected_warehouse]['currency'] : $currency;
            } else if (!isset($_REQUEST['wh_popups_change_wh_to'])) {
                $alt_warehouses_list = self::get_alt_warehouses_list();
                $client_data = self::client_data();

                foreach ($alt_warehouses_list as $one_wh) {
                    if (is_array($one_wh['countries'])) {
                        if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                            $currency = (isset($one_wh['currency'])) ? $one_wh['currency'] : $currency;
                            break;
                        }
                    } else {
                        if ($client_data['name'] === $one_wh['countries']) {
                            $currency = (isset($one_wh['currency'])) ? $one_wh['currency'] : $currency;
                            break;
                        }
                    }
                }
            }
            return $currency;
        }
    }

    public function woocommerce_custom_currency_symbol($custom_currency_symbol, $custom_currency)
    {
        if (is_admin()) {
            return false;
        }

        $symbols = get_woocommerce_currency_symbols();

        if (isset($_REQUEST['wh_popups_change_wh_to'])) {
            if ($_REQUEST['wh_popups_change_wh_to'] != 'default') {
                $warehouse_code = $_REQUEST['wh_popups_change_wh_to'];
                $alt_warehouses_list = self::get_alt_warehouses_list();

                if (isset($alt_warehouses_list[$warehouse_code]['currency'])) {
                    $currency = $alt_warehouses_list[$warehouse_code]['currency'];
                }
            } else {
                $currency = $this->global_currency_code;
            }
        } else {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            $client_data = self::client_data();

            foreach ($alt_warehouses_list as $one_wh) {
                if (is_array($one_wh['countries'])) {
                    if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                        $currency = $one_wh['currency'];
                        break;
                    }
                } else {
                    if ($client_data['name'] === $one_wh['countries']) {
                        $currency = $one_wh['currency'];
                        break;
                    }
                }
            }
        }

        if (!isset($currency)) {
            $currency = get_woocommerce_currency();
        }

        $currency_symbol = isset($symbols[$currency]) ? $symbols[$currency] : $symbols['USD'];

        return $currency_symbol;
    }


    // show only allowed warehouse gateways
    public static function wh_popups_hide_denied_gateways($gateways)
    {
        $selected_warehouse = self::get_selected_warehouse_details();

        if (isset($selected_warehouse['gateways']) && is_array($selected_warehouse['gateways']) && sizeof($selected_warehouse['gateways']) > 0) {
            if (is_array($gateways) && sizeof($gateways) > 0) {
                foreach ($gateways as $gateway_key => $gateway_obj) {
                    if (!in_array($gateway_key, $selected_warehouse['gateways'])) {
                        unset($gateways[$gateway_key]);
                    }
                }
            }
        }


        return $gateways;
    }

    // override woo price with warehouse currency
    public function wh_popups_override_price($price, $product)
    {
        if (is_admin()) {
            return $price;
        }

        $selected_warehouse = self::get_selected_warehouse();
        $api_key = '';
        $converter = 'free_currency_converter';

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) {
                    $converter = 'free_currency_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    $converter = 'currency_converter';
                    $api_key = $alt_warehouses_list[$selected_warehouse]['key'];
                }
            }
        } else {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            $client_data = self::client_data();

            foreach ((array)$alt_warehouses_list as $one_wh) {
                if (is_array($one_wh['countries'])) {
                    if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                        if (isset($one_wh['convert_ratio'])) {
                            if (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) {
                                $converter = 'free_currency_converter';
                                $api_key = (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) ? $one_wh['free_api_key'] : '';
                            }
                            if (strpos($one_wh['convert_ratio'], 'cur-convert-') !== false) {
                                $converter = 'currency_converter';
                                $api_key = $one_wh['key'];
                            }
                        }
                        break;
                    }
                }
            }
        }

        if ($this->global_currency_code != get_woocommerce_currency()) {
            $convert_ratio = $this->get_convert_ratio($this->global_currency_code, get_woocommerce_currency(), $converter, $api_key);
            $converted_price = (float)$price * $convert_ratio;
            $this->current_currency_code = get_woocommerce_currency();

            return $converted_price;
        }

        return $price;
    }

    // override woo price filter min amount with warehouse currency
    public function wh_popups_override_price_filter_min_amount($min_price)
    {
        $selected_warehouse = self::get_selected_warehouse();
        $api_key = '';
        $converter = 'free_currency_converter';

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();

            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) {
                    $converter = 'free_currency_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    $converter = 'currency_converter';
                    $api_key = $alt_warehouses_list[$selected_warehouse]['key'];
                }
            }
        } else {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            $client_data = self::client_data();

            foreach ((array)$alt_warehouses_list as $one_wh) {
                if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                    if (isset($one_wh['convert_ratio'])) {
                        if (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) {
                            $converter = 'free_currency_converter';
                            $api_key = (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) ? $one_wh['free_api_key'] : '';
                        }
                        if (strpos($one_wh['convert_ratio'], 'cur-convert-') !== false) {
                            $converter = 'currency_converter';
                            $api_key = $one_wh['key'];
                        }
                    }
                    break;
                }
            }
        }

        $convert_ratio = $this->get_convert_ratio($this->current_currency_code, get_woocommerce_currency(), $converter, $api_key);

        return $convert_ratio * $min_price;
    }

    // override woo price filter max amount with warehouse currency
    public function wh_popups_override_price_filter_max_amount($max_price)
    {
        $selected_warehouse = self::get_selected_warehouse();
        $api_key = '';
        $converter = 'free_currency_converter';

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();

            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) {
                    $converter = 'free_currency_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    $converter = 'currency_converter';
                    $api_key = $alt_warehouses_list[$selected_warehouse]['key'];
                }
            }
        } else {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            $client_data = self::client_data();

            foreach ((array)$alt_warehouses_list as $one_wh) {
                if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                    if (isset($one_wh['convert_ratio'])) {
                        if (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) {
                            $converter = 'free_currency_converter';
                            $api_key = (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) ? $one_wh['free_api_key'] : '';
                        }
                        if (strpos($one_wh['convert_ratio'], 'cur-convert-') !== false) {
                            $converter = 'currency_converter';
                            $api_key = $one_wh['key'];
                        }
                    }
                    break;
                }
            }
        }

        $convert_ratio = $this->get_convert_ratio($this->current_currency_code, get_woocommerce_currency(), $converter, $api_key);

        return $convert_ratio * $max_price;
    }

    // override woo variable price with warehouse currency
    public function wh_popups_override_variable_price($price, $variation, $product)
    {
        $selected_warehouse = self::get_selected_warehouse();
        $api_key = '';
        $converter = 'free_currency_converter';

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) {
                    $converter = 'free_currency_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'google-round-') !== false) {
                    $converter = 'google_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    $converter = 'currency_converter';
                    $api_key = $alt_warehouses_list[$selected_warehouse]['key'];
                }
            }
        }

        if ($this->global_currency_code != get_woocommerce_currency()) {
            $converted_price = $price * $this->get_convert_ratio($this->global_currency_code, get_woocommerce_currency(), $converter, $api_key);
            $this->current_currency_code = get_woocommerce_currency();
            return $converted_price;
        }
        return $price;
    }

    public function wh_popups_override_variable_price_hash($hash)
    {
        $selected_warehouse = self::get_selected_warehouse();
        $api_key = '';
        $converter = 'free_currency_converter';

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) {
                    $converter = 'free_currency_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'google-round-') !== false) {
                    $converter = 'google_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    $converter = 'currency_converter';
                    $api_key = $alt_warehouses_list[$selected_warehouse]['key'];
                }
            }
        }

        $converted_price[] = 1.0;

        if ($this->global_currency_code != get_woocommerce_currency()) {
            $converted_price[] = $this->get_convert_ratio($this->global_currency_code, get_woocommerce_currency(), $converter, $api_key);
            $this->current_currency_code = get_woocommerce_currency();
        }

        return $converted_price;
    }

    public function wh_popups_override_cart_price_totals($cart)
    {
        // This is necessary for WC 3.0+
        if (is_admin() && !defined('DOING_AJAX'))
            return;

        // Avoiding hook repetition (when using price calculations for example)
        if (did_action('woocommerce_before_calculate_totals') >= 2)
            return;

        $selected_warehouse = self::get_selected_warehouse();
        $api_key = '';
        $converter = 'free_currency_converter';

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) {
                    $converter = 'free_currency_converter';
                    $api_key = (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'free-currency-') !== false) ? $alt_warehouses_list[$selected_warehouse]['free_api_key'] : '';
                }
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    $converter = 'currency_converter';
                    $api_key = $alt_warehouses_list[$selected_warehouse]['key'];
                }
            }
        } else {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            $client_data = self::client_data();

            foreach ($alt_warehouses_list as $one_wh) {
                if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                    if (isset($one_wh['convert_ratio'])) {
                        if (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) {
                            $converter = 'free_currency_converter';
                            $api_key = (strpos($one_wh['convert_ratio'], 'free-currency-') !== false) ? $one_wh['free_api_key'] : '';
                        }
                        if (strpos($one_wh['convert_ratio'], 'cur-convert-') !== false) {
                            $converter = 'currency_converter';
                            $api_key = $one_wh['key'];
                        }
                    }
                    break;
                }
            }
        }

        $convert_ratio = 1.0;

        if ($this->global_currency_code != $this->current_currency_code) {
            $converted_ratio = $this->get_convert_ratio($this->global_currency_code, get_woocommerce_currency(), $converter, $api_key);
        }

        // Loop through cart items
        $temp_cart = array();

        foreach ($cart->get_cart() as $item) {
            $item['wh-popups-warehouse-id'] = $selected_warehouse;
            $temp_cart[] = $item;
            $item['data']->set_price($item['data']->get_price() * $convert_ratio);
        }

        $cart->set_cart_contents($temp_cart);
    }

    public function wh_popups_override_before_mini_cart()
    {
        WC()->cart->calculate_totals();
        $_REQUEST['woocs_woocommerce_before_mini_cart'] = 'mini_cart_refreshing';
    }

    public function wh_popups_override_after_mini_cart()
    {
        unset($_REQUEST['woocs_woocommerce_before_mini_cart']);
    }

    public function wh_popups_override_price_decimals($decimals)
    {
        if (is_admin()) {
            return $decimals;
        }

        $selected_warehouse = self::get_selected_warehouse();
        $return_decimals = $decimals;

        if ($selected_warehouse !== false) {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            if (isset($alt_warehouses_list[$selected_warehouse]['convert_ratio'])) {
                if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], 'cur-convert-') !== false) {
                    if (strpos($alt_warehouses_list[$selected_warehouse]['convert_ratio'], '-int') !== false) {
                        $return_decimals = 0;
                    } else {
                        $return_decimals = $alt_warehouses_list[$selected_warehouse]['convert_ratio_decimal'];
                    }
                }
            }
        } else {
            $alt_warehouses_list = self::get_alt_warehouses_list();
            $client_data = self::client_data();

            foreach ($alt_warehouses_list as $one_wh) {
                if (is_array($one_wh['countries'])) {
                    if (gettype(array_search($client_data['country_code1'], $one_wh['countries'])) != 'boolean') {
                        if (isset($one_wh['convert_ratio'])) {
                            if (strpos($one_wh['convert_ratio'], '-int') !== false) {
                                $return_decimals = 0;
                            } else {
                                $return_decimals = $one_wh['convert_ratio_decimal'];
                            }
                        }
                        break;
                    }
                } else {
                    if (isset($one_wh['convert_ratio'])) {
                        if (strpos($one_wh['convert_ratio'], '-int') !== false) {
                            $return_decimals = 0;
                        } else {
                            $return_decimals = $one_wh['convert_ratio_decimal'];
                        }
                    }
                    break;
                }
            }
        }

        return $return_decimals;
    }

    public function wh_popups_override_before_cart()
    {
        $shipping_packages = WC()->cart->get_shipping_packages();
        $shipping_zone = wc_get_shipping_zone(reset($shipping_packages));
        $zone_id = $shipping_zone->get_id(); // Get the zone ID
        $alt_warehouses_list = self::get_alt_warehouses_list();

        foreach ($alt_warehouses_list as $one_wh) {
            if (isset($one_wh['shipping_zone']) && $one_wh['shipping_zone']) {
                $warehouse_zone = $one_wh['zone_country'];
                if ($warehouse_zone == $zone_id) {
                    $_COOKIE['wh-popups-selected-warehouse'] = $one_wh['id'];
                    if (WC()) {
                        WC()->session->set('wh-popups-selected-warehouse', array('warehouse_id' => $one_wh['id'], 'warehouse_email' => $one_wh['email']));
                    }
                    break;
                }
            }
        }
    }

    private function get_cart_subtotal_price()
    {
        $items = WC()->cart->get_cart();
        $cart_subtotal = 0.0;

        foreach ($items as $item => $values) {
            $price = get_post_meta($values['product_id'], '_price', true);
            $cart_subtotal += $price * $values['quantity'];
        }

        return $cart_subtotal;
    }

    private function get_convert_ratio($from_currency, $to_currency, $converter, $api_key)
    {
        $converted_amount = 1.0;

        if ($this->current_currency_code != get_woocommerce_currency()) {
            if ($converter == 'google_converter') {
                $amount = urlencode($amount);
                $from_currency = urlencode($from_currency);
                $to_currency = urlencode($to_currency);
                $remote_get_raw = wp_remote_get("https://finance.google.com/finance/converter?a=$amount&from=$from_currency&to=$to_currency");
                $result = '';

                if (!is_wp_error($remote_get_raw)) {
                    $result = $remote_get_raw['body'];
                    $result = explode("<span class=bld>", $result);

                    if (is_array($result) && isset($result[1])) {
                        $result = explode("</span>", $result[1]);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

                $converted_amount = floatval(preg_replace("/[^0-9\.]/", null, $result[0]));
            } else if ($converter == 'free_currency_converter') {
                $apikey = $api_key;
                $keyType = "free"; // premium;
                if (empty($apikey)) {
                    return $converted_amount;
                }

                $from_Currency = urlencode(strtoupper($from_currency));
                $to_Currency = urlencode(strtoupper($to_currency));
                $query = "{$from_Currency}_{$to_Currency}";

                if ($keyType == 'premium') {
                    $json = file_get_contents("https://api.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
                } else {
                    $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
                }

                $obj = json_decode($json, true);
                $this->currency_ratio = floatval($obj["$query"]);
            } else if ($converter == 'currency_converter') {
                $from_Currency = urlencode(strtoupper($from_currency));
                $to_Currency = urlencode(strtoupper($to_currency));
                $query = "{$from_Currency}_{$to_Currency}";

                if (empty($api_key)) {
                    $this->currency_ratio = 1.0;
                } else {
                    $json = file_get_contents("https://api.currconv.com/api/v7/convert?apiKey={$api_key}&compact=ultra&q={$query}");
                    $obj = json_decode($json, true);
                    $this->currency_ratio = floatval($obj[$query]);
                }
            }
        }

        return $this->currency_ratio;
    }

    //private function get_geoip_data() {
    private static function get_geoip_data(){
        if (is_admin()) {
            return false;
        }

		$ip = self::get_client_ip();
		$cache_data = self::_wmw_get_data_from_cache( $ip );
		
		if( $cache_data ){
			$obj = array( 'name' => $cache_data['country_name'], 'alpha2' => $cache_data['country_code'], 'alpha3' => $cache_data['country_code'] );
		}else if (function_exists('geoip_detect2_get_info_from_current_ip')) {
			$geoInfo			= geoip_detect2_get_info_from_current_ip();
			$geo_country_code	= ($geoInfo->country->isoCode);
			$obj				= array( 'name' => $geoInfo->country->name, 'alpha2' => $geo_country_code, 'alpha3' => $geo_country_code );
			
			self::_wmw_add_data_to_cache( array( 'country_name' => $geoInfo->country->name, 'country_code' => $geo_country_code ), $ip );
		}else{
			//$geoip_url      = "https://api.ipgeolocationapi.com/geolocate/" . $ip;
 
			$json           = self::_wmw_get_geo_address( $ip ); //BMC
			$result         = json_decode( $json, true );
			$obj			= array( 'name' => $result['country_name'], 'alpha2' => $result['country_code'], 'alpha3' => $result['country_code'] );
			
			self::_wmw_add_data_to_cache( array( 'country_name' => $result['country_name'], 'country_code' => $result['country_code'] ), $ip );
		}
		
        $return_arr = array(
            'name' 			=> $obj['name'],
            'country_code1' => $obj['alpha2'],
            'country_code2' => $obj['alpha3']
        );

        return $return_arr;
    }
	
	private static function _wmw_ip_to_s($ip){
		$binary = '';
		try {
			$binary = @inet_pton($ip);
		} catch (\Throwable $e) { }
		if (empty($binary))
			return '';
		return base64_encode($binary);
	}
	
	private static function _wmw_get_data_from_cache( $ip ) {
		$ip_s = self::_wmw_ip_to_s($ip);
		if (!$ip_s) {
			return null;
		}
		
		$key = '_wmw_geoip_detect_c_' . $ip_s;
		$data = get_transient( $key );
	
		return $data;
	}
	
	private static function _wmw_add_data_to_cache( $data, $ip ) {
	
		$ip_s = self::_wmw_ip_to_s( $ip );
		
		// Do not cache invalid IPs
		if (!$ip_s) {
			return;
		}
		
		$key	= '_wmw_geoip_detect_c_' . $ip_s;
		$group	=  '';
		$expire	= 604800; //7 days
		set_transient( $key, $data, $expire );
	}
	
	private static function _wmw_get_geo_address( $ip ){
		$access_key			= '7583a28b676f8a5625ab332e02a5a9d1'; //BMC
		$geoip_url			= 'http://api.ipstack.com/'.$ip.'?access_key='.$access_key; //BMC
		$json				= self::curl_get_contents($geoip_url);
		
		return $json;
	}

    //private function get_client_ip() {
    private static function get_client_ip()
    {
        //whether ip is from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip_address = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
			if( $ip_address == '::1' ){
				$ip_address = '127.0.0.1';
			}
        }

        if (filter_var($ip_address, FILTER_VALIDATE_IP) !== false) {
            return $ip_address;
        } else {
            //return '127.0.0.1';
            return '8.8.8.8';
        }
    }

    //check stocks in warehouse
    public function check_stocks_in_warehouse()
    {
        foreach (WC()->cart->cart_contents as $item) {
            $product_id = $item['product_id'];
            $product_on_warehouse_quantity = $item['data']->get_data()['alt_wh_stock_' + $_COOKIE['wh-popups-selected-warehouse']];
            if ($item['quantity'] > $product_on_warehouse_quantity) {
                add_filter('woocommerce_get_availability', function ($availability, $_product) {
                    return array(
                        'availability' => 'Out of stock',
                        'class' => 'out-of-stock',
                    );
                }, 1, 2);
                return false;
            }
        }

        return true;
    }


    public function warehouse_popups_change_shipping_country()
    {
        $country_code = $_POST['country'];
        $warehouse_id = $_POST['warehouse'];
        $address = $_POST['address'];
        $address = str_replace(' ', '+', $address);
        $alt_warehouses_list = self::get_alt_warehouses_list();
        $zones_data = [];
        $zones_dest_data = [];
        $google_api_key = get_option('warehouse-popups-woocommerce-pro-google-api-key');

        foreach ($alt_warehouses_list as $warehouse) {
            if ($warehouse['shipping_zone']) {
                $zones = WC_Shipping_Zones::get_zone($warehouse['zone_country'])->get_data()['zone_locations'];
                foreach ($zones as $zone) {
                    if ($zone->code == $country_code) {
                        if ($warehouse_id == $warehouse['id']) {
                        }
                    }
                    $zones_data[$zone->code] = $warehouse['id'];
                }
            }

            foreach ($warehouse['countries'] as $country) {
                $zones_data[trim($country)] = $warehouse['id'];
            }

            if ($google_api_key && $warehouse['location'] && $address) {
                $warehouse['location'] = str_replace(' ', '+', $warehouse['location']);
                $dest_url = 'https://maps.googleapis.com/maps/api/directions/json?origin=' . $warehouse['location'] . '&destination=' . $address . '&key=' . $google_api_key;
                $dest_json = file_get_contents($dest_url);
                $dest_object = json_decode($dest_json, true);
                $dest_value = trim(strtoupper($dest_object['routes'][0]['legs'][0]['distance']['value']));
                $zones_dest_data[$warehouse['id']] = $dest_value;
            }
        }

        if (in_array($country_code, array_keys($zones_data))) {
            $in_our_country = array();

            if ($google_api_key) {
                foreach ($zones_dest_data as $key => $country) {
                    if ($zones_dest_data[$key]) {
                        $in_our_country[$key] = $zones_dest_data[$key];
                    }
                }
            }

            if (count($in_our_country) > 1) {
                $new_warehouse_id = array_keys($in_our_country, min($in_our_country))[0];
            } else {
                $new_warehouse_id = $zones_data[$country_code];
            }
        } else {
            $new_warehouse_id = $warehouse_id;
        }
        $to_change = ($new_warehouse_id != $warehouse_id);

        foreach (WC()->cart->cart_contents as $item) {
            $product_id = $item['product_id'];
            $product_on_warehouse_quantity = get_post_meta($product_id, 'alt_wh_stock_' . $new_warehouse_id, 1);

            if ($item['quantity'] > intval($product_on_warehouse_quantity)) {
                $to_change = false;
                break;
            }
        }

        wp_send_json_success(array(
            'success' => 1,
            'to_change' => $to_change,
            'new_warehouse_id' => $new_warehouse_id,
            'warehouse_id' => $warehouse_id,
        ));
    }

    public static function curl_get_contents($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
