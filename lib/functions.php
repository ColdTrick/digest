<?php 
	
	global $running_interval;
	global $interval_ts_upper;
	global $interval_ts_lower;

	/**
	 * Make the site digest
	 * 
	 * @param ElggUser $user
	 * @param string $interval
	 * @return true (mail sent successfull) || false (some error) || -1 (no content)
	 */
	function digest_site(ElggUser $user, $interval){
		global $SESSION;
		global $interval_ts_upper;
		global $interval_ts_lower;
		
		static $custom_text_header;
		static $custom_text_footer;
		
		$result = false;
		
		if(!empty($user) && elgg_instanceof($user, "user", null, "ElggUser")){
			// remove some view extensions
			digest_prepare_run();
			
			// set timestamps for interval
			digest_set_interval_timestamps($interval);
			
			// store current user
			$current_user = elgg_get_logged_in_user_entity();
			
			// impersonate new user
			$SESSION["user"] = $user;
			$SESSION["username"] = $user->username;
			$SESSION["name"] = $user->name;
			$SESSION["guid"] = $user->getGUID();
			$SESSION["id"] = $user->getGUID();
			
			// prepare some vars for the different views
			$vars = array(
				"user" => $user,
				"ts_lower" => $interval_ts_lower,
				"ts_upper" => $interval_ts_upper,
				"interval" => $interval
			);
			
			// get data for user
			$userdata = elgg_view("digest/elements/site", $vars);
			
			if(!empty($userdata)){
				// check if there are custom header/footer texts
				if(!isset($custom_text_header)){
					$custom_text_header = "";
					
					if($text = elgg_get_plugin_setting("custom_text_site_header", "digest")){
						$custom_text_header = elgg_view_module("digest", "", "<div class='elgg-output'>" . $text . "</div>");
					}
				}
				
				if(!isset($custom_text_footer)){
					$custom_text_footer = "";
						
					if($text = elgg_get_plugin_setting("custom_text_site_footer", "digest")){
						$custom_text_footer = elgg_view_module("digest", "", "<div class='elgg-output'>" . $text . "</div>");
					}
				}
				
				// there is content so send it to the user
				$params = array(
					"title" => elgg_get_site_entity()->name,
					"content" => $custom_text_header . $userdata . $custom_text_footer,
					"footer" => elgg_view("digest/elements/footer", $vars),
					"digest_header" => elgg_view("digest/elements/header", $vars),
					"digest_online" => elgg_view("digest/elements/online", $vars),
					"digest_unsubscribe" => elgg_view("digest/elements/unsubscribe", $vars)
				);
				
				// link to online view
				$digest_online_url = digest_get_online_url($vars);
				
				// message_subject
				$message_subject = elgg_echo("digest:message:title:site", array(elgg_get_site_entity()->name, elgg_echo("digest:interval:" . $interval)));
				// message body
				$message_body = elgg_view_layout("digest", $params);
				
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
			$SESSION["user"] = $current_user;
			if(elgg_is_logged_in()){
				$SESSION["username"] = $current_user->username;
				$SESSION["name"] = $current_user->name;
				$SESSION["guid"] = $current_user->getGUID();
				$SESSION["id"] = $current_user->getGUID();
			} else {
				unset($SESSION["username"]);
				unset($SESSION["name"]);
				unset($SESSION["guid"]);
				unset($SESSION["id"]);
			}
			
			// to save memory
			unset($current_user);
		}
		
		return $result;
	}

	/**
	 * make group digest
	 * 
	 * @param ElggGroup $group
	 * @param ElggUser $user
	 * @param string $interval
	 * @return true (mail sent successfull) || false (some error) || -1 (no content)
	 */
	function digest_group(ElggGroup $group, ElggUser $user, $interval){
		global $SESSION;
		global $interval_ts_upper;
		global $interval_ts_lower;
		global $is_admin;
		
		static $custom_text_header;
		static $custom_text_footer;
		
		$result = false;
		
		// check if group digest is enabled
		if(digest_group_enabled()){
			
			if(!empty($group) && elgg_instanceof($group, "group", null, "ElggGroup") && !empty($user) && elgg_instanceof($user, "user", null, "ElggUser")){
				
				// remove some view extensions
				digest_prepare_run();
				
				// set timestamps for interval
				digest_set_interval_timestamps($interval);
				
				// store current user
				$current_user = elgg_get_logged_in_user_entity();
				
				// impersonate new user
				$SESSION["user"] = $user;
				$SESSION["username"] = $user->username;
				$SESSION["name"] = $user->name;
				$SESSION["guid"] = $user->getGUID();
				$SESSION["id"] = $user->getGUID();
				
				// prepare some vars for the different views
				$vars = array(
					"user" => $user,
					"group" => $group,
					"ts_lower" => $interval_ts_lower,
					"ts_upper" => $interval_ts_upper,
					"interval" => $interval
				);
				
				// get data for user
				$userdata = elgg_view("digest/elements/group", $vars);
				
				if(!empty($userdata)){
					// check if there are custom header/footer texts
					if(!isset($custom_text_header)){
						$custom_text_header = "";
							
						if($text = elgg_get_plugin_setting("custom_text_group_header", "digest")){
							$custom_text_header = elgg_view_module("digest", "", "<div class='elgg-output'>" . $text . "</div>");
						}
					}
					
					if(!isset($custom_text_footer)){
						$custom_text_footer = "";
					
						if($text = elgg_get_plugin_setting("custom_text_group_footer", "digest")){
							$custom_text_footer = elgg_view_module("digest", "", "<div class='elgg-output'>" . $text . "</div>");
						}
					}
					
					// there is content so send it to the user
					$params = array(
						"title" => elgg_get_site_entity()->name,
						"content" => $custom_text_header . $userdata . $custom_text_footer,
						"footer" => elgg_view("digest/elements/footer", $vars),
						"digest_header" => elgg_view("digest/elements/header", $vars),
						"digest_online" => elgg_view("digest/elements/online", $vars),
						"digest_unsubscribe" => elgg_view("digest/elements/unsubscribe", $vars)
					);
					
					// link to online view
					$digest_online_url = digest_get_online_url($vars);
					
					// message_subject
					$message_subject = elgg_echo("digest:message:title:group", array(elgg_get_site_entity()->name, $group->name, elgg_echo("digest:interval:" . $interval)));
					// message body
					$message_body = elgg_view_layout("digest", $params);
		
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
				$SESSION["user"] = $current_user;
				if(elgg_is_logged_in()){
					$SESSION["username"] = $current_user->username;
					$SESSION["name"] = $current_user->name;
					$SESSION["guid"] = $current_user->getGUID();
					$SESSION["id"] = $current_user->getGUID();
				} else {
					unset($SESSION["username"]);
					unset($SESSION["name"]);
					unset($SESSION["guid"]);
					unset($SESSION["id"]);
				}
				
				// save memory
				unset($current_user);
			}
		}
		
		return $result;
	}
	
	/**
	 * Sets the right upper and lower ts for digest queries
	 * 
	 * @param string $interval
	 */
	function digest_set_interval_timestamps($interval){
		global $running_interval;
		global $interval_ts_upper;
		global $interval_ts_lower;
		
		if($running_interval != $interval){
			$running_interval = $interval;
			
			switch($interval){
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
					throw new InvalidParameterException(elgg_echo("digest:interval:error") . ": " . $interval);
					break;
			}
		}
	}
	
	/**
	 * Send out the generated digest
	 * 
	 * @param ElggUser $user
	 * @param string $subject
	 * @param string $html_body
	 * @param string $plain_link
	 * @param bool $bypass
	 * @return boolean
	 */
	function digest_send_mail(ElggUser $user, $subject, $html_body, $plain_link = ""){
		global $digest_mail_send;
		
		$result = false;
		
		if(!empty($user) && elgg_instanceof($user, "user", null, "ElggUser") && !empty($subject) && !empty($html_body)){
			// convert css
			if($transform = html_email_handler_css_inliner($html_body)){
				$html_body = $transform;
			}
			
			// email settings
			$to = html_email_handler_make_rfc822_address($user);
			
			if(!empty($plain_link)){
				// make a plaintext message for non HTML users
				$plaintext_message .= elgg_echo("digest:mail:plaintext:description", array($plain_link));
			}
			
			// send out the mail
			$options = array(
				"to" => $to,
				"subject" => $subject,
				"html_message" => $html_body,
				"plaintext_message" => $plaintext_message
			);
			
			if(html_email_handler_send_email($options)){
				if(empty($digest_mail_send)){
					$digest_mail_send = 1;
				} else {
					$digest_mail_send++;
				}
				
				$result = true;
			}
		}
		
		return $result;
	}
	
	/**
	 * convert a byte value into something more readable
	 * 
	 * @param int $value
	 * @return bool|string  false | human readable byte value
	 */
	function digest_readable_bytes($value){
		$result = false;
		
		if(!empty($value)){
			$neg = ($value < 0);
			$value = abs($value);
			
			if($value > 1024){
				$value = round($value / 1024, 2);
				
				if($value > 1024){
					$value = round($value / 1024, 2);
					
					if($value > 1024){
						$value = round($value / 1024, 2);
						
						$result = $value . " GB";
					} else {
						$result = $value . " MB";
					}
				} else {
					$result = $value . " KB";
				}
			} else {
				$result = $value . " B";
			}
			
			if($neg){
				$result = "-" . $result;
			}
		}
		
		return $result;
	}
	
	/**
	 * Convert a time in microseconds to something readable
	 * 
	 * @param int $value
	 * @return bool|string false | human readable time value
	 */
	function digest_readable_time($microtime){
		$time_array = array(
			"hours" => 0,
			"minutes" => 0,
			"seconds" => 0,
			"microseconds" => 0
		);
		
		$ts = (int) $microtime;
		$time_array["microseconds"] = $microtime - $ts;
		
		$time_array["seconds"] = ($ts % 60);
		$ts = $ts - $time_array["seconds"];
		
		$time_array["minutes"] = (($ts % 3600) / 60);
		$time_array["hours"] = (($ts - ($time_array["minutes"] * 60)) / 3600);
		
		// build result
		$result = "";
		if ($time_array["hours"]) {
			$result = $time_array["hours"] . " " . elgg_echo("digest:readable:time:hours");
		}
		
		if ($time_array["minutes"]) {
			$result .= " " . $time_array["minutes"] . " "  . elgg_echo("digest:readable:time:minutes");
		} elseif(!empty($result)) {
			$result .= " 00 M";
		}
		
		if ($time_array["seconds"]) {
			$result .= " " . $time_array["seconds"] . " "  . elgg_echo("digest:readable:time:seconds");
		} elseif(!empty($result)) {
			$result .= " 00 sec";
		}
		
		if ($time_array["microseconds"]) {
			$result .= " " . round($time_array["microseconds"] * 1000) . " " . elgg_echo("digest:readable:time:mseconds");
		}
		
		return trim($result);
	}
	
	/**
	 * Check if group digest is enabled
	 * 
	 * @return boolean true|false
	 */
	function digest_group_enabled(){
		static $result;
		
		if(!isset($result)){
			$result = false;
			
			if(elgg_get_plugin_setting("group_production", "digest") == "yes"){
				$result = true;
			}
		}
		
		return $result;
	}
	
	/**
	* Check if site digest is enabled
	*
	* @return boolean true|false
	*/
	function digest_site_enabled(){
		static $result;
	
		if(!isset($result)){
			$result = false;
				
			if(elgg_get_plugin_setting("in_production", "digest") == "yes"){
				$result = true;
			}
		}
	
		return $result;
	}
	
	/**
	 * create an unsubscribe link for a digest
	 * 
	 * @param int $guid
	 * @param ElggUser $user
	 * @return bool|string false | unsubscribe link
	 */
	function digest_create_unsubscribe_link($guid, ElggUser $user){
		global $CONFIG;
		
		$result = false;
		
		$guid = sanitise_int($guid, false);
		
		if(!empty($guid) && !empty($user) && elgg_instanceof($user, "user", null, "ElggUser")){
			$site_secret = get_site_secret();
			
			$code = md5($guid . $site_secret . $user->getGUID() . $user->time_created);
			
			$result = elgg_get_site_url() . "digest/unsubscribe?guid=" . $guid . "&user_guid=" . $user->getGUID() . "&code=" . $code;
		}
		
		return $result;
	}
	
	/**
	 * Validate an unsubscribe code
	 * 
	 * @param int $guid
	 * @param ElggUser $user
	 * @param string $code
	 * @return bool false|true
	 */
	function digest_validate_unsubscribe_code($guid, ElggUser $user, $code){
		$result = false;
		
		$guid = sanitise_int($guid, false);
		
		if(!empty($guid) && !empty($user) && elgg_instanceof($user, "user", null, "ElggUser") && !empty($code)){
			$site_secret = get_site_secret();
			
			$valid_code = md5($guid . $site_secret . $user->getGUID() . $user->time_created);
			
			if($code === $valid_code){
				$result = true;
			}
		}
		
		return $result;
	}
	
	/**
	 * Undo some extension to view by other plugins
	 * 
	 * @param bool $refresh
	 */
	function digest_prepare_run($refresh = false){
		global $CONFIG;
		
		static $run_once;
		
		if(!isset($run_once) || ($refresh === true)){
			// add views and css to digest handling
			digest_message_css();
			
			// let other plugins know they need to add their views/css
			elgg_trigger_event("prepare", "digest");
			
			// undo likes extension
			elgg_unregister_event_handler("pagesetup", "system", "likes_setup");
			
			// undo river_comments extensions
			elgg_unregister_event_handler("pagesetup", "system", "river_comments_setup");
			
			// undo more extensions
			// trigger pagesetup
			elgg_view_title("dummy");
			
			// check for more extensions
			if(isset($CONFIG->views->extensions)){
				foreach($CONFIG->views->extensions as $view => $extensions){
					
					if(stristr($view, "river/")){
						unset($CONFIG->views->extensions[$view]);
					}
				}
			}
			
			// undo registrations on menu hooks
			if(isset($CONFIG->hooks["register"])){
				if(isset($CONFIG->hooks["register"]["menu:user_hover"])){
					$CONFIG->hooks["register"]["menu:user_hover"] = array();
				}
			
				if(isset($CONFIG->hooks["register"]["menu:river"])){
					$CONFIG->hooks["register"]["menu:river"] = array();
				}
				
				if(isset($CONFIG->hooks["register"]["menu:entity"])){
					$CONFIG->hooks["register"]["menu:entity"] = array();
				}
			}
				
			if(isset($CONFIG->hooks["prepare"])){
				if(isset($CONFIG->hooks["prepare"]["menu:user_hover"])){
					$CONFIG->hooks["prepare"]["menu:user_hover"] = array();
				}
			
				if(isset($CONFIG->hooks["prepare"]["menu:river"])){
					$CONFIG->hooks["prepare"]["menu:river"] = array();
				}
				
				if(isset($CONFIG->hooks["prepare"]["menu:entity"])){
					$CONFIG->hooks["prepare"]["menu:entity"] = array();
				}
			}
			
			// set alternate view location for some views
			elgg_set_view_location("icon/user/default", dirname(dirname(__FILE__)) . "/views_alt/", "default");
			elgg_set_view_location("river/elements/body", dirname(dirname(__FILE__)) . "/views_alt/", "default");
			
			// only let this happen once
			$run_once = true;
		}
	}
	
	function digest_get_default_site_interval(){
		static $result;
		
		if(!isset($result)){
			$result = DIGEST_INTERVAL_NONE;
			
			if($setting = elgg_get_plugin_setting("site_default", "digest")){
				$result = $setting;
			}
		}
		
		return $result;
	}
	
	function digest_get_default_group_interval(){
		static $result;
		
		if(!isset($result)){
			$result = DIGEST_INTERVAL_NONE;
			
			if($setting = elgg_get_plugin_setting("group_default", "digest")){
				$result = $setting;
			}
		}
		
		return $result;
	}
	
	function digest_get_online_url($params = array()){
		$result = false;
		
		if(!empty($params) && is_array($params)){
			$base_url = elgg_get_site_url() . "digest/show";
			
			$url_params = array(
				"ts_lower" => $params["ts_lower"],
				"ts_upper" => $params["ts_upper"],
				"interval" => $params["interval"]
			);
			
			if(!empty($params["group"])){
				$url_params["group_guid"] = $params["group"]->getGUID();
			}
			
			$result = elgg_http_add_url_query_elements($base_url, $url_params);
		}
		
		return $result;
	}
	
	function digest_message_css(){
	
		elgg_extend_view("css/digest/core", "css/digest/river");
		elgg_extend_view("digest/elements/site", "digest/elements/site/river");
		elgg_extend_view("digest/elements/group", "digest/elements/group/river");
	
		if(elgg_is_active_plugin("blog")){
			elgg_extend_view("css/digest/core", "css/digest/blog");
				
			elgg_extend_view("digest/elements/site", "digest/elements/site/blog");
		}
	
		if(elgg_is_active_plugin("groups")){
			elgg_extend_view("css/digest/core", "css/digest/groups");
				
			elgg_extend_view("digest/elements/site", "digest/elements/site/groups");
		}
	
		if(elgg_is_active_plugin("profile")){
			elgg_extend_view("css/digest/core", "css/digest/profile");
				
			elgg_extend_view("digest/elements/site", "digest/elements/site/profile");
		}
	}
	
	function digest_get_default_distribution($interval){
		static $distributions;
		
		if(!isset($distributions)){
			$distributions = array();
		}
		
		if(!empty($interval) && in_array($interval, array(DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY))){
			
			if(!isset($distributions[$interval])){
				if($setting = elgg_get_plugin_setting($interval . "_distribution", "digest")){
					if(is_numeric($setting)){
						$setting = (int) $setting;
					}
					$distributions[$interval] = $setting;
				} else {
					// no setting or 0 (zero)
					if($interval == DIGEST_INTERVAL_MONTHLY){
						$distributions[$interval] = 1; // first day of the month
					} else {
						$distributions[$interval] = 0; // first day of the week (sunday)
					}
				}
			}
		}
		
		return $distributions[$interval];
	}
	
	function digest_get_site_users($settings){
		global $interval_ts_upper;
		
		$site = elgg_get_site_entity();
		$dbprefix = elgg_get_config("dbprefix");
		
		$dotw = (int) date("w", $interval_ts_upper); // Day of the Week (0 (sunday) - 6 (saturday))
		$dotm = (int) date("j", $interval_ts_upper); // Day of the Month (1 - 31)
		$odd_week = (date("W", $interval_ts_upper) & 1); // Odd weeknumber or not 
		
		$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
		if(!$odd_week){
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
				if(!$include_never_logged_in){
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
		
		if($limit = (int) elgg_extract("limit", $settings, 0)){
			$offset = (int) elgg_extract("offset", $settings, 0);
			
			$query .= " LIMIT " . $offset . ", " . $limit;
		}
		
		// execute the query
		return get_data($query, "digest_row_to_array");
	}
	
	function digest_get_group_users($group_guid, $interval_settings, $include_never_logged_in = false) {
		global $interval_ts_upper;
		
		$dbprefix = elgg_get_config("dbprefix");
		
		$dotw = (int) date("w", $interval_ts_upper); // Day of the Week (0 (sunday) - 6 (saturday))
		$dotm = (int) date("j", $interval_ts_upper); // Day of the Month (1 - 31)
		$odd_week = (date("W", $interval_ts_upper) & 1); // Odd weeknumber or not
		
		$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
		if(!$odd_week){
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
				if(!$include_never_logged_in){
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
	
	function digest_row_to_guid($row) {
		return (int) $row->guid;
	}
	
	function digest_row_to_array($row){
		return (array) $row;
	}
	
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
	
	function digest_save_site_statistics($stats, $timestamp, $fork_id = 0){
		$dotw = (int) date("w", $timestamp); // Day of the Week (0 (sunday) - 6 (saturday))
		$dotm = (int) date("j", $timestamp); // Day of the Month (1 - 31)
		$odd_week = (date("W", $timestamp) & 1); // Odd weeknumber or not
		
		$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
		if(!$odd_week){
			$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
		}
		
		// get saved site statistics
		if($site_stats = elgg_get_plugin_setting("site_statistics", "digest")){
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
	
	function digest_save_group_statistics($stats, $timestamp, $fork_id = 0){
		$dotw = (int) date("w", $timestamp); // Day of the Week (0 (sunday) - 6 (saturday))
		$dotm = (int) date("j", $timestamp); // Day of the Month (1 - 31)
		$odd_week = (date("W", $timestamp) & 1); // Odd weeknumber or not
		
		$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
		if(!$odd_week){
			$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
		}
		
		// get saved site statistics
		if($group_stats = elgg_get_plugin_setting("group_statistics", "digest")){
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
	
	function digest_compress_statistics($stats){
		
		// combine the interval stats
		foreach(array(DIGEST_INTERVAL_DAILY, DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY) as $interval){
			$temp_stats = array();
			
			if(!empty($stats[$interval])){
				
				foreach($stats[$interval] as $day_fork_id){
					
					foreach($day_fork_id as $key => $value){
						if(!isset($temp_stats[$key])){
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
		
		foreach($stats["general"] as $fork_id => $info){
			
			if(!empty($info)){
				foreach($info as $key => $value){
					
					switch($key){
						case "users":
						case "mails":
						case "groups":
						case "total_time_user_selection":
						case "total_memory":
							// need total count
							if(!isset($combined_stats[$key])){
								$combined_stats[$key] = 0;
							}
							
							$combined_stats[$key] += $value;
							break;
						case "mts_start_digest":
						case "ts_start_cron":
						case "peak_memory_start":
							// needs minimum
							if(!isset($combined_stats[$key])){
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
							if(!isset($combined_stats[$key])){
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
	
	function digest_generate_commandline_secret(){
		static $result;
		
		if(!isset($result)){
			$site_secret = get_site_secret();
			$digest_plugin = elgg_get_plugin_from_id("digest");
			
			$result = md5($digest_plugin->getGUID() . $site_secret . $digest_plugin->time_created);
		}
		
		return $result;
	}
	
	function digest_validate_commandline_secret($secret){
		$result = false;
		
		if(!empty($secret)){
			if($correct_secret = digest_generate_commandline_secret()){
				if($secret === $correct_secret){
					$result = true;
				}
			}
		}
		
		return $result;
	}
	
	function digest_process($settings){
		global $DB_QUERY_CACHE;
		global $ENTITY_CACHE;
		global $interval_ts_upper;
		
		$interval_ts_upper = (int) elgg_extract("timestamp", $settings, time());
		$fork_id = (int) elgg_extract("fork_id", $settings, 0);
		
		// should new users be included
		$never_logged_in = false;
		if (elgg_get_plugin_setting("include_never_logged_in", "digest") == "yes") {
			$never_logged_in = true;
		}
			
		// backup some cache
		$entity_cache_backup = $ENTITY_CACHE;
		
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
			if ($users = digest_get_site_users($site_intervals)) {
				// log selection time
				$site_stats["general"]["mts_user_selection_done"] = microtime(true);
					
				// use a fair memory footprint
				if($DB_QUERY_CACHE){
					$DB_QUERY_CACHE->clear();
				}
				$stats_last_memory = memory_get_usage(false);
					
				// process users
				foreach($users as $user_setting){
					// stat logging
					$site_stats[$user_setting["user_interval"]]["users"]++;
					$site_stats["general"]["users"]++;
		
					// sent site digest for this user
					$user = get_user($user_setting["guid"]);
		
					// log start time
					$stats_mts_before = microtime(true);
		
					// sent out the digest
					if(digest_site($user, $user_setting["user_interval"]) === true){
						// mail was sent
						$site_stats[$user_setting["user_interval"]]["mails"]++;
						$site_stats["general"]["mails"]++;
					}
		
					// stats logging
					$site_stats[$user_setting["user_interval"]]["total_time"] += (microtime(true) - $stats_mts_before);
		
					// reset cache
					$GLOBALS["ENTITY_CACHE"] = $entity_cache_backup;
		
					if($DB_QUERY_CACHE){
						$DB_QUERY_CACHE->clear();
					}
		
					unset($user);
		
					// stats logging of memory leak
					$stats_current_memory = memory_get_usage(false);
					$site_stats[$user_setting["user_interval"]]["total_memory"] += ($stats_current_memory - $stats_last_memory);
					$stats_last_memory = $stats_current_memory;
				}
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
				"callback" => "digest_row_to_guid"
			);
			
			if($limit = (int) elgg_extract("group_limit", $settings, 0)){
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
		
			if ($group_guids = elgg_get_entities($options)) {
				// log selection time
				$group_stats["general"]["mts_group_selection_done"] = microtime(true);
		
				// use a fair memory footprint
				if($DB_QUERY_CACHE){
					$DB_QUERY_CACHE->clear();
				}
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
		
					if ($users = digest_get_group_users($group_guid, $group_intervals, $never_logged_in)) {
						// stats loggin
						$group_stats["general"]["total_time_user_selection"] += (microtime(true) - $stats_begin_user_selection);
							
						// use a fair memory footprint
						if($DB_QUERY_CACHE){
							$DB_QUERY_CACHE->clear();
						}
						$stats_last_memory = memory_get_usage(false);
							
						// process users
						foreach ($users as $user_setting) {
							// stat logging
							$group_stats[$user_setting["user_interval"]]["users"]++;
							if(!in_array($group_guid, $group_stats[$user_setting["user_interval"]]["groups"])){
								$group_stats[$user_setting["user_interval"]]["groups"][] = $group_guid;
							}
							$group_stats["general"]["users"]++;
		
							// get the user
							$user = get_user($user_setting["guid"]);
		
							// log start time
							$stats_mts_before = microtime(true);
		
							// sent digest
							if(digest_group($group, $user, $user_setting["user_interval"]) === true){
								// mail was sent
								$group_stats[$user_setting["user_interval"]]["mails"]++;
								$group_stats["general"]["mails"]++;
							}
		
							// stats logging
							$group_stats[$user_setting["user_interval"]]["total_time"] += (microtime(true) - $stats_mts_before);
		
							// reset cache
							$GLOBALS["ENTITY_CACHE"] = $entity_cache_backup;
		
							if($DB_QUERY_CACHE){
								$DB_QUERY_CACHE->clear();
							}
		
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
		
					// reset cache
					$GLOBALS["ENTITY_CACHE"] = $entity_cache_backup;
		
					if($DB_QUERY_CACHE){
						$DB_QUERY_CACHE->clear();
					}
		
					unset($group);
		
					// stats logging of memory leak
					$stats_current_group_memory = memory_get_usage(false);
					$group_stats["general"]["total_memory"] += ($stats_current_group_memory - $stats_last_group_memory);
					$stats_last_group_memory = $stats_current_group_memory;
				}
			}
		
			// restore access settings
			elgg_set_ignore_access($ia);
		
			// log some end stats
			$group_stats["general"]["mts_end_digest"] = microtime(true);
			$group_stats["general"]["peak_memory_end"] = memory_get_peak_usage(false);
		
			// save stats logging
			digest_save_group_statistics($group_stats, $interval_ts_upper, $fork_id);
		}
	}
	
	function digest_start_commandline($settings){
		$script_location = dirname(dirname(__FILE__)) . "/procedures/cli.php";
		
		$query_string = http_build_query($settings, "", " ");
		
		if(PHP_OS === "WINNT"){
			pclose(popen("start /B php " . $script_location . " " . $query_string, "r"));
		} else {
			exec("php " . $script_location . " " . $query_string . " > /dev/null &");
		}
	}
	
	function digest_rebase_stats($timestamp){
		$dotw = date("w", $timestamp); // Day of the Week (0 (sunday) - 6 (saturday))
		$dotm = date("j", $timestamp); // Day of the Month (1 - 31)
		$odd_week = (date("W", $timestamp) & 1); // Odd weeknumber or not
		
		$dotfn = $dotw; // Day of the Fortnight (0 (sunday 1st week) - 6 (saturday 1st week))
		if(!$odd_week){
			$dotfn += 7; // in even weeks + 7 days (7 (sunday 2nd week) - 13 (saturday 2nd week))
		}
		
		// reset site stats
		if(digest_site_enabled()){
			if($site_stats = elgg_get_plugin_setting("site_statistics", "digest")){
				$site_stats = json_decode($site_stats, true);
				
				$site_stats["general"] = array();
				$site_stats[DIGEST_INTERVAL_DAILY] = array();
				
				// reset weekly stats
				foreach($site_stats[DIGEST_INTERVAL_WEEKLY] as $key => $values){
					if(stristr($key, "day_" . $dotw . "_")){
						unset($site_stats[DIGEST_INTERVAL_WEEKLY][$key]);
					}
				}
				// reset weekly stats
				foreach($site_stats[DIGEST_INTERVAL_FORTNIGHTLY] as $key => $values){
					if(stristr($key, "day_" . $dotfn . "_")){
						unset($site_stats[DIGEST_INTERVAL_FORTNIGHTLY][$key]);
					}
				}
				// reset weekly stats
				foreach($site_stats[DIGEST_INTERVAL_MONTHLY] as $key => $values){
					if(stristr($key, "day_" . $dotm . "_")){
						unset($site_stats[DIGEST_INTERVAL_MONTHLY][$key]);
					}
				}
				
				// save the new stats
				elgg_set_plugin_setting("site_statistics", json_encode($site_stats), "digest");
			}
		}
		
		// reset group stats
		if(digest_group_enabled()){
			if($group_stats = elgg_get_plugin_setting("group_statistics", "digest")){
				$group_stats = json_decode($group_stats, true);
				
				$group_stats["general"] = array();
				$group_stats[DIGEST_INTERVAL_DAILY] = array();
				
				// reset weekly stats
				foreach($group_stats[DIGEST_INTERVAL_WEEKLY] as $key => $values){
					if(stristr($key, "day_" . $dotw . "_")){
						unset($group_stats[DIGEST_INTERVAL_WEEKLY][$key]);
					}
				}
				// reset weekly stats
				foreach($group_stats[DIGEST_INTERVAL_FORTNIGHTLY] as $key => $values){
					if(stristr($key, "day_" . $dotfn . "_")){
						unset($group_stats[DIGEST_INTERVAL_FORTNIGHTLY][$key]);
					}
				}
				// reset weekly stats
				foreach($group_stats[DIGEST_INTERVAL_MONTHLY] as $key => $values){
					if(stristr($key, "day_" . $dotm . "_")){
						unset($group_stats[DIGEST_INTERVAL_MONTHLY][$key]);
					}
				}
				
				// save the new stats
				elgg_set_plugin_setting("group_statistics", json_encode($group_stats), "digest");
			}
		}
	}