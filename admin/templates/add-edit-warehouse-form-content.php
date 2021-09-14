<table class="form-table <?php echo $field_prefix; ?>-warehouse-cont">
    <?php $is_warehouse_shipping_zone = isset($edit_warehouse['shipping_zone']) && $edit_warehouse['shipping_zone']; ?>
    <tbody id="warehouse-popups-woocommerce-list-add-<?php echo $field_prefix ?>-cont"
           class="<?php echo $tbody_class ?>">
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-use-default">
                <?php _e('Use default Woocommerce Product Quantity?', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-select">
            <select name="<?php echo $field_prefix ?>-warehouse-use-default"
                    id="<?php echo $field_prefix ?>-warehouse-use-default">
                <option value="0" <?php if ($edit_warehouse !== false && (!isset($edit_warehouse['use-default']) || (isset($edit_warehouse['use-default']) && intval($edit_warehouse['use-default']) === 0))) echo 'selected'; ?>><?php _e('No', 'warehouse-popups-woocommerce'); ?></option>
                <option value="1" <?php if ($edit_warehouse !== false && (isset($edit_warehouse['use-default']) && intval($edit_warehouse['use-default']) === 1)) echo 'selected'; ?>><?php _e('Yes', 'warehouse-popups-woocommerce'); ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-name"><?php _e('Warehouse Name', 'warehouse-popups-woocommerce') ?></label>
        </th>
        <td class="forminp forminp-text">
            <input name="<?php echo $field_prefix ?>-warehouse-name" id="<?php echo $field_prefix ?>-warehouse-name"
                   type="text"
                   required
                   value="<?php if ($edit_warehouse !== false && isset($edit_warehouse['name'])) echo $edit_warehouse['name']; ?>">
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-name">
                <?php _e('Warehouse Country', 'warehouse-popups-woocommerce-pro') ?>
            </label>
        </th>
        <td class="forminp forminp-text">
            <select name="<?php echo $field_prefix ?>-warehouse-country"
                    id="<?php echo $field_prefix ?>-warehouse-country">
                <?php foreach ($countries_list as $country) {
                    $selected = '';
                    if ($edit_warehouse !== false && isset($edit_warehouse['country']) && $country === $edit_warehouse['country']) {
                        $selected = 'selected';
                    } ?>
                    <option value="<?php echo $country; ?>" <?php echo $selected; ?>><?php echo $country; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-name">
                <?php _e('Warehouse City', 'warehouse-popups-woocommerce-pro') ?>
            </label>
        </th>
        <td class="forminp forminp-text">
            <input name="<?php echo $field_prefix ?>-warehouse-city" id="<?php echo $field_prefix ?>-warehouse-city"
                   type="text"
                   value="<?php if ($edit_warehouse !== false && isset($edit_warehouse['city'])) echo $edit_warehouse['city']; ?>">
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-name">
                <?php _e('Warehouse Address line 1', 'warehouse-popups-woocommerce-pro') ?>
            </label>
        </th>
        <td class="forminp forminp-text">
            <input name="<?php echo $field_prefix ?>-warehouse-address1"
                   id="<?php echo $field_prefix ?>-warehouse-address1" type="text"
                   value="<?php if ($edit_warehouse !== false && isset($edit_warehouse['address1'])) echo $edit_warehouse['address1']; ?>">
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-name">
                <?php _e('Warehouse Address line 2', 'warehouse-popups-woocommerce-pro') ?>
            </label>
        </th>
        <td class="forminp forminp-text">
            <input name="<?php echo $field_prefix ?>-warehouse-address2"
                   id="<?php echo $field_prefix ?>-warehouse-address2" type="text"
                   value="<?php if ($edit_warehouse !== false && isset($edit_warehouse['address2'])) echo $edit_warehouse['address2']; ?>">
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-email">
                <?php _e('Warehouse Admin Email', 'warehouse-popups-woocommerce-pro') ?>
            </label>
        </th>
        <td class="forminp forminp-text">
            <input name="<?php echo $field_prefix ?>-warehouse-email" id="<?php echo $field_prefix ?>-warehouse-email"
                   type="text"
                   value="<?php if ($edit_warehouse !== false && isset($edit_warehouse['email'])) echo $edit_warehouse['email']; ?>">
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-currency">
                <?php _e('Warehouse Currency', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-select">
            <select name="<?php echo $field_prefix ?>-warehouse-currency"
                    id="<?php echo $field_prefix ?>-warehouse-currency">
                <?php
                if (is_array($currency_code_options) && sizeof($currency_code_options)) {
                    foreach ($currency_code_options as $curr_code => $curr_title) {
                        ?>
                        <option value="<?php echo $curr_code ?>" <?php if ($edit_warehouse !== false && isset($edit_warehouse['currency']) && $edit_warehouse['currency'] == $curr_code) echo 'selected'; ?>><?php echo $curr_title ?></option>
                        <?php
                    }
                }
                ?>
            </select>
    </tr>
    <tr>
        <th class="titledesc">
            <label>
                <?php _e('Currency Conversion Ratio', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-text">
            <input type="radio" <?php if (($edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] === 'flat') || ($edit_warehouse !== false && !isset($edit_warehouse['convert_ratio'])) || !$edit_warehouse) echo 'checked'; ?>
                   name="<?php echo $field_prefix ?>-warehouse-convert-ratio"
                   id="<?php echo $field_prefix ?>-warehouse-convert-ratio-flat" value="flat"><label
                    for="<?php echo $field_prefix ?>-warehouse-convert-ratio-flat"><?php _e('Flat (only changes currency symbol)', 'warehouse-popups-woocommerce'); ?></label><br>
            <input type="radio" <?php if ($edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'manual') echo 'checked'; ?>
                   name="<?php echo $field_prefix ?>-warehouse-convert-ratio"
                   id="<?php echo $field_prefix ?>-warehouse-convert-ratio-manual" value="manual">
            <label for="<?php echo $field_prefix ?>-warehouse-convert-ratio-manual">
                <?php _e('Manual, set your own exchange ratio:', 'warehouse-popups-woocommerce'); ?>
                <input name="<?php echo $field_prefix ?>-warehouse-manual-ratio"
                       style="width: 60px !important; padding: 2px 0; text-align: center; vertical-align: middle;"
                       type="number" size="5" maxlength="5" min="0" step="0.01" placeholder="1.00"
                       pattern="^\d+(?:\.\d{1,2})?$"
                       value="<?php echo $edit_warehouse['convert_manual_ratio'] ?>">
            </label> (
            <?php echo $woo_currency ?> = <?php echo $local_ratio ?><?php echo $local_currency ?> )
            <br>
            <input type="radio" <?php if ($edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'cur-convert-int') echo 'checked'; ?>
                   name="<?php echo $field_prefix ?>-warehouse-convert-ratio"
                   id="<?php echo $field_prefix ?>-warehouse-convert-ratio-free-forex-int"
                   value="cur-convert-int">
            <label for="<?php echo $field_prefix ?>-warehouse-convert-ratio-free-forex-int">
                <?php _e('Real-time currency conversion, round up to nearest whole currency unit', 'warehouse-popups-woocommerce'); ?>
            </label>
            <br>
            <input type="radio" <?php if ($edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && $edit_warehouse['convert_ratio'] == 'cur-convert-dec') echo 'checked'; ?>
                   name="<?php echo $field_prefix ?>-warehouse-convert-ratio"
                   id="<?php echo $field_prefix ?>-warehouse-convert-ratio-free-forex-dec"
                   value="cur-convert-dec">
            <label for="<?php echo $field_prefix ?>-warehouse-convert-ratio-free-forex-dec">
                <?php _e('Real-time currency conversion, round up to nearest decimal XX.', 'warehouse-popups-woocommerce'); ?>
                <input name="<?php echo $field_prefix ?>-warehouse-ratio-decimal"
                       style="width: 50px !important; padding: 2px 0; text-align: center; vertical-align: middle;"
                       type="number" size="2" maxlength="1" min="0" step="1" placeholder="2"
                       value="<?php echo is_null($edit_warehouse['convert_ratio_decimal']) ? 2 : $edit_warehouse['convert_ratio_decimal']; ?>">
            </label>
            <p>
                <a href="https://www.currencyconverterapi.com/" target="_blank">
                    <?php _e('Currency Converter API', 'warehouse-popups-woocommerce'); ?>
                </a>
            </p>
        </td>
    </tr>
    <?php
    $show_api_key = '';
    if ($edit_warehouse !== false && isset($edit_warehouse['convert_ratio']) && strpos($edit_warehouse['convert_ratio'], "cur-convert-") !== false) $show_api_key = 'active';
    ?>
    <tr id="currency-api-key" class="<?php echo $show_api_key; ?>">
        <th class="titledesc">
            <label>
                <?php _e('Currency Converter API Key', 'warehouse-popups-woocommerce-pro') ?>
            </label>
        </th>
        <td>
            <input name="<?php echo $field_prefix ?>-warehouse-key"
                   id="<?php echo $field_prefix ?>-warehouse-key" type="text"
                   value="<?php if ($edit_warehouse !== false && isset($edit_warehouse['key'])) echo $edit_warehouse['key']; ?>" <?php if ($show_api_key == 'active') echo 'required'; ?> >
        </td>
    </tr>
    <tr>
        <th class="titledesc">
            <label>
                <?php _e('Connected Payment Gateways', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-checkbox">
            <?php
            if (is_array($all_gateways) && sizeof($all_gateways) > 0) {
                $enabled_gateways_count = 0;
                foreach ($all_gateways as $gateway_key => $gateway_obj) {
                    if ($gateway_obj->enabled != 'yes') continue; // skip disabled gateways
                    else $enabled_gateways_count++;
                    ?><input type="checkbox"
                             id="<?php echo $field_prefix ?>-warehouse-gateways-<?php echo $gateway_key ?>"
                             name="<?php echo $field_prefix ?>-warehouse-gateways[<?php echo $gateway_key ?>]"
                             value="on"
                    <?php if ($edit_warehouse !== false && isset($edit_warehouse['gateways']) && in_array($gateway_key, $edit_warehouse['gateways'])) echo 'checked'; ?>
                    >
                    <label for="<?php echo $field_prefix ?>-warehouse-gateways-<?php echo $gateway_key ?>"><?php echo $gateway_obj->method_title . ' (' . $gateway_obj->title . ')' ?></label>
                    <br>
                    <?php
                }
                if ($enabled_gateways_count === 0) {
                    _e('No enabled warehouses in WooCommerce.', 'warehouse-popups-woocommerce');
                }
            }
            ?>
        </td>
    </tr>
    <tr id="shipping-zone-select">
        <th class="titledesc">
            <label for="<?php echo $field_prefix; ?>-warehouse-shipping">
                <?php _e('Auto GEO IP / Location Detection Checkout', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-radio">
            <label for="<?php echo $field_prefix; ?>-warehouse-shipping-zone"><input
                        type="checkbox" <?php if ($is_warehouse_shipping_zone) echo 'checked'; ?>
                        id="<?php echo $field_prefix; ?>-warehouse-shipping-zone"
                        name="<?php echo $field_prefix; ?>-warehouse-shipping-zone">
                <?php _e('by Shipping Zone', 'warehouse-popups-woocommerce') ?>
            </label>
        </td>
    </tr>
    </tbody>
    <tbody id="shipping-zone-mode" class="<?php if (!$is_warehouse_shipping_zone) echo 'hidden'; ?>">
    <tr>
        <th class="titledesc">
            <label for="warehouse-zone-country"><?php _e('Shipping Zones', 'warehouse-popups-woocommerce') ?></label>
        </th>
        <td class="forminp forminp-checkbox">
            <?php
            $delivery_zones = WC_Shipping_Zones::get_zones();
            if (is_array($delivery_zones) && sizeof($delivery_zones) > 0) {
                ?>
                <select name="<?php echo $field_prefix ?>-warehouse-zone-country" id="warehouse-zone-country">
                    <?php
                    foreach ($delivery_zones as $key => $the_zone) {
                        ?>
                        <option
                                value="<?php echo $the_zone['zone_id'] ?>" <?php if ($edit_warehouse !== false && isset($edit_warehouse['zone_country']) && $the_zone['zone_id'] === $edit_warehouse['zone_country']) echo 'selected'; ?>><?php echo $the_zone['zone_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
            }
            ?>
        </td>
    </tr>
    <tr class="<?php if (empty($edit_warehouse['zone_country']) || !in_array($edit_warehouse['zone_country'], $SHIPPING_ZONES_ENABLED)) echo 'hidden' ?>">
        <th class="titledesc">
            <label for="warehouse-zone-zip-codes">
                <?php _e('Zip Codes', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-textarea">
            <textarea style="width: 50%" id="warehouse-zone-zip-codes"
                      name="<?php echo $field_prefix ?>-warehouse-zone-zip-codes">
                <?php if ($edit_warehouse !== false && isset($edit_warehouse['zone_zip_codes'])) echo implode(', ', $edit_warehouse['zone_zip_codes']) ?>
            </textarea>
            <p><?php _e('List Zip postal codes separated by comma.', 'warehouse-popups-woocommerce'); ?></p>
            <p><?php _e('Zone Zip Codes option available only in USA, Canada, United Kingdom and Germany.', 'warehouse-popups-woocommerce'); ?>
                <br> <?php _e('Zip Code detection can be restricted by customer Internet Service Provider.', 'warehouse-popups-woocommerce'); ?>
            </p>
        </td>
    </tr>
    </tbody>
    <?php $is_warehouse_shipping_country = isset($edit_warehouse['shipping_country']) && $edit_warehouse['shipping_country']; ?>
    <tbody id="shipping-country-select" class="<?php if ($edit_warehouse === false) echo 'hidden'; ?>">
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-shipping">
                <?php _e('Auto GEO IP / Location Detection Checkout', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-radio">
            <label for="<?php echo $field_prefix; ?>-warehouse-shipping-country"><input
                        type="checkbox" <?php if ($is_warehouse_shipping_country) echo 'checked'; ?>
                        id="<?php echo $field_prefix; ?>-warehouse-shipping-country"
                        name="<?php echo $field_prefix; ?>-warehouse-shipping-country"> <?php _e('by Country', 'warehouse-popups-woocommerce') ?>
            </label>
        </td>
    </tr>
    </tbody>
    <tbody id="shipping-country-mode" class="<?php if (!$is_warehouse_shipping_country) echo 'hidden'; ?>">
    <tr>
        <th class="titledesc">
            <label for="<?php echo $field_prefix ?>-warehouse-countries-list">
                <?php _e('Countries', 'warehouse-popups-woocommerce') ?>
            </label>
        </th>
        <td class="forminp forminp-textarea">
            <textarea style="width: 50%" id="<?php echo $field_prefix ?>-warehouse-countries-list"
                      name="<?php echo $field_prefix ?>-warehouse-countries-list"><?php if ($edit_warehouse && isset($edit_warehouse['countries']) && !empty($edit_warehouse['countries'])) echo implode(', ', $edit_warehouse['countries']) ?></textarea>
            <p><?php _e('Users from selected countries will be automatically redirected to this warehouse.', 'warehouse-popups-woocommerce'); ?></p>
            <p><?php _e('List country codes separated by comma.', 'warehouse-popups-woocommerce'); ?>
                <a href="#" id="wh-popups-see-country-codes-list">
                    <?php _e('See country codes list', 'warehouse-popups-woocommerce'); ?>
                </a>
            </p>
            <p id="wh-popups-all-countries-codes" class="hidden">
                <?php
                if (is_array($countries_list) && sizeof($countries_list) > 0) {
                    foreach ($countries_list as $country_code => $country_arr) {
                        ?><b><?php echo $country_code; ?></b> - <?php echo $country_arr; ?> <?php
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
            <?php if (!$edit_warehouse) { ?>
                <button id="warehouse-popups-woocommerce-list-save" type="submit" name="save"
                        class="button button-primary warehouse-popups-woocommerce-list-save hidden"
                        value="<?php echo __('Create Warehouse', 'warehouse-popups-woocommerce'); ?>"
                        disabled><?php echo __('Create Warehouse', 'warehouse-popups-woocommerce'); ?></button>
                <?php if (get_option('warehouse-popups-woocommerce-license') || ($warehouses_count <= 1)): ?>
                <button id="warehouse-popups-woocommerce-list-add-btn"
                        class="button button-secondary warehouse-popups-woocommerce-list-add"
                        value="<?php echo __('Add Warehouse', 'warehouse-popups-woocommerce'); ?>"><?php echo __('Add Warehouse', 'warehouse-popups-woocommerce'); ?></button>
                <?php endif; ?>
                <button id="warehouse-popups-woocommerce-list-cancel-btn"
                        class="button button-secondary warehouse-popups-woocommerce-list-cancel hidden"
                        value="<?php echo __('Cancel', 'warehouse-popups-woocommerce'); ?>"><?php echo __('Cancel', 'warehouse-popups-woocommerce'); ?></button>
                <?php
            } else {
                // buttons for edit wh form
                ?>
                <input type="hidden" name="edit-warehouse-id" value="<?php echo $edit_warehouse['id']; ?>">
                <input type="hidden" name="redirect_to" class="redirect_to" value="<?php echo admin_url('admin.php?page=wc-settings&tab=warehouses'); ?>">
                <button id="warehouse-popups-woocommerce-edit-save-btn"
                        type="submit"
                        name="save"
                        class="button button-primary woocommerce-save-button warehouse-popups-woocommerce-edit-save"
                        value="<?php echo __('Save Changes', 'warehouse-popups-woocommerce'); ?>">
                    <?php echo __('Save Changes', 'warehouse-popups-woocommerce'); ?>
                </button>
                <button id="warehouse-popups-woocommerce-edit-cancel-btn"
                        onclick="window.location.href='admin.php?page=wc-settings&tab=warehouses'; return false;"
                        class="button button-secondary warehouse-popups-woocommerce-edit-cancel"
                        value="<?php echo __('Cancel', 'warehouse-popups-woocommerce'); ?>"><?php echo __('Cancel', 'warehouse-popups-woocommerce'); ?></button>
                <?php
            }
            ?>
        </th>
    </tr>
    </tfoot>
</table>