<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Warehouse_Popups_Woocommerce
 * @subpackage Warehouse_Popups_Woocommerce/admin
 * @author     Venby
 */
class Warehouse_Popups_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private static $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		self::$version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/warehouse-popups-woocommerce-admin.css', array(), self::$version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/warehouse-popups-woocommerce-admin.js', array( 'jquery' ), static::$version, false );

	}


	public function woo_add_warehouses_section() {

		$current_tab = ( isset($_GET['tab']) == 'warehouses' ) ? 'nav-tab-active' : '';
		?>
			<a href="admin.php?page=wc-settings&tab=warehouses" class="nav-tab <?php echo $current_tab; ?>"><?php echo __( "Warehouses", "warehouse-popups-woocommerce" )?></a>
		<?php
	}

	public function warehouse_popups_tab_content($settings) {

		wp_nonce_field( 'add-edit-warehouse', 'wh-nonce' );

		if ( isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['edit_id']) )
        {
            // show edit form
            self::warehouse_popups_show_edit_form($settings);

        }
        else
        {
            // show list
            self::warehouse_popups_show_list($settings);
        }

	}

	private function warehouse_popups_show_edit_form($settings)
    {
	    global $hide_save_button;
	    $hide_save_button = true;

        $edit_wh_id = trim($_REQUEST['edit_id']);
        if ( $edit_wh_id != '' )
        {
            // get wh data
	        $alt_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
	        $wh_to_edit = ( isset($alt_warehouses_list[$edit_wh_id]) ) ? $alt_warehouses_list[$edit_wh_id] : false;

            // display edit form

	        ?><h2><?php _e( 'Edit Warehouse Details', 'warehouse-popups-woocommerce'  );?></h2><?php

	        self::add_edit_warehouse_form_content($wh_to_edit);
        }
        else
        {
            // display list if wh id not found
	        self::warehouse_popups_show_list($settings);
        }
    }

	private function warehouse_popups_show_list($settings)
    {
	    global $hide_save_button;
	    $hide_save_button = true;

	    woocommerce_admin_fields( self::get_warehouses_tab_settings() );

			?>
			<br>
			<?php

	    $is_wh_popups = 'yes';
	    if ( $is_wh_popups == 'yes' ) {

		    self::warehouses_list_content();
	    }
    }

	public static function get_warehouses_tab_settings() {

		$settings = array(
			'section_title' => array(
				'name'     => __( "Venby Woocommerce Warehouses", "warehouse-popups-woocommerce" ),
				'type'     => 'title',
				'desc'     => 'v'.self::$version,
				'id'       => 'wh_popups_warehouses_title'
			),
			'section_end' => array(
			'type' => 'sectionend',
			'id' => 'wh_popups_warehouses_end'
		    )
		);

		return apply_filters( 'wh_popups_warehouses_settings', $settings );
	}

	public static function dependencies_notice() {
		?>
    <div class="notice notice-warning is-dismissible">
        <p>
			<strong><?php _e( 'Warehouse Popups Woocommerce PRO', 'warehouse-popups-woocommerce'  );?></strong> <?php _e( 'plugin requires', 'warehouse-popups-woocommerce'  );?>
		</p>
    </div>
    <?php
	}

	public static function update_settings() {

	    global $SHIPPING_ZONES_ENABLED;

		if ( ! wp_verify_nonce( $_REQUEST['wh-nonce'], 'add-edit-warehouse' ) ) die( 'Nonce Security check failed.' );

		// update turn on off
		if ( !isset($_REQUEST['action']) || ( isset($_REQUEST['action']) && $_REQUEST['action'] !== 'edit') ){
			woocommerce_update_options( self::get_warehouses_tab_settings() );
		}

        // add new warehouse
	    if ( isset($_POST['new-warehouse-name']) && isset($_POST['new-warehouse-currency']) ){
	        $new_warehouse_use_default					= intval(sanitize_text_field( $_POST['new-warehouse-use-default'] ));
			$new_warehouse_name							= sanitize_text_field( $_POST['new-warehouse-name'] );
			$new_warehouse_country						= sanitize_text_field( $_POST['new-warehouse-country'] );
			$new_warehouse_address1						= sanitize_text_field( $_POST['new-warehouse-address1'] );
			$new_warehouse_address2						= sanitize_text_field( $_POST['new-warehouse-address2'] );
			$new_warehouse_email						= sanitize_text_field( $_POST['new-warehouse-email'] );
			$new_warehouse_city							= sanitize_text_field( $_POST['new-warehouse-city'] );
			$new_warehouse_currency						= sanitize_text_field( $_POST['new-warehouse-currency'] );
	        $new_warehouse_convert_ratio				= sanitize_text_field( $_POST['new-warehouse-convert-ratio'] );
	        $new_warehouse_key							= sanitize_text_field( $_POST['new-warehouse-key'] );
	        $new_warehouse_convert_manual_ratio			= floatval( $_POST['new-warehouse-manual-ratio'] );
	        $new_warehouse_convert_free_ratio_decimal	= floatval( isset($_POST['new-warehouse-free-ratio-decimal']) );
	        $new_warehouse_convert_ratio_decimal		= floatval( $_POST['new-warehouse-ratio-decimal'] );
	        $new_warehouse_gateways						= $_POST['new-warehouse-gateways'];
	        $new_warehouse_gateways_keys				= ( is_array($new_warehouse_gateways) && sizeof($new_warehouse_gateways) > 0 ) ? array_keys($new_warehouse_gateways) : array();
	        $new_warehouse_shipping_country				= sanitize_text_field(isset($_POST['new-warehouse-shipping-country']));
	        $new_warehouse_shipping_zone				= sanitize_text_field(isset($_POST['new-warehouse-shipping-zone']));
	        $new_warehouse_location						= $new_warehouse_country.' '.$new_warehouse_address1.' '.$new_warehouse_address2.' '.$new_warehouse_city;
			
			//if ( $new_warehouse_shipping_country == 'on' ){
	        if ( $new_warehouse_shipping_country == 1 ){
		        // countries
		        $new_warehouse_countries_list = explode(',', strtoupper(str_replace(' ', '', sanitize_text_field( $_POST['new-warehouse-countries-list'] ))));
		        $new_warehouse_zone_zip_codes = array();
	        }
	        if ( $new_warehouse_shipping_zone == 1 ){
		        $new_warehouse_zone_country = sanitize_text_field( $_POST['new-warehouse-zone-country'] );

		        if ( in_array($new_warehouse_zone_country, $SHIPPING_ZONES_ENABLED) ){
			        $new_warehouse_zone_zip_codes = explode(',', strtoupper(str_replace(' ', '', sanitize_text_field( $_POST['new-warehouse-zone-zip-codes'] ))));
			        $new_warehouse_zone_zip_codes = array_filter($new_warehouse_zone_zip_codes);
		        }else{
			        $new_warehouse_zone_zip_codes = array();
		        }

	        }

			if ( $new_warehouse_name != '' ){
	            $older_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
                $older_warehouses_list[md5($new_warehouse_name)] = array('id'							=> md5($new_warehouse_name),
                                                                         'use-default'					=> $new_warehouse_use_default,
                                                                         'name'							=> $new_warehouse_name,
                                                                         'location'						=> $new_warehouse_location,
                                                                         'email'						=> $new_warehouse_email,
                                                                         'city'							=> $new_warehouse_city,
																		 'country'						=> $new_warehouse_country,
                                                                         'address1'						=> $new_warehouse_address1,
                                                                         'address2'						=> $new_warehouse_address2,
                                                                         'currency'						=> $new_warehouse_currency,
                                                                         'key'							=> $new_warehouse_key,
                                                                         'gateways'						=> $new_warehouse_gateways_keys,
                                                                         'shipping_country'				=> $new_warehouse_shipping_country == 1 ? true : false,
                                                                         'shipping_zone'				=> $new_warehouse_shipping_zone == 1 ? true : false,
                                                                         /*'countries' => isset($new_warehouse_countries_list),
                                                                         'zone_country' => isset($new_warehouse_zone_country),
                                                                         'zone_zip_codes' => isset($new_warehouse_zone_zip_codes),*/
																		 'countries'					=> $new_warehouse_countries_list,
                                                                         'zone_country'					=> $new_warehouse_zone_country,
                                                                         'zone_zip_codes'				=> $new_warehouse_zone_zip_codes,
                                                                         'convert_ratio'				=> $new_warehouse_convert_ratio,
                                                                         'convert_manual_ratio'			=> $new_warehouse_convert_manual_ratio,
                                                                         'convert_free_ratio_decimal'	=> $new_warehouse_convert_free_ratio_decimal,
                														 'convert_ratio_decimal'		=> $new_warehouse_convert_ratio_decimal);

                $new_warehouses_list = json_encode($older_warehouses_list);
				update_option('warehouse-popups-woocommerce-list', $new_warehouses_list);
				//var_dump($new_warehouse_countries_list); exit;
            }
        }
        // edit warehouse
        else if ( isset($_POST['edit-warehouse-id']) && isset($_POST['edit-warehouse-name']) && isset($_POST['edit-warehouse-currency']) ){
	        $edit_warehouse_id							= sanitize_text_field( $_POST['edit-warehouse-id'] );
	        $edit_warehouse_use_default 				= intval(sanitize_text_field( $_POST['edit-warehouse-use-default'] ));
	        $edit_warehouse_name						= sanitize_text_field( $_POST['edit-warehouse-name'] );
			$edit_warehouse_country						= sanitize_text_field( $_POST['edit-warehouse-country'] );
			$edit_warehouse_address1					= sanitize_text_field( $_POST['edit-warehouse-address1'] );
			$edit_warehouse_address2					= sanitize_text_field( $_POST['edit-warehouse-address2'] );
			$edit_warehouse_email						= sanitize_text_field( $_POST['edit-warehouse-email'] );
			$edit_warehouse_city						= sanitize_text_field( $_POST['edit-warehouse-city'] );
	        $edit_warehouse_currency					= sanitize_text_field( $_POST['edit-warehouse-currency'] );
	        $edit_warehouse_convert_ratio				= sanitize_text_field( $_POST['edit-warehouse-convert-ratio'] );
	        $edit_warehouse_key							= sanitize_text_field( $_POST['edit-warehouse-key'] );
	        $edit_warehouse_convert_manual_ratio		= floatval( $_POST['edit-warehouse-manual-ratio'] );
	        $edit_warehouse_convert_free_ratio_decimal	= floatval( $_POST['edit-warehouse-free-ratio-decimal'] );
	        $edit_warehouse_convert_ratio_decimal		= floatval( $_POST['edit-warehouse-ratio-decimal'] );
	        $edit_warehouse_gateways					= $_POST['edit-warehouse-gateways'];
	        $edit_warehouse_gateways_keys				= ( is_array($edit_warehouse_gateways) && sizeof($edit_warehouse_gateways) > 0 ) ? array_keys($edit_warehouse_gateways) : array();
	        $edit_warehouse_shipping_country			= sanitize_text_field($_POST['edit-warehouse-shipping-country']);
	        $edit_warehouse_shipping_zone				= sanitize_text_field($_POST['edit-warehouse-shipping-zone']);
	        $edit_warehouse_location					= $edit_warehouse_country.' '. $edit_warehouse_city .' '. $edit_warehouse_address1.' '.$edit_warehouse_address2;
			
			//print_r($SHIPPING_ZONES_ENABLED);
			
			// countries
	        if ( $edit_warehouse_shipping_country == 'on' ){
	            $edit_warehouse_countries_list	= explode(',', strtoupper(str_replace(' ', '', sanitize_text_field( $_POST['edit-warehouse-countries-list'] ))));
	            $edit_warehouse_zone_country	= '';
	            $edit_warehouse_zone_zip_codes	= array();
            }
			// shipping zones
            if ( $edit_warehouse_shipping_zone == 'on' ){
	            //$edit_warehouse_countries_list = array(); //BMC
	            $edit_warehouse_zone_country	= sanitize_text_field( $_POST['edit-warehouse-zone-country'] );
	            if ( in_array( $edit_warehouse_zone_country, $SHIPPING_ZONES_ENABLED ) ){
	                $edit_warehouse_zone_zip_codes = explode(',', strtoupper(str_replace(' ', '', sanitize_text_field( $_POST['edit-warehouse-zone-zip-codes'] ))));
	                $edit_warehouse_zone_zip_codes = array_filter($edit_warehouse_zone_zip_codes);
                }else{
	                $edit_warehouse_zone_zip_codes = array();
                }
            }

	        if ( $edit_warehouse_id != '' ){
		        $older_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
		        $older_warehouses_list[$edit_warehouse_id] = array(
		                'id'							=> $edit_warehouse_id,
                        'use-default'					=> $edit_warehouse_use_default,
                        'name'							=> $edit_warehouse_name,
                        'location'						=> $edit_warehouse_location,
                        'email'							=> $edit_warehouse_email,
                        'currency'						=> $edit_warehouse_currency,
                        'key'							=> $edit_warehouse_key,
		                'gateways'						=> $edit_warehouse_gateways_keys,
		                'shipping_country'				=> $edit_warehouse_shipping_country == 'on' ? true : false,
		                'shipping_zone'					=> $edit_warehouse_shipping_zone == 'on' ? true : false,
                        'countries'						=> $edit_warehouse_countries_list,
                        'zone_country'					=> $edit_warehouse_zone_country,
                        'zone_zip_codes'				=> $edit_warehouse_zone_zip_codes,
		                'convert_ratio'					=> $edit_warehouse_convert_ratio,
		                'convert_manual_ratio'			=> $edit_warehouse_convert_manual_ratio,
		                'convert_free_ratio_decimal'	=> $edit_warehouse_convert_free_ratio_decimal,
		                'convert_ratio_decimal'			=> $edit_warehouse_convert_ratio_decimal,
		                'country'						=> $edit_warehouse_country,
		                'address1'						=> $edit_warehouse_address1,
		                'address2'						=> $edit_warehouse_address2,
		                'city'							=> $edit_warehouse_city,
                );
		        $edit_warehouses_list = json_encode( $older_warehouses_list );
		        update_option( 'warehouse-popups-woocommerce-list', $edit_warehouses_list );
	        }
        }


	}

	public static function warehouse_popups_save_google_api_key() {

		if ( ! wp_verify_nonce( $_REQUEST['_nonce'], 'add-edit-warehouse' ) ) die( __('Nonce Security check failed.', 'warehouse-popups-woocommerce-pro') );

		if ( isset($_REQUEST['action']) && isset($_REQUEST['google_api_key']) ) {
			update_option('warehouse-popups-woocommerce-pro-google-api-key', $_REQUEST['google_api_key']);
			$result['type'] = "success";
		} else {
			$result['type'] = "error";
			$result['message'] = __('API Key is required.', 'warehouse-popups-woocommerce-pro');
		}
		wp_send_json_success(array(
			'result' => $result,
			'REQUEST' => $_REQUEST
		));
	}

	public static function warehouses_list_content()
    {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-warehouse-popups-woocommerce-list-table.php';

		?><h2><?php _e( 'Connected Warehouses', 'warehouse-popups-woocommerce'  );?></h2><?php
		?><p><?php _e( 'Do not include your main [default] woocommerce warehouse.', 'warehouse-popups-woocommerce'  );?></p><?php
    	self::add_edit_warehouse_form_content(false, $items_count);
    	
		$whListTable = new Warehouse_Popups_Woocommerce_List_table();
		
		$whListTable->prepare_items();
		
		$whListTable->display();

		$items_count = $whListTable->_pagination_args['total_items'];

	
		$google_api_key = get_option('warehouse-popups-woocommerce-pro-google-api-key');

		self::whp_unlock_unlimited_warehouses();
		?>

		<div class="button-wrapper">
			<h2><?php echo __('Activate Auto Site Entry Detection', 'warehouse-popups-woocommerce-pro'); ?></h2>
			<button id="warehouse-popups-woocommerce-pro-manage-google" class="button button-secondary warehouse-popups-woocommerce-pro-manage-google" type="button" value="<?php echo __( 'Manage Google API key', 'warehouse-popups-woocommerce-pro' ); ?>"><?php echo __( 'Connect', 'warehouse-popups-woocommerce-pro' ); ?></button>
       	</div>
       	<!--
		<div class="button-wrapper">
			<h2><?php _e( 'Integrate Venby Woocommerce Warehouses With Zapier', 'warehouse-popups-woocommerce'  );?></h2>
			<button id="warehouse-popups-woocommerce-manage-inventory" class="button button-secondary warehouse-popups-woocommerce-manage-inventory" type="button" value="<?php echo __( 'Manage Inventory', 'warehouse-popups-woocommerce' ); ?>"><?php echo __( 'Connect', 'warehouse-popups-woocommerce' ); ?></button>
       	</div>
       	-->
       	<div id="manage-inventory-popup">
       		<div class="popup-background"></div>
       		<div class="inventory-form">
       			<div class="loading-wrapper">
       				<img src="images/loading.gif" alt="loading" />
       			</div>
       			<a href="javascript:void(0)" class="close-btn">Close</a>
       			<div class="form-group">
       				<span id="apiKey-response-message"></span>
       			</div>
       			<div class="form-group">
       				<?php $api_key = get_option('warehouse-popups-woocommerce-api-key'); ?>
       				<p><a href="https://venby.tv/#/settings/edit/api_key" target="_blank"><?php _e( 'Venby API Key', 'warehouse-popups-woocommerce'  );?></a></p>
       				<input type="text" id="venby-api-key" value="<?php echo isset($api_key) ? $api_key : ''; ?>" name="zapier-hook-url" />
       				<p style="margin-top: 10px;"><a href="https://zapier.com/developer/public-invite/8566/845c5fc947fbfb36e6bbfd13b298add4/" target="_blank"><?php _e( 'Connect With Zapier', 'warehouse-popups-woocommerce'  );?></a></p>
       			</div>
       			<div class="form-group">
       				<button type="button" id="warehouse-popups-woocommerce-inventory-save" data-action="<?php echo admin_url('admin-ajax.php'); ?>" name="inventory-save" value="inventory-save" class="btn btn-save"><?php _e( 'Save', 'warehouse-popups-woocommerce'  );?></button>
       				<button type="button" class="btn btn-cancel"><?php _e( 'Cancel', 'warehouse-popups-woocommerce'  );?></button>
       			</div>
       		</div>
       	</div>


       	<div id="manage-google-popup" class="manage-popup">
       		<div class="popup-background"></div>
       		<div class="google-form manage-form"  style="background: #f1f1f1;">
       			<div class="loading-wrapper">
       				<img src="images/loading.gif" alt="loading" />
       			</div>
       			<a href="javascript:void(0)" class="close-btn">Close</a>
       			<div class="form-group">
       				<span id="apiKey-response-message"></span>
       			</div>
       			<div class="form-group">
				   <h2><?php _e( 'Insert Your Google Maps API Key', 'warehouse-popups-woocommerce'  );?></h2>
				   
       				<p><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php echo __('Get a Google Maps API Key', 'warehouse-popups-woocommerce-pro'); ?></a></p>
       				<input type="text" id="google-api-key" value="<?php echo isset($google_api_key) ? $google_api_key : ''; ?>" name="warehouse-popups-woocommerce-warehouses-google-api-key" />
					<p><?php _e( 'Woocommerce Warehouses uses Google Maps for site entry auto detection', 'warehouse-popups-woocommerce'  );?></p>
       			</div>
       			<div class="form-group">
       				<button type="button" id="warehouse-popups-woocommerce-pro-google-save" data-action="<?php echo admin_url('admin-ajax.php'); ?>" name="google-save" value="google-save" class="btn btn-save"><?php echo __('Save', 'warehouse-popups-woocommerce-pro'); ?></button>
       				<button type="button" class="btn btn-cancel"><?php echo __('Cancel', 'warehouse-popups-woocommerce-pro'); ?></button>
       			</div>
       		</div>
       	</div>

       	
		<p><b><?php echo __('Shortcode Instructions:', 'warehouse-popups-woocommerce'); ?></b><br>
        <?php echo __('Insert the following code snippet to display your warehouses.', 'warehouse-popups-woocommerce'); ?> 
         <br><?php echo __('or insert as PHP code into your theme files:', 'warehouse-popups-woocommerce'); ?>
         <code><?php echo htmlspecialchars('<?php echo do_shortcode(\'[wh_popups_warehouses_switch]\'); ?>')?></code>
        <br><?php echo __('Display your warehouses in a dropdown selector', 'warehouse-popups-woocommerce'); ?>
		<br><?php echo __('Display your warehouses in a popup / lightbox', 'warehouse-popups-woocommerce'); ?>
        </p>     
        <?php echo __('Activate currency conversions using The Currency Converter API:', 'warehouse-popups-woocommerce'); ?>  <a href="https://www.currencyconverterapi.com/" target="_blank"><?php echo __('The Currency Converter API', 'warehouse-popups-woocommerce'); ?></a><br/><br/></p>
		<?php
    }

    public static function is_geoip_enabled()
    {
	    return is_plugin_active( 'wpengine-geoip/wpengine-geoip.php' );
    }

    public static function add_edit_warehouse_form_content($edit_warehouse = false, $warehouses_count = 0)
    {
        global $SHIPPING_ZONES_ENABLED;

	    $currency_code_options = get_woocommerce_currencies();
	    $currency_symbols = array(
            'AED' => '&#x62f;.&#x625;',
            'AFN' => '&#x60b;',
            'ALL' => 'L',
            'AMD' => 'AMD',
            'ANG' => '&fnof;',
            'AOA' => 'Kz',
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => 'Afl.',
            'AZN' => 'AZN',
            'BAM' => 'KM',
            'BBD' => '&#36;',
            'BDT' => '&#2547;&nbsp;',
            'BGN' => '&#1083;&#1074;.',
            'BHD' => '.&#x62f;.&#x628;',
            'BIF' => 'Fr',
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => 'Bs.',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTC' => '&#3647;',
            'BTN' => 'Nu.',
            'BWP' => 'P',
            'BYR' => 'Br',
            'BYN' => 'Br',
            'BZD' => '&#36;',
            'CAD' => '&#36;',
            'CDF' => 'Fr',
            'CHF' => '&#67;&#72;&#70;',
            'CLP' => '&#36;',
            'CNY' => '&yen;',
            'COP' => '&#36;',
            'CRC' => '&#x20a1;',
            'CUC' => '&#36;',
            'CUP' => '&#36;',
            'CVE' => '&#36;',
            'CZK' => '&#75;&#269;',
            'DJF' => 'Fr',
            'DKK' => 'DKK',
            'DOP' => 'RD&#36;',
            'DZD' => '&#x62f;.&#x62c;',
            'EGP' => 'EGP',
            'ERN' => 'Nfk',
            'ETB' => 'Br',
            'EUR' => '&euro;',
            'FJD' => '&#36;',
            'FKP' => '&pound;',
            'GBP' => '&pound;',
            'GEL' => '&#x20be;',
            'GGP' => '&pound;',
            'GHS' => '&#x20b5;',
            'GIP' => '&pound;',
            'GMD' => 'D',
            'GNF' => 'Fr',
            'GTQ' => 'Q',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => 'L',
            'HRK' => 'kn',
            'HTG' => 'G',
            'HUF' => '&#70;&#116;',
            'IDR' => 'Rp',
            'ILS' => '&#8362;',
            'IMP' => '&pound;',
            'INR' => '&#8377;',
            'IQD' => '&#x639;.&#x62f;',
            'IRR' => '&#xfdfc;',
            'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
            'ISK' => 'kr.',
            'JEP' => '&pound;',
            'JMD' => '&#36;',
            'JOD' => '&#x62f;.&#x627;',
            'JPY' => '&yen;',
            'KES' => 'KSh',
            'KGS' => '&#x441;&#x43e;&#x43c;',
            'KHR' => '&#x17db;',
            'KMF' => 'Fr',
            'KPW' => '&#x20a9;',
            'KRW' => '&#8361;',
            'KWD' => '&#x62f;.&#x643;',
            'KYD' => '&#36;',
            'KZT' => 'KZT',
            'LAK' => '&#8365;',
            'LBP' => '&#x644;.&#x644;',
            'LKR' => '&#xdbb;&#xdd4;',
            'LRD' => '&#36;',
            'LSL' => 'L',
            'LYD' => '&#x644;.&#x62f;',
            'MAD' => '&#x62f;.&#x645;.',
            'MDL' => 'MDL',
            'MGA' => 'Ar',
            'MKD' => '&#x434;&#x435;&#x43d;',
            'MMK' => 'Ks',
            'MNT' => '&#x20ae;',
            'MOP' => 'P',
            'MRO' => 'UM',
            'MUR' => '&#x20a8;',
            'MVR' => '.&#x783;',
            'MWK' => 'MK',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => 'MT',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => 'C&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#x631;.&#x639;.',
            'PAB' => 'B/.',
            'PEN' => 'S/',
            'PGK' => 'K',
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PRB' => '&#x440;.',
            'PYG' => '&#8370;',
            'QAR' => '&#x631;.&#x642;',
            'RMB' => '&yen;',
            'RON' => 'lei',
            'RSD' => '&#x434;&#x438;&#x43d;.',
            'RUB' => '&#8381;',
            'RWF' => 'Fr',
            'SAR' => '&#x631;.&#x633;',
            'SBD' => '&#36;',
            'SCR' => '&#x20a8;',
            'SDG' => '&#x62c;.&#x633;.',
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&pound;',
            'SLL' => 'Le',
            'SOS' => 'Sh',
            'SRD' => '&#36;',
            'SSP' => '&pound;',
            'STD' => 'Db',
            'SYP' => '&#x644;.&#x633;',
            'SZL' => 'L',
            'THB' => '&#3647;',
            'TJS' => '&#x405;&#x41c;',
            'TMT' => 'm',
            'TND' => '&#x62f;.&#x62a;',
            'TOP' => 'T&#36;',
            'TRY' => '&#8378;',
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => 'Sh',
            'UAH' => '&#8372;',
            'UGX' => 'UGX',
            'USD' => '&#36;',
            'UYU' => '&#36;',
            'UZS' => 'UZS',
            'VEF' => 'Bs F',
            'VES' => 'Bs.S',
            'VND' => '&#8363;',
            'VUV' => 'Vt',
            'WST' => 'T',
            'XAF' => 'CFA',
            'XCD' => '&#36;',
            'XOF' => 'CFA',
            'XPF' => 'Fr',
            'YER' => '&#xfdfc;',
            'ZAR' => '&#82;',
            'ZMW' => 'ZK',
        ); 

			if ( is_array($currency_code_options) && sizeof($currency_code_options) > 0 )
			{
				foreach ( $currency_code_options as $code => $name ) {
			    $currency_code_options[ $code ] = $name . ' (' . $currency_symbols[$code] . ')';
		    }
			}

			$countries_list = self::geoip_country_list();

	    $all_gateways = WC()->payment_gateways->payment_gateways();

	    $tbody_class = ( $edit_warehouse === false ) ? 'hidden' : '';
	    $field_prefix = ( $edit_warehouse === false ) ? 'new' : 'edit';

	    $woo_currency = get_woocommerce_currency();
	    $local_currency = ( isset($edit_warehouse['currency']) && !empty($edit_warehouse['currency']) ) ? $edit_warehouse['currency'] : ' local currency';
	    $local_ratio = ( isset($edit_warehouse['convert_manual_ratio']) && !empty($edit_warehouse['convert_manual_ratio']) ) ? $edit_warehouse['convert_manual_ratio'] : 'X.XX';
		
		//print_r($edit_warehouse);
        ?>
        <table class="form-table <?php echo $field_prefix; ?>-warehouse-cont">
            <tbody id="warehouse-popups-woocommerce-list-add-<?php echo $field_prefix?>-cont" class="<?php echo $tbody_class?>">
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-use-default"><?php _e( 'Use Main Woocommerce Warehouse Inventory for orders?', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-select">
                        <select name="<?php echo $field_prefix?>-warehouse-use-default" id="<?php echo $field_prefix?>-warehouse-use-default">
                            <option value="0" <?php if ( $edit_warehouse !== false && ( !isset($edit_warehouse['use-default']) || ( isset($edit_warehouse['use-default']) && intval($edit_warehouse['use-default']) === 0 ) ) ) echo 'selected';?>><?php _e('No', 'warehouse-popups-woocommerce'); ?></option>
                            <option value="1" <?php if ( $edit_warehouse !== false && ( isset($edit_warehouse['use-default']) && intval($edit_warehouse['use-default']) == 1 ) ) echo 'selected';?>><?php _e('Yes', 'warehouse-popups-woocommerce'); ?></option>
                        </select>
                </tr>
                
                 <tr>
                    <th class="titledesc" colspan="2"><h2>Warehouse Address</h2></th>
                </tr>
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-name"><?php _e( 'Warehouse Name', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-text">
                        <input name="<?php echo $field_prefix?>-warehouse-name" id="<?php echo $field_prefix?>-warehouse-name" type="text" value="<?php if ( $edit_warehouse !== false && isset($edit_warehouse['name'])) echo $edit_warehouse['name'];?>">
                        <p><?php _e('This name will be displayed in your website', 'warehouse-popups-woocommerce'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-name"><?php _e( 'Warehouse Country', 'warehouse-popups-woocommerce-pro' )?></label></th>
                    
                    <td class="forminp forminp-text">
                    	<select name="<?php echo $field_prefix?>-warehouse-country" id="<?php echo $field_prefix?>-warehouse-country">
                    		<?php foreach( $countries_list as $country ){
                    			$selected = '';
                    			if( $edit_warehouse !== false && isset($edit_warehouse['country']) && $country['country']  == $edit_warehouse['country'] ){
                    				$selected = 'selected';
                    			} ?>
                    			<option value="<?php echo $country['country']; ?>" <?php echo $selected; ?>><?php echo $country['country']; ?></option>
                    		<?php } ?>
                    	</select>
                    </td>
                </tr>
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-name"><?php _e( 'Warehouse City', 'warehouse-popups-woocommerce-pro' )?></label></th>
                    <td class="forminp forminp-text">
                        <input name="<?php echo $field_prefix?>-warehouse-city" id="<?php echo $field_prefix?>-warehouse-city"  type="text" value="<?php if ( $edit_warehouse !== false && isset($edit_warehouse['city'])) echo $edit_warehouse['city'];?>">
                        <p><?php _e('The city where your warehouse is located.', 'warehouse-popups-woocommerce'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-name"><?php _e( 'Warehouse Address line 1', 'warehouse-popups-woocommerce-pro' )?></label></th>
                    <td class="forminp forminp-text"><input name="<?php echo $field_prefix?>-warehouse-address1" id="<?php echo $field_prefix?>-warehouse-address1"  type="text" value="<?php if ( $edit_warehouse !== false && isset($edit_warehouse['address1'])) echo $edit_warehouse['address1'];?>"></td>
                </tr>
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-name"><?php _e( 'Warehouse Address line 2', 'warehouse-popups-woocommerce-pro' )?></label></th>
                    <td class="forminp forminp-text"><input name="<?php echo $field_prefix?>-warehouse-address2" id="<?php echo $field_prefix?>-warehouse-address2"  type="text" value="<?php if ( $edit_warehouse !== false && isset($edit_warehouse['address2'])) echo $edit_warehouse['address2'];?>"></td>
                </tr>

                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-email"><?php _e( 'Warehouse Admin Email', 'warehouse-popups-woocommerce-pro' )?></label></th>
                    <td class="forminp forminp-text">
                        <input name="<?php echo $field_prefix?>-warehouse-email" id="<?php echo $field_prefix?>-warehouse-email"  type="text" value="<?php if ( $edit_warehouse !== false && isset($edit_warehouse['email'])) echo $edit_warehouse['email'];?>">
                        <p><?php _e('Orders will be sent to this email address.', 'warehouse-popups-woocommerce'); ?></p>
                        </td>
                </tr>

                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-currency"><?php _e( 'Warehouse Currency', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-select">
                        <select name="<?php echo $field_prefix?>-warehouse-currency" id="<?php echo $field_prefix?>-warehouse-currency">
                            <?php
                            if ( is_array($currency_code_options) && sizeof($currency_code_options) )
                            {
                                foreach ($currency_code_options as $curr_code => $curr_title)
                                {
                                    ?><option value="<?php echo $curr_code?>" <?php if ( $edit_warehouse !== false && isset($edit_warehouse['currency']) && $edit_warehouse['currency'] == $curr_code ) echo 'selected';?>><?php echo $curr_title?></option> <?php
                                }
                            }
                            ?>
                        </select>
                </tr>
				<tr>
					<th class="titledesc"><label><?php _e( 'Currency Conversion', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-text">
                        <input type="radio" <?php if ( ($edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'flat') || ( $edit_warehouse !== false &&  !isset($edit_warehouse['convert_ratio']) ) || !$edit_warehouse ) echo 'checked';?> name="<?php echo $field_prefix?>-warehouse-convert-ratio" id="<?php echo $field_prefix?>-warehouse-convert-ratio-flat" value="flat"><label for="<?php echo $field_prefix?>-warehouse-convert-ratio-flat"><?php _e('Flat (only changes currency symbol)', 'warehouse-popups-woocommerce'); ?></label><br>
                        <input type="radio" <?php if ( $edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'manual' ) echo 'checked';?> name="<?php echo $field_prefix?>-warehouse-convert-ratio" id="<?php echo $field_prefix?>-warehouse-convert-ratio-manual" value="manual"><label for="<?php echo $field_prefix?>-warehouse-convert-ratio-manual"><?php _e('Manual, set your own exchange ratio:', 'warehouse-popups-woocommerce'); ?> <input name="<?php echo $field_prefix?>-warehouse-manual-ratio" style="width: 60px !important; padding: 2px 0; text-align: center; vertical-align: middle;" type="number" size="5" maxlength="5" min="0" step="0.01" placeholder="1.00" pattern="^\d+(?:\.\d{1,2})?$" value="<?php echo $edit_warehouse['convert_manual_ratio']?>"></label> ( 1<?php echo $woo_currency?> = <?php echo $local_ratio?><?php echo $local_currency?> )<br>
                        <input type="radio" <?php if ( $edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'cur-convert-int' ) echo 'checked';?> name="<?php echo $field_prefix?>-warehouse-convert-ratio" id="<?php echo $field_prefix?>-warehouse-convert-ratio-free-forex-int" value="cur-convert-int"><label for="<?php echo $field_prefix?>-warehouse-convert-ratio-free-forex-int"><?php _e('Real-time currency conversion, round up to nearest whole currency unit', 'warehouse-popups-woocommerce'); ?></label><br>
                        <input type="radio" <?php if ( $edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'cur-convert-dec' ) echo 'checked';?> name="<?php echo $field_prefix?>-warehouse-convert-ratio" id="<?php echo $field_prefix?>-warehouse-convert-ratio-free-forex-dec" value="cur-convert-dec"><label for="<?php echo $field_prefix?>-warehouse-convert-ratio-free-forex-dec"><?php _e('Real-time currency conversion, round up to nearest decimal XX.', 'warehouse-popups-woocommerce'); ?><input name="<?php echo $field_prefix?>-warehouse-ratio-decimal" style="width: 50px !important; padding: 2px 0; text-align: center; vertical-align: middle;" type="number" size="2" maxlength="1" min="0" step="1" placeholder="2" value="<?php echo is_null($edit_warehouse['convert_ratio_decimal']) ? 2 : $edit_warehouse['convert_ratio_decimal']; ?>"></label>
                        <p><a href="https://www.currencyconverterapi.com/" target="_blank"><?php _e('Currency Converter API', 'warehouse-popups-woocommerce'); ?></a></p>
                        <p><?php _e('Product Prices are converted across your entire product catalog.', 'warehouse-popups-woocommerce'); ?></p>
                    </td>
                </tr>
                <?php 
                	$show_api_key = '';
                	if ( $edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && strpos($edit_warehouse['convert_ratio'], "cur-convert-") !== false ) $show_api_key = 'active';
                ?>
                <tr id="currency-api-key" class="<?php echo $show_api_key; ?>">
                	<th class="titledesc"><label><?php _e( 'Currency Converter API Key', 'warehouse-popups-woocommerce-pro' )?></label></th>
                	<td>
                		<input name="<?php echo $field_prefix?>-warehouse-key" id="<?php echo $field_prefix?>-warehouse-key"  type="text" value="<?php if ( $edit_warehouse !== false && isset($edit_warehouse['key'])) echo $edit_warehouse['key'];?>" <?php if($show_api_key == 'active') echo 'required'; ?> >
                		<p><?php _e('Product Prices are converted across your entire product catalog.', 'warehouse-popups-woocommerce'); ?></p>
                	</td>
                </tr>
                <tr>
                    <th class="titledesc"><label><?php _e( 'Payment Gateways', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-checkbox">
		                <?php

		                if ( is_array($all_gateways) && sizeof($all_gateways) > 0 )
		                {

			                $enabled_gateways_count = 0;
			                foreach ($all_gateways as $gateway_key => $gateway_obj)
			                {
				                if ( $gateway_obj->enabled != 'yes' ) continue; // skip disabled gateways
				                else $enabled_gateways_count ++;

				                ?><input type="checkbox" id="<?php echo $field_prefix?>-warehouse-gateways-<?php echo $gateway_key?>" name="<?php echo $field_prefix?>-warehouse-gateways[<?php echo $gateway_key?>]" value="on"
				                <?php if ( $edit_warehouse !== false && isset($edit_warehouse['gateways']) && in_array($gateway_key, $edit_warehouse['gateways']) ) echo 'checked';?>
                                ><label for="<?php echo $field_prefix?>-warehouse-gateways-<?php echo $gateway_key?>"><?php echo $gateway_obj->method_title.' ('.$gateway_obj->title.')'?></label><br>
				                <?php
			                }
                            
			                if ( $enabled_gateways_count == 0 )
			                {
				                _e( 'No enabled warehouses in WooCommerce.', 'warehouse-popups-woocommerce' );
			                } else {
			                 ?>                            
                            <p><?php _e('Warehouse will auto update the payment gateway during checkout.', 'warehouse-popups-woocommerce'); ?></p>
                            <?php
			                }
			                ?>
			              
			                <?php
		                }

		                ?>
                    </td>
                </tr>
                <tr id="shipping-zone-select">
                    <th class="titledesc"><label for="<?php echo $field_prefix; ?>-warehouse-shipping"><?php _e( 'Autodetect Warehouse Checkouts', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-radio">
                        <label for="<?php echo $field_prefix; ?>-warehouse-shipping-zone">
                        <input type="checkbox" <?php if ( $edit_warehouse['shipping_zone'] == 1 ) echo 'checked';?> id="<?php echo $field_prefix; ?>-warehouse-shipping-zone" name="<?php echo $field_prefix; ?>-warehouse-shipping-zone" <?php if ( isset($edit_warehouse['shipping_zone']) && $edit_warehouse['shipping_zone'] != 1 ) echo 'disabled="disabled"';?>><?php _e( 'by Shipping Zone', 'warehouse-popups-woocommerce' )?>
                        <p><?php _e('The warehouse will update according to your customer’s ship-to address.', 'warehouse-popups-woocommerce'); ?></p>
                        </label>
                    </td>
                </tr>
            </tbody>
            <tbody id="shipping-zone-mode" class="<?php if ( $edit_warehouse['shipping_zone'] != 1 ) echo 'hidden'; ?>">
                <tr >
                    <th class="titledesc"><label for="warehouse-zone-country"><?php _e( 'Shipping Zones', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-checkbox">
                        <?php
                        $delivery_zones = WC_Shipping_Zones::get_zones();
                        if( is_array($delivery_zones) && sizeof($delivery_zones) > 0 ){
                        ?>
                        <select name="<?php echo $field_prefix?>-warehouse-zone-country" id="warehouse-zone-country">
							<?php
	                        foreach( $delivery_zones as $key => $the_zone ){
                            ?>
                            <option value="<?php echo $the_zone['zone_id']?>" <?php selected( $the_zone['zone_id'], $edit_warehouse['zone_country'], true ) ?>><?php echo $the_zone['zone_name']?></option>
							<?php
	                        }
                            ?>
                        </select>
						<?php
                        }
                        ?>
                    </td>
                </tr>
                <tr class="<?php if ( empty($edit_warehouse['zone_country']) || !in_array($edit_warehouse['zone_country'], $SHIPPING_ZONES_ENABLED) ) echo 'hidden' ?>">
                    <th class="titledesc"><label for="warehouse-zone-zip-codes"><?php _e( 'Zip Codes', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-textarea">
                        <textarea style="width: 50%" id="warehouse-zone-zip-codes" name="<?php echo $field_prefix?>-warehouse-zone-zip-codes"><?php if ( $edit_warehouse !== false && isset($edit_warehouse['zone_zip_codes'])) echo implode(', ', $edit_warehouse['zone_zip_codes'])?></textarea>
                        <p><?php _e('List Zip postal codes separated by comma.', 'warehouse-popups-woocommerce'); ?></p>
                        <p><?php _e('Zone Zip Codes option available only in USA, Canada, United Kingdom and Germany.', 'warehouse-popups-woocommerce'); ?><br> <?php _e('Zip Code detection can be restricted by customer Internet Service Provider.', 'warehouse-popups-woocommerce'); ?></p>
                    </td>
                </tr>
            </tbody>
            <tbody id="shipping-country-select" class="<?php if ($edit_warehouse === false) echo 'hidden'; ?>">
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-shipping"><?php _e( 'Restrict Checkouts by Country', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-radio">
                        <label for="<?php echo $field_prefix; ?>-warehouse-shipping-country">
                        <input type="checkbox" <?php if ( $edit_warehouse['shipping_country'] == 1 ) echo 'checked'; ?> id="<?php echo $field_prefix; ?>-warehouse-shipping-country" name="<?php echo $field_prefix; ?>-warehouse-shipping-country" <?php if ( isset($edit_warehouse['shipping_country']) && $edit_warehouse['shipping_country'] != 1 ) echo 'disabled="disabled"';?>> <?php _e( 'by Country', 'warehouse-popups-woocommerce' )?>
                        <p><?php _e('Allow checkouts for this warehouse for the following countries (all other countries will be blocked).', 'warehouse-popups-woocommerce'); ?></p>
                        </label>
                    </td>
                </tr>
            </tbody>
            <tbody id="shipping-country-mode" class="<?php if ( ( $edit_warehouse['shipping_country'] != 1 ) || !isset($edit_warehouse)) echo 'hidden';?>">
                <tr>
                    <th class="titledesc"><label for="<?php echo $field_prefix?>-warehouse-countries-list"><?php _e( 'Countries', 'warehouse-popups-woocommerce' )?></label></th>
                    <td class="forminp forminp-textarea">
                        <textarea style="width: 50%" id="<?php echo $field_prefix?>-warehouse-countries-list" name="<?php echo $field_prefix?>-warehouse-countries-list"><?php if ( $edit_warehouse !== false && isset($edit_warehouse['countries'])) echo implode(', ', $edit_warehouse['countries'])?></textarea>
                        <p><?php _e('Users from the above countries will be automatically redirected to this warehouse.', 'warehouse-popups-woocommerce'); ?></p>
                        <p><?php _e('List country codes separated by comma.', 'warehouse-popups-woocommerce'); ?> <a href="#" id="wh-popups-see-country-codes-list"><?php _e('See country codes list', 'warehouse-popups-woocommerce'); ?></a> </p>
                        <p id="wh-popups-all-countries-codes" class="hidden">
                            <?php
                            if ( is_array($countries_list) && sizeof($countries_list) > 0 )
                            {
                                foreach ($countries_list as $country_code => $country_arr)
                                {
                                    ?><b><?php echo $country_code?></b> - <?php echo $country_arr['country']?>   <?php
                                }
                            }
                            ?>
                        </p>
                    </td>
                </tr>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="2">
                    <?php
										$alt_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);

                    if ( !$edit_warehouse  )
                    {
											$alt_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);

						// do activation license here
						//if ( sizeof((array)$alt_warehouses_list) == 0 ) {
                        // show buttons for create form

                        ?>
                        <button id="warehouse-popups-woocommerce-list-save" type="submit" name="save" class="button button-primary warehouse-popups-woocommerce-list-save hidden" value="<?php echo __( 'Create Warehouse', 'warehouse-popups-woocommerce' ); ?>" disabled><?php echo __( 'Create Warehouse', 'warehouse-popups-woocommerce' ); ?></button>
                        <button id="warehouse-popups-woocommerce-list-add-btn" class="button button-secondary warehouse-popups-woocommerce-list-add" value="<?php echo __( 'Add Warehouse', 'warehouse-popups-woocommerce' ); ?>"><?php echo __( 'Add Warehouse', 'warehouse-popups-woocommerce' ); ?></button>
                        <button id="warehouse-popups-woocommerce-list-cancel-btn" class="button button-secondary warehouse-popups-woocommerce-list-cancel hidden" value="<?php echo __( 'Cancel', 'warehouse-popups-woocommerce' ); ?>"><?php echo __( 'Cancel', 'warehouse-popups-woocommerce' ); ?></button>
                        <?php
						//}
                    }
                    else
                    {
                        // buttons for edit wh form

                        ?>
                        <input type="hidden" name="edit-warehouse-id" value="<?php echo $edit_warehouse['id']; ?>">
                        <input type="hidden" name="redirect_to" class="redirect_to" value="<?php echo admin_url('admin.php?page=wc-settings&tab=warehouses'); ?>">

												<button id="warehouse-popups-woocommerce-edit-save-btn"
													type="submit"
													name="save"
													class="button button-primary woocommerce-save-button warehouse-popups-woocommerce-edit-save"
													value="<?php echo __( 'Save Changes', 'warehouse-popups-woocommerce' ); ?>">
														<?php echo __( 'Save Changes', 'warehouse-popups-woocommerce' ); ?>
												</button>
                        <button id="warehouse-popups-woocommerce-edit-cancel-btn" onclick="window.location.href='admin.php?page=wc-settings&tab=warehouses'; return false;" class="button button-secondary warehouse-popups-woocommerce-edit-cancel" value="<?php echo __( 'Cancel', 'warehouse-popups-woocommerce' ); ?>"><?php echo __( 'Cancel', 'warehouse-popups-woocommerce' ); ?></button>
                        <?php

                    }
                    ?>

                </th>
            </tr>
            </tfoot>
        </table>
        <?php
    }

    public static function woo_edit_variation_hook($variation_id, $i)
    {
        if ( isset($_REQUEST['alt_warehouses_variable_stock']) )
        {
            $alt_wh_variables_stock = $_REQUEST['alt_warehouses_variable_stock'];
            if ( is_array($alt_wh_variables_stock) && sizeof($alt_wh_variables_stock) > 0 )
            {
                foreach ($alt_wh_variables_stock as $variable_id => $warehouses)
                {
                    if ( $variable_id > 0 && is_array($warehouses) && sizeof($warehouses) > 0 )
                    {
                        foreach ($warehouses as $wh_id => $stock_value)
                        {
                            update_post_meta($variable_id, 'alt_wh_stock_'.$wh_id, $stock_value);
                        }
                    }
                }
            }
        }

        if ( isset($_REQUEST['alt_warehouses_variable_backorder']) )
        {
            $alt_wh_variables_backorder = $_REQUEST['alt_warehouses_variable_backorder'];
            if ( is_array($alt_wh_variables_backorder) && sizeof($alt_wh_variables_backorder) > 0 )
            {
                foreach ($alt_wh_variables_backorder as $variable_id => $warehouses)
                {
                    if ( $variable_id > 0 && is_array($warehouses) && sizeof($warehouses) > 0 )
                    {
                        foreach ($warehouses as $wh_id => $backorder_value)
                        {
                            update_post_meta($variable_id, 'alt_wh_backorder_'.$wh_id, $backorder_value);
                        }
                    }
                }
            }
        }
    }

    // update alt warehouses stock when product edited
    public static function woo_edit_product_hook( $product_id )
    {
        $product = wc_get_product( $product_id );

        if ( !$product ) return; // product not found

        // get alt wh stock for simple product
        $alt_wh_stock = ( isset($_REQUEST['alt_warehouses_stock']) ) ? $_REQUEST['alt_warehouses_stock'] : array();
        if ( is_array($alt_wh_stock) && sizeof($alt_wh_stock) > 0 )
        {
            foreach ($alt_wh_stock as $wh_id => $wh_stock_count)
            {
                $wh_stock_count = intval($wh_stock_count);
				$product->update_meta_data('alt_wh_stock_'.$wh_id, $wh_stock_count);
            }
        }

	    // get alt wh backorder for simple product
	    $alt_wh_backorder = ( isset($_REQUEST['alt_warehouses_backorder']) ) ? $_REQUEST['alt_warehouses_backorder'] : array();
	    if ( is_array($alt_wh_backorder) && sizeof($alt_wh_backorder) > 0 )
	    {
		    foreach ($alt_wh_backorder as $wh_id => $wh_backorder_value)
		    {
			    $wh_backorder_value = sanitize_text_field($wh_backorder_value);
			    $product->update_meta_data('alt_wh_backorder_'.$wh_id, $wh_backorder_value);
		    }
	    }

        $product->save_meta_data();
    }

    public static function woo_variable_variation_stock_content($loop, $variation_data, $variation)
    {
        if ( !$variation->ID || !$variation->post_parent) return;  // do nothing for all but variable product only

        $product_id = $variation->post_parent;
		$product_object = wc_get_product( $product_id );

		if ( !$product_object->is_type( 'variable' ) ) return; // do nothing for all but variable product only

		$alt_warehouses_list = array();
		if ( get_option('warehouse-popups-woocommerce-list') !== '' )
		{
			$alt_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
		}

		if ( is_array($alt_warehouses_list) && sizeof($alt_warehouses_list) > 0 )
		{
			?><p class="form-row form-row-full"><label><b><?php _e('Additional Warehouses', 'warehouse-popups-woocommerce');?></b></label></p> <?php

			foreach ($alt_warehouses_list as $one_alt_wh)
			{
			    $alt_wh_use_default = intval($one_alt_wh['use-default']);
			    if ( $alt_wh_use_default == 1 ) continue;

				$alt_wh_id = $one_alt_wh['id'];
				$alt_wh_name = $one_alt_wh['name'];
				$alt_wh_location = $one_alt_wh['location'];

				$variation_id = $variation->ID;

				$wh_stock_value = intval(get_post_meta($variation_id, 'alt_wh_stock_'.$alt_wh_id, true));

				$wh_backorder_allowed = get_post_meta($variation_id, 'alt_wh_backorder_'.$alt_wh_id, true);

				woocommerce_wp_text_input( array(
					'id'                => 'alt_warehouses_variable_stock['.$variation_id.']]['.$alt_wh_id.']',
					'value'             => $wh_stock_value,
					'label'             => $alt_wh_name.' '.__('Stock quantity Alt', 'warehouse-popups-woocommerce' ),
					'desc_tip'          => true,
					'description'       => __( 'Warehouse location: '.$alt_wh_location, 'warehouse-popups-woocommerce' ),
					'type'              => 'number',
					'custom_attributes' => array(
						'step'          => 'any',
					),
					'data_type'         => 'stock',
					'wrapper_class' => 'form-row form-row-first',
				) );


	                // display allow backorder field
	                woocommerce_wp_select( array(
		                'id'          => 'alt_warehouses_variable_backorder['.$variation_id.']]['.$alt_wh_id.']',
		                'value'       => $wh_backorder_allowed,
		                'label'       => $alt_wh_name.' '.__( ' Allow backorders?', 'warehouse-popups-woocommerce' ),
		                'options'     => wc_get_product_backorder_options(),
		                'desc_tip'    => true,
		                'description' => __( 'This control will manage backorders for warehouse only: ', 'warehouse-popups-woocommerce' ).' '.$alt_wh_name,
		                'wrapper_class' => 'form-row form-row-last',
	                ) );

			}
		}
    }

    public static function woo_inventory_single_stock_content()
    {
        global $product_object;

        if ( !$product_object->is_type( 'simple' ) ) return; // do nothing for all but simple product only

		$alt_warehouses_list = array();
		if ( get_option('warehouse-popups-woocommerce-list') !== '' )
		{
			$alt_warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
		}


		if ( is_array($alt_warehouses_list) && sizeof($alt_warehouses_list) > 0 )
        {
            ?><p class="form-field"><label><b><?php _e('Additional Warehouses', 'warehouse-popups-woocommerce');?></b></label></p> <?php

// print_r($alt_warehouses_list);
            foreach ($alt_warehouses_list as $one_alt_wh)
            {
	           $alt_wh_use_default = intval($one_alt_wh['use-default']);
	           if ( $alt_wh_use_default == 1 ) continue;

               $alt_wh_id = $one_alt_wh['id'];
               $alt_wh_name = $one_alt_wh['name'];
               $alt_wh_location = $one_alt_wh['location'];

               $wh_stock_value = intval($product_object->get_meta('alt_wh_stock_'.$alt_wh_id, true));

               $wh_backorder_allowed = $product_object->get_meta('alt_wh_backorder_'.$alt_wh_id, true);

               ?><p class="form-field"><label><?php echo $alt_wh_name ?>:</label></p><?php

               // display stock quantity field
				woocommerce_wp_text_input( array(
					'id'                => 'alt_warehouses_stock['.$alt_wh_id.']',
					'value'             => $wh_stock_value,
					'label'             => __( ' Stock quantity Alt', 'warehouse-popups-woocommerce' ),
					'desc_tip'          => true,
					'description'       => __( 'Warehouse location: '.$alt_wh_location, 'warehouse-popups-woocommerce' ),
					'type'              => 'number',
					'custom_attributes' => array(
						'step'          => 'any',
					),
					'data_type'         => 'stock',
				) );

		            // display allow backorder field
		            woocommerce_wp_select( array(
			            'id'          => 'alt_warehouses_backorder[' . $alt_wh_id . ']',
			            'value'       => $wh_backorder_allowed,
			            'label'       => __( 'Allow backorders?', 'warehouse-popups-woocommerce' ),
			            'options'     => wc_get_product_backorder_options(),
			            'desc_tip'    => true,
			            'description' => __( 'This control will manage backorders for warehouse only: ', 'warehouse-popups-woocommerce' ) . ' ' . $alt_wh_name,
		            ) );

            }
        }


    }

    public function warehouse_popups_save_api_key() {
    	if ( ! wp_verify_nonce( $_REQUEST['_nonce'], 'add-edit-warehouse' ) ) die( 'Nonce Security check failed.' );

		if ( isset($_REQUEST['action']) && isset($_REQUEST['api_key']) ) {
			update_option('warehouse-popups-woocommerce-api-key', $_REQUEST['api_key']);
			$result['type'] = "success";
		} else {
			$result['type'] = "error";
			$result['message'] = __( 'API Key is required.', 'warehouse-popups-woocommerce' );
		}

		echo json_encode($result);
		die();
    }

    /**
	 * Returns an array of country codes and their respective continents
	 */
	private static function geoip_country_list() {

		$countries = array(
			'AF' => array(
				'country'   => 'Afghanistan',
				'continent' => 'AS',
			),
			'AX' => array(
				'country'   => 'Åland Islands',
				'continent' => 'EU',
			),
			'AL' => array(
				'country'   => 'Albania',
				'continent' => 'EU',
			),
			'DZ' => array(
				'country'   => 'Algeria',
				'continent' => 'AF',
			),
			'AS' => array(
				'country'   => 'American Samoa',
				'continent' => 'OC',
			),
			'AD' => array(
				'country'   => 'Andorra',
				'continent' => 'EU',
			),
			'AO' => array(
				'country'   => 'Angola',
				'continent' => 'AF',
			),
			'AI' => array(
				'country'   => 'Anguilla',
				'continent' => 'NA',
			),
			'AQ' => array(
				'country'   => 'AN',
				'continent' => 'AN',
			),
			'AG' => array(
				'country'   => 'Antigua and Barbuda',
				'continent' => 'NA',
			),
			'AR' => array(
				'country'   => 'Argentina',
				'continent' => 'SA',
			),
			'AM' => array(
				'country'   => 'Armenia',
				'continent' => 'AS',
			),
			'AW' => array(
				'country'   => 'Aruba',
				'continent' => 'NA',
			),
			'AU' => array(
				'country'   => 'Australia',
				'continent' => 'OC',
			),
			'AT' => array(
				'country'   => 'Austria',
				'continent' => 'EU',
			),
			'AZ' => array(
				'country'   => 'Azerbaijan',
				'continent' => 'AS',
			),
			'BS' => array(
				'country'   => 'Bahamas',
				'continent' => 'NA',
			),
			'BH' => array(
				'country'   => 'Bahrain',
				'continent' => 'AS',
			),
			'BD' => array(
				'country'   => 'Bangladesh',
				'continent' => 'AS',
			),
			'BB' => array(
				'country'   => 'Barbados',
				'continent' => 'NA',
			),
			'BY' => array(
				'country'   => 'Belarus',
				'continent' => 'EU',
			),
			'BE' => array(
				'country'   => 'Belgium',
				'continent' => 'EU',
			),
			'BZ' => array(
				'country'   => 'Belize',
				'continent' => 'NA',
			),
			'BJ' => array(
				'country'   => 'Benin',
				'continent' => 'AF',
			),
			'BM' => array(
				'country'   => 'Bermuda',
				'continent' => 'NA',
			),
			'BT' => array(
				'country'   => 'Bhutan',
				'continent' => 'AS',
			),
			'BO' => array(
				'country'   => 'Bolivia',
				'continent' => 'SA',
			),
			'BA' => array(
				'country'   => 'Bosnia and Herzegovina',
				'continent' => 'EU',
			),
			'BW' => array(
				'country'   => 'Botswana',
				'continent' => 'AF',
			),
			'BV' => array(
				'country'   => 'Bouvet Island',
				'continent' => 'AN',
			),
			'BR' => array(
				'country'   => 'Brazil',
				'continent' => 'SA',
			),
			'IO' => array(
				'country'   => 'British Indian Ocean Territory',
				'continent' => 'AS',
			),
			'BN' => array(
				'country'   => 'Brunei Darussalam',
				'continent' => 'AS',
			),
			'BG' => array(
				'country'   => 'Bulgaria',
				'continent' => 'EU',
			),
			'BF' => array(
				'country'   => 'Burkina Faso',
				'continent' => 'AF',
			),
			'BI' => array(
				'country'   => 'Burundi',
				'continent' => 'AF',
			),
			'KH' => array(
				'country'   => 'Cambodia',
				'continent' => 'AS',
			),
			'CM' => array(
				'country'   => 'Cameroon',
				'continent' => 'AF',
			),
			'CA' => array(
				'country'   => 'Canada',
				'continent' => 'NA',
			),
			'CV' => array(
				'country'   => 'Cape Verde',
				'continent' => 'AF',
			),
			'KY' => array(
				'country'   => 'Cayman Islands',
				'continent' => 'NA',
			),
			'CF' => array(
				'country'   => 'Central African Republic',
				'continent' => 'AF',
			),
			'TD' => array(
				'country'   => 'Chad',
				'continent' => 'AF',
			),
			'CL' => array(
				'country'   => 'Chile',
				'continent' => 'SA',
			),
			'CN' => array(
				'country'   => 'China',
				'continent' => 'AS',
			),
			'CX' => array(
				'country'   => 'Christmas Island',
				'continent' => 'AS',
			),
			'CC' => array(
				'country'   => 'Cocos (Keeling) Islands',
				'continent' => 'AS',
			),
			'CO' => array(
				'country'   => 'Colombia',
				'continent' => 'SA',
			),
			'KM' => array(
				'country'   => 'Comoros',
				'continent' => 'AF',
			),
			'CG' => array(
				'country'   => 'Congo',
				'continent' => 'AF',
			),
			'CD' => array(
				'country'   => 'The Democratic Republic of The Congo',
				'continent' => 'AF',
			),
			'CK' => array(
				'country'   => 'Cook Islands',
				'continent' => 'OC',
			),
			'CR' => array(
				'country'   => 'Costa Rica',
				'continent' => 'NA',
			),
			'CI' => array(
				'country'   => 'Cote D\'ivoire',
				'continent' => 'AF',
			),
			'HR' => array(
				'country'   => 'Croatia',
				'continent' => 'EU',
			),
			'CU' => array(
				'country'   => 'Cuba',
				'continent' => 'NA',
			),
			'CY' => array(
				'country'   => 'Cyprus',
				'continent' => 'AS',
			),
			'CZ' => array(
				'country'   => 'Czech Republic',
				'continent' => 'EU',
			),
			'DK' => array(
				'country'   => 'Denmark',
				'continent' => 'EU',
			),
			'DJ' => array(
				'country'   => 'Djibouti',
				'continent' => 'AF',
			),
			'DM' => array(
				'country'   => 'Dominica',
				'continent' => 'NA',
			),
			'DO' => array(
				'country'   => 'Dominican Republic',
				'continent' => 'NA',
			),
			'EC' => array(
				'country'   => 'Ecuador',
				'continent' => 'SA',
			),
			'EG' => array(
				'country'   => 'Egypt',
				'continent' => 'AF',
			),
			'SV' => array(
				'country'   => 'El Salvador',
				'continent' => 'NA',
			),
			'GQ' => array(
				'country'   => 'Equatorial Guinea',
				'continent' => 'AF',
			),
			'ER' => array(
				'country'   => 'Eritrea',
				'continent' => 'AF',
			),
			'EE' => array(
				'country'   => 'Estonia',
				'continent' => 'EU',
			),
			'ET' => array(
				'country'   => 'Ethiopia',
				'continent' => 'AF',
			),
			'FK' => array(
				'country'   => 'Falkland Islands (Malvinas)',
				'continent' => 'SA',
			),
			'FO' => array(
				'country'   => 'Faroe Islands',
				'continent' => 'EU',
			),
			'FJ' => array(
				'country'   => 'Fiji',
				'continent' => 'OC',
			),
			'FI' => array(
				'country'   => 'Finland',
				'continent' => 'EU',
			),
			'FR' => array(
				'country'   => 'France',
				'continent' => 'EU',
			),
			'GF' => array(
				'country'   => 'French Guiana',
				'continent' => 'SA',
			),
			'PF' => array(
				'country'   => 'French Polynesia',
				'continent' => 'OC',
			),
			'TF' => array(
				'country'   => 'French Southern Territories',
				'continent' => 'AN',
			),
			'GA' => array(
				'country'   => 'Gabon',
				'continent' => 'AF',
			),
			'GM' => array(
				'country'   => 'Gambia',
				'continent' => 'AF',
			),
			'GE' => array(
				'country'   => 'Georgia',
				'continent' => 'AS',
			),
			'DE' => array(
				'country'   => 'Germany',
				'continent' => 'EU',
			),
			'GH' => array(
				'country'   => 'Ghana',
				'continent' => 'AF',
			),
			'GI' => array(
				'country'   => 'Gibraltar',
				'continent' => 'EU',
			),
			'GR' => array(
				'country'   => 'Greece',
				'continent' => 'EU',
			),
			'GL' => array(
				'country'   => 'Greenland',
				'continent' => 'NA',
			),
			'GD' => array(
				'country'   => 'Grenada',
				'continent' => 'NA',
			),
			'GP' => array(
				'country'   => 'Guadeloupe',
				'continent' => 'NA',
			),
			'GU' => array(
				'country'   => 'Guam',
				'continent' => 'OC',
			),
			'GT' => array(
				'country'   => 'Guatemala',
				'continent' => 'NA',
			),
			'GG' => array(
				'country'   => 'Guernsey',
				'continent' => 'EU',
			),
			'GN' => array(
				'country'   => 'Guinea',
				'continent' => 'AF',
			),
			'GW' => array(
				'country'   => 'Guinea-bissau',
				'continent' => 'AF',
			),
			'GY' => array(
				'country'   => 'Guyana',
				'continent' => 'SA',
			),
			'HT' => array(
				'country'   => 'Haiti',
				'continent' => 'NA',
			),
			'HM' => array(
				'country'   => 'Heard Island and Mcdonald Islands',
				'continent' => 'AN',
			),
			'VA' => array(
				'country'   => 'Holy See (Vatican City State)',
				'continent' => 'EU',
			),
			'HN' => array(
				'country'   => 'Honduras',
				'continent' => 'NA',
			),
			'HK' => array(
				'country'   => 'Hong Kong',
				'continent' => 'AS',
			),
			'HU' => array(
				'country'   => 'Hungary',
				'continent' => 'EU',
			),
			'IS' => array(
				'country'   => 'Iceland',
				'continent' => 'EU',
			),
			'IN' => array(
				'country'   => 'India',
				'continent' => 'AS',
			),
			'ID' => array(
				'country'   => 'Indonesia',
				'continent' => 'AS',
			),
			'IR' => array(
				'country'   => 'Iran',
				'continent' => 'AS',
			),
			'IQ' => array(
				'country'   => 'Iraq',
				'continent' => 'AS',
			),
			'IE' => array(
				'country'   => 'Ireland',
				'continent' => 'EU',
			),
			'IM' => array(
				'country'   => 'Isle of Man',
				'continent' => 'EU',
			),
			'IL' => array(
				'country'   => 'Israel',
				'continent' => 'AS',
			),
			'IT' => array(
				'country'   => 'Italy',
				'continent' => 'EU',
			),
			'JM' => array(
				'country'   => 'Jamaica',
				'continent' => 'NA',
			),
			'JP' => array(
				'country'   => 'Japan',
				'continent' => 'AS',
			),
			'JE' => array(
				'country'   => 'Jersey',
				'continent' => 'EU',
			),
			'JO' => array(
				'country'   => 'Jordan',
				'continent' => 'AS',
			),
			'KZ' => array(
				'country'   => 'Kazakhstan',
				'continent' => 'AS',
			),
			'KE' => array(
				'country'   => 'Kenya',
				'continent' => 'AF',
			),
			'KI' => array(
				'country'   => 'Kiribati',
				'continent' => 'OC',
			),
			'KP' => array(
				'country'   => 'Democratic People\'s Republic of Korea',
				'continent' => 'AS',
			),
			'KR' => array(
				'country'   => 'Republic of Korea',
				'continent' => 'AS',
			),
			'KW' => array(
				'country'   => 'Kuwait',
				'continent' => 'AS',
			),
			'KG' => array(
				'country'   => 'Kyrgyzstan',
				'continent' => 'AS',
			),
			'LA' => array(
				'country'   => 'Lao People\'s Democratic Republic',
				'continent' => 'AS',
			),
			'LV' => array(
				'country'   => 'Latvia',
				'continent' => 'EU',
			),
			'LB' => array(
				'country'   => 'Lebanon',
				'continent' => 'AS',
			),
			'LS' => array(
				'country'   => 'Lesotho',
				'continent' => 'AF',
			),
			'LR' => array(
				'country'   => 'Liberia',
				'continent' => 'AF',
			),
			'LY' => array(
				'country'   => 'Libya',
				'continent' => 'AF',
			),
			'LI' => array(
				'country'   => 'Liechtenstein',
				'continent' => 'EU',
			),
			'LT' => array(
				'country'   => 'Lithuania',
				'continent' => 'EU',
			),
			'LU' => array(
				'country'   => 'Luxembourg',
				'continent' => 'EU',
			),
			'MO' => array(
				'country'   => 'Macao',
				'continent' => 'AS',
			),
			'MK' => array(
				'country'   => 'Macedonia',
				'continent' => 'EU',
			),
			'MG' => array(
				'country'   => 'Madagascar',
				'continent' => 'AF',
			),
			'MW' => array(
				'country'   => 'Malawi',
				'continent' => 'AF',
			),
			'MY' => array(
				'country'   => 'Malaysia',
				'continent' => 'AS',
			),
			'MV' => array(
				'country'   => 'Maldives',
				'continent' => 'AS',
			),
			'ML' => array(
				'country'   => 'Mali',
				'continent' => 'AF',
			),
			'MT' => array(
				'country'   => 'Malta',
				'continent' => 'EU',
			),
			'MH' => array(
				'country'   => 'Marshall Islands',
				'continent' => 'OC',
			),
			'MQ' => array(
				'country'   => 'Martinique',
				'continent' => 'NA',
			),
			'MR' => array(
				'country'   => 'Mauritania',
				'continent' => 'AF',
			),
			'MU' => array(
				'country'   => 'Mauritius',
				'continent' => 'AF',
			),
			'YT' => array(
				'country'   => 'Mayotte',
				'continent' => 'AF',
			),
			'MX' => array(
				'country'   => 'Mexico',
				'continent' => 'NA',
			),
			'FM' => array(
				'country'   => 'Micronesia',
				'continent' => 'OC',
			),
			'MD' => array(
				'country'   => 'Moldova',
				'continent' => 'EU',
			),
			'MC' => array(
				'country'   => 'Monaco',
				'continent' => 'EU',
			),
			'MN' => array(
				'country'   => 'Mongolia',
				'continent' => 'AS',
			),
			'ME' => array(
				'country'   => 'Montenegro',
				'continent' => 'EU',
			),
			'MS' => array(
				'country'   => 'Montserrat',
				'continent' => 'NA',
			),
			'MA' => array(
				'country'   => 'Morocco',
				'continent' => 'AF',
			),
			'MZ' => array(
				'country'   => 'Mozambique',
				'continent' => 'AF',
			),
			'MM' => array(
				'country'   => 'Myanmar',
				'continent' => 'AS',
			),
			'NA' => array(
				'country'   => 'Namibia',
				'continent' => 'AF',
			),
			'NR' => array(
				'country'   => 'Nauru',
				'continent' => 'OC',
			),
			'NP' => array(
				'country'   => 'Nepal',
				'continent' => 'AS',
			),
			'NL' => array(
				'country'   => 'Netherlands',
				'continent' => 'EU',
			),
			'AN' => array(
				'country'   => 'Netherlands Antilles',
				'continent' => 'NA',
			),
			'NC' => array(
				'country'   => 'New Caledonia',
				'continent' => 'OC',
			),
			'NZ' => array(
				'country'   => 'New Zealand',
				'continent' => 'OC',
			),
			'NI' => array(
				'country'   => 'Nicaragua',
				'continent' => 'NA',
			),
			'NE' => array(
				'country'   => 'Niger',
				'continent' => 'AF',
			),
			'NG' => array(
				'country'   => 'Nigeria',
				'continent' => 'AF',
			),
			'NU' => array(
				'country'   => 'Niue',
				'continent' => 'OC',
			),
			'NF' => array(
				'country'   => 'Norfolk Island',
				'continent' => 'OC',
			),
			'MP' => array(
				'country'   => 'Northern Mariana Islands',
				'continent' => 'OC',
			),
			'NO' => array(
				'country'   => 'Norway',
				'continent' => 'EU',
			),
			'OM' => array(
				'country'   => 'Oman',
				'continent' => 'AS',
			),
			'PK' => array(
				'country'   => 'Pakistan',
				'continent' => 'AS',
			),
			'PW' => array(
				'country'   => 'Palau',
				'continent' => 'OC',
			),
			'PS' => array(
				'country'   => 'Palestinia',
				'continent' => 'AS',
			),
			'PA' => array(
				'country'   => 'Panama',
				'continent' => 'NA',
			),
			'PG' => array(
				'country'   => 'Papua New Guinea',
				'continent' => 'OC',
			),
			'PY' => array(
				'country'   => 'Paraguay',
				'continent' => 'SA',
			),
			'PE' => array(
				'country'   => 'Peru',
				'continent' => 'SA',
			),
			'PH' => array(
				'country'   => 'Philippines',
				'continent' => 'AS',
			),
			'PN' => array(
				'country'   => 'Pitcairn',
				'continent' => 'OC',
			),
			'PL' => array(
				'country'   => 'Poland',
				'continent' => 'EU',
			),
			'PT' => array(
				'country'   => 'Portugal',
				'continent' => 'EU',
			),
			'PR' => array(
				'country'   => 'Puerto Rico',
				'continent' => 'NA',
			),
			'QA' => array(
				'country'   => 'Qatar',
				'continent' => 'AS',
			),
			'RE' => array(
				'country'   => 'Reunion',
				'continent' => 'AF',
			),
			'RO' => array(
				'country'   => 'Romania',
				'continent' => 'EU',
			),
			'RU' => array(
				'country'   => 'Russian Federation',
				'continent' => 'EU',
			),
			'RW' => array(
				'country'   => 'Rwanda',
				'continent' => 'AF',
			),
			'SH' => array(
				'country'   => 'Saint Helena',
				'continent' => 'AF',
			),
			'KN' => array(
				'country'   => 'Saint Kitts and Nevis',
				'continent' => 'NA',
			),
			'LC' => array(
				'country'   => 'Saint Lucia',
				'continent' => 'NA',
			),
			'PM' => array(
				'country'   => 'Saint Pierre and Miquelon',
				'continent' => 'NA',
			),
			'VC' => array(
				'country'   => 'Saint Vincent and The Grenadines',
				'continent' => 'NA',
			),
			'WS' => array(
				'country'   => 'Samoa',
				'continent' => 'OC',
			),
			'SM' => array(
				'country'   => 'San Marino',
				'continent' => 'EU',
			),
			'ST' => array(
				'country'   => 'Sao Tome and Principe',
				'continent' => 'AF',
			),
			'SA' => array(
				'country'   => 'Saudi Arabia',
				'continent' => 'AS',
			),
			'SN' => array(
				'country'   => 'Senegal',
				'continent' => 'AF',
			),
			'RS' => array(
				'country'   => 'Serbia',
				'continent' => 'EU',
			),
			'SC' => array(
				'country'   => 'Seychelles',
				'continent' => 'AF',
			),
			'SL' => array(
				'country'   => 'Sierra Leone',
				'continent' => 'AF',
			),
			'SG' => array(
				'country'   => 'Singapore',
				'continent' => 'AS',
			),
			'SK' => array(
				'country'   => 'Slovakia',
				'continent' => 'EU',
			),
			'SI' => array(
				'country'   => 'Slovenia',
				'continent' => 'EU',
			),
			'SB' => array(
				'country'   => 'Solomon Islands',
				'continent' => 'OC',
			),
			'SO' => array(
				'country'   => 'Somalia',
				'continent' => 'AF',
			),
			'ZA' => array(
				'country'   => 'South Africa',
				'continent' => 'AF',
			),
			'GS' => array(
				'country'   => 'South Georgia and The South Sandwich Islands',
				'continent' => 'AN',
			),
			'ES' => array(
				'country'   => 'Spain',
				'continent' => 'EU',
			),
			'LK' => array(
				'country'   => 'Sri Lanka',
				'continent' => 'AS',
			),
			'SD' => array(
				'country'   => 'Sudan',
				'continent' => 'AF',
			),
			'SR' => array(
				'country'   => 'Suriname',
				'continent' => 'SA',
			),
			'SJ' => array(
				'country'   => 'Svalbard and Jan Mayen',
				'continent' => 'EU',
			),
			'SZ' => array(
				'country'   => 'Swaziland',
				'continent' => 'AF',
			),
			'SE' => array(
				'country'   => 'Sweden',
				'continent' => 'EU',
			),
			'CH' => array(
				'country'   => 'Switzerland',
				'continent' => 'EU',
			),
			'SY' => array(
				'country'   => 'Syrian Arab Republic',
				'continent' => 'AS',
			),
			'TW' => array(
				'country'   => 'Taiwan, Province of China',
				'continent' => 'AS',
			),
			'TJ' => array(
				'country'   => 'Tajikistan',
				'continent' => 'AS',
			),
			'TZ' => array(
				'country'   => 'Tanzania, United Republic of',
				'continent' => 'AF',
			),
			'TH' => array(
				'country'   => 'Thailand',
				'continent' => 'AS',
			),
			'TL' => array(
				'country'   => 'Timor-leste',
				'continent' => 'AS',
			),
			'TG' => array(
				'country'   => 'Togo',
				'continent' => 'AF',
			),
			'TK' => array(
				'country'   => 'Tokelau',
				'continent' => 'OC',
			),
			'TO' => array(
				'country'   => 'Tonga',
				'continent' => 'OC',
			),
			'TT' => array(
				'country'   => 'Trinidad and Tobago',
				'continent' => 'NA',
			),
			'TN' => array(
				'country'   => 'Tunisia',
				'continent' => 'AF',
			),
			'TR' => array(
				'country'   => 'Turkey',
				'continent' => 'AS',
			),
			'TM' => array(
				'country'   => 'Turkmenistan',
				'continent' => 'AS',
			),
			'TC' => array(
				'country'   => 'Turks and Caicos Islands',
				'continent' => 'NA',
			),
			'TV' => array(
				'country'   => 'Tuvalu',
				'continent' => 'OC',
			),
			'UG' => array(
				'country'   => 'Uganda',
				'continent' => 'AF',
			),
			'UA' => array(
				'country'   => 'Ukraine',
				'continent' => 'EU',
			),
			'AE' => array(
				'country'   => 'United Arab Emirates',
				'continent' => 'AS',
			),
			'GB' => array(
				'country'   => 'United Kingdom',
				'continent' => 'EU',
			),
			'US' => array(
				'country'   => 'United States',
				'continent' => 'NA',
			),
			'UM' => array(
				'country'   => 'United States Minor Outlying Islands',
				'continent' => 'OC',
			),
			'UY' => array(
				'country'   => 'Uruguay',
				'continent' => 'SA',
			),
			'UZ' => array(
				'country'   => 'Uzbekistan',
				'continent' => 'AS',
			),
			'VU' => array(
				'country'   => 'Vanuatu',
				'continent' => 'OC',
			),
			'VE' => array(
				'country'   => 'Venezuela',
				'continent' => 'SA',
			),
			'VN' => array(
				'country'   => 'Viet Nam',
				'continent' => 'AS',
			),
			'VG' => array(
				'country'   => 'Virgin Islands, British',
				'continent' => 'NA',
			),
			'VI' => array(
				'country'   => 'Virgin Islands, U.S.',
				'continent' => 'NA',
			),
			'WF' => array(
				'country'   => 'Wallis and Futuna',
				'continent' => 'OC',
			),
			'EH' => array(
				'country'   => 'Western Sahara',
				'continent' => 'AF',
			),
			'YE' => array(
				'country'   => 'Yemen',
				'continent' => 'AS',
			),
			'ZM' => array(
				'country'   => 'Zambia',
				'continent' => 'AF',
			),
			'ZW' => array(
				'country'   => 'Zimbabwe',
				'continent' => 'AF',
			),
		);

		return $countries;
	}

	public function plugin_row_meta( $links, $file ) {
		// 	if( strpos( $plugin_file_name, basename(__FILE__) )) {
		$row_meta = array();
		if( $file === 'warehouse-popups-woocommerce/warehouse-popups-woocommerce.php' ) {
			$row_meta = array(
				'pro'		=> '<a href="https://venby.io/wordpress-plugin-woocommerce-warehouses-pro/" title="Pro version">Pro version</a>',
				'docs'		=> '<a href="https://venby.io/help/" title="Documentation">Documentation</a>',
				'support'	=> '<a href="https://venby.io/wordpress-plugin-woocommerce-warehouses-pro/" title="Documentation">Support</a>',
			);
		}
		
		return array_merge( $links, $row_meta );
		
	}

	public function setting_links( $links, $plugin_file_name ) {
		if( strpos( $plugin_file_name, basename(__FILE__) )) {
			if( $file === 'warehouse-popups-woocommerce/warehouse-popups-woocommerce.php' ) {
				array_unshift( $links, '<a href="#">Settings</a>');
			}
		}
		return $links;
	}

	private static function whp_unlock_unlimited_warehouses() {
		?>
		<div class="button-wrapper">
			<h2>Buy Warehouses Pro & add unlimited warehouses.</h2>
				<div class="form-group">
					<input type="text" class="forminp forminp-text" placeholder="License Key">
					<button class="button button-primary" type="submit">Unlock</button>
					<button class="button button-secondary" type="button">Buy License</button>
				</div>
				<p>Venby's free version will add one additional Warehouse. To add Unlimited Warehouses, buy our PRO version. Buy the PRO version license <a href="https://venby.io/wordpress-plugin-woocommerce-warehouses-pro/" target="_blank">here</a></p>
				<h4>Status: <span style="color: green;">Activated</span></h4>
				
		</div>
		<?php
	}
	
	private function whp_get_pro_version() {

	}

	private static function custom_checkout_field_display_admin_order_meta( $item_id, $item, $par ) {
	    global $post;
	    if (get_class($item) != 'WC_Order_Item_Shipping') {
	        return;
	    }
	    $order_id = $post->ID;
	    $warehouse_list = (array)json_decode(get_option('warehouse-popups-woocommerce-list'));
	    $warehouse_id = get_post_meta( $order_id, '_wh-popups-warehouse-id', true );
	    if (count($warehouse_list) && $warehouse_id) {
	        echo '<p><strong>'.__('Офис самовывоза').':</strong> ' . $warehouse_list[$warehouse_id]->name . '</p>';
	    }
	}
}

function action_woocommerce_settings_save_warehouses( $array ) { 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	if( isset($_POST['redirect_to']) ){
    		wp_redirect( $_POST['redirect_to'] );
    	}
	}
};
add_action( "woocommerce_settings_save_warehouses", 'action_woocommerce_settings_save_warehouses', 10, 1 ); 