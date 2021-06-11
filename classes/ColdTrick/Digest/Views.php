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
	public static function preventUserHoverMenu(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return['use_hover'] = false;
		return $return;
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
	public static function preventRiverResponses(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return['responses'] = ' ';
		return $return;
	}
}
