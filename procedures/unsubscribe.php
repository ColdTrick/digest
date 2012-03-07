<?php

	// get inputs
	$guid = (int) get_input("guid");
	$user_guid = (int) get_input("user_guid");
	$code = get_input("code");
	
	if(!empty($guid) && !empty($user_guid) && !empty($code)){
		if(($user = get_user($user_guid)) && digest_validate_unsubscribe_code($guid, $user, $code)){
			if($user->setPrivateSetting("digest_" . $guid, DIGEST_INTERVAL_NONE)){
				system_message(elgg_echo("digest:unsubscribe:success"));
			} else {
				register_error(elgg_echo("digest:unsubscribe:error:save"));
			}
		} else {
			register_error(elgg_echo("digest:unsubscribe:error:code"));
		}
	} else {
		register_error(elgg_echo("digest:unsubscribe:error:input"));
	}
	
	forward();
	