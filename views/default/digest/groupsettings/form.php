<?php 

	$group = elgg_extract("entity", $vars);

	if(!empty($group) && elgg_instanceof($group, "group", null, "ElggGroup")){
		
		if(elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)){
			$group_interval = $group->digest_interval;
			
			if(empty($group_interval)){
				$group_interval = digest_get_default_group_interval();
			}
			
			$form_body = elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
			
			$form_body .= "<div>\n";
			$form_body .= elgg_echo("digest:groupsettings:setting");
			$form_body .= "&nbsp;<select name='digest_interval'>\n";
			
			$form_body .= "<option value='" . DIGEST_INTERVAL_NONE . "' class='digest_interval_disabled'";
			if($group_interval == DIGEST_INTERVAL_NONE){
				$form_body .= " selected='selected'";
			}
			$form_body .= ">" . elgg_echo("digest:interval:none") . "</option>\n";
			
			$form_body .= "<option value='" . DIGEST_INTERVAL_DAILY . "'";
			if($group_interval == DIGEST_INTERVAL_DAILY){
				$form_body .= " selected='selected'";
			}
			$form_body .= ">" . elgg_echo("digest:interval:daily") . "</option>\n";
			
			$form_body .= "<option value='" . DIGEST_INTERVAL_WEEKLY . "'";
			if($group_interval == DIGEST_INTERVAL_WEEKLY){
				$form_body .= " selected='selected'";
			}
			$form_body .= ">" . elgg_echo("digest:interval:weekly") . "</option>\n";
			
			$form_body .= "<option value='" . DIGEST_INTERVAL_FORTNIGHTLY . "'";
			if($group_interval == DIGEST_INTERVAL_FORTNIGHTLY){
				$form_body .= " selected='selected'";
			}
			$form_body .= ">" . elgg_echo("digest:interval:fortnightly") . "</option>\n";
			
			$form_body .= "<option value='" . DIGEST_INTERVAL_MONTHLY . "'";
			if($group_interval == DIGEST_INTERVAL_MONTHLY){
				$form_body .= " selected='selected'";
			}
			$form_body .= ">" . elgg_echo("digest:interval:monthly") . "</option>\n";
			
			$form_body .= "</select>\n";
			$form_body .= "</div>\n";
			
			$form_body .= "<div>\n";
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