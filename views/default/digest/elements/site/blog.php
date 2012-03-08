<?php

	/**
	* Shows the latests blogs in the Digest
	*
	*/
	
	$user = elgg_extract("user", $vars, elgg_get_logged_in_user_entity());
	$ts_lower = (int) elgg_extract("ts_lower", $vars);
	$ts_upper = (int) elgg_extract("ts_upper", $vars);

	$blog_options = array(
		"type" => "object",
		"subtype" => "blog",
		"limit" => 5,
		"created_time_lower" => $ts_lower,
		"created_time_upper" => $ts_upper,
		"full_view" => false
	);

	if($latest_blogs = elgg_list_entities($blog_options)){
		echo elgg_view_module("digest", elgg_echo("blog:blogs"), $latest_blogs);
	}
	