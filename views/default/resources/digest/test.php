<?php
/**
 * show a test page for the digest, this can also be used to test the styling
 */

// remove some view extensions
digest_prepare_run();

$digest = elgg_extract('digest', $vars);
$interval = elgg_extract('interval', $vars);

$group = null;

switch ($digest) {
	case 'group':
		$group_guid = elgg_extract('group_guid', $vars);
		$group = get_entity($group_guid);
		if (!($group instanceof ElggGroup)) {
			forward();
		}
		
		$header_text = elgg_get_plugin_setting('custom_text_group_header', 'digest');
		$footer_text = elgg_get_plugin_setting('custom_text_group_footer', 'digest');
		
		break;
	case 'site':
	default:
		$header_text = elgg_get_plugin_setting('custom_text_site_header', 'digest');
		$footer_text = elgg_get_plugin_setting('custom_text_site_footer', 'digest');
		
		break;
}

// set some interval settings
$ts_upper = time();

switch ($interval) {
	case DIGEST_INTERVAL_DAILY:
		$ts_lower = $ts_upper - (60 * 60 * 24);
		break;
	case DIGEST_INTERVAL_WEEKLY:
		$ts_lower = $ts_upper - (60 * 60 * 24 * 7);
		break;
	case DIGEST_INTERVAL_FORTNIGHTLY:
		$ts_lower = $ts_upper - (60 * 60 * 24 * 14);
		break;
	case DIGEST_INTERVAL_MONTHLY:
		$ts_lower = $ts_upper - (60 * 60 * 24 * 31);
		break;
	default:
		$interval = DIGEST_INTERVAL_MONTHLY;
		$ts_lower = $ts_upper - (60 * 60 * 24 * 31);
}

$user = elgg_get_logged_in_user_entity();

$params = [
	'user' => $user,
	'ts_lower' => $ts_lower,
	'ts_upper' => $ts_upper,
	'interval' => $interval,
	'group' => $group,
];
	
$content = '';

if (!empty($header_text)) {
	$content .= elgg_view_module('digest', '', elgg_format_element('div', ['class' => 'elgg-output'], $header_text));
}

$content .= elgg_view("digest/elements/{$digest}", $params);

if (!empty($footer_text)) {
	$content .= elgg_view_module('digest', '', elgg_format_element('div', ['class' => 'elgg-output'], $footer_text));
}

$msgbody = elgg_view_layout('digest', [
	'title' => elgg_get_site_entity()->name,
	'content' => $content,
	'footer' => elgg_view('digest/elements/footer', $params),
	'digest_header' => elgg_view('digest/elements/header', $params),
	'digest_online' => elgg_view('digest/elements/online', $params),
	'digest_unsubscribe' => elgg_view('digest/elements/unsubscribe', $params),
]);

echo $msgbody;

// mail the result?
if (get_input('mail')) {
	if (digest_send_mail($user, 'Test message from Digest', $msgbody, digest_get_online_url($params))) {
		echo 'mail send<br />';
	}
}
