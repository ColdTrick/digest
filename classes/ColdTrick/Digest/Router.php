<?php

namespace ColdTrick\Digest;

class Router {
	
	/**
	 * The digest page handler
	 *
	 * @param array $page the page elements
	 *
	 * @return bool
	 */
	public static function digest($page) {
		
		$vars = [];
		
		switch ($page[0]) {
			case 'test':
				echo elgg_view_resource('digest/test');
				return true;
			case 'show':
				echo elgg_view_resource('digest/show');
				return true;
			case 'unsubscribe':
				echo elgg_view_resource('digest/unsubscribe');
				return true;
			case 'user':
			default:
				$vars['username'] = elgg_extract(1, $page);
				
				echo elgg_view_resource('digest/usersettings', $vars);
				return true;
		}
		
		return false;
	}
}
