<?php
/*************************************************************************************************************************************************************************
																				linkedin.class.php
																	https://developer.linkedin.com/plugins
																	
																Class allows easy use of LinkedIn API

Functions:
	linkedin()														--class constructor
	
****************************************************************************************************************************************************************************/
if ( ! class_exists( 'pb_frolic_linkedin' ) ) 
{
	class pb_frolic_linkedin
	{

	/***********************Properties********************************/
		public $var = '';
		public $var1 = '';
	/***********************End Properties*****************************/
	
		/* pb_frolic_linkedin()
		*
		* class constructor
		*
		* @return nul
		*/
		public function pb_frolic_linkedin() {
			$var = 'hello';
		}
		
		/* linkedin_script()
		*
		* needed in order for linkedin objects to run properly.
		*
		* @return nul
		*/
		public function linkedin_script() {
			return '<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>';
		}
		
		/* share_button()
		*
		* class constructor
		* 
		* $url		string			--specify the target url to share
		* 
		* $layout	string			--specify where you want the bubble to pop up. will take 'right', 'top', or '' for blank/none.
		*
		* @return nul
		*/
		public function share_button( $url, $layout, $instance ) {
			return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-share-button pb-frolic-object-' . $instance . '"><script type="IN/Share" data-url="' . $url . '" data-counter="' . $layout . '"></script></div>';
		}
		
		/* profile()
		*
		* class constructor
		* 
		* $data_id		string			--usually a profile url
		* $format		string			--inline, hover, click
		* $related		string			--true or false - show related profiles below your own
		* $text			string			--for use with hover or click format, put your name next to the icon.
		*
		* @return nul
		*/
		public function profile( $data_id, $format, $related, $text, $instance) {
			$data_text = 'data-text="' . $text . '"';
			if($format == 'inline') { 
				return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-linkedin-profile pb-frolic-object-' . $instance . '"><script type="IN/MemberProfile" data-id="' . $data_id . '" data-format="' . $format . '" data-related="' . $related . '"></script></div>';
			}else{ 
				return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-linkedin-profile pb-frolic-object-' . $instance . '"><script type="IN/MemberProfile" data-id="' . $data_id . '" data-format="' . $format . '"' . $data_text . ' data-related="' . $related . '"></script></div>';
			}
		}
		
		/* company_insider()
		*
		* class constructor
		* 
		* $data_id		string			--usually a profile url
		* $format		string			--inline, hover, click
		* $related		string			--true or false - show related profiles below your own
		* $text			string			--for use with hover or click format, put your name next to the icon.
		*
		* @return nul
		*/
		public function company_insider( $id, $innetwork, $newhires, $jobchanges, $instance ) {
			$data_modules = '';
			if( $innetwork == 'show' ){
				$data_modules .= 'innetwork';
			}
			
			if( $newhires == 'show' && $data_modules != '' ){
				$data_modules .= ',newhires';
			} elseif( $newhires == 'show' ) { 							//we need to check to see if $data_modules is empty, then plan accordingly
				$data_modules .= 'newhires'; 
			}
			
			if( $jobchanges == 'show' && $data_modules != '' ){
				$data_modules .= ',jobchanges';
			} elseif( $jobchanges == 'show' ) { 
				$data_modules .= 'jobchanges'; 
			}
			
			return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-insider pb-frolic-object-' . $instance . '"><script type="IN/CompanyInsider" data-id="' . $id . '" data-modules="' . $data_modules . '"></script></div>';
		}
	
	
		/* company_profile()
		*
		* class constructor
		* 
		* $data_id		string			--usually a profile url
		* $format		string			--inline, hover, click
		* $related		string			--true or false - show related profiles below your own
		* $text			string			--for use with hover or click format, put your name next to the icon.
		*
		* @return nul
		*/
		public function company_profile( $data_id, $format, $data_text, $connections, $instance ){  //TODO: finish setting this up.
			$related = '';
			$data_text = '';
			if($data_text != ''){$data_text = 'data-text="' . $text . '"';}
			if($connections != '' && $connections != 'false'){$related = 'data-related="' . $connections . '"';}
			
			if($format == 'inline') { 
				return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-company-profile pb-frolic-object-' . $instance . '"><script type="IN/CompanyProfile" data-id="' . $data_id . '" data-format="' . $format . '" ' . $related . ' ' . $data_text . '></script></div>';
			}else{ 
				return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-company-profile pb-frolic-object-' . $instance . '"><script type="IN/CompanyProfile" data-id="' . $data_id . '" data-format="' . $format . '" ' . $related . ' ' . $data_text . '></script></div>';
			}
		}
	
	
		/* recommend()
		*
		* class constructor
		* 
		* $data_id		string			--usually a profile url
		* $format		string			--inline, hover, click
		* $related		string			--true or false - show related profiles below your own
		* $text			string			--for use with hover or click format, put your name next to the icon.
		*
		* @return nul
		*/
		public function recommend( $data_company, $data_product, $data_counter, $instance ){
			return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-recommend pb-frolic-object-' . $instance . '"><script type="IN/RecommendProduct" data-company="' . $data_company . '" data-product="' . $data_product . '" data-counter="' . $data_counter . '"></script></div>';
		}
	
		/* show_jobs()
		*
		* class constructor
		* 
		* $data_id		string			--usually a profile url
		* $format		string			--inline, hover, click
		* $related		string			--true or false - show related profiles below your own
		* $text			string			--for use with hover or click format, put your name next to the icon.
		*
		* @return nul
		*/
		public function show_jobs( $format, $company_id, $instance ) {
			if( $format == 'yours' ) {
				return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-jobs pb-frolic-object-' . $instance . '"><script type="IN/JYMBII" data-companyid="' . $company_id . '" data-format="inline"></script></div>';
			} elseif ( $format == 'all' ) {
				return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-jobs pb-frolic-object-' . $instance . '"><script type="IN/JYMBII" data-format="inline"></script></div>';
			}
		}
		
		/* follow()
		*
		* class constructor
		* 
		* $data_id		string			--usually a profile url
		* $format		string			--inline, hover, click
		* $related		string			--true or false - show related profiles below your own
		* $text			string			--for use with hover or click format, put your name next to the icon.
		*
		* @return nul
		*/
		public function follow( $id, $format, $instance ){
			return '<div class="pb-frolic pb-frolic-linkedin pb-frolic-follow pb-frolic-object-' . $instance . '"><script type="IN/FollowCompany" data-id="' . $id . '" data-counter="' . $format . '"></script></div>';
		}
	
	
	}
}
?>
