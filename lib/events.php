<?php

	function digest_create_user_event_handler($event, $type, $object){
		global $CONFIG;
		
		if(!empty($object) && ($object instanceof ElggUser)){
			// only on register of a user, not admin created users
			if(get_input("action") == "register"){
				if(get_input("digest_site") == "yes"){
					// user wishes to receive digest
					$site_interval = get_plugin_setting("site_default", "digest");
					
					$object->setPrivateSetting("digest_" . $CONFIG->site_guid, $site_interval);
				} else {
					// user doesn't wish to receive digest
					$object->setPrivateSetting("digest_" . $CONFIG->site_guid, DIGEST_INTERVAL_NONE);
				}
			}
		}
	}