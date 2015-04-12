<?php
//namespace pluginbuddy\frolic;
/*************************************************************************************************************************************************************************
																				google.class.php
																	http://developers.google.com/+/plugins/+1button/
																	
																Class allows easy use of Google+ API

Functions:
	googleplus()														--class constructor
	gscript()															--loads the javascript needed to for the google+ objects
	plusone()															--easy to add plus one button to website.
	badge($plusPageUrl)													--adds a g+ badge to site, url should correspond to google plus page
****************************************************************************************************************************************************************************/
if ( ! class_exists( 'pb_frolic_googleplus' ) ) 
{
	class pb_frolic_googleplus
	{

	/***********************Properties********************************/
		public $var = '';
		public $var1 = '';
	/***********************End Properties*****************************/




	/***********************Functions**********************************/

	/* pb_frolic_googleplus()
	 *
	 * class constructor
	 *
	 * @return nul
	 */
	public function pb_frolic_googleplus() {
		$var = 'hello';
	}

	/* gscript()
	 *
	 * prints out the script needed for google plus plugins
	 *
	 * @return null
	 */
	public function gscript() {
		return '<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>';
	}

	/* plusone()
	 *
	 * adds a plusone button to page 
	 *
	 * @return null
	 */
	public function plusone($size, $instance, $an) {
		if($an != ''){
			$annotation = 'annotation="' . $an . '"';
		} else {
			$annotation = '';
		}
		return '<div class="pb-frolic pb-frolic-google pb-frolic-plus-one pb-frolic-object-' . $instance . '"><g:plusone ' . $annotation . ' size="' . $size . '"></g:plusone></div>';
	}

	/* badge()
	 *
	 * adds a google+ badge to given site
	 *
	 * $plusPageUrl		int			--number that you get from google+ when you create a page
	 *
	 * @return null
	 */
	public function badge($plusPageUrl, $instance, $theme, $height, $width) { //width is constant, height will be either 131 or 69
				
		return '<div class="pb-frolic pb-frolic-google pb-frolic-badge pb-frolic-object-' . $instance . '"><g:plus theme="' . $theme . '" width="' . $width. '" height="' . $height . '" href="https://plus.google.com/' . $plusPageUrl . '"></g:plus></div>';
	}
	
	/* share()
	 * 
	 * adds a google+ share button to site
	 * 
	 * size			int		--height of button
	 * annotation	str		--where to show the bubble for number of shares
	 * 
	 * @return null
	 */
	public function share($size, $annotation, $instance) {
		if($size == 'medium'){
			$height = '';
		} else {
			$height = 'height="' . $size . '"';
		}
		
		if($annotation = 'inline'){
			return '<div class="pb-frolic pb-frolic-google pb-frolic-gplus-share pb-frolic-object-' . $instance . '"><g:plus action="share" ' . $height . '></g:plus></div>';
		} else {
			return '<div class="pb-frolic pb-frolic-google pb-frolic-gplus-share pb-frolic-object-' . $instance . '"><g:plus action="share" annotation="' . $annotation . '" ' . $height . '></g:plus></div>';
		}
	}
	/*************************End Functions*******************************/



	/*************************Aux****************************************/
	/***********************End Aux**************************************/

	}
}
?>
