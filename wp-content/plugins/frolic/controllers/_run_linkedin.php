<?php

//namespace pluginbuddy\frolic;

$link = pb_frolic::plugin_path() . '/classes/linkedin.class.php';
include pb_frolic::plugin_path() . '/classes/linkedin.class.php'; //used for twitter buttons



function run_linkedin( $account_id, $object_id ) {
	
	
	$li = new pb_frolic_linkedin();
	$item = pb_frolic::$options['accounts'][$account_id]['objects'][$object_id];
	$instance = $account_id . $object_id;
	//$twit_return = $twit->twitter_js();
	$li_return = '';
	
	switch( $item['type'] ) {
		
		case 'share':
			$li_return .= $li->share_button( $item['url'], $item['layout'], $instance );
			return $li_return;
			break;
		case 'profile':
			$li_return .= $li->profile( $item['url'], $item['layout'], $item['related'], $item['data_text'], $instance );
			return $li_return;
			break;
		case 'insider':
			$li_return .= $li->company_insider( $item['c_name'], $item['innetwork'], $item['newhires'], $item['jobchanges'], $instance );
			return $li_return;
			break;
		case 'c_profile':
			$li_return .= $li->company_profile( $item['c_name'], $item['layout'], $item['related'], $item['data_text'], $instance );
			return $li_return;
			break;
		case 'recommend':
			$li_return .= $li->recommend( $item['data_company'], $item['data_product'], $item['format'], $instance );
			return $li_return;
			break;
		case 'jobs':
			$li_return .= $li->show_jobs( $item['layout'], $item['data_company'], $instance );
			return $li_return;
			break;
		case 'follow':
			$li_return .= $li->follow( $item['data_company'], $item['layout'], $instance );
			return $li_return;
			break;
		
	}
	

	
}

?>