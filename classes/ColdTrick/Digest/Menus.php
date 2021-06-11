<?php

namespace ColdTrick\Digest;

class Menus {
	
	/**
	 * Adds items to the page menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return array
	 */
	public static function registerPageMenuItems(\Elgg\Hook $hook) {
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggUser) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'digest',
				'text' => elgg_echo('digest:page_menu:settings'),
				'href' => "digest/user/{$page_owner->username}",
				'context' => 'settings',
				'section' => 'notifications',
			]);
		}
				
		return $return;
	}
	
	/**
	 * Adds items to the theme sandbox menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:theme_sandbox'
	 *
	 * @return array
	 */
	public static function registerThemeSandboxMenuItems(\Elgg\Hook $hook) {
		if (!elgg_is_active_plugin('developers')) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'digest',
			'text' => elgg_echo('digest:page_menu:theme_preview'),
			'href' => 'digest/test',
		]);
		
		return $return;
	}
	
	/**
	 * Adds a link to the digest settings for the groups
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:groups:my_status',
	 *
	 * @return array
	 */
	public static function registerGroupStatusMenuItems(\Elgg\Hook $hook) {
		if (!digest_group_enabled()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$user = elgg_get_logged_in_user_entity();
		$group = elgg_get_page_owner_entity();
		if (!$user instanceof \ElggUser || !$group instanceof \ElggGroup) {
			return;
		}
		
		if (!$group->isMember($user)) {
			return;
		}
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'digest',
			'text' => elgg_echo('digest:usersettings:groups:title'),
			'href' => "digest/user/{$user->username}",
			'is_trusted' => true,
		]);
		
		return $return;
	}
}
