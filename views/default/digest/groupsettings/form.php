<?php 

	// is group digest enabled
	if(digest_group_enabled()){
		// check the group
		$group = elgg_extract("entity", $vars);
	
		if(!empty($group) && elgg_instanceof($group, "group", null, "ElggGroup")){
			
			// is this group limited by some other plugin
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
				$form_body = "<div>";
				$form_body .= elgg_echo("digest:groupsettings:setting");
				$form_body .= elgg_view("input/dropdown", array("name" => "digest_interval", "options_values" => $interval_options, "value" => $group_interval, "class" => "mlm"));
				$form_body .= "</div>";
				
				$form_body .= "<div class='elgg-foot'>";
				$form_body .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
				$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
				$form_body .= "</div>";
				
				$form = elgg_view("input/form", array("body" => $form_body, "action" => "action/digest/update/groupsettings"));
				
				echo elgg_view_module("info", elgg_echo("digest:groupsettings:title") . "<span class='elgg-icon elgg-icon-info mlm digest-settings-title-info' title='" . elgg_echo("digest:groupsettings:description") . "'></span>", $form); 
			}
		}
	}