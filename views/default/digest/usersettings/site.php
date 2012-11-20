<?php 
	if (digest_site_enabled()) {
		$user = elgg_extract("user", $vars);
		
		$site_guid = elgg_get_site_entity()->getGUID();
		
		$site_interval = $user->getPrivateSetting("digest_" . $site_guid);
		
		if (empty($site_interval)) {
			$site_interval = digest_get_default_site_interval();
		}
		
		$interval_options = array(
			DIGEST_INTERVAL_NONE => elgg_echo("digest:interval:none"),
			DIGEST_INTERVAL_DAILY => elgg_echo("digest:interval:daily"),
			DIGEST_INTERVAL_WEEKLY => elgg_echo("digest:interval:weekly"),
			DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo("digest:interval:fortnightly"),
			DIGEST_INTERVAL_MONTHLY => elgg_echo("digest:interval:monthly")
		);
		
		// make form
		$body = "<div>";
		$body .= elgg_echo("digest:usersettings:site:setting");
		$body .= elgg_view("input/dropdown", array("name" => "digest[" . $site_guid . "]", "options_values" => $interval_options, "value" => $site_interval, "class" => "mlm"));
		$body .= "</div>";
		
		$title = elgg_echo("digest:usersettings:site:title") . "<span class='elgg-icon elgg-icon-info digest-settings-title-info mlm' title='" . elgg_echo("digest:usersettings:site:description") . "'></span>";
		
		echo elgg_view_module("info", $title, $body);
	}