<?php

	/**
	 * Handler the cron of Elgg to send out digests
	 * 
	 * @param string $hook
	 * @param string $entity_type => the interval of the current cron
	 * @param bool $returnvalue
	 * @param array $params
	 */
	function digest_cron_handler_old($hook, $entity_type, $returnvalue, $params){
		global $dbcalls;
		global $DB_QUERY_CACHE;
		global $ENTITY_CACHE;
		global $digest_mail_send;
		global $interval_ts_upper;
	
		set_time_limit(0);
	
		$intervals = array();
		
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
				// clear cache to save memory
				$DB_QUERY_CACHE->clear();
				
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
	
						// clear cache to save memory
						$DB_QUERY_CACHE->clear();
				
						$ENTITY_CACHE = null;
						unset($GLOBALS["ENTITY_CACHE"]);
						$ENTITY_CACHE = $entity_backup;
	
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
	
				// clear cache to save memory
				$DB_QUERY_CACHE->clear();
				
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
										
									// clear cache to save memory
									$DB_QUERY_CACHE->clear();
									
									$ENTITY_CACHE = null;
									unset($GLOBALS["ENTITY_CACHE"]);
									$ENTITY_CACHE = $entity_backup_members;
										
								}
							}
								
							// clear cache to save memory
							$DB_QUERY_CACHE->clear();
							
							$ENTITY_CACHE = null;
							unset($GLOBALS["ENTITY_CACHE"]);
							$ENTITY_CACHE = $entity_backup;
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
			
			// clear cache to save memory
			$DB_QUERY_CACHE->clear();
				
			$ENTITY_CACHE = null;
			unset($GLOBALS["ENTITY_CACHE"]);
			$ENTITY_CACHE = $entity_backup;
		}
	}
	
	/**
	 * Lets figure out what we need to do
	 * 
	 * @param string $hook
	 * @param string $entity_type
	 * @param bool $returnvalue
	 * @param array $params
	 */
	function digest_cron_handler($hook, $entity_type, $returnvalue, $params) {
		global $interval_ts_upper;
		global $DB_QUERY_CACHE;
		global $ENTITY_CACHE;
		
		if (!empty($params) && is_array($params)) {
			// set global start time of digest run
			$interval_ts_upper = elgg_extract("time", $params, time());
			
			// should new users be included
			$never_logged_in = false;
			if (elgg_get_plugin_setting("include_never_logged_in", "digest") == "yes") {
				$never_logged_in = true;
			}
			
			// backup some cache
			$entity_cache_backup = $ENTITY_CACHE;
			
			// should the site digest be sent
			if (digest_site_enabled()) {
				// prepare stats logging
				$site_stats = digest_prepare_site_statistics();
				
				// log some beginning stats
				$site_stats["general"]["mts_start_digest"] = microtime(true);
				$site_stats["general"]["ts_start_cron"] = $interval_ts_upper;
				$site_stats["general"]["peak_memory_start"] = memory_get_peak_usage(false);
				
				$site_intervals = array(
					DIGEST_INTERVAL_DEFAULT => digest_get_default_site_interval(),
					DIGEST_INTERVAL_WEEKLY => digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY),
					DIGEST_INTERVAL_FORTNIGHTLY => digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY),
					DIGEST_INTERVAL_MONTHLY => digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY)
				);
				
				// find users 
				if ($users = digest_get_site_users($site_intervals, $never_logged_in)) {
					// log selection time
					$site_stats["general"]["mts_user_selection_done"] = microtime(true);
					
					// use a fair memory footprint
					$DB_QUERY_CACHE->clear();
					$stats_last_memory = memory_get_usage(false);
					
					// process users
					foreach($users as $user_setting){
						// stat logging
						$site_stats[$user_setting["user_interval"]]["users"]++;
						
						// sent site digest for this user
						$user = get_user($user_setting["guid"]);
						
						// log start time
						$stats_mts_before = microtime(true);
						
						// sent out the digest
						if(digest_site($user, $user_setting["user_interval"]) === true){
							// mail was sent
							$site_stats[$user_setting["user_interval"]]["mails"]++;
						}
						
						// stats logging
						$site_stats[$user_setting["user_interval"]]["total_time"] += (microtime(true) - $stats_mts_before);
						
						// reset cache
						$GLOBALS["ENTITY_CACHE"] = $entity_cache_backup;

						$DB_QUERY_CACHE->clear();
						
						unset($user);
						
						// stats logging of memory leak
						$stats_current_memory = memory_get_usage(false);
						$site_stats[$user_setting["user_interval"]]["total_memory"] += ($stats_current_memory - $stats_last_memory);
						$stats_last_memory = $stats_current_memory;
					}
				}
				
				// cleanup some stuff
				unset($users);
				unset($site_intervals);
				
				// log some end stats
				$site_stats["general"]["mts_end_digest"] = microtime(true);
				$site_stats["general"]["peak_memory_end"] = memory_get_peak_usage(false);
				
				// save stats logging
				digest_save_site_statistics($site_stats, $interval_ts_upper);
				unset($site_stats);
			}
			
			// should the group digest be sent
			if (digest_group_enabled()) {
				// prepare stats logging
				$group_stats = digest_prepare_group_statistics();
				
				// log some beginning stats
				$group_stats["general"]["mts_start_digest"] = microtime(true);
				$group_stats["general"]["ts_start_cron"] = $interval_ts_upper;
				$group_stats["general"]["peak_memory_start"] = memory_get_peak_usage(false);
				
				// prepare group options
				$options = array(
					"type" => "group",
					"limit" => false,
					"callback" => "digest_row_to_guid"
				);
				
				$group_intervals = array(
					DIGEST_INTERVAL_WEEKLY => digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY),
					DIGEST_INTERVAL_FORTNIGHTLY => digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY),
					DIGEST_INTERVAL_MONTHLY => digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY)
				);
				
				// ignore access to get all groups
				$ia = elgg_set_ignore_access(true);
				
				if ($group_guids = elgg_get_entities($options)) {
					// log selection time
					$group_stats["general"]["mts_group_selection_done"] = microtime(true);
						
					// use a fair memory footprint
					$DB_QUERY_CACHE->clear();
					$stats_last_group_memory = memory_get_usage(false);
					
					foreach ($group_guids as $group_guid) {
						// make sure we can get the group
						elgg_set_ignore_access(true);
						
						// get group
						$group = get_entity($group_guid);
						
						// get group default interval
						$group_interval = $group->digest_interval;
						
						if (empty($group_interval)) {
							// group has no interval, so fallback to site default
							$group_interval = digest_get_default_group_interval();
						}
						
						$group_intervals[DIGEST_INTERVAL_DEFAULT] = $group_interval;
						
						// restore access
						elgg_set_ignore_access($ia);
						
						$stats_begin_user_selection = microtime(true);
						
						if ($users = digest_get_group_users($group_guid, $group_intervals, $never_logged_in)) {
							// stats loggin
							$group_stats["general"]["total_time_user_selection"] += (microtime(true) - $stats_begin_user_selection);
							
							// use a fair memory footprint
							$DB_QUERY_CACHE->clear();
							$stats_last_memory = memory_get_usage(false);
							
							// process users
							foreach ($users as $user_setting) {
								// stat logging
								$group_stats[$user_setting["user_interval"]]["users"]++;
								if(!in_array($group_guid, $group_stats[$user_setting["user_interval"]]["groups"])){
									$group_stats[$user_setting["user_interval"]]["groups"][] = $group_guid;
								}
								
								// get the user 
								$user = get_user($user_setting["guid"]);
								
								// log start time
								$stats_mts_before = microtime(true);
								
								// sent digest
								if(digest_group($group, $user, $user_setting["user_interval"]) === true){
									// mail was sent
									$group_stats[$user_setting["user_interval"]]["mails"]++;
								}
								
								// stats logging
								$group_stats[$user_setting["user_interval"]]["total_time"] += (microtime(true) - $stats_mts_before);
								
								// reset cache
								$GLOBALS["ENTITY_CACHE"] = $entity_cache_backup;
								
								$DB_QUERY_CACHE->clear();
								
								unset($user);
								
								// stats logging of memory leak
								$stats_current_memory = memory_get_usage(false);
								$group_stats[$user_setting["user_interval"]]["total_memory"] += ($stats_current_memory - $stats_last_memory);
								$stats_last_memory = $stats_current_memory;
							}
						} else {
							// stats logging
							$group_stats["general"]["total_time_user_selection"] += (microtime(true) - $stats_begin_user_selection);
						}
						
						// reset cache
						$GLOBALS["ENTITY_CACHE"] = $entity_cache_backup;
						
						$DB_QUERY_CACHE->clear();
						
						unset($group);
						
						// stats logging of memory leak
						$stats_current_group_memory = memory_get_usage(false);
						$group_stats["general"]["total_memory"] += ($stats_current_group_memory - $stats_last_group_memory);
						$stats_last_group_memory = $stats_current_group_memory;
					}
				}
				
				// restore access settings
				elgg_set_ignore_access($ia);
				
				// log some end stats
				$group_stats["general"]["mts_end_digest"] = microtime(true);
				$group_stats["general"]["peak_memory_end"] = memory_get_peak_usage(false);
				
				// save stats logging
				digest_save_group_statistics($group_stats, $interval_ts_upper);
			}
		}
	}
	
	/**
	 * when a default site interval is set, the user must tell us wether he/shw wants te receive a digest
	 * 
	 * @param string $hook
	 * @param string $type
	 * @param bool $return_value
	 * @param array $params
	 */
	function digest_register_user_hook($hook, $type, $return_value, $params){
		
		if(!empty($params) && is_array($params)){
			$user = elgg_extract("user", $params);
			
			if(!empty($user) && elgg_instanceof($user, "user", null, "ElggUser")){
				if(($site_interval = digest_get_default_site_interval()) && ($site_interval != DIGEST_INTERVAL_NONE)){
					// show hidden users (maybe disabled by uservalidationbyemail)
					$show_hidden = access_get_show_hidden_status();
					access_show_hidden_entities(true);
					
					if(get_input("digest_site") == "yes"){
						$user->setPrivateSetting("digest_" . elgg_get_config("site_guid"), $site_interval);
					} else {
						$user->setPrivateSetting("digest_" . elgg_get_config("site_guid"), DIGEST_INTERVAL_NONE);
					}
					
					access_show_hidden_entities($show_hidden);
				}
			}
		}
	}
	
	/**
	 * Allow users to directly unsubscribe even in walled garden
	 * 
	 * @param string $hook
	 * @param string $type
	 * @param array $return_value
	 * @param array $params
	 * @return array
	 */
	function digest_walled_garden_hook($hook, $type, $return_value, $params){
		$return_value[] = "digest/unsubscribe";
		
		return $return_value;
	}