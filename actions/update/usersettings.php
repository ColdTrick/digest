<?php 

	$user_guid = (int) get_input("user_guid", elgg_get_logged_in_user_guid());
	$digests = get_input("digest");
	
	if(!empty($user_guid) && !empty($digests) && is_array($digests)){
		if(($user = get_user($user_guid)) && $user->canEdit()){
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
			register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($user_guid, "ElggUser")));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);
	