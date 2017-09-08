<?php

namespace ColdTrick\Digest;

class Views {
	
	/**
	 * Change the view vars for the user icon, to prevent the user_hover menu
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function preventUserHoverMenu($hook, $type, $return_value, $params) {
		
		$return_value['use_hover'] = false;
		
		return $return_value;
	}
	
	/**
	 * Change the view vars for the river body, to prevent responses
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function preventRiverResponses($hook, $type, $return_value, $params) {
		
		$return_value['responses'] = ' ';
		
		return $return_value;
	}
}
