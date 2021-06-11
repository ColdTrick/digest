<?php
/**
 * All helper functions are bundled here
 */

global $running_interval;
global $interval_ts_upper;
global $interval_ts_lower;

/**
 * Make the site digest
 *
 * @param ElggUser $user     the user to create the site digest for
 * @param string   $interval what interval
 *
 * @return bool|int true (mail sent successfull) || false (some error) || -1 (no content)
 */
function digest_site(ElggUser $user, $interval) {
	global $interval_ts_upper;
	global $interval_ts_lower;
	
	static $custom_text_header;
	static $custom_text_footer;
	
	$result = false;
	
	if (!($user instanceof ElggUser)) {
		return $result;
	}
	
	// remove some view extensions
	digest_prepare_run();
	
	// set timestamps for interval
	digest_set_interval_timestamps($interval);
	
	// store current user
	$current_user = elgg_get_logged_in_user_entity();
	
	// impersonate new user
	$session = elgg_get_session();
	$session->setLoggedInUser($user);
	
	// prepare some vars for the different views
	$vars = [
		'user' => $user,
		'ts_lower' => $interval_ts_lower,
		'ts_upper' => $interval_ts_upper,
		'interval' => $interval,
	];
	
	// get data for user
	$userdata = elgg_view('digest/elements/site', $vars);
	
	if (!empty($userdata)) {
		// check if there are custom header/footer texts
		if (!isset($custom_text_header)) {
			$custom_text_header = '';
			
			$text = elgg_get_plugin_setting('custom_text_site_header', 'digest');
			if (!empty($text)) {
				$custom_text_header = elgg_view_module('digest', '', elgg_format_element('div', ['class' => 'elgg-output'], $text));
			}
		}
		
		if (!isset($custom_text_footer)) {
			$custom_text_footer = '';
			
			$text = elgg_get_plugin_setting('custom_text_site_footer', 'digest');
			if (!empty($text)) {
				$custom_text_footer = elgg_view_module('digest', '', elgg_format_element('div', ['class' => 'elgg-output'], $text));
			}
		}
		
		// there is content so send it to the user
		$params = [
			'title' => elgg_get_site_entity()->name,
			'content' => $custom_text_header . $userdata . $custom_text_footer,
			'footer' => elgg_view('digest/elements/footer', $vars),
			'digest_header' => elgg_view('digest/elements/header', $vars),
			'digest_online' => elgg_view('digest/elements/online', $vars),
			'digest_unsubscribe' => elgg_view('digest/elements/unsubscribe', $vars),
		];
		
		// link to online view
		$digest_online_url = digest_get_online_url($vars);
		
		// message_subject
		$message_subject = elgg_echo('digest:message:title:site', [
			elgg_get_site_entity()->name,
			elgg_echo("digest:interval:{$interval}"),
		]);
		
		// message body
		$message_body = elgg_view_layout('digest', $params);
		
		// send message
		// if succesfull mail return true
		$result = digest_send_mail($user, $message_subject, $message_body, $digest_online_url);
	} else {
		// no data is still succesful
		$result = -1;
	}
	
	// to save memory
	unset($userdata);
	
	// restore current user
	if (!empty($current_user)) {
		$session->setLoggedInUser($current_user);
	} else {
		$session->invalidate();
	}
	
	// to save memory
	unset($current_user);
	
	return $result;
}

/**
 * Make group digest
 *
 * @param ElggGroup $group    the group to make the digest from
 * @param ElggUser  $user     the user to create the digest for
 * @param string    $interval which interval
 *
 * @return bool|int true (mail sent successfull) || false (some error) || -1 (no content)
 */
function digest_group(ElggGroup $group, ElggUser $user, $interval) {
	global $interval_ts_upper;
	global $interval_ts_lower;
	global $is_admin;
	
	static $custom_text_header;
	static $custom_text_footer;
	
	$result = false;
	
	// check if group digest is enabled
	if (!digest_group_enabled()) {
		return $result;
	}
	
	// do we have a group and user
	if (!($group instanceof ElggGroup) || !($user instanceof ElggUser)) {
		return $result;
	}
	
	// remove some view extensions
	digest_prepare_run();
	
	// set timestamps for interval
	digest_set_interval_timestamps($interval);
	
	// store current user
	$current_user = elgg_get_logged_in_user_entity();
	
	// impersonate new user
	$session = elgg_get_session();
	$session->setLoggedInUser($user);
	
	// prepare some vars for the different views
	$vars = [
		'user' => $user,
		'group' => $group,
		'ts_lower' => $interval_ts_lower,
		'ts_upper' => $interval_ts_upper,
		'interval' => $interval,
	];
	
	// get data for user
	$userdata = elgg_view('digest/elements/group', $vars);
	
	if (!empty($userdata)) {
		// check if there are custom header/footer texts
		if (!isset($custom_text_header)) {
			$custom_text_header = '';
			
			$text = elgg_get_plugin_setting('custom_text_group_header', 'digest');
			if (!empty($text)) {
				$custom_text_header = elgg_view_module('digest', '', elgg_format_element('div', ['class' => 'elgg-output'], $text));
			}
		}
		
		if (!isset($custom_text_footer)) {
			$custom_text_footer = '';
			
			$text = elgg_get_plugin_setting('custom_text_group_footer', 'digest');
			if (!empty($text)) {
				$custom_text_footer = elgg_view_module('digest', '', elgg_format_element('div', ['class' => 'elgg-output'], $text));
			}
		}
		
		// there is content so send it to the user
		$params = [
			'title' => elgg_get_site_entity()->name,
			'content' => $custom_text_header . $userdata . $custom_text_footer,
			'footer' => elgg_view('digest/elements/footer', $vars),
			'digest_header' => elgg_view('digest/elements/header', $vars),
			'digest_online' => elgg_view('digest/elements/online', $vars),
			'digest_unsubscribe' => elgg_view('digest/elements/unsubscribe', $vars),
		];
		
		// link to online view
		$digest_online_url = digest_get_online_url($vars);
		
		// message_subject
		$message_subject = elgg_echo('digest:message:title:group', [
			elgg_get_site_entity()->name,
			$group->name,
			elgg_echo("digest:interval:{$interval}"),
		]);
		
		// message body
		$message_body = elgg_view_layout('digest', $params);

		// send message
		// if succesfull mail return true
		$result = digest_send_mail($user, $message_subject, $message_body, $digest_online_url);
	} else {
		// no data is still succesful
		$result = -1;
	}
	
	// save memory
	unset($userdata);
	
	// restore current user
	if (!empty($current_user)) {
		$session->setLoggedInUser($current_user);
	} else {
		$session->invalidate();
	}
	
	// save memory
	unset($current_user);
	
	return $result;
}

