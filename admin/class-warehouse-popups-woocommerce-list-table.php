<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Warehouse_Popups_Woocommerce_List_table extends WP_List_Table {

	function __construct() {
		parent::__construct( array(
			'singular'=> 'wh-popups-warehouse', //Singular label
			'plural' => 'warehouse-popups-woocommerce-list', //plural label, also this well be one of the table css class
			'ajax'   => false //We won't support Ajax for this table
		) );
	}

	function get_columns() {
		$columns = array(
			'name' => __( 'Warehouse Name', 'warehouse-popups-woocommerce' ),
			'location' => __( 'Location', 'warehouse-popups-woocommerce' ),
			'currency' => __( 'Currency', 'warehouse-popups-woocommerce' ),
			'convert_ratio' => __('Ratio', 'warehouse-popups-woocommerce'),
			'gateway' => __( 'Payment Gateway', 'warehouse-popups-woocommerce' ),
			'countries' => __( 'Countries', 'warehouse-popups-woocommerce' ),
			'use-default' => __( 'Use Default Stock Quantity?', 'warehouse-popups-woocommerce' ),
		);

		return $columns;
	}

	function column_name( $item ) {

		// create a nonce
		$delete_nonce = wp_create_nonce( 'wh_popups_delete_warehouse' );

		$title = '<strong>' . $item->name . '</strong>';

		$actions = [
			'edit' => sprintf( '<a href="?page=%s&tab=%s&action=%s&edit_id=%s" >'.__( 'Edit', 'warehouse-popups-woocommerce' ).'</a>', esc_attr( $_REQUEST['page'] ), esc_attr( $_REQUEST['tab'] ), 'edit', trim( $item->id )),
			'delete' => sprintf( '<a href="?page=%s&tab=%s&action=%s&warehouse=%s&_wpnonce=%s" onclick="return confirm('.__( 'Delete Entry?', 'warehouse-popups-woocommerce' ).')">'.__( 'Delete', 'warehouse-popups-woocommerce' ).'</a>', esc_attr( $_REQUEST['page'] ), esc_attr( $_REQUEST['tab'] ), 'delete', trim( $item->id ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'convert_ratio':
				return $item->convert_ratio;
			case 'currency':
			case 'location':			   
				return ( isset($item->{$column_name}) ) ? $item->{$column_name} : 'n/a';
			case 'gateway':
				{
					$all_gateways = WC()->payment_gateways->payment_gateways();

					$gateway_name = __('n/a', 'warehouse-popups-woocommerce' );

					if (  isset($item->gateways) && is_array( $item->gateways ) && sizeof( $item->gateways ) > 0 )
					{

						$selected_gateways_titles = array();
						foreach ( $item->gateways as $selected_gateway )
						{
							$selected_gateways_titles[] = $all_gateways[$selected_gateway]->title;
						}

						if ( sizeof($selected_gateways_titles) > 0 )
						{
							$gateway_name = implode(', ', $selected_gateways_titles);
						}
					}

					return $gateway_name;
				}
			case 'countries':
				{
					//if ( isset($item->shipping) == 'country' && is_array($item->countries) && sizeof($item->countries) > 0 )
					if( $item->shipping_country == 1 && is_array( $item->countries ) && sizeof( $item->countries ) > 0 ){
						return implode(', ', $item->countries);
					}else if ( $item->shipping_zone == 1 && !empty($item->zone_country) ){
						$delivery_zones = WC_Shipping_Zones::get_zones();
						$return = $delivery_zones[$item->zone_country]['zone_name'];
						if ( is_array( $item->zone_zip_codes ) && sizeof( $item->zone_zip_codes ) > 0 ) $return .= ' ('.implode(' ', $item->zone_zip_codes).')';

						return $return;
					}else{
						return 'WorldWide';
					}

				}
			case 'use-default':
				{
					return ( intval($item->{'use-default'}) == 1 ) ? 'yes' : 'no';
				}
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	public function process_bulk_action()
	{
		$action = $this->current_action();

		switch ($action) {
			case 'delete': {
				// security check!
				if (isset($_REQUEST['_wpnonce']) && !empty($_REQUEST['_wpnonce'])) {

					$nonce = filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING);
					$nonce_action = 'wh_popups_' . $action . '_warehouse';

					if (!wp_verify_nonce($nonce, $nonce_action)) wp_die('Nope! Security check failed!');

					$wh_id = filter_input(INPUT_GET, 'warehouse', FILTER_SANITIZE_STRING);

					if (get_option('warehouse-popups-woocommerce-list') !== '') {
						$warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
						unset($warehouses_list[$wh_id]);
						update_option('warehouse-popups-woocommerce-list', json_encode($warehouses_list));
					}
				}
				wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=warehouses' ) );
			}
		}

        return;
    }

	public function prepare_items() {

	    $this->process_bulk_action();
		$this->_column_headers = array($this->get_columns(), array(), array());

		$per_page     = $this->get_items_per_page( 'warehouses_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::warehouses_count();
		$total_pages = ceil($total_items/$per_page);

		$this->set_pagination_args( array(
			"total_items" => $total_items
		) );

		$this->items = self::get_warehouses( $per_page, $current_page );
	}

	public static function get_warehouses( $per_page = 5, $page_number = 1 ) {

		$warehouses_list = array();
		if ( get_option('warehouse-popups-woocommerce-list') )
		{
			$warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
		}

		$page_warehouses_list = array_slice($warehouses_list, ( $page_number - 1 ) * $per_page, $per_page, true);
		$warehouses_objs = array();

		foreach ( $page_warehouses_list as $key => $one_wh )
		{
			if ( !isset($one_wh['id']) ) $one_wh['id'] = $key;
			$warehouses_objs[] = (object) $one_wh;
		}
		return $warehouses_objs;
	}

	public static function warehouses_count() {
		$warehouses_list = array();
		if ( get_option('warehouse-popups-woocommerce-list') )
		{
			$warehouses_list = json_decode(get_option('warehouse-popups-woocommerce-list'), true);
		}

		return sizeof($warehouses_list);
	}

	public function no_items() {
		_e( 'No warehouses have been created.', 'warehouse-popups-woocommerce'  );
	}
}
