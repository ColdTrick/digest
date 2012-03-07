<?php

	$site_interval = get_input("site_interval");
	
	$site_default = digest_get_default_site_interval();
	
	$intervals = array(
		DIGEST_INTERVAL_DAILY,
		DIGEST_INTERVAL_WEEKLY,
		DIGEST_INTERVAL_FORTNIGHTLY,
		DIGEST_INTERVAL_MONTHLY,
	);
	
	// site digest analysis
	echo elgg_view("digest/analysis/site", array("default_interval" => $site_default, "intervals" => $intervals, "site_interval" => $site_interval));
	
	// group digest analysis
	if(elgg_is_active_plugin("groups")){
		$group_interval = get_input("group_interval");
		
		$group_default = digest_get_default_group_interval();
	
		echo elgg_view("digest/analysis/groups", array("default_interval" => $group_default, "intervals" => $intervals, "group_interval" => $group_interval));
	}
	