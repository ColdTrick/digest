<?php 

	define("DIGEST_INTERVAL_NONE", "none");
	define("DIGEST_INTERVAL_DEFAULT", "default");
	define("DIGEST_INTERVAL_DAILY", "daily");
	define("DIGEST_INTERVAL_WEEKLY", "weekly");
	define("DIGEST_INTERVAL_FORTNIGHTLY", "fortnightly");
	define("DIGEST_INTERVAL_MONTHLY", "monthly");
	
	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");
	
	function digest_init(){
		// extend css
		elgg_extend_view("css/elgg", "digest/css/site");
		
		// extend the Digest message CSS
		digest_message_css();
		
		// register page handler for nice url's
		elgg_register_page_handler("digest", "digest_page_handler");
		
		// extend register with subscribe option
		if(($setting = digest_get_default_site_interval()) && ($setting != DIGEST_INTERVAL_NONE)){
			elgg_extend_view("register/extend", "digest/register");
			
			elgg_register_plugin_hook_handler("register", "user", "digest_register_user_hook");
		}
	}
	
	function digest_pagesetup(){
		
		if($user = elgg_get_logged_in_user_entity()){
			$context = elgg_get_context();
			
			// extend groups edit screen
			if(($context == "groups") && digest_group_enabled()){
				elgg_extend_view("groups/edit", "digest/groupsettings/form", 400);
			}
			
			if((elgg_get_page_owner_entity() instanceof ElggGroup) && ($context == "group_profile")){
				elgg_extend_view("page/elements/owner_block/extend", "digest/usersettings/group");
			}
			
			elgg_register_menu_item("page", array(
				"name" => "digest",
				"text" => elgg_echo("digest:page_menu:settings"),
				"href" => "digest/user/" . $user->username,
				"context" => "settings"
			));
			
			elgg_register_menu_item('page', array(
				"name" => "digest",
				"text" => elgg_echo("digest:page_menu:theme_preview"),
				"href" => "digest/test",
				"context" => "theme_preview"
			));
			
			elgg_register_admin_menu_item("administer", "digest", "statistics");
		}
	}
	
	function digest_page_handler($page){
		
		switch($page[0]){
			case "test":
				include(dirname(__FILE__) . "/pages/test.php");
				break;
			case "show":
				include(dirname(__FILE__) . "/pages/show.php");
				break;
			case "unsubscribe":
				include(dirname(__FILE__) . "/procedures/unsubscribe.php");
				break;
			case "user":
			default:
				if(!empty($page[1])){
					set_input("username", $page[1]);
				}
				include(dirname(__FILE__) . "/pages/usersettings.php");
				break;
		}
		
		return true;
	}
	
	function digest_message_css(){
		
		elgg_extend_view("css/digest/core", "css/digest/river");
		elgg_extend_view("digest/elements/site", "digest/elements/site/river");
		elgg_extend_view("digest/elements/group", "digest/elements/group/river");
		
		if(elgg_is_active_plugin("blog")){
			elgg_extend_view("css/digest/core", "css/digest/blog");
			
			elgg_extend_view("digest/elements/site", "digest/elements/site/blog");
		}
		
		if(elgg_is_active_plugin("groups")){
			elgg_extend_view("css/digest/core", "css/digest/groups");
			
			elgg_extend_view("digest/elements/site", "digest/elements/site/groups");
		}
		
		if(elgg_is_active_plugin("profile")){
			elgg_extend_view("css/digest/core", "css/digest/profile");
			
			elgg_extend_view("digest/elements/site", "digest/elements/site/profile");
		}
	}
	
	// register elgg events
	elgg_register_event_handler("init", "system", "digest_init");
	elgg_register_event_handler("pagesetup", "system", "digest_pagesetup");

	// register cron events
	elgg_register_plugin_hook_handler("cron", "daily", "digest_cron_handler");
	elgg_register_plugin_hook_handler("cron", "weekly", "digest_cron_handler");
	elgg_register_plugin_hook_handler("cron", "monthly", "digest_cron_handler");
	
	// allow some pages through walled garden
	elgg_register_plugin_hook_handler("public_pages", "walled_garden", "digest_walled_garden_hook");
	
	// register on group leave
	elgg_register_event_handler("leave", "group", "digest_group_leave_event");
	
	// register actions
	elgg_register_action("digest/settings/save", dirname(__FILE__) . "/actions/settings/save.php", "admin");
	
	elgg_register_action("digest/update/usersettings", dirname(__FILE__) . "/actions/update/usersettings.php");
	elgg_register_action("digest/update/groupsettings", dirname(__FILE__) . "/actions/update/groupsettings.php");
	