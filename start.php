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
		
	// register page handler for nice url's
	elgg_register_page_handler('digest', '\ColdTrick\Digest\Router::digest');
	
	// extend register with subscribe option
	elgg_extend_view('register/extend', 'digest/register');
	
	// extend groups edit screen
	elgg_extend_view('groups/edit', 'digest/groupsettings', 400);
	
	// digest elements
	elgg_extend_view('css/digest/core', 'css/digest/river');
	elgg_extend_view('digest/elements/site', 'digest/elements/site/river');
	elgg_extend_view('digest/elements/group', 'digest/elements/group/river');

	if (elgg_is_active_plugin('blog')) {
		elgg_extend_view('css/digest/core', 'css/digest/blog');
		elgg_extend_view('digest/elements/site', 'digest/elements/site/blog');
	}

	if (elgg_is_active_plugin('groups')) {
		elgg_extend_view('css/digest/core', 'css/digest/groups');
		elgg_extend_view('digest/elements/site', 'digest/elements/site/groups');
	}

	if (elgg_is_active_plugin('profile')) {
		elgg_extend_view('css/digest/core', 'css/digest/profile');
		elgg_extend_view('digest/elements/site', 'digest/elements/site/profile');
	}
	
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
	
	elgg_register_action('digest/usersettings', dirname(__FILE__) . '/actions/usersettings.php');
	elgg_register_action('digest/groupsettings', dirname(__FILE__) . '/actions/groupsettings.php');
}
