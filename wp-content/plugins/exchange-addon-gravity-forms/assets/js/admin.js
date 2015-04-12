(function($) {
    $(function() {
        var $exchangeProductSelect = $( 'select.it_gravity_forms_product_select' );

        $exchangeProductSelect.change(function() {
            SetFieldProperty( 'itExchangeProduct', $exchangeProductSelect.val() );

            var $option = $exchangeProductSelect.find( 'option:selected' );

            var basePrice = $option.data( 'price' );

            if ( typeof( basePrice ) !== 'undefined' ) {
                SetBasePrice( basePrice );
            }

            SetFieldLabel( $option.text() );
        });
    });
}(jQuery));