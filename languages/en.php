<?php 

	$english = array(
		'digest' => "Digest",
	
		// digest intervals
		'digest:interval:none' => "Disabled",
		'digest:interval:default' => "Use group interval (currently: %s)",
		'digest:interval:daily' => "Daily",
		'digest:interval:weekly' => "Weekly",
		'digest:interval:fortnightly' => "Fortnightly",
		'digest:interval:monthly' => "Monthly",
	
		'digest:interval:error' => "Invalid Interval",
	
		// readable time
		'digest:readable:time:seconds' => "sec",
		'digest:readable:time:minutes' => "min",
		'digest:readable:time:hours' => "hrs",
		
		// menu items
		'digest:page_menu:settings' => "Digest settings",
		'digest:page_menu:theme_preview' => "Digest preview",
		'digest:submenu:groupsettings' => "Digest settings",
		'admin:statistics:digest' => "Digest analysis",
		
		// admin settings
		'digest:settings:production:title' => "Digest production settings",
		'digest:settings:production:description' => "Using Digest could send out a lot of mails to your users, depending on the settings. To make sure no mails are send to your users before you're ready, this setting allows you to test the system. When you're ready set the system in production.",
		'digest:settings:production:option' => "Enable sending of digest mails",
		'digest:settings:production:group_option' => "Enable group digest mails",
	
		'digest:settings:interval:title' => "Digest interval settings",
		'digest:settings:interval:site_default' => "The default interval of the Site digest",
		'digest:settings:interval:group_default' => "The default interval of the Group digest",
		'digest:settings:interval:description' => "Setting a default value will send out the digest on this interval to all the users who haven't configured a setting of their own.<br /><br /><b>WARNING:</b> This could send out a lot of mails.",
		
		'digest:settings:never:title' => "Never logged in users",
		'digest:settings:never:include' => "Should users who have never logged in on the site be included?",
		
		// usersettings
		'digest:usersettings:title' => "Personal digest settings",
		'digest:usersettings:error:user' => "You don't have access to this users digest settings",
		
		'digest:usersettings:site:title' => "Site digest settings",
		'digest:usersettings:site:description' => "The Site digest will inform you of recent activity on the site in different categories like Blog, Groups, recent members.",
		'digest:usersettings:site:setting' => "How often do you wish to receive the Site digest",
		
		'digest:usersettings:groups:title' => "Group digest settings",
		'digest:usersettings:groups:description' => "The Group digest will inform you of recent activity in the selected Group. This can include newest members, latest discussions and much more.",
		'digest:usersettings:groups:group_header' => "Groupname",
		'digest:usersettings:groups:setting_header' => "Interval",
		
		// user group setting
		'digest:usersettings:group:setting' => "Your current digest interval for this group is",
		'digest:usersettings:group:more' => "Digest settings",
		
		// group settings
		'digest:groupsettings:title' => "Group digest setting",
		'digest:groupsettings:description' => "At what interval do you which your members to receive a digest of the group activity. This setting will be used as a default value, your members can personaly overrule this setting.",
		'digest:groupsettings:setting' => "Group digest interval",
		
		// layout
		'digest:elements:unsubscribe:info' => "This mail is brought to you by %s because you are signed up for these digests.",
		'digest:elements:unsubscribe:settings' => "Change your %sdelivery settings%s.",	
		'digest:elements:unsubscribe:unsubscribe' => "To directly unsubscribe from this digest, %sclick here%s.",	
	
		// show a digest online
		'digest:show:error:input' => "Incorrect input to view the digest",
		'digest:show:no_data' => "No data was found for the selected interval",
	
		// message body
		'digest:message:title:site' => "%s: %s digest",
		'digest:message:title:group' => "%s - %s: %s digest",
	
		'digest:elements:online' => "If you can't read the mail, view this digest %sonline%s",
		
		// admin analysis
		'digest:analysis:title' => "Digest server analysis",
		
		'digest:analysis:last_run' => "Last run",
		'digest:analysis:current' => "Current",
		'digest_analysis:predict' => "Predict",
		
		'digest:analysis:site:title' => "Site digest",
		'digest:analysis:site:members' => "Members",
		'digest:analysis:site:avg_memory' => "Avg memory",
		'digest:analysis:site:total_memory' => "Total memory",
		'digest:analysis:site:avg_run_time' => "Avg run time",
		'digest:analysis:site:run_time' => "Total run time",
		'digest:analysis:site:send' => "Sent digests",
		
		'digest:analysis:group:title' => "Group digest",
		'digest:analysis:group:count' => "Groups on site",
		'digest:analysis:group:total_members' => "Total group members",
		'digest:analysis:group:avg_members' => "Avg members per group",
		'digest:analysis:group:avg_members_memory' => "Avg memory per member",
		'digest:analysis:group:total_members_memory' => "Total memory for members",
		'digest:analysis:group:avg_memory' => "Avg memory per group",
		'digest:analysis:group:total_memory' => "Total memory",
		'digest:analysis:group:avg_run_time' => "Avg run time per group",
		'digest:analysis:group:run_time' => "Total run time",
		'digest:analysis:group:send' => "Sent digests",
	
		// register 
		'digest:register:enable' => "I wish to receive a site digest",
		
		// unsubscribe
		'digest:unsubscribe:error:input' => "Incorrect input to unsubscribe from digest",
		'digest:unsubscribe:error:code' => "The provided validation code is invalid",
		'digest:unsubscribe:error:save' => "An unknown error occured while unsubscribing you from the digest",
		'digest:unsubscribe:success' => "You've successfully been unsubscribed from the digest",
		
		// actions
		// update usersettings
		'digest:action:update:usersettings:error:unknown' => "A unknown error occured while saving your digest settings",
		'digest:action:update:usersettings:success' => "Your digest settings have been saved successfully",
		
		// update groupsettings
		'digest:action:update:groupsettings:error:save' => "An unknown error occured while saving the settings, please try again",
		'digest:action:update:groupsettings:success' => "The group digest settings have been saved successfully",
		
		// send digest mail
		'digest:mail:plaintext:description' => "Your e-mail client needs to support HTML mails to view the digest. 

You can also view the digest online using the following link: %s.",
		
	);
	
	add_translation("en", $english);
