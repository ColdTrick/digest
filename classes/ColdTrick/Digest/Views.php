<?php

namespace ColdTrick\Digest;

class Views {
	
	/**
	 * Change the view vars for the user icon, to prevent the user_hover menu
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'icon/user/default'
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
	 * @param \Elgg\Hook $hook 'view_vars', 'river/elements/body'
	 *
	 * @return array
	 */
	public static function preventRiverResponses(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return['responses'] = ' ';
		return $return;
	}
}
