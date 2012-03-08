<?php 
	
	admin_gatekeeper();

	// remove some view extensions
	digest_revert_views();
	
	// set some interval settings
	$ts_upper = time();
	$ts_lower = time() - (60*60*24*31);
	$interval = "monthly";
	
	$user = elgg_get_logged_in_user_entity();
	
	$subject = elgg_echo("digest:message:title:site", array(elgg_get_site_entity()->name, elgg_echo("digest:interval:" . $interval)));
	
//	$group_options = array(
//		"type" => "group",
//		"limit" => 1,
//		"relationship" => "member",
//		"relationship_guid" => $user->getGUID()
//	);
//	
//	if($group = elgg_get_entities($group_options)){
//		$group = $group[0];
//		$content = elgg_view("digest/message/group_body", array("ts_lower" => time() - (60*60*24*31), "ts_upper" => time(), "group" => $group));
//	}
	
	$digest_url = elgg_get_site_url() . "digest/show?ts_upper=" . $ts_upper . "&ts_lower=" . $ts_lower . "&interval=monthly";
	$digest_online = "<a href='" . $digest_url . "'>" . elgg_echo("digest:message:online") . "</a><br />";
	
	$digest_unsubscribe = digest_create_unsubscribe_link(get_config("site_guid"), $user);
	
	$content = elgg_view("digest/elements/site", array("user" => $user, "ts_lower" => $ts_lower, "ts_upper" => $ts_upper));
	
	$params = array(
		"title" => $subject,
		"content" => $content,
		"online_link" => $digest_online,
		"footer" => $digest_unsubscribe
	);
	
	$msgbody = elgg_view_layout("digest", $subject, $content, $digest_online, $digest_unsubscribe);

	echo $msgbody;
	
	// mail the result?
	if(get_input("mail")){
		if(digest_send_mail($user, $subject, $msgbody, $digest_url, true)){
			echo "mail";
		}
	}
	