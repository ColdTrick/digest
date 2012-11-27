<?php

	/**
	 * Lets figure out what we need to do
	 * 
	 * @param string $hook
	 * @param string $entity_type
	 * @param bool $returnvalue
	 * @param array $params
	 */
	function digest_cron_handler($hook, $entity_type, $returnvalue, $params) {
		
		if (!empty($params) && is_array($params)) {
			// prepare some settings
			$digest_settings = array(
				"timestamp" => (int) elgg_extract("time", $params, time()),
				"memory_limit" => ini_get("memory_limit"),
				"host" => $_SERVER["HTTP_HOST"],
				"secret" => digest_generate_commandline_secret()
			);
			
			// is multicore support enabled
			if(($cores = (int) elgg_get_plugin_setting("multi_core", "digest")) && ($cores > 1)){
				// multi core is enabled now try to find out how many users/groups to send per core
				$site_users_count = 0;
				$site_users_interval = 0;
				$group_count = 0;
				$group_interval = 0;
				
				// site digest settings
				if(digest_site_enabled()){
					$site_intervals = array(
						DIGEST_INTERVAL_DEFAULT => digest_get_default_site_interval(),
						DIGEST_INTERVAL_WEEKLY => digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY),
						DIGEST_INTERVAL_FORTNIGHTLY => digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY),
						DIGEST_INTERVAL_MONTHLY => digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY),
						"count" => true,
					);
					
					$site_users_count = digest_get_site_users($site_intervals);
					$site_users_count = (int) $site_users_count[0]["total"];
					
					$site_users_interval = (int) floor($site_users_count / $cores);
				}
				
				// group digest settings
				if(digest_group_enabled()){
					$group_options = array(
						"type" => "group",
						"count" => true
					);
					
					$group_count = elgg_get_entities($group_options);
					$group_interval = (int) floor($group_count / $cores);
				}
				
				$site_offset = 0;
				$group_offset = 0;
				
				for($i = 0; $i < $cores; $i++){
					if($site_users_count > 0){
						$digest_settings["site_offset"] = $site_users_interval * $i;
						if($i > 0){
							$digest_settings["site_offset"]++;
						}
						
						if(($i + 1) == $cores){
							$digest_settings["site_limit"] = $site_users_count;
						} else {
							$digest_settings["site_limit"] = $site_users_interval * ($i + 1);
						}
					}
					
					if ($group_count > 0){
						$digest_settings["group_offset"] = $group_interval * $i;
						if($i > 0){
							$digest_settings["group_offset"]++;
						}
						
						if(($i + 1) == $cores){
							$digest_settings["group_limit"] = $group_count;
						} else {
							$digest_settings["group_limit"] = $group_interval * ($i + 1);
						}
					}
					
					digest_start_commandline($digest_settings);
				}
			} else {
				// procces the digest
				digest_process($digest_settings);
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