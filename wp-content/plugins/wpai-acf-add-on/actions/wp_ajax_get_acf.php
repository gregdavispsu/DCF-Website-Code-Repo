<?php

function pmai_wp_ajax_get_acf(){

	extract($_POST);

	ob_start();

	$acf_obj = get_post($acf);
	
	PMXI_Plugin::$session = PMXI_Session::get_instance();	
		
	?>
	<div class="postbox  acf_postbox default acf_signle_group" rel="<?php echo $acf_obj->ID; ?>">
		<h3 class="hndle"><span><?php echo $acf_obj->post_title; ?></span></h3>
		<div class="inside">
		<?php
		foreach (get_post_meta($acf_obj->ID, '') as $cur_meta_key => $cur_meta_val)
		{	
			if (strpos($cur_meta_key, 'field_') !== 0) continue;

			$field = (!empty($cur_meta_val[0])) ? unserialize($cur_meta_val[0]) : array();			
			
			echo pmai_render_field($field, (!empty(PMXI_Plugin::$session->data['pmxi_import']['options'])) ? PMXI_Plugin::$session->data['pmxi_import']['options'] : array() );
		}
		?>								
		</div>
	</div>
	<?php			

	exit(json_encode(array('html' => ob_get_clean()))); die;

}

?>