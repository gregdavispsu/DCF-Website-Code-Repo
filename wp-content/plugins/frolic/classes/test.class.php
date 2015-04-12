<?php
	
class test
  {
    public static function run($param) 
	{
		if($param != '')
		{
			return 'the paramater is: ' . $param . '.';
		}
	}
	public function does_stuff() {
		$return = '';
		$return .= '<div id="this_div">';
		$return .= '<p>This is come random content.</p>';
		$return .= '</div>';
		return $return;
	}
		
  }



?>
