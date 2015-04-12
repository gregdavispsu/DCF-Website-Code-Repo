<?php

function pmai_is_acf_update_allowed( $cur_meta_key, $options ){

	if ($options['update_acf_logic'] == 'full_update') return true;
	
	// Update only these ACF, leave the rest alone
	if ($options['update_all_data'] == 'no' and $options['is_update_acf'] and $options['update_acf_logic'] == 'only'){
		
		if (! empty($options['acf_list']) and is_array($options['acf_list'])){
			foreach ($options['acf_list'] as $key => $acf_field) {
				$field_parts = explode('---', $acf_field);
				$field_name = trim(array_shift(explode(" ", $field_parts[0])), "[]");				
				if (!empty($field_parts[1])){
					$sub_field_name = trim($field_parts[1], "[]");
					if (preg_match('%^_{0,1}'.$field_name.'_[0-9]{1,}_'.$sub_field_name.'$%', $cur_meta_key)){
						return true;
						break;
					}
				}
				elseif ( preg_match('%^_{0,1}'.$field_name.'$%', $cur_meta_key) ){
					return true;
					break;
				}				
			}
			return false;
		}					

	}

	// Leave these ACF alone, update all other ACF
	if ($options['update_all_data'] == 'no' and $options['is_update_acf'] and $options['update_acf_logic'] == 'all_except'){
		
		if (! empty($options['acf_list']) and is_array($options['acf_list'])){
			foreach ($options['acf_list'] as $key => $acf_field) {
				$field_parts = explode('---', $acf_field);
				$field_name = trim(array_shift(explode(" ", $field_parts[0])), "[]");
				if (!empty($field_parts[1])){
					$sub_field_name = trim($field_parts[1], "[]");
					if (preg_match('%^_{0,1}'.$field_name.'_[0-9]{1,}_'.$sub_field_name.'$%', $cur_meta_key)){
						return false;
						break;
					}
				}
				elseif ( preg_match('%^_{0,1}'.$field_name.'$%', $cur_meta_key) ){
					return false;
					break;
				}
			}
		}		
	}

	// Update only mapped ACF fields
	if ($options['update_all_data'] == 'no' and $options['is_update_acf'] and $options['update_acf_logic'] == 'mapped'){
			
		$mapped_acf = $options['acf'];

		if ( ! empty($mapped_acf)){			
			foreach ($mapped_acf as $acf_group_id => $is_mapped) {				
				if ( ! $is_mapped ) continue;
				$acf_fields = get_post_meta($acf_group_id, '');
				if (!empty($acf_fields)){
					foreach ($acf_fields as $meta_key => $cur_meta_val){
						
						if (strpos($meta_key, 'field_') !== 0) continue;
						
						$field = (!empty($cur_meta_val[0])) ? unserialize($cur_meta_val[0]) : array();

						if ( preg_match('%^_{0,1}'.$field['name'].'$%', $cur_meta_key) ){
							return true;
							break;
						}

						if (!empty($field['sub_fields'])){
							foreach ($field['sub_fields'] as $sub_field) {
								if (preg_match('%^_{0,1}'.$field['name'].'_[0-9]{1,}_'.$sub_field['name'].'$%', $cur_meta_key)){
									return true;
									break;
								}
							}
						}
					}
				}
			}			
		}

		return false;		
	}

	return true;		
}

?>