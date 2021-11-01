( function( $ ) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $( function() {
        $( '.woocommerce-checkout' ).on( 'change', '#shipping_method input[name="wh-popups-warehouse-id"]', function() {
            $( this ).closest( '#custom_checkout_field' ).siblings( 'input[name^="shipping_method"]' ).prop( 'checked', true );
        } );
        $( '.woocommerce-checkout' ).on( 'change', 'input[id*="local_pickup"]', function() {
            console.log( 'change' );
            if ( $( this ).prop( 'checked' ) == false ) {
                $( this ).children( 'input[type="radio"]' ).prop( 'checked', false );
            }
        } );
        if ( $( "#wh-popups-change-wh-select" ).length > 0 ) {

            $( ".wh-popups-wh-switch" ).delegate( '#wh-popups-change-wh-select', "change", function() {
                $( this ).closest( 'form' ).submit();
            } );

            // $("#wh-popups-change-wh-select").change(function(){
            //     console.log('switch changed');
            //     $(this).closest('form').submit();
            // });
        }

        if ( $( ".wh_flybox_popup" ).length > 0 ) {
            console.log( 'flybox exists' );
            $( ".wh_popups_flybox_switch" ).click( function() {
                $( ".wh_flybox_popup" ).fadeIn( 300 );
                return false;
            } );

            $( ".wh_flybox_close_btn_container" ).click( function() {
                $( ".wh_flybox_popup" ).fadeOut( 200 );
                return false;
            } );

            $( '.wh_flybox_popup_content form input[type=radio][name=wh_popups_change_wh_to]' ).change( function() {
                $( this ).closest( 'form' ).submit();
                return false;
            } );
        }
    } );
} )( jQuery );