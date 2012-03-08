<?php
$digest_unsubscribe = digest_create_unsubscribe_link(get_config("site_guid"), $user);

$site_url = elgg_view("output/url", array("href" => $vars["config"]->site->url, "text" => $vars["config"]->site->name));
$digest_url = elgg_view("output/url", array("href" => $vars["url"] . "digest", "text" => elgg_echo("digest:layout:footer:update")));
	
$unsubscribe_link = $vars["area4"];
	
echo elgg_echo("digest:layout:footer:info", array($site_url));
echo "&nbsp;" . $digest_url . "<br />";
echo elgg_view("output/url", array("href" => $unsubscribe_link, "text" => elgg_echo("digest:layout:footer:unsubscribe")));