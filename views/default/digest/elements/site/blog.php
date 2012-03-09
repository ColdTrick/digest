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
		"created_time_upper" => $ts_upper
	);

	if($blogs = elgg_get_entities($blog_options)){
		$title = elgg_view("output/url", array("text" => elgg_echo("blog:blogs"), "href" => "blog/all" ));
		
		$latest_blogs = "";
		
		foreach($blogs as $blog){
			$blog_url = $blog->getURL();
			
			$latest_blogs .= "<div class='digest-blog'>";
			if($blog->icontime){
				$latest_blogs .= "<a href='" . $blog_url. "'><img src='". $blog->getIconURL("medium") . "' /></a>";
			}
			$latest_blogs .= "<span>";
			$latest_blogs .= "<h4><a href='" . $blog_url. "'>" . $blog->title . "</a></h4>";
			$latest_blogs .= elgg_get_excerpt($blog->description);
			$latest_blogs .= "</span>";
			$latest_blogs .= "</div>";
		}
		
		echo elgg_view_module("digest", $title, $latest_blogs);
	}
	