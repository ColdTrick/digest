<?php 

	$user = $vars["user"];
	$groups = $vars["groups"];
	
	$form_body .= elgg_view("input/hidden", array("internalname" => "user_guid", "value" => $user->getGUID()));
	
	$form_body .= elgg_view("digest/usersettings/site", array("user" => $user));
	
	if(!empty($groups)){
		$form_body .= "<br />\n";
		$form_body .= elgg_view("digest/usersettings/groups", array("user" => $user, "groups" => $groups));
	}
	
	$form_body .= "<div>\n";
	$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
	$form_body .= "</div>\n";
	
	$form = elgg_view("input/form", array("body" => $form_body,
											"action" => $vars["url"] . "action/digest/update/usersettings"
	));

?>
<div class="contentWrapper">
	<?php echo $form; ?>
</div>