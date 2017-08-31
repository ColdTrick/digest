<?php

namespace ColdTrick\Digest;

class Menus {
	
	/**
	 * Adds items to the page menu
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function registerPageMenuItems($hook, $type, $return_value, $params) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggUser) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'digest',
				'text' => elgg_echo('digest:page_menu:settings'),
				'href' => "digest/user/{$page_owner->username}",
				'context' => 'settings',
				'section' => 'notifications',
			]);
		}
		
		if (elgg_is_admin_logged_in() && elgg_in_context('admin')) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'statistics:digest',
				'text' => elgg_echo('admin:statistics:digest'),
				'href' => 'admin/statistics/digest',
				'section' => 'administer',
				'parent_name' => 'statistics',
			]);
		}
				
		return $return_value;
	}
	
	/**
	 * Adds items to the theme sandbox menu
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function registerThemeSandboxMenuItems($hook, $type, $return_value, $params) {
		
		if (!elgg_is_active_plugin('developers')) {
			return;
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'digest',
			'text' => elgg_echo('digest:page_menu:theme_preview'),
			'href' => 'digest/test',
		]);
		
		return $return_value;
	}
	
	/**
	 * Adds a link to the digest settings for the groups
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function registerGroupStatusMenuItems($hook, $type, $return_value, $params) {
		
		if (!digest_group_enabled()) {
			return;
		}
		
		$user = elgg_get_logged_in_user_entity();
		$group = elgg_get_page_owner_entity();
		if (!($user instanceof \ElggUser) || !($group instanceof \ElggGroup)) {
			return;
		}
		
		if (!$group->isMember($user)) {
			return;
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'digest',
			'text' => elgg_echo('digest:usersettings:groups:title'),
			'href' => "digest/user/{$user->username}",
			'is_trusted' => true,
		]);
		
		return $return_value;
	}
}