/**
 * Sets the right upper and lower ts for digest queries
 *
 * @param string $interval the interval to set
 *
 * @throws InvalidParameterException when using an invalid interval
 *
 * @return void
 */
function digest_set_interval_timestamps($interval) {
	global $running_interval;
	global $interval_ts_upper;
	global $interval_ts_lower;
	
	if ($running_interval === $interval) {
		return;
	}
	
	$running_interval = $interval;
	
	switch ($interval) {
		case DIGEST_INTERVAL_DAILY:
			$interval_ts_lower = $interval_ts_upper - (60 * 60 * 24);
			break;
		case DIGEST_INTERVAL_WEEKLY:
			$interval_ts_lower = $interval_ts_upper - (60 * 60 * 24 * 7);
			break;
		case DIGEST_INTERVAL_FORTNIGHTLY:
			$interval_ts_lower = $interval_ts_upper - (60 * 60 * 24 * 14);
			break;
		case DIGEST_INTERVAL_MONTHLY:
			$interval_ts_lower = $interval_ts_upper - (60 * 60 * 24 * 31);
			break;
		default:
			throw new InvalidParameterException(elgg_echo('digest:interval:error') . ': ' . $interval);
			break;
	}
}

/**
 * Send out the generated digest
 *
 * @param ElggUser $user       the user to send the digest to
 * @param string   $subject    message subject
 * @param string   $html_body  html message
 * @param string   $plain_link plaintext message
 *
 * @return bool
 */
function digest_send_mail(ElggUser $user, $subject, $html_body, $plain_link = "") {
	global $digest_mail_send;
	
	// validate input
	if (!($user instanceof ElggUser) || empty($subject) || empty($html_body)) {
		return false;
	}
	
	// convert css
	$transform = html_email_handler_css_inliner($html_body);
	if (!empty($transform)) {
		$html_body = $transform;
	}
	
	// email settings - prevent sending to any other address than the recipient personn
	$to = html_email_handler_make_rfc822_address($user, false);
	
	$plaintext_message = '';
	if (!empty($plain_link)) {
		// make a plaintext message for non HTML users
		$plaintext_message = elgg_echo('digest:mail:plaintext:description', [$plain_link]);
	}
	
	// send out the mail
	$options = [
		'to' => $to,
		'subject' => $subject,
		'html_message' => $html_body,
		'plaintext_message' => $plaintext_message,
	];
	
	if (html_email_handler_send_email($options)) {
		if (empty($digest_mail_send)) {
			$digest_mail_send = 1;
		} else {
			$digest_mail_send++;
		}
		
		return true;
	}
	
	return false;
}

/**
 * Convert a time in microseconds to something readable
 *
 * @param int $microtime time value
 *
 * @return bool|string false | human readable time value
 */
function digest_readable_time($microtime) {
	$time_array = [
		'hours' => 0,
		'minutes' => 0,
		'seconds' => 0,
		'microseconds' => 0,
	];
	
	$ts = (int) $microtime;
	$time_array['microseconds'] = $microtime - $ts;
	
	$time_array['seconds'] = ($ts % 60);
	$ts = $ts - $time_array['seconds'];
	
	$time_array['minutes'] = (($ts % 3600) / 60);
	$time_array['hours'] = (($ts - ($time_array['minutes'] * 60)) / 3600);
	
	// build result
	$result = '';
	if ($time_array['hours']) {
		$result = $time_array['hours'] . ' ' . elgg_echo('digest:readable:time:hours');
	}
	
	if ($time_array['minutes']) {
		$result .= ' ' . $time_array['minutes'] . ' '  . elgg_echo('digest:readable:time:minutes');
	} elseif (!empty($result)) {
		$result .= ' 00 M';
	}
	
	if ($time_array['seconds']) {
		$result .= ' ' . $time_array['seconds'] . ' '  . elgg_echo('digest:readable:time:seconds');
	} elseif (!empty($result)) {
		$result .= ' 00 sec';
	}
	
	if ($time_array['microseconds']) {
		$result .= ' ' . round($time_array['microseconds'] * 1000) . ' ' . elgg_echo('digest:readable:time:mseconds');
	}
	
	return trim($result);
}

/**
 * Check if group digest is enabled
 *
 * @return bool
 */
function digest_group_enabled() {
	static $result;
	
	if (isset($result)) {
		return $result;
	}
	
	$result = false;
	if (elgg_get_plugin_setting('group_production', 'digest') === 'yes') {
		$result = true;
	}
	
	return $result;
}

/**
* Check if site digest is enabled
*
* @return bool
*/
function digest_site_enabled() {
	static $result;

	if (isset($result)) {
		return $result;
	}
	
	$result = false;
	if (elgg_get_plugin_setting('in_production', 'digest') === 'yes') {
		$result = true;
	}

	return $result;
}

/**
 * create an unsubscribe link for a digest
 *
 * @param int      $guid the guid to unsubscribe from
 * @param ElggUser $user the user to unsubscribe
 *
 * @return bool|string false | unsubscribe link
 */
function digest_create_unsubscribe_link($guid, ElggUser $user) {
	
	$guid = sanitise_int($guid, false);
	
	if (empty($guid) || !($user instanceof ElggUser)) {
		return false;
	}
	
	$site_secret = _elgg_services()->siteSecret->get();
	
	$code = md5($guid . $site_secret . $user->getGUID() . $user->time_created);
	
	$url = elgg_http_add_url_query_elements('digest/unsubscribe', [
		'guid' => $guid,
		'user_guid'  => $user->getGUID(),
		'code' => $code,
	]);
	
	return elgg_normalize_url($url);
}

/**
 * Validate an unsubscribe code
 *
 * @param int      $guid the guid to unsubscribe from
 * @param ElggUser $user the user to unsubscribe
 * @param string   $code the supplied unscubscribe code
 *
 * @return bool
 */
function digest_validate_unsubscribe_code($guid, ElggUser $user, $code) {
	
	$guid = sanitise_int($guid, false);
	
	if (empty($guid) || !($user instanceof ElggUser) || empty($code)) {
		return false;
	}
	
	$site_secret = _elgg_services()->siteSecret->get();
	
	$valid_code = md5($guid . $site_secret . $user->getGUID() . $user->time_created);
	
	return ($code === $valid_code);
}

