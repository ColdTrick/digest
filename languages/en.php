<?php 

	$english = array(
		'digest' => "Digest",
	
		// init
		'digest:init:plugin_required:html_email_handler' => "This plugin require an other plugin 'html_email_handler', please enable this plugin first!",
	
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
		'digest:submenu:usersettings' => "Digest settings",
		'digest:submenu:groupsettings' => "Digest settings",
		'digest:submenu:analysis' => "Digest analysis",
		
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
		'digest:settings:never:include' => "Should user who never logged in on the site be included?",
		
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
		'digest:layout:footer:info' => "This mail is brought to you by %s because you are signed up for these digests.",
		'digest:layout:footer:update' => "Change your delivery settings",	
	
		// show a digest online
		'digest:show:error:input' => "Incorrect input to view the digest",
		'digest:show:no_data' => "No data was found for the selected interval",
	
		// message body
		'digest:message:title:site' => "%s: %s digest",
		'digest:message:title:group' => "%s - %s: %s digest",
	
		'digest:message:online' => "If you can't read the mail, you can view the digest online by clicking on this text.",
		
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
		
		// actions
		// update usersettings
		'digest:action:update:usersettings:error:input' => "Invalid input provided to save your digest settings",
		'digest:action:update:usersettings:error:user' => "The supplied GUID is not a user",
		'digest:action:update:usersettings:error:unknown' => "A unknown error occured while saving your digest settings",
		'digest:action:update:usersettings:success' => "Your digest settings have been saved successfully",
		
		// update groupsettings
		'digest:action:update:groupsettings:error:input' => "Invalid input provided to save the group digest settings",
		'digest:action:update:groupsettings:error:entity' => "The supplied GUID is not an entity",
		'digest:action:update:groupsettings:error:can_edit' => "Your not allowed to edit this group",
		'digest:action:update:groupsettings:error:save' => "An unknown error occured while saving the settings, please try again",
		'digest:action:update:groupsettings:success' => "The group digest settings have been saved successfully",
		
		// send digest mail
		'digest:mail:plaintext:description' => "Your e-mail client needs to support HTML mails to view the digest. 

You can also view the digest online using the following link: %s.",
		'' => "",
		'' => "",
		'' => "",
	
	
	);
	
	add_translation("en", $english);

?>