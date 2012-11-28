<?php

	$plugin = $vars["entity"];
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
		
	$distribution_options_week = array(
		0 => elgg_echo("digest:day:sunday"),
		1 => elgg_echo("digest:day:monday"),
		2 => elgg_echo("digest:day:tuesday"),
		3 => elgg_echo("digest:day:wednesday"),
		4 => elgg_echo("digest:day:thursday"),
		5 => elgg_echo("digest:day:friday"),
		6 => elgg_echo("digest:day:saturday"),
		"distributed" => elgg_echo("digest:distribution:distributed"),
	);
	
	$distribution_options_month = array_combine(range(1, 28), range(1, 28)); // both ranges are needed to keep keys and values same value. 28 is max to prevent complex issues with last day of month
	$distribution_options_month["distributed"] = elgg_echo("digest:distribution:distributed");

	$site_default_checked = array(
		DIGEST_INTERVAL_NONE => "",
		DIGEST_INTERVAL_DAILY => "",
		DIGEST_INTERVAL_WEEKLY => "",
		DIGEST_INTERVAL_FORTNIGHTLY => "",
		DIGEST_INTERVAL_MONTHLY => ""
	);
	
	$group_default_checked = $site_default_checked;
	
	if($site_default = $plugin->site_default){
		$site_default_checked[$site_default] = "checked='checked'";
	} else {
		$site_default_checked[DIGEST_INTERVAL_NONE] = "checked='checked'";
	}
	
	if($group_default = $plugin->group_default){
		$group_default_checked[$group_default] = "checked='checked'";
	} else {
		$group_default_checked[DIGEST_INTERVAL_NONE] = "checked='checked'";
	}
	
	// Interval Settings
	$settings_interval = "<table class='elgg-table-alt mbm'>";
	$settings_interval .= "<tr><th>&nbsp;</th><th class='center'>" . elgg_echo("site") . "</th><th class='center'>" . elgg_echo("group") . "</th><th>" . elgg_echo("digest:distribution") . "<span class='elgg-icon elgg-icon-digest-info mlm' title='" . elgg_echo("digest:distribution:description") . "'></span></th></tr>";
	
	$settings_interval .= "<tr>";
	$settings_interval .= "<td>" . elgg_echo("digest:settings:production") . "<span class='elgg-icon elgg-icon-digest-info mlm' title='" . elgg_echo("digest:settings:production:description") . "'></span></td>";
	$settings_interval .= "<td class='center'>" . elgg_view("input/dropdown", array("name" => "params[in_production]", "options_values" => $noyes_options, "value" => $plugin->in_production)) . "</td>";
	$settings_interval .= "<td class='center'>" . elgg_view("input/dropdown", array("name" => "params[group_production]", "options_values" => $noyes_options, "value" => $plugin->group_production)) . "</td>";
	$settings_interval .= "<td>&nbsp;</td>";
	$settings_interval .= "</tr>";
	
	$settings_interval .= "<tr><td colspan='4'>&nbsp;</td></tr>";
	
	$settings_interval .= "<tr><th colspan='4'>" . elgg_echo("digest:settings:interval:default") . "</th></tr>";
	
	$settings_interval .= "<tr>";
	$settings_interval .= "<td><span class='plm'>" . elgg_echo("digest:interval:none") . "</span></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_NONE . "' " . $site_default_checked[DIGEST_INTERVAL_NONE] . " title='" . elgg_echo("digest:interval:none") . "'/></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_NONE . "' " . $group_default_checked[DIGEST_INTERVAL_NONE] . " title='" . elgg_echo("digest:interval:none") . "'/></td>";
	$settings_interval .= "<td>&nbsp;</td>";
	$settings_interval .= "</tr>";
	
	$settings_interval .= "<tr>";
	$settings_interval .= "<td><span class='plm'>" . elgg_echo("digest:interval:daily") . "</span></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_DAILY . "' " . $site_default_checked[DIGEST_INTERVAL_DAILY] . " title='" . elgg_echo("digest:interval:daily") . "'/></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_DAILY . "' " . $group_default_checked[DIGEST_INTERVAL_DAILY] . " title='" . elgg_echo("digest:interval:daily") . "'/></td>";
	$settings_interval .= "<td>&nbsp;</td>";
	$settings_interval .= "</tr>";
	
	$settings_interval .= "<tr>";
	$settings_interval .= "<td><span class='plm'>" . elgg_echo("digest:interval:weekly") . "</span></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_WEEKLY . "' " . $site_default_checked[DIGEST_INTERVAL_WEEKLY] . " title='" . elgg_echo("digest:interval:weekly") . "'/></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_WEEKLY . "' " . $group_default_checked[DIGEST_INTERVAL_WEEKLY] . " title='" . elgg_echo("digest:interval:weekly") . "'/></td>";
	$settings_interval .= "<td>" . elgg_view("input/dropdown", array("name" => "params[weekly_distribution]", "options_values" => $distribution_options_week, "value" => $plugin->weekly_distribution)) . "</td>";
	$settings_interval .= "</tr>";
	
	$settings_interval .= "<tr>";
	$settings_interval .= "<td><span class='plm'>" .  elgg_echo("digest:interval:fortnightly") . "</span></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_FORTNIGHTLY . "' " . $site_default_checked[DIGEST_INTERVAL_FORTNIGHTLY] . " title='" . elgg_echo("digest:interval:fortnightly") . "'/></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_FORTNIGHTLY . "' " . $group_default_checked[DIGEST_INTERVAL_FORTNIGHTLY] . " title='" . elgg_echo("digest:interval:fortnightly") . "'/></td>";
	$settings_interval .= "<td>" . elgg_view("input/dropdown", array("name" => "params[fortnightly_distribution]", "options_values" => $distribution_options_week, "value" => $plugin->fortnightly_distribution)) . "</td>";
	$settings_interval .= "</tr>";
	
	$settings_interval .= "<tr>";
	$settings_interval .= "<td><span class='plm'>" .  elgg_echo("digest:interval:monthly") . "</span></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_MONTHLY . "' " . $site_default_checked[DIGEST_INTERVAL_MONTHLY] . " title='" . elgg_echo("digest:interval:monthly") . "'/></td>";
	$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_MONTHLY . "' " . $group_default_checked[DIGEST_INTERVAL_MONTHLY] . " title='" . elgg_echo("digest:interval:monthly") . "'/></td>";
	$settings_interval .= "<td>" . elgg_view("input/dropdown", array("name" => "params[monthly_distribution]", "options_values" => $distribution_options_month, "value" => $plugin->monthly_distribution)) . "</td>";
	$settings_interval .= "</tr>";
	
	$settings_interval .= "</table>";
	
	// Should we include user who never logged in
	$settings_interval .= "<div>";
	$settings_interval .= elgg_echo("digest:settings:never:include");
	$settings_interval .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[include_never_logged_in]", "options_values" => $noyes_options, "value" => $plugin->include_never_logged_in));
	$settings_interval .= "</div>";
	
	// add custom header and footer to every digest
	$custom_text = "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:site:header") . "</label>";
	$custom_text .= "<div>" . elgg_view("input/longtext", array("name" => "params[custom_text_site_header]", "value" => $plugin->custom_text_site_header)) . "</div>";
	$custom_text .= "</div>";
		
	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:site:footer") . "</label>";
	$custom_text .= "<div>" . elgg_view("input/longtext", array("name" => "params[custom_text_site_footer]", "value" => $plugin->custom_text_site_footer)) . "</div>";
	$custom_text .= "</div>";
	
	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:group:header") . "</label>";
	$custom_text .= "<div>" . elgg_view("input/longtext", array("name" => "params[custom_text_group_header]", "value" => $plugin->custom_text_group_header)) . "</div>";
	$custom_text .= "</div>";
		
	$custom_text .= "<div class='mtm'>";
	$custom_text .= "<label>" . elgg_echo("digest:settings:custom_text:group:footer") . "</label>";
	$custom_text .= "<div>" . elgg_view("input/longtext", array("name" => "params[custom_text_group_footer]", "value" => $plugin->custom_text_group_footer)) . "</div>";
	$custom_text .= "</div>";
	
	// multi-core support
	$multi_core =  "<div class='elgg-admin-notices pbn'><p>" . elgg_echo("digest:settings:multi_core:warning") . "</p></div>";
	
	$multi_core .= "<div>";
	$multi_core .= elgg_echo("digest:settings:multi_core:number");
	$multi_core .= "&nbsp;";
	$multi_core .= elgg_view("input/dropdown", array("name" => "params[multi_core]", "value" => $plugin->multi_core, "options" => array(1, 2, 4, 8)));
	$multi_core .= "</div>";
	
	// stats
	$stats = "<div>";
	$stats .= elgg_view("output/confirmlink", array("href" => "action/digest/reset_stats", "text" => elgg_echo("digest:settings:stats:reset"),"class" => "elgg-button elgg-button-action float"));
	$stats .= "</div>";
	
	// output to screen
	echo "<div class='elgg-admin-notices pbn'><p>" . elgg_echo("digest:settings:notice") . "</p></div>";
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:interval:title") . "<span class='elgg-icon elgg-icon-digest-info mlm' title='" . elgg_echo("digest:settings:interval:description") . "'></span>", $settings_interval);
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:custom_text:title") . "<span class='elgg-icon elgg-icon-digest-info mlm' title='" . elgg_echo("digest:settings:custom_text:description") . "'></span>", $custom_text);
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:multi_core:title") . "<span class='elgg-icon elgg-icon-digest-info mlm' title='" . elgg_echo("digest:settings:multi_core:description") . "'></span>", $multi_core);
	
	echo elgg_view_module("inline", elgg_echo("digest:settings:stats:title"), $stats);
	