<?php
/**
 * display the user's digest settings
 */
gatekeeper();

$username = elgg_extract('username', $vars);

$user = $username ? get_user_by_username($username) : elgg_get_logged_in_user_entity();

if (empty($user) || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('digest:usersettings:error:user'));
}

// set correct context
elgg_push_context('settings');

// make breadcrumb
elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/{$user->username}");
elgg_push_breadcrumb(elgg_echo('digest:page_menu:settings'));

// set page owner
elgg_set_page_owner_guid($user->getGUID());

$groups = false;
if (digest_group_enabled()) {
	// get groups user is a member of
	$groups = elgg_get_entities_from_relationship([
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => $user->guid,
		'limit' => false,
		'joins' => [
			'JOIN ' . get_config('dbprefix') . 'groups_entity ge ON e.guid = ge.guid',
		],
		'order_by' => 'ge.name ASC',
	]);
}

// build page elements
$title_text = elgg_echo('digest:usersettings:title');

$body = elgg_view_layout('one_sidebar', [
	'title' => $title_text,
	'content' => elgg_view('digest/usersettings/form', [
		'user' => $user,
		'groups' => $groups,
	]),
]);

// draw page
echo elgg_view_page($title_text, $body);

// reset context
elgg_pop_context();
	