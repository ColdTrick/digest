<?php

namespace ColdTrick\Digest;

class User {
	
	/**
	 * When a default site interval is set, the user must tell us wether he/she wants to receive a digest
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void
	 */
	public static function savePreferenceOnRegister($hook, $type, $return_value, $params) {

		$user = elgg_extract("user", $params);
		
		if (empty($user) || !elgg_instanceof($user, "user", null, "ElggUser")) {
			return;
		}
		
		$site_interval = digest_get_default_site_interval();
		if (!empty($site_interval) && ($site_interval != DIGEST_INTERVAL_NONE)) {
			// show hidden users (maybe disabled by uservalidationbyemail)
			$show_hidden = access_get_show_hidden_status();
			access_show_hidden_entities(true);
			
			if (get_input("digest_site") == "yes") {
				$user->setPrivateSetting("digest_" . elgg_get_config("site_guid"), $site_interval);
			} else {
				$user->setPrivateSetting("digest_" . elgg_get_config("site_guid"), DIGEST_INTERVAL_NONE);
			}
			
			access_show_hidden_entities($show_hidden);
		}
	}
}
