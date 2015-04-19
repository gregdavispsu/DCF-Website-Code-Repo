<?php $search_box_DCF = __( 'Search site', 'it-l10n-BuilderChild-DCF-Custom' ); ?>
 
<?php $search_box_value = esc_attr( apply_filters( 'the_search_query', get_search_query() ) ); ?>
 
<?php $search_box_value = ( empty( $search_box_value ) ) ? $search_box_DCF : $search_box_value; ?>
 
<form role="search" method="get" id="searchform" action="<?php echo get_option( 'home' ); ?>/">
    <div>
        <input type="text" value="<?php echo $search_box_value; ?>" name="s" class="search-text-box" onfocus="if(this.value == '<?php echo $search_box_DCF; ?>') this.value = '';" onblur="if(this.value == '') this.value = '<?php echo $search_box_DCF; ?>';" />
        <input type="submit" id="searchsubmit" value="<?php echo esc_attr__( 'Search', 'it-l10n-BuilderChild-DCF-Custom' ); ?>" />
    </div>
</form>