/**
 * Undo some extension to view by other plugins
 *
 * @param bool $refresh redo the unextending
 *
 * @return void
 */
function digest_prepare_run($refresh = false) {
	static $run_once;
	
	if (isset($run_once) && ($refresh !== true)) {
		return;
	}
	
	// only let this happen once
	$run_once = true;
		
	// let other plugins know they need to add their views/css
	elgg_trigger_event('prepare', 'digest');
	
	// undo extensions
	// trigger pagesetup
	elgg_view_title("dummy");
	
	// remove river extensions
	$views_service = _elgg_services()->views;
	
	$inspector_data = $views_service->getInspectorData();
	if (!empty($inspector_data['extensions'])) {
		foreach ($inspector_data['extensions'] as $view => $extensions) {
			if (stristr($view, 'river/') === false) {
				continue;
			}
			
			foreach ($extensions as $prio => $extension) {
				$views_service->unextendView($view, $extension);
			}
		}
	}
	
	// undo registrations on menu hooks
	elgg_clear_plugin_hook_handlers('register', 'menu:river');
	elgg_clear_plugin_hook_handlers('prepare', 'menu:river');
	
	elgg_clear_plugin_hook_handlers('register', 'menu:entity');
	elgg_clear_plugin_hook_handlers('prepare', 'menu:entity');
	
	// register hooks
	elgg_register_plugin_hook_handler('view_vars', 'icon/user/default', '\ColdTrick\Digest\Views::preventUserHoverMenu');
	elgg_register_plugin_hook_handler('view_vars', 'river/elements/body', '\ColdTrick\Digest\Views::preventRiverResponses');
}

/**
 * get the default digest interval for the site
 *
 * @return string
 */
function digest_get_default_site_interval() {
	static $result;
	
	if (isset($result)) {
		return $result;
	}
	
	$result = DIGEST_INTERVAL_NONE;
	
	$setting = elgg_get_plugin_setting('site_default', 'digest');
	if (!empty($setting)) {
		$result = $setting;
	}
	
	return $result;
}

/**
 * get the default digest interval for the groups
 *
 * @return string
 */
function digest_get_default_group_interval() {
	static $result;
	
	if (isset($result)) {
		return $result;
	}
	
	$result = DIGEST_INTERVAL_NONE;
	
	$setting = elgg_get_plugin_setting('group_default', 'digest');
	if (!empty($setting)) {
		$result = $setting;
	}
	
	return $result;
}

/**
 * Get the online view link to a digest
 *
 * @param array $params supplied params
 *
 * @return string|bool
 */
function digest_get_online_url($params = []) {
	
	if (empty($params) || !is_array($params)) {
		return false;
	}
	
	$base_url = elgg_get_site_url() . 'digest/show';
	
	$url_params = [
		'ts_lower' => $params['ts_lower'],
		'ts_upper' => $params['ts_upper'],
		'interval' => $params['interval'],
	];
	
	if (elgg_extract('group', $params) instanceof ElggGroup) {
		$url_params['group_guid'] = $params['group']->getGUID();
	}
	
	return elgg_http_add_url_query_elements($base_url, $url_params);
}

/**
 * Get the distribution days for the digest interval
 *
 * @param string $interval the digest interval
 *
 * @return int
 */
function digest_get_default_distribution($interval) {
	static $distributions;
	
	if (!isset($distributions)) {
		$distributions = array();
	}
	
	if (!empty($interval) && in_array($interval, array(DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY))) {
		
		if (!isset($distributions[$interval])) {
			$setting = elgg_get_plugin_setting($interval . "_distribution", "digest");
			if (!empty($setting)) {
				if (is_numeric($setting)) {
					$setting = (int) $setting;
				}
				$distributions[$interval] = $setting;
			} else {
				// no setting or 0 (zero)
				if ($interval == DIGEST_INTERVAL_MONTHLY) {
					$distributions[$interval] = 1; // first day of the month
				} else {
					$distributions[$interval] = 0; // first day of the week (sunday)
				}
			}
		}
	}
	
	return $distributions[$interval];
}

/**
 * Get all the user who wish to recieve the site digest
 *
 * @param array $settings supplied settings
 *
 * @return array
 */
