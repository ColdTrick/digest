<?php 

	$user = elgg_extract("user", $vars);
	$groups = elgg_extract("groups", $vars);
	
	// start making the form
	$form_body = elgg_view_module("aside", elgg_echo("digest:usersettings:site:title"), elgg_view("digest/usersettings/site", array("user" => $user)));
	
	if(!empty($groups)){
		$form_body .= elgg_view_module("aside", elgg_echo("digest:usersettings:groups:title"), elgg_view("digest/usersettings/groups", array("user" => $user, "groups" => $groups)));
	}
	
	$form_body .= "<div class='elgg-foot'>\n";
	$form_body .= elgg_view("input/hidden", array("name" => "user_guid", "value" => $user->getGUID()));
	$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
	$form_body .= "</div>\n";
	
	echo elgg_view("input/form", array("body" => $form_body,
											"action" => $vars["url"] . "action/digest/update/usersettings",
											"class" => "elgg-form-alt"
	));
	