<?php
//namespace pluginbuddy\frolic;


/*	Extends \Widget_Widget()
 *	
 *	Each widget must extend the \WP_Widget class (\ is for namespace).
 */
class pb_frolic_widget_frolic extends WP_Widget {
	
	
	function __construct() {
		parent::WP_Widget(
							/* SLUG */				'frolic',
							/* TITLE0 */			'Frolic',
							/* DESCRIPTION */		array( 'description' => 'Displays a Frolic object in a widget.' )
						);
	}
	
	
	
	/**
	 *	\WP_Widget::widget() Override
	 *
	 *	Function is called when a widget is to be displayed. Use echo to display to page.
	 *
	 *	@param		$args		array		?
	 *	@param		$instance	array		Associative array containing the options saved on the widget form.
	 *	@return		null
	 */
	function widget( $args, $instance ) {
		extract($args);
		$title = $instance['title'];
		$title = apply_filters( 'widget_title', $title );
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		
		
		if( $instance['object'] != '' ){
			$account_object = explode(',', $instance['object']); // named so because the first item is the account and the second the object.
			$item = pb_frolic::$options['accounts'][$account_object[0]]['type'];
			if( pb_frolic::$options['accounts'][$account_object[0]]['enabled'] == 'true' ) {
				switch ( $item ) {
					case 'facebook':
						require_once(pb_frolic::plugin_path() . '/js/facebook.javascript.php');
						pb_frolic::load_controller( '_run_facebook' );
						echo run_facebook( $account_object[0], $account_object[1] );
						break;
					case 'twitter':
						require_once(pb_frolic::plugin_path() . '/js/twitter.javascript.php');
						pb_frolic::load_controller( '_run_twitter' );
						echo run_twitter( $account_object[0], $account_object[1] );
						break;
					case 'google':
						require_once(pb_frolic::plugin_path() . '/js/gplus.javascript.php');
						pb_frolic::load_controller( '_run_googleplus' );
						echo run_googleplus( $account_object[0], $account_object[1] );
						break;
					case 'linkedin':
						require_once(pb_frolic::plugin_path() . '/js/linkedin.javascript.php');
						pb_frolic::load_controller( '_run_linkedin' );
						echo run_linkedin( $account_object[0], $account_object[1] );
						break;
				}
			}
		}
		echo $after_widget;
	}
	
	
	/**
	 *	\WP_Widget::form() Override
	 *
	 *	Displays the widget form on the widget selection page for setting widget settings.
	 *	Widget defaults are pre-merged into $instance right before this function is called.
	 *	Use $widget->get_field_id() and $widget->get_field_name() to get field IDs and names for form elements.
	 *	Anything to display should be echo'd out.
	 *	@see WP_Widget class
	 *
	 *	@param		$instance	array		Associative array containing the options set previously in this form and/or the widget defaults (merged already).
	 *	@return		null
	 */
	
	function form( $instance ) {
		/*echo '<pre>';
		print_r($instance);  //uncomment to check what $account_object is going to be equal to up above. $instance[object]
		echo '</pre>';*/
		$instance = array_merge( (array)pb_frolic::settings( 'widget_defaults' ), (array)$instance ); //merge array with current $instance
		
		if( empty( pb_frolic::$options['accounts'] ) ) {
			echo 'You must create a frolic object to place in this widget. Please do so within the plugin\'s page.';
		} else {
			if(isset($instance['title'])) {
				$title = $instance['title'];
			} else {
				$title = 'New Widget';
			}
			?>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				Title:
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				
			
			
			<br><br>
			
			<label for="<?php echo $this->get_field_id('object'); ?>">
				Frolic Object:
				<select class="widefat" id="<?php echo $this->get_field_id('object'); ?>" name="<?php echo $this->get_field_name('object'); ?>">
				<?php
					foreach( (array)pb_frolic::$options['accounts'] as $id => $account ) {
						echo '<optgroup label="' . stripslashes( $account['title'] ) . '(' . stripslashes( $account['type'] ) . ')' . '">'; //http://www.w3schools.com/tags/tag_optgroup.asp
						foreach ( (array)pb_frolic::$options['accounts'][$id]['objects'] as $obj_id => $object ){
							$new_id = $id . ',' . $obj_id;
							if( ( $instance['object'] == $new_id )  ) {
								$select = 'selected';
							} else { 
								$select = '';
							}
							echo '<option value="' . $id . ',' . $obj_id . '"' . $select . '>' . stripslashes( $object['name'] ) . '</option>';
						}
						echo '</optgroup>';
					}
					?>
					</select>
				</label>
				<br><br>
				
				
				<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
				<?php
			}
		}
	
	
} // End extending WP_Widget class.
?>
