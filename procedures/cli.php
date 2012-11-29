<?php

	if(isset($argv) && is_array($argv)){
		$site_offset = 0;
		$site_limit = 0;
		$group_offset = 0;
		$group_limit = 0;
		$timestamp = time();
		$secret = "";
		$memory_limit = "64M";
		$fork_id = 0;
		
		foreach($argv as $index => $arg){
			if(($index > 0) && !empty($arg)){
				
				list($key, $value) = explode("=", $arg);
				
				switch($key){
					case "host":
						$_SERVER["HTTP_HOST"] = $value;
						break;
					case "https":
						$_SERVER["HTTPS"] = $value;
						break;
					case "site_offset":
					case "site_limit":
					case "group_offset":
					case "group_limit":
					case "timestamp":
					case "fork_id":
						$value = (int) $value;
						
						if($value > 0){
							$$key = $value;
						}
						break;
					default:
						$$key = $value;
						break;
				}
			}
		}
		
		if(!empty($secret) && (!empty($site_limit) || !empty($group_limit))){
			ini_set("memory_limit", $memory_limit);
			
			require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
			
			if(digest_validate_commandline_secret($secret)){
				$params = array(
					"site_offset" => $site_offset,
					"site_limit" => $site_limit,
					"group_offset" => $group_offset,
					"group_limit" => $group_limit,
					"timestamp" => $timestamp,
					"fork_id" => $fork_id
				);
				
				digest_process($params);
			} else {
				echo elgg_echo("digest:cli:error:secret");
			}
		} else {
			echo "Wrong input to run this script, please provide a site_limit or group_limit and secret.";
		}
	} else {
		echo "This script can only be run from the commandline";
	}
	