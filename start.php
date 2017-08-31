<?php
/**
 * the main plugin file
 */

define('DIGEST_INTERVAL_NONE', 'none');
define('DIGEST_INTERVAL_DEFAULT', 'default');
define('DIGEST_INTERVAL_DAILY', 'daily');
define('DIGEST_INTERVAL_WEEKLY', 'weekly');
define('DIGEST_INTERVAL_FORTNIGHTLY', 'fortnightly');
define('DIGEST_INTERVAL_MONTHLY', 'monthly');

require_once(dirname(__FILE__) . '/lib/functions.php');

// register elgg events
elgg_register_event_handler('init', 'system', 'digest_init');

/**
 * gets called when the system initializes
 *
 * @return void
 */
function digest_init() {
	// extend css
	elgg_extend_view('css/elgg', 'css/digest/site');
	elgg_extend_view('css/admin', 'css/digest/admin');
	
	// register page handler for nice url's
	elgg_register_page_handler('digest', 'digest_page_handler');
	
	// extend register with subscribe option
	elgg_extend_view('register/extend', 'digest/register');
	
	// extend groups edit screen
	elgg_extend_view('groups/edit', 'digest/groupsettings/form', 400);
	
	// register plugin hooks
	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\Digest\Menus::registerPageMenuItems');
	elgg_register_plugin_hook_handler('register', 'menu:theme_sandbox', '\ColdTrick\Digest\Menus::registerThemeSandboxMenuItems');
	elgg_register_plugin_hook_handler('register', 'menu:groups:my_status', '\ColdTrick\Digest\Menus::registerGroupStatusMenuItems');
	
	elgg_register_plugin_hook_handler('register', 'user', '\ColdTrick\Digest\User::savePreferenceOnRegister');
	
	elgg_register_plugin_hook_handler('cron', 'daily', '\ColdTrick\Digest\Cron::sendDigests');
	
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', '\ColdTrick\Digest\Site::extendWalledGardenPages');
		
	// register events
	elgg_register_event_handler('leave', 'group', '\ColdTrick\Digest\Groups::removeDigestSettingOnLeave');
	
	// register actions
	elgg_register_action('digest/settings/save', dirname(__FILE__) . '/actions/settings/save.php', 'admin');
	elgg_register_action('digest/reset_stats', dirname(__FILE__) . '/actions/reset_stats.php', 'admin');
	
	elgg_register_action('digest/update/usersettings', dirname(__FILE__) . '/actions/update/usersettings.php');
	elgg_register_action('digest/update/groupsettings', dirname(__FILE__) . '/actions/update/groupsettings.php');
}

/**
 * The digest page handler
 *
 * @param array $page the page elements
 *
 * @return bool
 */
function digest_page_handler($page) {

	switch ($page[0]) {
		case 'test':
			echo elgg_view_resource('digest/test');
			return true;
		case 'show':
			echo elgg_view_resource('digest/show');
			return true;
		case 'unsubscribe':
			include(dirname(dirname(__FILE__)) . '/procedures/unsubscribe.php');
			return true;
		case 'user':
		default:
			if (!empty($page[1])) {
				set_input('username', $page[1]);
			}
			
			echo elgg_view_resource('digest/usersettings');
			return true;
	}
}
