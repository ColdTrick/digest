<?php 
	
	global $running_interval;
	global $interval_ts_upper;
	global $interval_ts_lower;

	/**
	 * Function to retrieve all user guid that need to receive a digest
	 * 
	 * @param int $guid of the site or group
	 * @param string $interval
	 * @param bool $including_default if true will also retrieve users with no personal configuration
	 * @return false (no results) or array of objects with user guids
	 */
	function digest_get_users($guid, $interval, $including_default = false){
		static $include_never_logged_in;
		
		if(!isset($include_never_logged_in)){
			$include_never_logged_in = false;
			if(elgg_get_plugin_setting("include_never_logged_in", "digest") == "yes"){
				$include_never_logged_in = true;	
			}
		}
		
		$result = false;
		
		// validate input
		$guid = sanitise_int($guid, false);
		
		if(!empty($guid)){
			// get some config values
			$dbprefix = get_config("dbprefix");
			$site_guid = (int) get_config("site_guid");
			
			// begin building query
			$user_setting_name = "digest_" . $guid;
	
			$query = "SELECT u.guid";
			$query .= " FROM " . $dbprefix . "users_entity u";
			$query .= " JOIN " . $dbprefix . "entities e ON e.guid = u.guid";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON u.guid = r.guid_one";
			$query .= " WHERE u.banned = 'no' AND u.email <> '' AND e.enabled = 'yes' AND e.type = 'user'";
	
			if($guid != $site_guid){
				// there should also be a relation with a group
				$relationship = "member";
			} else {
				// there should be a relationship between user and site
				$relationship = "member_of_site";
			}
			$query .= " AND r.guid_two = " . $guid . " AND r.relationship = '" . $relationship . "'";
			
			// select correct interval		
			$query .= " AND (u.guid IN (SELECT entity_guid FROM " . $dbprefix . "private_settings WHERE name = '" . $user_setting_name . "' AND value = '" . $interval . "')";
			
			if($including_default){
				//also include the users which have NO configuration for this setting
				$query .= " || u.guid NOT IN (SELECT entity_guid FROM " . $dbprefix . "private_settings WHERE name = '" . $user_setting_name . "')";
			}
			
			$query .= ")";
			
			if(!$include_never_logged_in){
				// exclude account without a single login (but is an enabled account)
				// this could occur when a user registers and validates, but never logs in or in situations with imported users 
				$query .= " AND u.last_login > 0";
			}
			
			// execute query and return results
			$result = get_data($query);
		}
		
		return $result;
	}
	
	/**
	 * Make the site digest
	 * 
	 * @param ElggUser $user
	 * @param string $interval
	 * @return boolean
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
			digest_revert_views();
			
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
				$result = true;
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
	 * @return boolean
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
				digest_revert_views();
				
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
					$result = true;
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
	function digest_send_mail(ElggUser $user, $subject, $html_body, $plain_link = "", $bypass = false){
		global $digest_mail_send;
		
		static $in_production;
		if(!isset($in_production)){
			$in_production = false;
			if(elgg_get_plugin_setting("in_production", "digest") == "yes"){
				$in_production = true;
			}
		}
		
		$result = false;
		
		if(!empty($user) && elgg_instanceof($user, "user", null, "ElggUser") && !empty($subject) && !empty($html_body)){
			// convert css
			if(defined("XML_DOCUMENT_NODE")){
				if($transform = html_email_handler_css_inliner($html_body)){
					$html_body = $transform;
				}
			}
			
			// email settings
			$to = $user->name . " <" . $user->email . ">";
			
			if(!empty($plain_link)){
				// make a plaintext message for non HTML users
				$plaintext_message .= elgg_echo("digest:mail:plaintext:description", array($plain_link));
			}
			
			// send out the mail
			if(($in_production === true) || ($bypass === true)){
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
			} else {
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
		}
		
		return $result;
	}
	
	/**
	 * Convert a time in seconds to something readable
	 * 
	 * @param int $value
	 * @return bool|string false | human readable time value
	 */
	function digest_readable_time($value){
		$result = false;
		
		if(!empty($value)){
			if($value > 60){
				$value = round($value / 60, 2);
				
				if($value > 60){
					$value = round($value / 60, 2);
					
					$result = $value . " " . elgg_echo("digest:readable:time:hours");
				} else {
					$result = $value . " " . elgg_echo("digest:readable:time:minutes");
				}
			} else {
				$result = $value . " " . elgg_echo("digest:readable:time:seconds");
			}
		}
		
		return $result;
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
	function digest_revert_views($refresh = false){
		global $CONFIG;
		
		static $run_once;
		
		if(!isset($run_once) || ($refresh === true)){
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