function digest_get_site_users($settings) {
	global $interval_ts_upper;
	
	$site = elgg_get_site_entity();
	$dbprefix = elgg_get_config("dbprefix");
	
	$dotw = (int) date("w", $interval_ts_upper); // Day of the Week (0 (sunday) - 6 (saturday))
	$dotm = (int) date("j", $interval_ts_upper); // Day of the Month (1 - 31)
	$odd_week = (date("W", $interval_ts_upper) & 1); // Odd weeknumber or not
	
	$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
	if (!$odd_week) {
		$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
	}
	
	$include_never_logged_in = (bool) elgg_extract("include_never_logged_in", $settings, false);
	
	$query = "SELECT ue.guid, ps.value as user_interval";
	$query .= " FROM " . $dbprefix . "users_entity ue";
	$query .= " JOIN " . $dbprefix . "entities e ON ue.guid = e.guid";
	$query .= " JOIN " . $dbprefix . "private_settings ps ON ue.guid = ps.entity_guid";
	$query .= " JOIN " . $dbprefix . "entity_relationships r ON ue.guid = r.guid_one";
	$query .= " WHERE (r.guid_two = " . $site->getGUID() . " AND r.relationship = 'member_of_site')"; // user must be a member of the site
	$query .= " AND (e.enabled = 'yes' AND ue.banned = 'no'"; // user must be enabled and not banned
	if (!$include_never_logged_in) {
		$query .= " AND ue.last_login > 0"; // exclude all users that have never logged in
	}
	$query .= ")";
	$query .= " AND (ps.name = 'digest_" . $site->getGUID() . "')"; // check the digest setting for this site
	$query .= " AND (ps.value = '" . DIGEST_INTERVAL_DAILY . "'"; // user has daily delivery
	
	// check the weekly interval settings
	if (($setting = $settings[DIGEST_INTERVAL_WEEKLY]) === "distributed") {
		// delivery is distributed, this means user_guid % 7 = day of the week
		$query .= " OR (ps.value = '" . DIGEST_INTERVAL_WEEKLY . "' AND (ue.guid % 7) = " . $dotw . ")";
	} elseif ($setting === $dotw) {
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_WEEKLY . "'";
	}
	
	// check the fortnightly interval settings
	if (($setting = $settings[DIGEST_INTERVAL_FORTNIGHTLY]) === "distributed") {
		// delivery is distributed, this means user_guid % 14 = day of the week
		$query .= " OR (ps.value = '" . DIGEST_INTERVAL_FORTNIGHTLY . "' AND (ue.guid % 14) = " . $dotfn . ")";
	} elseif ($odd_week && ($setting === $dotw)) {
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_FORTNIGHTLY . "'";
	}
	
	// check the monthly interval settings
	if (($setting = $settings[DIGEST_INTERVAL_MONTHLY]) === "distributed") {
		// delivery is distributed, this means (user_guid % 28) + 1 = day of the month
		$query .= " OR (ps.value = '" . DIGEST_INTERVAL_MONTHLY . "' AND (((ue.guid % 28) + 1) = " . $dotm . "))";
	} elseif ($setting === $dotm) {
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_MONTHLY . "'";
	}
	
	$query .= ")";
	
	// check default site setting
	if ($settings[DIGEST_INTERVAL_DEFAULT] != DIGEST_INTERVAL_NONE) {
		// should the default run today
		if ($settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_DAILY // daily interval
			|| ($settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_WEEKLY && // weekly interval
					($settings[DIGEST_INTERVAL_WEEKLY] === "distributed" || $settings[DIGEST_INTERVAL_WEEKLY] === $dotw))
			|| ($settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_FORTNIGHTLY && // fortnightly interval
					($settings[DIGEST_INTERVAL_FORTNIGHTLY] === "distributed" || ($odd_week && $settings[DIGEST_INTERVAL_FORTNIGHTLY] === $dotw)))
			|| ($settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_MONTHLY && // monthly interval
					($settings[DIGEST_INTERVAL_MONTHLY] === "distributed" || $settings[DIGEST_INTERVAL_MONTHLY] === $dotm))
		) {
			
			// there is a default site setting
			$query .= " UNION ALL";
			
			$query .= " SELECT ue2.guid, '" . $settings[DIGEST_INTERVAL_DEFAULT] . "' as user_interval";
			$query .= " FROM " . $dbprefix . "users_entity ue2";
			$query .= " JOIN " . $dbprefix . "entities e2 ON ue2.guid = e2.guid";
			$query .= " JOIN " . $dbprefix . "entity_relationships r2 ON ue2.guid = r2.guid_one";
			$query .= " WHERE (r2.guid_two = " . $site->getGUID() . " AND r2.relationship = 'member_of_site')"; // user must be a member of the site
			$query .= " AND (e2.enabled = 'yes' AND ue2.banned = 'no'"; // user must be enabled and not banned
			if (!$include_never_logged_in) {
				$query .= " AND ue2.last_login > 0"; // exclude all users that have never logged in
			}
			$query .= ")";
			$query .= " AND ue2.guid NOT IN (";
			$query .= "SELECT DISTINCT entity_guid";
			$query .= " FROM " . $dbprefix . "private_settings";
			$query .= " WHERE name = 'digest_" . $site->getGUID() . "'";
			$query .= ")";
			
			switch ($settings[DIGEST_INTERVAL_DEFAULT]) {
				case DIGEST_INTERVAL_DAILY:
					// no further limit
					break;
				case DIGEST_INTERVAL_WEEKLY:
					if ($settings[DIGEST_INTERVAL_WEEKLY] === "distributed") {
						// delivery is distributed, this means user_guid % 7 = day of the week
						$query .= " AND (ue2.guid % 7) = " . $dotw;
					}
					break;
				case DIGEST_INTERVAL_FORTNIGHTLY:
					if ($settings[DIGEST_INTERVAL_FORTNIGHTLY] === "distributed") {
						// delivery is distributed, this means user_guid % 14 = day of the week
						$query .= " AND (ue2.guid % 14) = " . $dotfn;
					}
					break;
				case DIGEST_INTERVAL_MONTHLY:
					if ($settings[DIGEST_INTERVAL_MONTHLY] === "distributed") {
						// delivery is distributed, this means (user_guid % 28) + 1 = day of the month
						$query .= " AND ((ue2.guid % 28) + 1) = " . $dotm;
					}
					break;
			}
		}
	}
	
	if ($limit = (int) elgg_extract("limit", $settings, 0)) {
		$offset = (int) elgg_extract("offset", $settings, 0);
		
		$query .= " LIMIT " . $offset . ", " . $limit;
	}
	
	// execute the query
	return get_data($query, "digest_row_to_array");
}

/**
 * Get all the user who wish to recieve the group digest
 *
 * @param int   $group_guid              the group guid to check
 * @param array $interval_settings       interval setting
 * @param bool  $include_never_logged_in include never logged in users
 *
 * @return array
 */
