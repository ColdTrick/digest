<?php

	/**
	 * Handler the cron of Elgg to send out digests
	 * 
	 * @param string $hook
	 * @param string $entity_type => the interval of the current cron
	 * @param bool $returnvalue
	 * @param array $params
	 */
	function digest_cron_handler($hook, $entity_type, $returnvalue, $params){
		global $dbcalls;
		global $DB_QUERY_CACHE;
		global $ENTITY_CACHE;
		global $DB_PROFILE;
		global $digest_mail_send;
		global $interval_ts_upper;
	
		set_time_limit(0);
	
		$intervals = array();
		$debug_mode = false;
	
		if(get_config("debug")){
			$debug_mode = true;
		}
		
		// set correct time
		$interval_ts_upper = elgg_extract("time", $params, time());
	
		switch($entity_type){
			case "daily":
				$intervals[] = DIGEST_INTERVAL_DAILY;
				break;
			case "weekly":
				$intervals[] = DIGEST_INTERVAL_WEEKLY;
	
				if(date("W", $interval_ts_upper) & 1){
					// odd weeks
					$intervals[] =  DIGEST_INTERVAL_FORTNIGHTLY;
				} else {
					// even weeks
				}
				break;
			case "monthly":
				$intervals[] = DIGEST_INTERVAL_MONTHLY;
				break;
		}
	
		if(!empty($intervals)){
			$digest_site_sent = 0;
			$digest_group_sent = 0;
			
			foreach($intervals as $interval){
				// some base values for stat logging
				$START_TIME = microtime(true);
				$INITIAL_MEMORY = memory_get_usage(false);
				
				// run site digest
				$include_site_default = false;
				$site_default_interval = digest_get_default_site_interval();
	
				if($site_default_interval == $interval){
					$include_site_default = true;
				}
	
				$site_members = digest_get_users(get_config("site_guid"), $interval, $include_site_default);
	
				if(!empty($site_members)){
					$db_query_backup = $DB_QUERY_CACHE;
					$entity_backup = $ENTITY_CACHE;
						
					foreach($site_members as $index => $site_member){
						$cur_mem = memory_get_usage(false);
	
						if($user = get_user($site_member->guid)){
							if(digest_site($user, $interval)){
								$digest_site_sent++;
							} else {
								error_log("Site digest failed for user: " . $user->name . " (" . $user->getGUID() . ")");
							}
						} else {
							error_log("Site digest tried a non user: " . $site_member->guid);
						}
	
						$DB_QUERY_CACHE = null;
						unset($GLOBALS["DB_QUERY_CACHE"]);
						$DB_QUERY_CACHE = $db_query_backup;
	
						$ENTITY_CACHE = null;
						unset($GLOBALS["ENTITY_CACHE"]);
						$ENTITY_CACHE = $entity_backup;
	
						if(!$debug_mode){
							$DB_PROFILE = null;
							unset($GLOBALS["DB_PROFILE"]);
						}
	
						$last_mem = $cur_mem;
					}
						
					// site stats
					$site_members_count = count($site_members);
					$site_mem_usage = (memory_get_usage(false) - $INITIAL_MEMORY);
					$avg_site_members_memory = round($site_mem_usage / $site_members_count, 2);
					$site_run_time = (microtime(true) - $START_TIME);
				} else {
					$site_members_count = 0;
					$avg_site_members_memory = 0;
					$site_run_time = 0;
				}
	
				// save site stats
				elgg_set_plugin_setting("site_digest_" . $interval . "_members", $site_members_count, "digest");
				elgg_set_plugin_setting("site_digest_" . $interval . "_avg_memory", $avg_site_members_memory, "digest");
				elgg_set_plugin_setting("site_digest_" . $interval . "_run_time", $site_run_time, "digest");
				elgg_set_plugin_setting("site_digest_" . $interval . "_send", $digest_mail_send, "digest");
	
				// reset mail counter
				$digest_mail_send = 0;
	
				// run group digest
				if(elgg_is_active_plugin("groups") && digest_group_enabled()){
					$group_options = array(
						"type" => "group",
						"count" => true
					);
						
					if($group_count = elgg_get_entities($group_options)){
						// get groups
						$group_options["count"] = false;
						$group_options["limit"] = $group_count;
	
						$groups = elgg_get_entities($group_options);
	
						// set counters
						$total_group_members_count = 0;
	
						// backup cache
						$db_query_backup = $DB_QUERY_CACHE;
						$entity_backup = $ENTITY_CACHE;
	
						foreach($groups as $group){
							if(elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)){
								$include_group_default = false;
								$group_default_interval = $group->digest_interval;
	
								if(empty($group_default_interval)){
									$group_default_interval = digest_get_default_group_interval();
								}
	
								if($group_default_interval == $interval){
									$include_group_default = true;
								}
	
								$group_members = digest_get_users($group->getGUID(), $interval, $include_group_default);
	
								if(!empty($group_members)){
									// increase for stats
									$total_group_members_count += count($group_members);
										
									$db_query_backup_members = $DB_QUERY_CACHE;
									$entity_backup_members = $ENTITY_CACHE;
										
									foreach($group_members as $group_member){
										if($user = get_user($group_member->guid)){
											if(digest_group($group, $user, $interval)){
												$digest_group_sent++;
											} else {
												error_log("Group digest (" . $group->name . ") failed for user: " . $user->name . " (" . $user->getGUID() . ")");
											}
										} else {
											error_log("Group digest (" . $group->name . ") tried a non user: " . $group_member->guid);
										}
									}
										
									$DB_QUERY_CACHE = null;
									unset($GLOBALS["DB_QUERY_CACHE"]);
									$DB_QUERY_CACHE = $db_query_backup_members;
										
									$ENTITY_CACHE = null;
									unset($GLOBALS["ENTITY_CACHE"]);
									$ENTITY_CACHE = $entity_backup_members;
										
									if(!$debug_mode){
										$DB_PROFILE = null;
										unset($GLOBALS["DB_PROFILE"]);
									}
								}
							}
								
							$DB_QUERY_CACHE = null;
							unset($GLOBALS["DB_QUERY_CACHE"]);
							$DB_QUERY_CACHE = $db_query_backup;
								
							$ENTITY_CACHE = null;
							unset($GLOBALS["ENTITY_CACHE"]);
							$ENTITY_CACHE = $entity_backup;
								
							if(!$debug_mode){
								$DB_PROFILE = null;
								unset($GLOBALS["DB_PROFILE"]);
							}
						}
	
						// group stats
						$group_mem_usage = (memory_get_peak_usage(true) - $INITIAL_MEMORY);
						$avg_group_members_memory = round($group_mem_usage / $total_group_members_count, 2);
						$avg_group_memory = round(($group_mem_usage / $group_count), 2);
						$avg_group_members = round(($total_group_members_count / $group_count), 2);
						$group_run_time = ((microtime(true) - $START_TIME) - $site_run_time);
					} else {
						$total_group_members_count = 0;
						$avg_group_members = 0;
						$avg_group_members_memory = 0;
						$avg_group_memory = 0;
						$group_run_time = 0;
					}
						
					// save group stats
					elgg_set_plugin_setting("group_digest_" . $interval . "_count", $group_count, "digest");
					elgg_set_plugin_setting("group_digest_" . $interval . "_total_members", $total_group_members_count, "digest");
					elgg_set_plugin_setting("group_digest_" . $interval . "_avg_members", $avg_group_members, "digest");
					elgg_set_plugin_setting("group_digest_" . $interval . "_avg_members_memory", $avg_group_members_memory, "digest");
					elgg_set_plugin_setting("group_digest_" . $interval . "_avg_memory", $avg_group_memory, "digest");
					elgg_set_plugin_setting("group_digest_" . $interval . "_run_time", $group_run_time, "digest");
					elgg_set_plugin_setting("group_digest_" . $interval . "_send", $digest_mail_send, "digest");
						
					// reset mail counter
					$digest_mail_send = 0;
				}
			}
		}
	}
	
	/**
	 * when a default site interval is set, the user must tell us wether he/shw wants te receive a digest
	 * 
	 * @param string $hook
	 * @param string $type
	 * @param bool $return_value
	 * @param arrat$params
	 */
	function digest_register_user_hook($hook, $type, $return_value, $params){
		
		if(!empty($params) && is_array($params)){
			$user = elgg_extract("user", $params);
			
			if(!empty($user) && elgg_instanceof($user, "user", null, "ElggUser") && ($site_interval = digest_get_default_site_interval())){
				// show hidden users (maybe disabled by uservalidationbyemail)
				$show_hidden = access_get_show_hidden_status();
				access_show_hidden_entities(true);
				
				if(get_input("digest_site") == "yes"){
					$user->setPrivateSetting("digest_" . get_config("site_guid"), $site_interval);
				} else {
					$user->setPrivateSetting("digest_" . get_config("site_guid"), DIGEST_INTERVAL_NONE);
				}
				
				access_show_hidden_entities($show_hidden);
			}
		}
	}
	
	/**
	 * Allow users to directly unsubscribe even in walled garden
	 * 
	 * @param string $hook
	 * @param string $type
	 * @param array $return_value
	 * @param $params
	 * @return array
	 */
	function digest_walled_garden_hook($hook, $type, $return_value, $params){
		$return_value[] = "digest/unsubscribe";
		
		return $return_value;
	}