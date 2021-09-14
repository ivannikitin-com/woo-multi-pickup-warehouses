<div class="button-wrapper">
    <h2>Unlock Unlimited Warehouses</h2>
    <div class="form-group">
        <input type="text" id="whp-license-key" class="forminp forminp-text" placeholder="License Key" value="<?php echo get_option('warehouse-popups-woocommerce-license'); ?>">
        <?php if (!get_option('warehouse-popups-woocommerce-license')): ?>
            <button formnovalidate="formnovalidate" novalidate="novalidate" class="button button-primary whp-license-activate" type="submit">Unlock</button>
            <a href="https://venby.io" target="_blank" class="button button-secondary" type="button">Buy License</a>
        <?php endif; ?>
    </div>
    <?php if (!get_option('warehouse-popups-woocommerce-license')): ?>
    <p>Venby's free version can add one additional Warehouse. To add unlimited Warehouses, buy our PRO version. Buy
        the PRO version License <a href="https://venby.io/wordpress-plugin-woocommerce-warehouses-pro/" target="_blank">here</a></p>
    <?php endif; ?>
    <?php if (get_option('warehouse-popups-woocommerce-license')): ?>
        <h4>Status: <span style="color: green;">Activated</span></h4>
    <?php else: ?>
        <h4>Status: <span style="color: red;">Not Activated</span></h4>
    <?php endif; ?>
    <?php if (get_option('warehouse-popups-woocommerce-license')): ?>
        <p>Create Unlimited Warehouses.</p>
    <?php endif; ?>
</div>

<script>
    jQuery(document).on('click', '.whp-license-activate', function(event){
        event.preventDefault();

        jQuery.ajax({
            type: "POST",
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: {
                'action': 'ajax_form_activator',
                'whp-key': jQuery('#whp-license-key').val()
            }
        }).done(function( data ) {
            if (data !== 'valid') {
                alert('Wrong license key!');
            } else {
                alert('Activated. Please reload the page to see an effect.');
            }

            console.log(data);
        });
    });
</script>
