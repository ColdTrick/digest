<?php
/**
* Shows the latests blogs in the Digest
*
*/

$ts_lower = (int) elgg_extract("ts_lower", $vars);
$ts_upper = (int) elgg_extract("ts_upper", $vars);

// only show blogs that are published
$dbprefix = elgg_get_config("dbprefix");

$blog_status_name_id = elgg_get_metastring_id("status");
$blog_published_value_id = elgg_get_metastring_id("published");

$blog_options = array(
	"type" => "object",
	"subtype" => "blog",
	"limit" => 5,
	"created_time_lower" => $ts_lower,
	"created_time_upper" => $ts_upper,
	"joins" => array(
		"JOIN " . $dbprefix . "metadata bm ON e.guid = bm.entity_guid"
	),
	"wheres" => array(
		"bm.name_id = " . $blog_status_name_id,
		"bm.value_id = " . $blog_published_value_id
	)
);

$blogs = elgg_get_entities($blog_options);
if (!empty($blogs)) {
	$title = elgg_view("output/url", array(
		"text" => elgg_echo("blog:blogs"),
		"href" => "blog/all",
		"is_trusted" => true
	));
	
	$latest_blogs = "";
	
	foreach ($blogs as $blog) {
		$blog_url = $blog->getURL();
		
		$latest_blogs .= "<div class='digest-blog'>";
		if ($blog->icontime) {
			$icon = elgg_view("output/img", array(
				"src" => $blog->getIconURL("medium")
			));
			$latest_blogs .= elgg_view("output/url", array(
				"text" => $icon,
				"href" => $blog_url,
				"is_trusted" => true
			));
		}
		$latest_blogs .= "<span>";
		$latest_blogs .= "<h4>";
		$latest_blogs .= elgg_view("output/url", array(
			"text" => $blog->title,
			"href" => $blog_url,
			"is_trusted" => true
		));
		$latest_blogs .= "</h4>";
		$latest_blogs .= elgg_get_excerpt($blog->description);
		$latest_blogs .= "</span>";
		$latest_blogs .= "</div>";
	}
	
	echo elgg_view_module("digest", $title, $latest_blogs);
}
