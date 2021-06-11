<?php

namespace ColdTrick\Digest;

class Site {
	
	/**
	 * Allow users to directly unsubscribe even in walled garden
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function extendWalledGardenPages(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return[] = 'digest/unsubscribe';
	
		return $return;
	}
}
