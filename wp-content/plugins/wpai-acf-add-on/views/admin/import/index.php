<tr>
	<td colspan="3" style="padding-top:20px;">
		<fieldset class="optionsset pmai_options">
			<legend><?php _e('Advanced Custom Fields','pmxi_plugin');?></legend>
			<?php 

			$acfs = get_posts(array('posts_per_page' => -1, 'post_type' => 'acf'));

			if ( ! empty($acfs) ){
				?>
				<p><strong><?php _e("Please choose your Field Groups.","pmxi_plugin");?></strong></p>				
				<ul>
					<?php 
					foreach ($acfs as $key => $acf) {
						?>
						<li>
							<input type="hidden" name="acf[<?php echo $acf->ID;?>]" value="0"/>							
							<input id="acf_<?php echo $post_type . '_' . $acf->ID;?>" type="checkbox" name="acf[<?php echo $acf->ID;?>]" <?php if ( ! empty($post['acf'][$acf->ID]) ): ?>checked="checked"<?php endif; ?> value="1" rel="<?php echo $acf->ID;?>" class="pmai_acf_group"/>
							<label for="acf_<?php echo $post_type . '_' . $acf->ID; ?>"><?php echo $acf->post_title; ?></label>
						</li>
						<?php
					}
					?>
				</ul>
				<div class="acf_groups"></div>								
				<?php
			}
			else{
				?>
				<p><strong><?php _e("Please create Field Groups.","pmxi_plugin");?></strong></p>				
				<?php	
			}			
			?>
		</fieldset>		
	</td>
</tr>