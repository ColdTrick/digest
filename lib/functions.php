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
		global $CONFIG;
			
		$include_never_logged_in = false;
		if(get_plugin_setting("include_never_logged_in") == "yes"){
			$include_never_logged_in = true;	
		}
		
		$user_setting_name = "digest_" . $guid;

		$query = "SELECT u.guid FROM {$CONFIG->dbprefix}users_entity u";
		$query .= " JOIN {$CONFIG->dbprefix}entities e ON e.guid = u.guid";
		$query .= " JOIN {$CONFIG->dbprefix}entity_relationships r ON u.guid = r.guid_one";
		$query .= " WHERE u.banned = 'no' AND u.email <> '' AND e.enabled = 'yes'";

		if($guid != $CONFIG->site_guid){
			// there should also be a relation with a group
			$relationship = "member";
		} else {
			// there should be a relationship between user and site
			$relationship = "member_of_site";
		}
		$query .= " AND r.guid_two = " . $guid . " AND r.relationship = '" . $relationship . "'";
		
		// select correct interval		
		$query .= " AND (u.guid IN (SELECT entity_guid FROM {$CONFIG->dbprefix}private_settings WHERE name = '" . $user_setting_name . "' AND value = '" . $interval . "')";
		
		if($including_default){
			//also include the users which have NO configuration for this setting
			$query .= " || u.guid NOT IN (SELECT entity_guid FROM {$CONFIG->dbprefix}private_settings WHERE name = '" . $user_setting_name . "')";
		}
		
		$query .= ")";
		
		if(!$include_never_logged_in){
			// exclude account without a single login (but is an enabled account)
			// this could occur when a user registers and validates, but never logs in or in situations with imported users 
			$query .= " AND u.last_login > 0";
		}
		
		// execute query and return results
		return get_data($query);
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
		global $CONFIG;
		global $interval_ts_upper;
		global $interval_ts_lower;
		
		$result = false;
		
		// remove some view extensions
		digest_revert_views();
		
		// set timestamps for interval
		digest_set_interval_timestamps($interval);
		
		// store current user
		$current_user = get_loggedin_user();
		
		// impersonate new user
		$SESSION["user"] = $user;
		$SESSION["username"] = $user->username;
		$SESSION["name"] = $user->name;
		$SESSION["guid"] = $user->getGUID();
		$SESSION["id"] = $user->getGUID();
		
		// get data for user
		$userdata = elgg_view("digest/message/site_body", array("ts_lower" => $interval_ts_lower, "ts_upper" => $interval_ts_upper));
		
		if($userdata){
			// link to online view
			$digest_url = $CONFIG->wwwroot . "pg/digest/show?ts_upper=" . $interval_ts_upper . "&ts_lower=" . $interval_ts_lower . "&interval=" . $interval;
			$digest_online = "<a href='" . $digest_url . "'>" . elgg_echo("digest:message:online") . "</a><br />";
			
			// unsubscribe link
			$digest_unsubscribe = digest_create_unsubscribe_link($CONFIG->site_guid, $user);
			
			// message_subject
			$message_subject = sprintf( elgg_echo("digest:message:title:site"), $CONFIG->site->name, elgg_echo("digest:interval:" . $interval));
			// message body
			$message_body = elgg_view_layout("digest", $message_subject, $userdata, $digest_online, $digest_unsubscribe);
			
			// send message
			// if succesfull mail return true
			$result = digest_send_mail($user, $message_subject, $message_body, $digest_url);
		} else {
			// no data is still succesful
			$result = true;
		}	
		unset($userdata);
		
		// restore current user
		$SESSION["user"] = $current_user;
		if(isloggedin()){
			$SESSION["username"] = $current_user->username;
			$SESSION["name"] = $current_user->name;
			$SESSION["guid"] = $current_user->getGUID();
			$SESSION["id"] = $current_user->getGUID();
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
		global $CONFIG;
		global $interval_ts_upper;
		global $interval_ts_lower;
		global $is_admin;
		
		$result = false;
		
		// check if group digest is enabled
		if(digest_group_enabled()){
			
			// remove some view extensions
			digest_revert_views();
			
			// set timestamps for interval
			digest_set_interval_timestamps($interval);
			
			// store current user
			$current_user = get_loggedin_user();
			
			// impersonate new user
			$SESSION["user"] = $user;
			$SESSION["username"] = $user->username;
			$SESSION["name"] = $user->name;
			$SESSION["guid"] = $user->getGUID();
			$SESSION["id"] = $user->getGUID();
			
			// this is needed for 1.5 and 1.6
			$current_is_admin = $is_admin;
			if($user->admin || $user->siteadmin){
				$is_admin = true;
			} else {
				$is_admin = false;
			}
			
			// get data for user
			$userdata = elgg_view("digest/message/group_body", array("ts_lower" => $interval_ts_lower, "ts_upper" => $interval_ts_upper, "group" => $group));
			
			if($userdata){
				// link to online view
				$digest_url = $CONFIG->wwwroot . "pg/digest/show?ts_upper=" . $interval_ts_upper . "&ts_lower=" . $interval_ts_lower . "&interval=" . $interval . "&group_guid=" . $group->guid;
				$digest_online = "<a href='" . $digest_url . "'>" . elgg_echo("digest:message:online") . "</a><br />";
				
				// unsubscribe link
				$digest_unsubscribe = digest_create_unsubscribe_link($group->getGUID(), $user);
				
				// message_subject
				$message_subject = sprintf( elgg_echo("digest:message:title:group"), $CONFIG->site->name, $group->name, elgg_echo("digest:interval:" . $interval));
				// message body
				$message_body = elgg_view_layout("digest", $message_subject, $userdata, $digest_online, $digest_unsubscribe);
	
				// send message
				// if succesfull mail return true
				$result = digest_send_mail($user, $message_subject, $message_body, $digest_url);
			} else {
				// no data is still succesful
				$result = true;
			}	
			unset($userdata);
			
			// restore current user
			$SESSION["user"] = $current_user;
			if(isloggedin()){
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
			
			$is_admin = $current_is_admin;
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
			$interval_ts_upper = time(); 
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
		global $CONFIG;
		global $digest_mail_send;
		
		$result = false;
		
		if(!empty($user) && ($user instanceof ElggUser) && !empty($subject) && !empty($html_body)){
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
				$plaintext_message .= sprintf(elgg_echo("digest:mail:plaintext:description"), $plain_link);
			}
			
			// send out the mail
			if((get_plugin_setting("in_production", "digest") == "yes") || ($bypass === true)){
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
			if(get_plugin_setting("group_production", "digest") == "yes"){
				$result = true;
			} else {
				$result = false;
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
		
		if(!empty($guid) && !empty($user) && ($user instanceof ElggUser)){
			$site_secret = get_site_secret();
			
			$code = md5($guid . $site_secret . $user->getGUID() . $user->time_created);
			
			$result = $CONFIG->wwwroot . "pg/digest/unsubscribe?guid=" . $guid . "&user_guid=" . $user->getGUID() . "&code=" . $code;
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
		
		if(!empty($guid) && !empty($user) && ($user instanceof ElggUser) && !empty($code)){
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
			unregister_elgg_event_handler("pagesetup", "system", "likes_setup");
			
			// undo river_comments extensions
			unregister_elgg_event_handler("pagesetup", "system", "river_comments_setup");
			
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