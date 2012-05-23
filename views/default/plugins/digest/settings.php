<?php

	$plugin = $vars["entity"];
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
	
	$interval_options = array(
		DIGEST_INTERVAL_NONE => elgg_echo("digest:interval:none"),
		DIGEST_INTERVAL_DAILY => elgg_echo("digest:interval:daily"),
		DIGEST_INTERVAL_WEEKLY => elgg_echo("digest:interval:weekly"),
		DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo("digest:interval:fortnightly"),
		DIGEST_INTERVAL_MONTHLY => elgg_echo("digest:interval:monthly")
	);

	// Is Digest in production
	$settings_production = "<div>";
	$settings_production .= elgg_echo("digest:settings:production:description");
	$settings_production .= "</div><br />";
	 
	$settings_production .= "<div>";
	$settings_production .= elgg_echo("digest:settings:production:option");
	$settings_production .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[in_production]", "options_values" => $noyes_options, "value" => $plugin->in_production));
	$settings_production .= "</div>";
	
	$settings_production .= "<div>";
	$settings_production .= elgg_echo("digest:settings:production:group_option");
	$settings_production .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[group_production]", "options_values" => $noyes_options, "value" => $plugin->group_production));
	$settings_production .= "</div>";

	echo elgg_view_module("inline", elgg_echo("digest:settings:production:title"), $settings_production);

	// Digest interval settings
	$settings_interval = "<div>";
	$settings_interval .= elgg_echo("digest:settings:interval:site_default");
	$settings_interval .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[site_default]", "options_values" => $interval_options, "value" => $plugin->site_default));
	$settings_interval .= "</div>";
	
	$settings_interval .= "<div>";
	$settings_interval .= elgg_echo("digest:settings:interval:group_default");
	$settings_interval .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[group_default]", "options_values" => $interval_options, "value" => $plugin->group_default));
	$settings_interval .= "</div><br />";
	
	$settings_interval .= "<div>";
	$settings_interval .= elgg_echo("digest:settings:interval:description");
	$settings_interval .= "</div>";
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:interval:title"), $settings_interval);
	
	// Should we include user who never logged in
	$settings_never = elgg_echo("digest:settings:never:include");
	$settings_never .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[include_never_logged_in]", "options_values" => $noyes_options, "value" => $plugin->include_never_logged_in));
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:never:title"), $settings_never);

	// add custom header and footer to every digest
	$custom_text = "<div>" . elgg_echo("digest:settings:custon_text:description") . "</div>";
	
	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:site:header") . "</label>";
	$custom_text .= elgg_view("input/longtext", array("name" => "params[custom_text_site_header]", "value" => $plugin->custom_text_site_header));
	$custom_text .= "</div>";
	
	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:site:footer") . "</label>";
	$custom_text .= elgg_view("input/longtext", array("name" => "params[custom_text_site_footer]", "value" => $plugin->custom_text_site_footer));
	$custom_text .= "</div>";

	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:group:header") . "</label>";
	$custom_text .= elgg_view("input/longtext", array("name" => "params[custom_text_group_header]", "value" => $plugin->custom_text_group_header));
	$custom_text .= "</div>";
	
	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:group:footer") . "</label>";
	$custom_text .= elgg_view("input/longtext", array("name" => "params[custom_text_group_footer]", "value" => $plugin->custom_text_group_footer));
	$custom_text .= "</div>";
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:custon_text:title"), $custom_text);
	