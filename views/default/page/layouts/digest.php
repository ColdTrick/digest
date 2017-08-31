<?php

global $digest_css_contents;
if (!isset($digest_css_contents)) {
	// cache digest css as it is the same for all users / groups
	$digest_css_contents = elgg_view('css/digest/core');
}

$title = elgg_extract('title', $vars);

$head = <<<__HEAD
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<base target="_blank" />
<title>$title</title>
__HEAD;

$body = <<<__BODY
<style type="text/css">
	$digest_css_contents
</style>

<div id="digest_online">
	{$vars["digest_online"]}
</div>
<div id="digest_container">
	<div>
		<div id="digest_header">
			{$vars["digest_header"]}
		</div>
		<div id="digest_content">
			{$vars["content"]}
		</div>
	</div>
	<div id="digest_footer">
		{$vars["footer"]}
	</div>
	<div id="digest_unsubscribe">
		{$vars["digest_unsubscribe"]}
	</div>
</div>
__BODY;

echo elgg_view('page/elements/html', [
	'head' => $head,
	'body' => $body,
]);
