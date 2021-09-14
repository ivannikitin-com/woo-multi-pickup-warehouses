<div class="button-wrapper">
    <h2><?php echo __('Google Maps GEO IP Location Detection', 'warehouse-popups-woocommerce-pro'); ?></h2>
    <button id="warehouse-popups-woocommerce-pro-manage-google"
            class="button button-secondary warehouse-popups-woocommerce-pro-manage-google" type="button"
            value="<?php echo __('Manage Google API key', 'warehouse-popups-woocommerce-pro'); ?>">
        <?php echo __('Connect', 'warehouse-popups-woocommerce-pro'); ?>
    </button>
</div>

<div id="manage-inventory-popup">
    <div class="popup-background"></div>
    <div class="inventory-form">
        <div class="loading-wrapper">
            <img src="images/loading.gif" alt="loading"/>
        </div>
        <a href="javascript:void(0)" class="close-btn">Close</a>
        <div class="form-group">
            <span id="apiKey-response-message"></span>
        </div>
        <div class="form-group">
            <?php $api_key = get_option('warehouse-popups-woocommerce-api-key'); ?>
            <p>
                <a href="https://venby.tv/#/settings/edit/api_key" target="_blank">
                    <?php _e('Venby API Key', 'warehouse-popups-woocommerce'); ?>
                </a>
            </p>
            <input type="text" id="venby-api-key" value="<?php echo isset($api_key) ? $api_key : ''; ?>"
                   name="zapier-hook-url"/>
            <p style="margin-top: 10px;">
                <a href="https://zapier.com/developer/public-invite/8566/845c5fc947fbfb36e6bbfd13b298add4/"
                   target="_blank"><?php _e('Connect With Zapier', 'warehouse-popups-woocommerce'); ?>
                </a>
            </p>
        </div>
        <div class="form-group">
            <button type="button" id="warehouse-popups-woocommerce-inventory-save"
                    data-action="<?php echo admin_url('admin-ajax.php'); ?>" name="inventory-save"
                    value="inventory-save"
                    class="btn btn-save">
                <?php _e('Save', 'warehouse-popups-woocommerce'); ?>
            </button>
            <button type="button"
                    class="btn btn-cancel">
                <?php _e('Cancel', 'warehouse-popups-woocommerce'); ?>
            </button>
        </div>
    </div>
</div>

<div id="manage-google-popup" class="manage-popup">
    <div class="popup-background"></div>
    <div class="google-form manage-form">
        <div class="loading-wrapper">
            <img src="images/loading.gif" alt="loading"/>
        </div>
        <a href="javascript:void(0)" class="close-btn">Close</a>
        <div class="form-group">
            <span id="apiKey-response-message"></span>
        </div>
        <div class="form-group">
            <p>
                <a href="https://developers.google.com/maps/documentation/javascript/get-api-key"
                   target="_blank"><?php echo __('Google API Key', 'warehouse-popups-woocommerce-pro'); ?>
                </a>
            </p>
            <input type="text" id="google-api-key" value="<?php echo isset($google_api_key) ? $google_api_key : ''; ?>"
                   name="warehouse-popups-woocommerce-warehouses-google-api-key"/>
        </div>
        <div class="form-group">
            <button type="button" id="warehouse-popups-woocommerce-pro-google-save"
                    data-action="<?php echo admin_url('admin-ajax.php'); ?>" name="google-save"
                    value="google-save"
                    class="btn btn-save">
                <?php echo __('Save', 'warehouse-popups-woocommerce-pro'); ?>
            </button>
            <button type="button" class="btn btn-cancel">
                <?php echo __('Cancel', 'warehouse-popups-woocommerce-pro'); ?>
            </button>
        </div>
    </div>
</div>

<p>
    <b><?php echo __('Shortcode Instructions:', 'warehouse-popups-woocommerce'); ?></b>
    <br>
    <?php echo __('To display Warehouses Dropdown Switch on your website - use', 'warehouse-popups-woocommerce'); ?>
    <code>[wh_popups_warehouses_switch]</code> <?php echo __('shortcode.', 'warehouse-popups-woocommerce'); ?>
    <br>
    <?php echo __('You can put it anywhere into page content using Wp Admin editor just by copy-paste,', 'warehouse-popups-woocommerce'); ?>
    <br>
    <?php echo __('or insert as PHP code into your theme files:', 'warehouse-popups-woocommerce'); ?>
    <code><?php echo htmlspecialchars('<?php echo do_shortcode(\'[wh_popups_warehouses_switch]\']); ?>') ?></code>
    <br>
    <?php echo __('Or use', 'warehouse-popups-woocommerce'); ?>
    <code>[wh_popups_warehouses_flybox]</code> <?php echo __('shortcode to switch warehouses in popup flybox.', 'warehouse-popups-woocommerce'); ?>
</p>
<p>
    <b><?php echo __('Usage Instructions:', 'warehouse-popups-woocommerce'); ?></b>
    <br>
    <b style="color: red;"><?php echo __('Notes:', 'warehouse-popups-woocommerce'); ?> </b>
    <?php echo __('Get currency converter API key for:', 'warehouse-popups-woocommerce'); ?>
    <a href="https://www.currencyconverterapi.com/" target="_blank">
        <?php echo __('The Currency Converter API', 'warehouse-popups-woocommerce'); ?>
    </a>
    <br/><br/>
</p>