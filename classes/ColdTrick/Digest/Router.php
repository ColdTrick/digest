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
				
				$vars['digest'] = elgg_extract(1, $page, 'site');
				$vars['interval'] = elgg_extract(2, $page, DIGEST_INTERVAL_MONTHLY);
				$vars['group_guid'] = (int) elgg_extract(3, $page);
				
				echo elgg_view_resource('digest/test', $vars);
				return true;
			case 'show':
				echo elgg_view_resource('digest/show');
				return true;
			case 'unsubscribe':
				echo elgg_view_resource('digest/unsubscribe');
				return true;
			case 'user':
				$vars['username'] = elgg_extract(1, $page);
				
				echo elgg_view_resource('digest/usersettings', $vars);
				return true;
		}
		
		return false;
	}
}
