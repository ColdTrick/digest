<?php

$params = get_input('params', null, false);
$plugin_id = get_input('plugin_id');

$plugin = elgg_get_plugin_from_id($plugin_id);

if (empty($plugin)) {
	return elgg_error_response(elgg_echo('PluginException:InvalidID'));
}

if (empty($params) || !is_array($params)) {
	return elgg_error_response(elgg_echo('plugins:settings:save:fail'), [$plugin->getManifest()->getName()]);
}

$special_inputs = [
	'custom_text_site_header',
	'custom_text_site_footer',
	'custom_text_group_header',
	'custom_text_group_footer'
];

foreach ($params as $key => $value) {
	if (!in_array($key, $special_inputs)) {
		$value = filter_tags($value);
	}
	
	if (!$plugin->setSetting($key, $value)) {
		return elgg_error_response(elgg_echo('plugins:settings:save:fail'), [$plugin->getManifest()->getName()]);
	}
}

return elgg_ok_response();
