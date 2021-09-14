<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * The helper functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 */

/**
 * The helper functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the specific stylesheet and JavaScript.
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/includes
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce_Helper
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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->$version = $version;
        //$this->set_cookies = $this->wh_set_cookies();
    }

    public static function test_helper($string)
    {
        return $string;
    }

    public static function wh_set_cookies($name, $value)
    {
        if (!isset($_COOKIE[$name])) {
            // Set or Reset the cookie
            setcookie($name, $value, 600);
        }
    }

    public static function wh_get_client_ip_data()
    {
        $url = 'https://api.ipify.org?format=json';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data, true);
        //print_r($data['ip']);
        //exit;
        //return $data['ip'];
        //unset($_COOKIE['wh_client_ip_data']);
        setcookie('wh_client_ip_data', $data['ip'], 600);
        // self::wh_set_cookies('wh_client_ip_data', $data['ip']);
        self::wh_get_geoip_data();
    }

    public static function wh_get_ip_geo_location_data()
    {

    }

    public static function wh_get_geoip_data()
    {
        $url2 = 'http://ip-api.com/json/' . $_COOKIE['wh_client_ip_data'];
        $obj = json_decode(self::use_curl($url2), true);

        $return_arr = array(
            'country_name' => $obj['country'],
            'country_code1' => $obj['countryCode'],
            'country_code2' => $obj['countryCode'],
            'region' => $obj['region'],
            'region_name' => $obj['regionName'],
            'city' => $obj['city'],
            'district' => $obj['district'],
            'zip' => $obj['zip'],
            'latitude' => $obj['lat'],
            'longitude' => $obj['lon'],
            'timezone' => $obj['timezone'],
            'currency' => $obj['currency']
        );

        //unset($_COOKIE['wh_geoip_data']);
        setcookie('wh_geoip_data', json_encode($return_arr), 600);
        // self::wh_set_cookies('wh_geoip_data', json_encode($return_arr));
    }

    //private function get_client_ip() {
    public static function get_client_ip()
    {
        //whether ip is from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if (strpos('.', $ip_address) !== false) {
            return $ip_address;
        } else {
            //return '127.0.0.1';
            return '8.8.8.8';
        }
    }

    public static function use_curl($url)
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