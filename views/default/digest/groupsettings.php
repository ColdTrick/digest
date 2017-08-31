<?php

$body = elgg_view_form('digest/groupsettings', [], $vars);
if (empty($body)) {
	return;
}

echo elgg_view_module('info', elgg_echo('digest'), $body);
