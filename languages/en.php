<?php 

return [
	'digest' => "Digest",
	
	// digest intervals
	'digest:interval:none' => "None",
	'digest:interval:default' => "Use group interval (currently: %s)",
	'digest:interval:daily' => "Daily",
	'digest:interval:weekly' => "Weekly",
	'digest:interval:fortnightly' => "Fortnightly",
	'digest:interval:monthly' => "Monthly",
	
	'digest:distribution' => "Distribution",
	'digest:distribution:distributed' => "Distributed",
	'digest:distribution:description' => "Select a specific delivery day or day of month. If you choose distributed the delivery day will be generated for each user so it will spread out over the interval. Users will not have the option to set their delivery day.",
	
	'digest:day:sunday' => "Sunday",
	'digest:day:monday' => "Monday",
	'digest:day:tuesday' => "Tuesday",
	'digest:day:wednesday' => "Wednesday",
	'digest:day:thursday' => "Thursday",
	'digest:day:friday' => "Friday",
	'digest:day:saturday' => "Saturday",
	
	'digest:interval:error' => "Invalid Interval",
	
	// readable time
	'digest:readable:time:mseconds' => "msec",
	'digest:readable:time:seconds' => "sec",
	'digest:readable:time:minutes' => "min",
	'digest:readable:time:hours' => "hrs",
	
	// menu items
	'digest:page_menu:settings' => "Digest settings",
	'digest:page_menu:theme_preview' => "Digest preview",
	'digest:submenu:groupsettings' => "Digest settings",
	'admin:statistics:digest' => "Digest analysis",
	
	// admin settings
	'digest:settings:notice' => "<b>BE ADVISED:</b> Using digests can send out lots of emails.",
	
	'digest:settings:production' => "In production",
	'digest:settings:production:description' => "Using Digest could send out a lot of mails to your users, depending on the settings. To make sure no mails are send to your users before you're ready, this setting allows you to test the system. When you're ready set the system in production.",
	'digest:settings:production:option' => "Enable sending of digest mails",
	'digest:settings:production:group_option' => "Enable group digest mails",
	
	'digest:settings:interval:title' => "Digest interval settings",
	'digest:settings:interval:default' => "Default interval settings",
	'digest:settings:interval:site_default' => "The default interval of the Site digest",
	'digest:settings:interval:group_default' => "The default interval of the Group digest",
	'digest:settings:interval:description' => "Setting a default value will send out the digest on this interval to all the users who haven't configured a setting of their own.",
	
	'digest:settings:never:title' => "Never logged in users",
	'digest:settings:never:include' => "Should users who have never logged in on the site be included?",
	
	'digest:settings:custom_text:title' => "Custom text",
	'digest:settings:custom_text:description' => "Here you can define a custom text for the header and/or footer of each digest.",
	'digest:settings:custom_text:site:header' => "Site header text",
	'digest:settings:custom_text:site:footer' => "Site footer text",
	'digest:settings:custom_text:group:header' => "Group header text",
	'digest:settings:custom_text:group:footer' => "Group footer text",
	
	'digest:settings:multi_core:title' => "Multi-core support settings",
	'digest:settings:multi_core:description' => "When you have a large site (> 5000 users) you could split the handling of the Digest over several cores. Please do not select more cores then your webserver has, because this will only slow down the Digest.",
	'digest:settings:multi_core:warning' => "Please leave this setting alone if you don't understand the term multi-core or your site has less then 5000 users.",
	'digest:settings:multi_core:number' => "Please select the number of cores to use for Digest",
	
	'digest:settings:stats:title' => "Statistic settings",
	'digest:settings:stats:reset' => "Reset all gathered statistics",
	
	// usersettings
	'digest:usersettings:title' => "Personal digest settings",
	'digest:usersettings:error:user' => "You don't have access to this users digest settings",
	'digest:usersettings:no_settings' => "No digest settings available to configure.",
	
	'digest:usersettings:site:title' => "Site digest settings",
	'digest:usersettings:site:description' => "The Site digest will inform you of recent activity on the site in different categories like Blog, Groups, recent members.",
	'digest:usersettings:site:setting' => "How often do you wish to receive the Site digest",
	
	'digest:usersettings:groups:title' => "Group digest settings",
	'digest:usersettings:groups:description' => "The Group digest will inform you of recent activity in the selected Group. This can include newest members, latest discussions and much more.",
	'digest:usersettings:groups:group_header' => "Groupname",
	'digest:usersettings:groups:setting_header' => "Interval",
	
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
	
	// admin stats
	'digest:admin:stats:site:title' => "Site digest statistics",
	'digest:admin:stats:site:not_enabled' => "Site digest is not enabled in the plugin settings",
	'digest:admin:stats:general:server_name' => "Server that handled the digest",
	'digest:admin:stats:general:ts_start_cron' => "Time the CRON started",
	'digest:admin:stats:general:mts_start_digest' => "Time it took before the digest started",
	'digest:admin:stats:general:peak_memory_start' => "Peak memory before the digest started",
	'digest:admin:stats:general:peak_memory_end' => "Peak memory after the digest finished",
	'digest:admin:stats:general:mts_end_digest' => "Time it took to process the digest",
	
	'digest:admin:stats:site:general:mts_user_selection_done' => "Time it took to select all the users",
	'digest:admin:stats:total_time' => "Total run time",
	'digest:admin:stats:total_memory' => "Total memory leaked",
	'digest:admin:stats:not_collected' => "No statistics have been collected yet",
	
	'digest:admin:stats:groups' => "Groups processed",
	'digest:admin:stats:users' => "Users processed",
	'digest:admin:stats:mails' => "Mails sent",
	'digest:admin:stats:distribution:fortnightly' => "Odd weeks on %s",
	'digest:admin:stats:distribution:monthly' => "%s of the month",
	
	'digest:admin:stats:group:title' => "Group digest statistics",
	'digest:admin:stats:group:not_enabled' => "Group digest is not enabled in the plugin settings",
	'digest:admin:stats:group:general:mts_group_selection_done' => "Time it took to select all groups",
	'digest:admin:stats:group:general:total_time_user_selection' => "Total time spend on user selection",
	
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
	
	// reset stats
	'digest:action:reset_stats:success' => "The stats have been reset",
	
	// send digest mail
	'digest:mail:plaintext:description' => "Your e-mail client needs to support HTML mails to view the digest.

You can also view the digest online using the following link: %s.",
	
	// command line script
	'digest:cli:error:secret' => "The provided secret is invalid, the digest can't run",
];

