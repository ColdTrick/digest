<?php

	// reset new stats
	elgg_unset_plugin_setting("site_statistics", "digest");
	elgg_unset_plugin_setting("group_statistics", "digest");
	
	// cleanup old stats
	foreach(array(DIGEST_INTERVAL_DAILY, DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY) as $interval){
		elgg_unset_plugin_setting("site_digest_" . $interval . "_members", "digest");
		elgg_unset_plugin_setting("site_digest_" . $interval . "_avg_memory", "digest");
		elgg_unset_plugin_setting("site_digest_" . $interval . "_run_time", "digest");
		elgg_unset_plugin_setting("site_digest_" . $interval . "_send", "digest");
		
		elgg_unset_plugin_setting("group_digest_" . $interval . "_count", "digest");
		elgg_unset_plugin_setting("group_digest_" . $interval . "_total_members", "digest");
		elgg_unset_plugin_setting("group_digest_" . $interval . "_avg_members", "digest");
		elgg_unset_plugin_setting("group_digest_" . $interval . "_avg_members_memory", "digest");
		elgg_unset_plugin_setting("group_digest_" . $interval . "_avg_memory", "digest");
		elgg_unset_plugin_setting("group_digest_" . $interval . "_run_time", "digest");
		elgg_unset_plugin_setting("group_digest_" . $interval . "_send", "digest");
	}

	system_message(elgg_echo("digest:action:reset_stats:success"));