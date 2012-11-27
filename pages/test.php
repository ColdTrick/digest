<?php 
	
	admin_gatekeeper();

	// remove some view extensions
	digest_prepare_run();
	
	$digest = "site";
	$interval = DIGEST_INTERVAL_MONTHLY;
	$header_text = elgg_get_plugin_setting("custom_text_site_header", "digest");
	$footer_text = elgg_get_plugin_setting("custom_text_site_footer", "digest");
	
	switch($page[1]){
		case "group":
			if(!empty($page[3]) && ($group = get_entity($page[3])) && elgg_instanceof($group, "group")){
				$digest = "group";
				
				$header_text = elgg_get_plugin_setting("custom_text_group_header", "digest");
				$footer_text = elgg_get_plugin_setting("custom_text_group_footer", "digest");
				
			} else {
				forward();
			}
		case "site":
		default:
			$interval = $page[2];
			break;
	}
	
	// set some interval settings
	$ts_upper = time();
	
	switch($interval){
		case DIGEST_INTERVAL_DAILY:
			$ts_lower = $ts_upper - (60*60*24);
			break;
		case DIGEST_INTERVAL_WEEKLY:
			$ts_lower = $ts_upper - (60*60*24*7);
			break;
		case DIGEST_INTERVAL_FORTNIGHTLY:
			$ts_lower = $ts_upper - (60*60*24*14);
			break;
		case DIGEST_INTERVAL_MONTHLY:
			$ts_lower = $ts_upper - (60*60*24*31);
			break;
		default:
			$interval = DIGEST_INTERVAL_MONTHLY;
			$ts_lower = 1;			
	}
	
	$user = elgg_get_logged_in_user_entity();
	
	$vars = array(
		"user" => $user,
		"ts_lower" => $ts_lower,
		"ts_upper" => $ts_upper,
		"interval" => $interval,
		"group" => $group
	);
		
	$content = "";
	
	if(!empty($header_text)){
		$content .= elgg_view_module("digest", "", "<div class='elgg-output'>" . $header_text . "</div>");
	}
	
	$content .= elgg_view("digest/elements/" . $digest, $vars);
	
	if(!empty($footer_text)){
		$content .= elgg_view_module("digest", "", "<div class='elgg-output'>" . $footer_text . "</div>");
	}
	
	$params = array(
		"title" => elgg_get_site_entity()->name,
		"content" => $content,
		"footer" => elgg_view("digest/elements/footer", $vars),
		"digest_header" => elgg_view("digest/elements/header", $vars),
		"digest_online" => elgg_view("digest/elements/online", $vars),
		"digest_unsubscribe" => elgg_view("digest/elements/unsubscribe", $vars)
	);
	
	$msgbody = elgg_view_layout("digest", $params);

	echo $msgbody;
	
	// mail the result?
	if(get_input("mail")){
		if(digest_send_mail($user, "Test message from Digest", $msgbody, digest_get_online_url($vars), true)){
			echo "mail send<br />";
		}
	}
	