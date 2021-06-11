<?php
/**
* Shows the latests blogs in the Digest
*/

$ts_lower = (int) elgg_extract('ts_lower', $vars);
$ts_upper = (int) elgg_extract('ts_upper', $vars);

// only show blogs that are published
$dbprefix = elgg_get_config('dbprefix');

$blogs = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'blog',
	'limit' => 5,
	'created_time_lower' => $ts_lower,
	'created_time_upper' => $ts_upper,
	// @TODO Using literal MySQL statements in 'wheres' options parameter is deprecated. 
	// Instead use a closure that receives an instanceof of QueryBuilder and returns a composite DBAL expression
	/*
	'joins' => [
		'JOIN ' . $dbprefix . 'metadata bm ON e.guid = bm.entity_guid'
	],
	'wheres' => [
		'bm.name = \'status\'',
		'bm.value = \'published\'',
	],
	*/
	'metadata_name_value_pairs' => ['name' => 'status', 'value' => 'published'],
]);

if (empty($blogs)) {
	return;
}

$title = elgg_view('output/url', [
	'text' => elgg_echo('blog:blogs'),
	'href' => 'blog/all',
	'is_trusted' => true,
]);

$latest_blogs = '';

foreach ($blogs as $blog) {
	$blog_url = $blog->getURL();
	
	$latest_blogs .= '<div class="digest-blog">';
	if ($blog->hasIcon('medium')) {
		$icon = elgg_view('output/img', ['src' => $blog->getIconURL('medium')]);
		$latest_blogs .= elgg_view('output/url', [
			'text' => $icon,
			'href' => $blog_url,
			'is_trusted' => true,
		]);
	}
	$latest_blogs .= '<span>';
	$latest_blogs .= '<h4>';
	$latest_blogs .= elgg_view('output/url', [
		'text' => $blog->getDisplayName(),
		'href' => $blog_url,
		'is_trusted' => true,
	]);
	$latest_blogs .= '</h4>';
	$latest_blogs .= elgg_get_excerpt($blog->description);
	$latest_blogs .= '</span>';
	$latest_blogs .= '</div>';
}

echo elgg_view_module('digest', $title, $latest_blogs);
