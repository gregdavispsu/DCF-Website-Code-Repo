<?php
//namespace pluginbuddy\frolic;

$link = pb_frolic::plugin_path() . '/classes/google.class.php';
include pb_frolic::plugin_path() . '/classes/google.class.php'; 

function run_googleplus( $account_id, $object_id ) {
	$gp = new pb_frolic_googleplus();
	$item = pb_frolic::$options['accounts'][$account_id]['objects'][$object_id];
	$instance = $account_id . $object_id;
	$return = '';
	//$return = $gp->gscript();
	
	switch( $item['type'] ) {
		case 'plusone_button':
			$return .= $gp->plusone( $item['size'], $instance, $item['annotation'] );
			return $return;
			break;
		case 'badge':
			$return .= $gp->badge( $item['g_url'], $instance, $item['theme'], $item['height'], $item['width'] );
			return $return;
			break;
		case 'share_button':
			$return .= $gp->share( $item['height'], $item['annotation'], $instance );
			return $return;
			break;
	}
}

?>