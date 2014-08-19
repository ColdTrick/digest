<?php

$user = elgg_extract("user", $vars);
$groups = elgg_extract("groups", $vars);

if (empty($groups)) {
	return;
}

$site_group_interval = digest_get_default_group_interval();

$group_items = "";

foreach ($groups as $group) {
	if (!elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)) {
		continue;
	}
	
	$group_interval = $group->digest_interval;
	$user_group_interval = $user->getPrivateSetting("digest_" . $group->getGUID());
	
	if (empty($group_interval)) {
		$group_interval = $site_group_interval;
	}
	
	if (empty($user_group_interval)) {
		$user_group_interval = DIGEST_INTERVAL_DEFAULT;
	}
	
	$interval_options = array(
		DIGEST_INTERVAL_NONE => elgg_echo("digest:interval:none"),
		DIGEST_INTERVAL_DEFAULT => elgg_echo("digest:interval:default", array(elgg_echo("digest:interval:" . $group_interval))),
		DIGEST_INTERVAL_DAILY => elgg_echo("digest:interval:daily"),
		DIGEST_INTERVAL_WEEKLY => elgg_echo("digest:interval:weekly"),
		DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo("digest:interval:fortnightly"),
		DIGEST_INTERVAL_MONTHLY => elgg_echo("digest:interval:monthly")
	);
	
	$group_items .= "<tr>";
	$group_items .= "<td class='digest_table_layout_left'>";
	$group_items .= elgg_view("output/url", array(
		"text" => $group->name,
		"href" => $group->getURL(),
		"title" => strip_tags($group->description),
		"is_trusted" => true
	));
	$group_items .= "</td>";
	$group_items .= "<td>";
	$group_items .= elgg_view("input/dropdown", array(
		"name" => "digest[" . $group->getGUID() . "]",
		"options_values" => $interval_options,
		"value" => $user_group_interval
	));
	$group_items .= "</td>";
	$group_items .= "</tr>";
}

if (!empty($group_items)) {
	
	$group_list = "<table class='elgg-table-alt'>";
	$group_list .= "<tr>";
	$group_list .= "<th>" . elgg_echo("digest:usersettings:groups:group_header") . "</th>";
	$group_list .= "<th>" . elgg_echo("digest:usersettings:groups:setting_header") . "</th>";
	$group_list .= "</tr>";
	$group_list .= $group_items;
	$group_list .= "</table>";
	
	$title = elgg_echo("digest:usersettings:groups:title");
	$title .= "<span title='" . elgg_echo("digest:usersettings:groups:description") . "'>";
	$title .= elgg_view_icon("info", "digest-settings-title-info mlm");
	$title .= "</span>";
	
	echo elgg_view_module("info", $title, $group_list);
}
