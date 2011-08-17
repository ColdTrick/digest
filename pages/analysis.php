<?php 

	admin_gatekeeper();

	set_context("admin");
	
	$site_interval = get_input("site_interval");
	$group_interval = get_input("group_interval");
	
	$site_default = get_plugin_setting("site_default", "digest");
	if(empty($site_default)){
		$site_default = DIGEST_INTERVAL_NONE;
	}
	
	$intervals = array(
		DIGEST_INTERVAL_DAILY,
		DIGEST_INTERVAL_WEEKLY,
		DIGEST_INTERVAL_FORTNIGHTLY,
		DIGEST_INTERVAL_MONTHLY,
	);
	
	// build page elements
	$title_text = elgg_echo("digest:analysis:title");
	$title = elgg_view_title($title_text);
	
	$site = elgg_view("digest/analysis/site", array("default_interval" => $site_default, "intervals" => $intervals, "site_interval" => $site_interval));
	
	if(is_plugin_enabled("groups")){
		$group_default = get_plugin_setting("group_default", "digest");
		if(empty($group_default)){
			$group_default = DIGEST_INTERVAL_NONE;
		}
		
		$groups = elgg_view("digest/analysis/groups", array("default_interval" => $group_default, "intervals" => $intervals, "group_interval" => $group_interval));
	}
	
	$footer = elgg_view("digest/analysis/footer", array("site_default" => $site_default, "group_default" => $group_default, "site_interval" => $site_interval, "group_interval" => $group_interval, "intervals" => $intervals));
	
	// build page data
	$page_data = $title . $site . $groups . $footer;
	
	// draw page
	page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $page_data));

?>