<?php 

	$group = elgg_extract("entity", $vars);

	if(!empty($group) && elgg_instanceof($group, "group", null, "ElggGroup")){
		
		if(elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)){
			$group_interval = $group->digest_interval;
			
			if(empty($group_interval)){
				$group_interval = digest_get_default_group_interval();
			}
			
			$interval_options = array(
				DIGEST_INTERVAL_NONE => elgg_echo("digest:interval:none"),
				DIGEST_INTERVAL_DAILY => elgg_echo("digest:interval:daily"),
				DIGEST_INTERVAL_WEEKLY => elgg_echo("digest:interval:weekly"),
				DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo("digest:interval:fortnightly"),
				DIGEST_INTERVAL_MONTHLY => elgg_echo("digest:interval:monthly")
			);
			
			// make form
			$form_body = "<div>\n";
			$form_body .= elgg_echo("digest:groupsettings:setting");
			$form_body .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "digest_interval", "options_values" => $interval_options, "value" => $group_interval));
			$form_body .= "</div>\n";
			
			$form_body .= "<div class='elgg-foot'>\n";
			$form_body .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
			$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
			$form_body .= "</div>\n";
			
			$form = elgg_view("input/form", array("body" => $form_body,
													"action" => $vars["url"] . "action/digest/update/groupsettings"));
			
			// build content
			$content = "<div>" . elgg_echo("digest:groupsettings:description") . "</div>";
			$content .= "<br />";
			$content .= $form;
			
			echo elgg_view_module("info", elgg_echo("digest:groupsettings:title"), $content); 
		}
	}