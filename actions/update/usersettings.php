<?php 

	gatekeeper();
	action_gatekeeper();

	$user_guid = get_input("user_guid", get_loggedin_userid());
	$digests = get_input("digest");
	
	if(!empty($user_guid) && !empty($digests)){
		if($user = get_user($user_guid)){
			$error_count = 0;
			
			foreach($digests as $guid => $interval){
				if($interval != DIGEST_INTERVAL_DEFAULT){
					if(!($user->setPrivateSetting("digest_" . $guid, $interval))){
						$error_count++;
					}
				} else {
					$user->removePrivateSetting("digest_" . $guid);
				}
			}
			
			if($error_count == 0){
				system_message(elgg_echo("digest:action:update:usersettings:success"));
			} else {
				register_error(elgg_echo("digest:action:update:usersettings:error:unknown"));
			}
		} else {
			register_error(elgg_echo("digest:action:update:usersettings:error:user"));
		}
	} else {
		register_error(elgg_echo("digest:action:update:usersettings:error:input"));
	}
	
	forward($_SERVER["HTTP_REFERER"]);
?>