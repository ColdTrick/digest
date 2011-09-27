<?php 

	global $CONFIG;

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
		// this plugin require the plugin 'html_email_handler'
		if(!is_plugin_enabled("html_email_handler")){
			disable_plugin("digest");
			system_message(elgg_echo("digest:init:plugin_required:html_email_handler"));
			forward();
		}
		
		// extend css
		elgg_extend_view("css", "digest/css");
		
		// register page handler for nice url's
		register_page_handler("digest", "digest_page_handler");
		
		// extend register with subscribe option
		$setting = get_plugin_setting("site_default", "digest");
		
		if(!empty($setting) && ($setting != DIGEST_INTERVAL_NONE)){
			elgg_extend_view("register/extend", "digest/register");
			
			register_elgg_event_handler("create", "user", "digest_create_user_event_handler");
		}
	}
	
	function digest_pagesetup(){
		global $CONFIG;
		
		$context = get_context();
		
		if(isloggedin()){
			// extend groups edit screen
			if(($context == "groups") && digest_group_enabled()){
				elgg_extend_view("forms/groups/edit", "digest/groupsettings/form", 400);
				
				if(page_owner_entity() instanceof ElggGroup){
					elgg_extend_view("owner_block/extend", "digest/usersettings/group");
				}
			}
			
			if($context == "settings"){
				add_submenu_item(elgg_echo("digest:submenu:usersettings"), $CONFIG->wwwroot . "pg/digest/user/" . get_loggedin_user()->username);
			}
			
			if($context == "admin" && isadminloggedin()){
				add_submenu_item(elgg_echo("digest:submenu:analysis"), $CONFIG->wwwroot . "pg/digest/analysis/");
			}
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
			case "analysis":
				include(dirname(__FILE__) . "/pages/analysis.php");
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
	}
	
	// register elgg events
	register_elgg_event_handler("init", "system", "digest_init");
	register_elgg_event_handler("pagesetup", "system", "digest_pagesetup");

	// register cron events
	register_plugin_hook("cron", "daily", "digest_cron_handler");
	register_plugin_hook("cron", "weekly", "digest_cron_handler");
	register_plugin_hook("cron", "monthly", "digest_cron_handler");
	
	// register on group leave
	register_elgg_event_handler("leave", "group", "digest_group_leave_event");
	
	// register actions
	register_action("digest/update/usersettings", false, dirname(__FILE__) . "/actions/update/usersettings.php");
	register_action("digest/update/groupsettings", false, dirname(__FILE__) . "/actions/update/groupsettings.php");
	