function digest_get_group_users($group_guid, $interval_settings, $include_never_logged_in = false) {
	global $interval_ts_upper;
	
	$dbprefix = elgg_get_config("dbprefix");
	
	$dotw = (int) date("w", $interval_ts_upper); // Day of the Week (0 (sunday) - 6 (saturday))
	$dotm = (int) date("j", $interval_ts_upper); // Day of the Month (1 - 31)
	$odd_week = (date("W", $interval_ts_upper) & 1); // Odd weeknumber or not
	
	$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
	if (!$odd_week) {
		$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
	}
	
	$query = "SELECT ue.guid, ps.value as user_interval";
	$query .= " FROM " . $dbprefix . "users_entity ue";
	$query .= " JOIN " . $dbprefix . "entities e ON ue.guid = e.guid";
	$query .= " JOIN " . $dbprefix . "private_settings ps ON ue.guid = ps.entity_guid";
	$query .= " JOIN " . $dbprefix . "entity_relationships r ON ue.guid = r.guid_one";
	$query .= " WHERE (r.guid_two = " . $group_guid . " AND r.relationship = 'member')"; // user must be a member of the group
	$query .= " AND (e.enabled = 'yes' AND ue.banned = 'no'"; // user must be enabled and not banned
	if (!$include_never_logged_in) {
		$query .= " AND ue.last_login > 0"; // exclude all users that have never logged in
	}
	$query .= ")";
	$query .= " AND (ps.name = 'digest_" . $group_guid . "')"; // check the digest setting for this group
	$query .= " AND (ps.value = '" . DIGEST_INTERVAL_DAILY . "'"; // user has daily delivery
	
	// check the weekly interval settings
	if ((($setting = $interval_settings[DIGEST_INTERVAL_WEEKLY]) === "distributed") && (($group_guid % 7) === $dotw)) {
		// delivery is distributed, this means group_guid % 7 = day of the week
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_WEEKLY . "'";
	} elseif ($setting === $dotw) {
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_WEEKLY . "'";
	}
	
	// check the fortnightly interval settings
	if ((($setting = $interval_settings[DIGEST_INTERVAL_FORTNIGHTLY]) === "distributed") && (($group_guid % 14) === $dotfn)) {
		// delivery is distributed, this means group_guid % 14 = day of the week
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_FORTNIGHTLY . "'";
	} elseif ($odd_week && ($setting === $dotw)) {
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_FORTNIGHTLY . "'";
	}
	
	// check the monthly interval settings
	if ((($setting = $interval_settings[DIGEST_INTERVAL_MONTHLY]) === "distributed") && ((($group_guid % 28) + 1) === $dotm)) {
		// delivery is distributed, this means (group_guid % 28) + 1 = day of the month
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_MONTHLY . "'";
	} elseif ($setting === $dotm) {
		$query .= " OR ps.value = '" . DIGEST_INTERVAL_MONTHLY . "'";
	}
	
	$query .= ")";
	
	// check default group setting
	if ($interval_settings[DIGEST_INTERVAL_DEFAULT] != DIGEST_INTERVAL_NONE) {
		// should the default run today
		if ($interval_settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_DAILY // daily interval
		|| ($interval_settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_WEEKLY && // weekly interval
				(($interval_settings[DIGEST_INTERVAL_WEEKLY] === "distributed" && (($group_guid % 7) === $dotw)) || $interval_settings[DIGEST_INTERVAL_WEEKLY] === $dotw))
		|| ($interval_settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_FORTNIGHTLY && // fortnightly interval
				(($interval_settings[DIGEST_INTERVAL_FORTNIGHTLY] === "distributed" && (($group_guid % 14) === $dotfn)) || ($odd_week && $interval_settings[DIGEST_INTERVAL_FORTNIGHTLY] === $dotw)))
		|| ($interval_settings[DIGEST_INTERVAL_DEFAULT] === DIGEST_INTERVAL_MONTHLY && // monthly interval
				(($interval_settings[DIGEST_INTERVAL_MONTHLY] === "distributed" && ((($group_guid % 28) + 1) === $dotm)) || $interval_settings[DIGEST_INTERVAL_MONTHLY] === $dotm))
		) {
	
			// there is a default group setting
			$query .= " UNION ALL";
	
			$query .= " SELECT ue2.guid, '" . $interval_settings[DIGEST_INTERVAL_DEFAULT] . "' as user_interval";
			$query .= " FROM " . $dbprefix . "users_entity ue2";
			$query .= " JOIN " . $dbprefix . "entities e2 ON ue2.guid = e2.guid";
			$query .= " JOIN " . $dbprefix . "entity_relationships r2 ON ue2.guid = r2.guid_one";
			$query .= " WHERE (r2.guid_two = " . $group_guid . " AND r2.relationship = 'member')"; // user must be a member of the group
			$query .= " AND (e2.enabled = 'yes' AND ue2.banned = 'no'"; // user must be enabled and not banned
			if (!$include_never_logged_in) {
				$query .= " AND ue2.last_login > 0"; // exclude all users that have never logged in
			}
			$query .= ")";
			$query .= " AND ue2.guid NOT IN (";
			$query .= "SELECT DISTINCT entity_guid";
			$query .= " FROM " . $dbprefix . "private_settings";
			$query .= " WHERE name = 'digest_" . $group_guid . "'";
			$query .= ")";
		}
	}
	
	// execute the query
	return get_data($query, "digest_row_to_array");
}

/**
 * Custom sql callback to convert rows to arrays
 *
 * @param stdObj $row the row to convert
 *
 * @return array
 */
function digest_row_to_array($row) {
	return (array) $row;
}

/**
 * Stub for the site statistics
 *
 * @return array
 */
