<?php
/**
 * the main plugin file
 */

define("DIGEST_INTERVAL_NONE", "none");
define("DIGEST_INTERVAL_DEFAULT", "default");
define("DIGEST_INTERVAL_DAILY", "daily");
define("DIGEST_INTERVAL_WEEKLY", "weekly");
define("DIGEST_INTERVAL_FORTNIGHTLY", "fortnightly");
define("DIGEST_INTERVAL_MONTHLY", "monthly");

require_once(dirname(__FILE__) . "/lib/events.php");
require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/hooks.php");
require_once(dirname(__FILE__) . "/lib/page_handlers.php");

// register elgg events
elgg_register_event_handler("init", "system", "digest_init");
elgg_register_event_handler("pagesetup", "system", "digest_pagesetup");

/**
 * gets called when the system initializes
 *
 * @return void
 */
function digest_init() {
	// extend css
	elgg_extend_view("css/elgg", "css/digest/site");
	elgg_extend_view("css/admin", "css/digest/admin");
	
	// register page handler for nice url's
	elgg_register_page_handler("digest", "digest_page_handler");
	
	// extend register with subscribe option
	elgg_extend_view("register/extend", "digest/register");
	
	// extend groups edit screen
	elgg_extend_view("groups/edit", "digest/groupsettings/form", 400);
	
	// register plugin hooks
	elgg_register_plugin_hook_handler("register", "user", "digest_register_user_hook");
	
	elgg_register_plugin_hook_handler("cron", "daily", "digest_cron_handler");
	
	elgg_register_plugin_hook_handler("public_pages", "walled_garden", "digest_walled_garden_hook");
	
	elgg_register_plugin_hook_handler("register", "menu:groups:my_status", "digest_menu_groups_my_status_hook");
	
	// register events
	elgg_register_event_handler("leave", "group", "digest_group_leave_event");
	
	// register actions
	elgg_register_action("digest/settings/save", dirname(__FILE__) . "/actions/settings/save.php", "admin");
	elgg_register_action("digest/reset_stats", dirname(__FILE__) . "/actions/reset_stats.php", "admin");
	
	elgg_register_action("digest/update/usersettings", dirname(__FILE__) . "/actions/update/usersettings.php");
	elgg_register_action("digest/update/groupsettings", dirname(__FILE__) . "/actions/update/groupsettings.php");
	
}

/**
 * gets called just before the first output is generated
 *
 * @return void
 */
function digest_pagesetup() {
	
	if (elgg_is_logged_in()) {
		$page_owner = elgg_get_page_owner_entity();
		if (elgg_instanceof($page_owner, "user")) {
			elgg_register_menu_item("page", array(
				"name" => "digest",
				"text" => elgg_echo("digest:page_menu:settings"),
				"href" => "digest/user/" . $page_owner->username,
				"context" => "settings"
			));
		}
		
		elgg_register_menu_item("page", array(
			"name" => "digest",
			"text" => elgg_echo("digest:page_menu:theme_preview"),
			"href" => "digest/test",
			"context" => "theme_preview"
		));
		
		elgg_register_admin_menu_item("administer", "digest", "statistics");
	}
}
	