function digest_prepare_site_statistics() {
	return array(
		DIGEST_INTERVAL_DAILY => array(
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		DIGEST_INTERVAL_WEEKLY => array(
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		DIGEST_INTERVAL_FORTNIGHTLY => array(
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		DIGEST_INTERVAL_MONTHLY => array(
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		"general" => array(
			"users" => 0,
			"mails" => 0,
			"mts_start_digest" => 0,
			"ts_start_cron" => 0,
			"mts_user_selection_done" => 0,
			"mts_end_digest" => 0,
			"peak_memory_start" => 0,
			"peak_memory_end" => 0,
			"server_name" => php_uname("n")
		)
	);
}

/**
 * Save the site statistics
 *
 * @param array $stats     the stats to save
 * @param int   $timestamp the timestamp
 * @param int   $fork_id   the fork id (for multicore support)
 *
 * @return bool
 */
function digest_save_site_statistics($stats, $timestamp, $fork_id = 0) {
	$dotw = (int) date("w", $timestamp); // Day of the Week (0 (sunday) - 6 (saturday))
	$dotm = (int) date("j", $timestamp); // Day of the Month (1 - 31)
	$odd_week = (date("W", $timestamp) & 1); // Odd weeknumber or not
	
	$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
	if (!$odd_week) {
		$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
	}
	
	// get saved site statistics
	if ($site_stats = elgg_get_plugin_setting("site_statistics", "digest")) {
		$site_stats = json_decode($site_stats, true);
	} else {
		$site_stats = array(
			DIGEST_INTERVAL_DAILY => array(),
			DIGEST_INTERVAL_WEEKLY => array(),
			DIGEST_INTERVAL_FORTNIGHTLY => array(),
			DIGEST_INTERVAL_MONTHLY => array(),
			"general" => array()
		);
	}
	
	// convert collected stats to correct format
	$site_stats[DIGEST_INTERVAL_DAILY][$fork_id] = $stats[DIGEST_INTERVAL_DAILY];
	$site_stats[DIGEST_INTERVAL_WEEKLY]["day_" . $dotw . "_" . $fork_id] = $stats[DIGEST_INTERVAL_WEEKLY];
	$site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["day_" . $dotfn . "_" . $fork_id] = $stats[DIGEST_INTERVAL_FORTNIGHTLY];
	$site_stats[DIGEST_INTERVAL_MONTHLY]["day_" . $dotm . "_" . $fork_id] = $stats[DIGEST_INTERVAL_MONTHLY];
	$site_stats["general"][$fork_id] = $stats["general"];
	
	// save new stats
	return elgg_set_plugin_setting("site_statistics", json_encode($site_stats), "digest");
}

/**
 * Stub for the group statistics
 *
 * @return array
 */
function digest_prepare_group_statistics() {
	return array(
		DIGEST_INTERVAL_DAILY => array(
			"groups" => array(),
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		DIGEST_INTERVAL_WEEKLY => array(
			"groups" => array(),
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		DIGEST_INTERVAL_FORTNIGHTLY => array(
			"groups" => array(),
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		DIGEST_INTERVAL_MONTHLY => array(
			"groups" => array(),
			"users" => 0,
			"mails" => 0,
			"total_memory" => 0,
			"total_time" => 0
		),
		"general" => array(
			"groups" => 0,
			"users" => 0,
			"mails" => 0,
			"mts_start_digest" => 0,
			"ts_start_cron" => 0,
			"mts_group_selection_done" => 0,
			"total_time_user_selection" => 0,
			"mts_end_digest" => 0,
			"peak_memory_start" => 0,
			"peak_memory_end" => 0,
			"total_memory" => 0,
			"server_name" => php_uname("n")
		)
	);
}

/**
 * Save the group statistics
 *
 * @param array $stats     the stats to save
 * @param int   $timestamp the timestamp
 * @param int   $fork_id   the fork id (for multicore support)
 *
 * @return bool
 */
function digest_save_group_statistics($stats, $timestamp, $fork_id = 0) {
	$dotw = (int) date("w", $timestamp); // Day of the Week (0 (sunday) - 6 (saturday))
	$dotm = (int) date("j", $timestamp); // Day of the Month (1 - 31)
	$odd_week = (date("W", $timestamp) & 1); // Odd weeknumber or not
	
	$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
	if (!$odd_week) {
		$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
	}
	
	// get saved site statistics
	if ($group_stats = elgg_get_plugin_setting("group_statistics", "digest")) {
		$group_stats = json_decode($group_stats, true);
	} else {
		$group_stats = array(
			DIGEST_INTERVAL_DAILY => array(),
			DIGEST_INTERVAL_WEEKLY => array(),
			DIGEST_INTERVAL_FORTNIGHTLY => array(),
			DIGEST_INTERVAL_MONTHLY => array(),
			"general" => array()
		);
	}
	
	// convert group guids to count
	$stats[DIGEST_INTERVAL_DAILY]["groups"] = count($stats[DIGEST_INTERVAL_DAILY]["groups"]);
	$stats[DIGEST_INTERVAL_WEEKLY]["groups"] = count($stats[DIGEST_INTERVAL_WEEKLY]["groups"]);
	$stats[DIGEST_INTERVAL_FORTNIGHTLY]["groups"] = count($stats[DIGEST_INTERVAL_FORTNIGHTLY]["groups"]);
	$stats[DIGEST_INTERVAL_MONTHLY]["groups"] = count($stats[DIGEST_INTERVAL_MONTHLY]["groups"]);
	
	// convert collected stats to correct format
	$group_stats[DIGEST_INTERVAL_DAILY][$fork_id] = $stats[DIGEST_INTERVAL_DAILY];
	$group_stats[DIGEST_INTERVAL_WEEKLY]["day_" . $dotw . "_" . $fork_id] = $stats[DIGEST_INTERVAL_WEEKLY];
	$group_stats[DIGEST_INTERVAL_FORTNIGHTLY]["day_" . $dotfn . "_" . $fork_id] = $stats[DIGEST_INTERVAL_FORTNIGHTLY];
	$group_stats[DIGEST_INTERVAL_MONTHLY]["day_" . $dotm . "_" . $fork_id] = $stats[DIGEST_INTERVAL_MONTHLY];
	$group_stats["general"][$fork_id] = $stats["general"];
	
	// save new stats
	return elgg_set_plugin_setting("group_statistics", json_encode($group_stats), "digest");
}

/**
 * Combine different stats to one result
 *
 * @param array $stats the stats to combine
 *
 * @return array
 */
function digest_compress_statistics($stats) {
	
	// combine the interval stats
	foreach (array(DIGEST_INTERVAL_DAILY, DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY) as $interval) {
		$temp_stats = array();
		
		if (!empty($stats[$interval])) {
			
			foreach ($stats[$interval] as $day_fork_id) {
				
				foreach ($day_fork_id as $key => $value) {
					if (!isset($temp_stats[$key])) {
						$temp_stats[$key] = 0;
					}
					$temp_stats[$key] += $value;
				}
			}
		}
		
		$stats[$interval] = $temp_stats;
	}
	
	// combine all general stats
	$combined_stats = array();
	
	foreach ($stats["general"] as $fork_id => $info) {
		
		if (!empty($info)) {
			foreach ($info as $key => $value) {
				
				switch ($key) {
					case "users":
					case "mails":
					case "groups":
					case "total_time_user_selection":
					case "total_memory":
						// need total count
						if (!isset($combined_stats[$key])) {
							$combined_stats[$key] = 0;
						}
						
						$combined_stats[$key] += $value;
						break;
					case "mts_start_digest":
					case "ts_start_cron":
					case "peak_memory_start":
						// needs minimum
						if (!isset($combined_stats[$key])) {
							$combined_stats[$key] = $value;
						} else {
							$combined_stats[$key] = min($combined_stats[$key], $value);
						}
						break;
					case "mts_user_selection_done":
					case "mts_group_selection_done":
					case "mts_end_digest":
					case "peak_memory_end":
						// needs maximum
						if (!isset($combined_stats[$key])) {
							$combined_stats[$key] = $value;
						} else {
							$combined_stats[$key] = max($combined_stats[$key], $value);
						}
						break;
					default:
						$combined_stats[$key] = $value;
						break;
				}
			}
		}
	}
	
	$stats["general"] = $combined_stats;
	
	return $stats;
}

/**
 * Create a secret code to prevent misuse of the commandline options
 *
 * @return string
 */
function digest_generate_commandline_secret() {
	static $result;
	
	if (isset($result)) {
		return $result;
	}
	
	$digest_plugin = elgg_get_plugin_from_id('digest');
	
	$hmac = elgg_build_hmac([
		$digest_plugin->guid,
		$digest_plugin->time_created,
	]);
	
	$result = $hmac->getToken();
	
	return $result;
}

/**
 * validate a secret code to allow the commandline to be used
 *
 * @param string $secret the code to check
 *
 * @return bool
 */
function digest_validate_commandline_secret($secret) {
	
	if (empty($secret)) {
		return false;
	}
	
	$correct_secret = digest_generate_commandline_secret();
	
	return ($secret === $correct_secret);
}

/**
 * Start processing the digest
 *
 * @param array $settings the settings to be used
 *
 * @return void
 */
function digest_process($settings) {
	global $interval_ts_upper;
	
	// Make sending process safer by disabling max_execution_time (avoids breaks if set too low)
	set_time_limit(0);
	
	// ElggBatch would disable the query cache automatically, but we cannot use it because
	// we are not querying entities. Therefore the cache has to be disabled manually.
	_elgg_services()->db->disableQueryCache();
	
	$interval_ts_upper = (int) elgg_extract("timestamp", $settings, time());
	$fork_id = (int) elgg_extract("fork_id", $settings, 0);
	
	// should new users be included
	$never_logged_in = false;
	if (elgg_get_plugin_setting("include_never_logged_in", "digest") == "yes") {
		$never_logged_in = true;
	}
		
	// should the site digest be sent
	if (digest_site_enabled()) {
		// prepare stats logging
		$site_stats = digest_prepare_site_statistics();
	
		// log some beginning stats
		$site_stats["general"]["mts_start_digest"] = microtime(true);
		$site_stats["general"]["ts_start_cron"] = $interval_ts_upper;
		$site_stats["general"]["peak_memory_start"] = memory_get_peak_usage(false);
	
		$site_intervals = array(
			DIGEST_INTERVAL_DEFAULT => digest_get_default_site_interval(),
			DIGEST_INTERVAL_WEEKLY => digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY),
			DIGEST_INTERVAL_FORTNIGHTLY => digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY),
			DIGEST_INTERVAL_MONTHLY => digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY),
			"include_never_logged_in" => $never_logged_in,
			"offset" => (int) elgg_extract("site_offset", $settings, 0),
			"limit" => (int) elgg_extract("site_limit", $settings, 0)
		);
		
		// find users
		$users = digest_get_site_users($site_intervals);
		if (!empty($users)) {
			// log selection time
			$site_stats["general"]["mts_user_selection_done"] = microtime(true);
				
			$stats_last_memory = memory_get_usage(false);
				
			// process users
			foreach ($users as $user_setting) {
				// stat logging
				$site_stats[$user_setting["user_interval"]]["users"]++;
				$site_stats["general"]["users"]++;
	
				// sent site digest for this user
				$user = get_user($user_setting["guid"]);
	
				// log start time
				$stats_mts_before = microtime(true);
	
				// sent out the digest
				if (digest_site($user, $user_setting["user_interval"]) === true) {
					// mail was sent
					$site_stats[$user_setting["user_interval"]]["mails"]++;
					$site_stats["general"]["mails"]++;
				}
	
				// stats logging
				$site_stats[$user_setting["user_interval"]]["total_time"] += (microtime(true) - $stats_mts_before);
	
				unset($user);
	
				// stats logging of memory leak
				$stats_current_memory = memory_get_usage(false);
				$site_stats[$user_setting["user_interval"]]["total_memory"] += ($stats_current_memory - $stats_last_memory);
				$stats_last_memory = $stats_current_memory;
			}
		} else {
			// log selection time
			$site_stats["general"]["mts_user_selection_done"] = microtime(true);
		}
	
		// cleanup some stuff
		unset($users);
		unset($site_intervals);
	
		// log some end stats
		$site_stats["general"]["mts_end_digest"] = microtime(true);
		$site_stats["general"]["peak_memory_end"] = memory_get_peak_usage(false);
	
		// save stats logging
		digest_save_site_statistics($site_stats, $interval_ts_upper, $fork_id);
		unset($site_stats);
	}
		
	// should the group digest be sent
	if (digest_group_enabled()) {
		// prepare stats logging
		$group_stats = digest_prepare_group_statistics();
	
		// log some beginning stats
		$group_stats["general"]["mts_start_digest"] = microtime(true);
		$group_stats["general"]["ts_start_cron"] = $interval_ts_upper;
		$group_stats["general"]["peak_memory_start"] = memory_get_peak_usage(false);
	
		// prepare group options
		$options = array(
			"type" => "group",
			"limit" => false,
			"callback" => function($row) {
				return (int) $row->guid;
			},
		);
		
		if ($limit = (int) elgg_extract("group_limit", $settings, 0)) {
			$options["limit"] = $limit;
			$options["offset"] = (int) elgg_extract("group_offset", $settings, 0);
		}
	
		$group_intervals = array(
			DIGEST_INTERVAL_WEEKLY => digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY),
			DIGEST_INTERVAL_FORTNIGHTLY => digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY),
			DIGEST_INTERVAL_MONTHLY => digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY)
		);
	
		// ignore access to get all groups
		$ia = elgg_set_ignore_access(true);
	
		$group_guids = elgg_get_entities($options);
		if (!empty($group_guids)) {
			// log selection time
			$group_stats["general"]["mts_group_selection_done"] = microtime(true);
	
			$stats_last_group_memory = memory_get_usage(false);
				
			foreach ($group_guids as $group_guid) {
				// stats logging
				$group_stats["general"]["groups"]++;
	
				// make sure we can get the group
				elgg_set_ignore_access(true);
	
				// get group
				$group = get_entity($group_guid);
	
				// get group default interval
				$group_interval = $group->digest_interval;
	
				if (empty($group_interval)) {
					// group has no interval, so fallback to site default
					$group_interval = digest_get_default_group_interval();
				}
	
				$group_intervals[DIGEST_INTERVAL_DEFAULT] = $group_interval;
	
				// restore access
				elgg_set_ignore_access($ia);
	
				$stats_begin_user_selection = microtime(true);
	
				$users = digest_get_group_users($group_guid, $group_intervals, $never_logged_in);
				if (!empty($users)) {
					// stats loggin
					$group_stats["general"]["total_time_user_selection"] += (microtime(true) - $stats_begin_user_selection);
						
					$stats_last_memory = memory_get_usage(false);
						
					// process users
					foreach ($users as $user_setting) {
						// stat logging
						$group_stats[$user_setting["user_interval"]]["users"]++;
						if (!in_array($group_guid, $group_stats[$user_setting["user_interval"]]["groups"])) {
							$group_stats[$user_setting["user_interval"]]["groups"][] = $group_guid;
						}
						$group_stats["general"]["users"]++;
	
						// get the user
						$user = get_user($user_setting["guid"]);
	
						// log start time
						$stats_mts_before = microtime(true);
	
						// sent digest
						if (digest_group($group, $user, $user_setting["user_interval"]) === true) {
							// mail was sent
							$group_stats[$user_setting["user_interval"]]["mails"]++;
							$group_stats["general"]["mails"]++;
						}
	
						// stats logging
						$group_stats[$user_setting["user_interval"]]["total_time"] += (microtime(true) - $stats_mts_before);
	
						unset($user);
	
						// stats logging of memory leak
						$stats_current_memory = memory_get_usage(false);
						$group_stats[$user_setting["user_interval"]]["total_memory"] += ($stats_current_memory - $stats_last_memory);
						$stats_last_memory = $stats_current_memory;
					}
				} else {
					// stats logging
					$group_stats["general"]["total_time_user_selection"] += (microtime(true) - $stats_begin_user_selection);
				}
	
				unset($group);
	
				// stats logging of memory leak
				$stats_current_group_memory = memory_get_usage(false);
				$group_stats["general"]["total_memory"] += ($stats_current_group_memory - $stats_last_group_memory);
				$stats_last_group_memory = $stats_current_group_memory;
			}
		} else {
			// log selection time
			$group_stats["general"]["mts_group_selection_done"] = microtime(true);
		}
	
		// restore access settings
		elgg_set_ignore_access($ia);
	
		// log some end stats
		$group_stats["general"]["mts_end_digest"] = microtime(true);
		$group_stats["general"]["peak_memory_end"] = memory_get_peak_usage(false);
	
		// save stats logging
		digest_save_group_statistics($group_stats, $interval_ts_upper, $fork_id);
	}

	// Re-enable the query cache
	_elgg_services()->db->enableQueryCache();
}

/**
 * Start a commandline php process for the digest
 *
 * @param array $settings settings for the commandline
 *
 * @return void
 */
function digest_start_commandline($settings) {
	$script_location = dirname(dirname(__FILE__)) . "/procedures/cli.php";
	
	$query_string = http_build_query($settings, "", " ");
	
	if (PHP_OS === "WINNT") {
		pclose(popen("start /B php " . $script_location . " " . $query_string, "r"));
	} else {
		exec("php " . $script_location . " " . $query_string . " > /dev/null &");
	}
}

/**
 * reset the stats for a specific run
 *
 * @param int $timestamp the timestamp to use
 *
 * @return void
 */
function digest_rebase_stats($timestamp) {
	$dotw = date("w", $timestamp); // Day of the Week (0 (sunday) - 6 (saturday))
	$dotm = date("j", $timestamp); // Day of the Month (1 - 31)
	$odd_week = (date("W", $timestamp) & 1); // Odd weeknumber or not
	
	$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
	if (!$odd_week) {
		$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
	}
	
	// reset site stats
	if (digest_site_enabled()) {
		$site_stats = elgg_get_plugin_setting("site_statistics", "digest");
		if (!empty($site_stats)) {
			$site_stats = json_decode($site_stats, true);
			
			$site_stats["general"] = array();
			$site_stats[DIGEST_INTERVAL_DAILY] = array();
			
			// reset weekly stats
			foreach ($site_stats[DIGEST_INTERVAL_WEEKLY] as $key => $values) {
				if (stristr($key, "day_" . $dotw . "_")) {
					unset($site_stats[DIGEST_INTERVAL_WEEKLY][$key]);
				}
			}
			// reset weekly stats
			foreach ($site_stats[DIGEST_INTERVAL_FORTNIGHTLY] as $key => $values) {
				if (stristr($key, "day_" . $dotfn . "_")) {
					unset($site_stats[DIGEST_INTERVAL_FORTNIGHTLY][$key]);
				}
			}
			// reset weekly stats
			foreach ($site_stats[DIGEST_INTERVAL_MONTHLY] as $key => $values) {
				if (stristr($key, "day_" . $dotm . "_")) {
					unset($site_stats[DIGEST_INTERVAL_MONTHLY][$key]);
				}
			}
			
			// save the new stats
			elgg_set_plugin_setting("site_statistics", json_encode($site_stats), "digest");
		}
	}
	
	// reset group stats
	if (digest_group_enabled()) {
		$group_stats = elgg_get_plugin_setting("group_statistics", "digest");
		if (!empty($group_stats)) {
			$group_stats = json_decode($group_stats, true);
			
			$group_stats["general"] = array();
			$group_stats[DIGEST_INTERVAL_DAILY] = array();
			
			// reset weekly stats
			foreach ($group_stats[DIGEST_INTERVAL_WEEKLY] as $key => $values) {
				if (stristr($key, "day_" . $dotw . "_")) {
					unset($group_stats[DIGEST_INTERVAL_WEEKLY][$key]);
				}
			}
			// reset weekly stats
			foreach ($group_stats[DIGEST_INTERVAL_FORTNIGHTLY] as $key => $values) {
				if (stristr($key, "day_" . $dotfn . "_")) {
					unset($group_stats[DIGEST_INTERVAL_FORTNIGHTLY][$key]);
				}
			}
			// reset weekly stats
			foreach ($group_stats[DIGEST_INTERVAL_MONTHLY] as $key => $values) {
				if (stristr($key, "day_" . $dotm . "_")) {
					unset($group_stats[DIGEST_INTERVAL_MONTHLY][$key]);
				}
			}
			
			// save the new stats
			elgg_set_plugin_setting("group_statistics", json_encode($group_stats), "digest");
		}
	}
}

/**
 * Is multi-core execution supported (eg. is exec allowed)
 *
 * @return bool
 */
function digest_multi_core_supported() {
	
	$disabled_functions = ini_get('disable_functions');
	if (empty($disabled_functions)) {
		$disabled_functions = [];
	} else {
		$disabled_functions = explode(',', $disabled_functions);
	}
	
	$disabled_functions = array_map('trim', $disabled_functions);
	
	return is_callable('exec') && !in_array('exec', $disabled_functions);
}

/**
 * Get the number of configured cores
 *
 * @return int
 */
function digest_get_number_of_cores() {
	
	if (!digest_multi_core_supported()) {
		return 1;
	}
	
	$cores = (int) elgg_get_plugin_setting('multi_core', 'digest');
	if ($cores <= 1) {
		return 1;
	}
	
	return $cores;
}
