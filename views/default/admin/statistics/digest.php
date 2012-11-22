<?php

	$day_strings = array(
		elgg_echo("digest:day:sunday"),
		elgg_echo("digest:day:monday"),
		elgg_echo("digest:day:tuesday"),
		elgg_echo("digest:day:wednesday"),
		elgg_echo("digest:day:thursday"),
		elgg_echo("digest:day:friday"),
		elgg_echo("digest:day:saturday")
	);
	
	if(digest_site_enabled()){
		if($site_stats = elgg_get_plugin_setting("site_statistics", "digest")){
			$site_stats = json_decode($site_stats, true);
			
			$general = elgg_extract("general", $site_stats);
			
			if(!empty($general) && is_array($general)){
				if($general["ts_start_cron"]){
					$site_stats = digest_compress_statistics($site_stats);
					
					// site digest has run at least once
					$gen_table = "<table class='elgg-table mbl'>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:settings:interval:site_default") . "</td>";
					$gen_table .= "<td>" . elgg_echo("digest:interval:" . digest_get_default_site_interval()) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:ts_start_cron") . "</td>";
					$gen_table .= "<td>" . elgg_view_friendly_time($general["ts_start_cron"]) . " (" . htmlspecialchars(date(elgg_echo("friendlytime:date_format"), $general["ts_start_cron"])) . ") </td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:mts_start_digest") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["mts_start_digest"] - $general["ts_start_cron"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:site:general:mts_user_selection_done") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["mts_user_selection_done"] - $general["mts_start_digest"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:mts_end_digest") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["mts_end_digest"] - $general["mts_start_digest"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:users") . "</td>";
					$gen_table .= "<td>" . (int) $general["users"] . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:mails") . "</td>";
					$gen_table .= "<td>" . (int) $general["mails"] . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:peak_memory_start") . "</td>";
					$gen_table .= "<td>" . digest_readable_bytes($general["peak_memory_start"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:peak_memory_end") . "</td>";
					$gen_table .= "<td>" . digest_readable_bytes($general["peak_memory_end"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "</table>";
					
					// display interval stats
					$interval_table = "<table class='elgg-table mbm'>";
					
					$interval_table .= "<tr>";
					$interval_table .= "<th>&nbsp;</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:daily") . "</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:weekly") . "</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:fortnightly") . "</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:monthly") . "</th>";
					$interval_table .= "</tr>";
					
					// distribution settings
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:distribution") . "</td>";
					$interval_table .= "<td>&nbsp;</td>";
					
					$distribution = digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY);
					if($distribution === "distributed"){
						$dis = elgg_echo("digest:distribution:distributed");
					} else {
						$dis = $day_strings[$distribution];
					}
					$interval_table .= "<td>" . $dis . "</td>";
					
					$distribution = digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY);
					if($distribution === "distributed"){
						$dis = elgg_echo("digest:distribution:distributed");
					} else {
						$dis = elgg_echo("digest:admin:stats:distribution:fortnightly", array($day_strings[$distribution]));
					}
					$interval_table .= "<td>" . $dis . "</td>";
					
					$distribution = digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY);
					if($distribution === "distributed"){
						$dis = elgg_echo("digest:distribution:distributed");
					} else {
						$dis = elgg_echo("digest:admin:stats:distribution:monthly", array($distribution));
					}
					$interval_table .= "<td>" . $dis . "</td>";
					$interval_table .= "</tr>";
					
					// users processed
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:users") . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_DAILY]["users"] . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_WEEKLY]["users"] . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["users"] . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_MONTHLY]["users"] . "</td>";
					$interval_table .= "</tr>";
					
					// mails sent
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:mails") . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_DAILY]["mails"] . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_WEEKLY]["mails"] . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["mails"] . "</td>";
					$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_MONTHLY]["mails"] . "</td>";
					$interval_table .= "</tr>";
					
					// processing time
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:total_time") . "</td>";
					$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_DAILY]["total_time"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_WEEKLY]["total_time"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["total_time"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_MONTHLY]["total_time"]) . "</td>";
					$interval_table .= "</tr>";
					
					// memory used
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:total_memory") . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_DAILY]["total_memory"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_WEEKLY]["total_memory"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["total_memory"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_MONTHLY]["total_memory"]) . "</td>";
					$interval_table .= "</tr>";
					
					$interval_table .= "</table>";
					
					echo elgg_view_module("inline", elgg_echo("digest:admin:stats:site:title"), $gen_table . $interval_table);
				}
			}
		}
	}
	
	if(digest_group_enabled()){
		if($group_stats = elgg_get_plugin_setting("group_statistics", "digest")){
			$group_stats = json_decode($group_stats, true);
			
			$general = elgg_extract("general", $group_stats);
				
			if(!empty($general) && is_array($general)){
				if($general["ts_start_cron"]){
					$group_stats = digest_compress_statistics($group_stats);
					
					// site digest has run at least once
					$gen_table = "<table class='elgg-table mbl'>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:settings:interval:group_default") . "</td>";
					$gen_table .= "<td>" . elgg_echo("digest:interval:" . digest_get_default_group_interval()) . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:ts_start_cron") . "</td>";
					$gen_table .= "<td>" . elgg_view_friendly_time($general["ts_start_cron"]) . " (" . htmlspecialchars(date(elgg_echo("friendlytime:date_format"), $general["ts_start_cron"])) . ") </td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:mts_start_digest") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["mts_start_digest"] - $general["ts_start_cron"]) . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:group:general:mts_group_selection_done") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["mts_group_selection_done"] - $general["mts_start_digest"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:group:general:total_time_user_selection") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["total_time_user_selection"]) . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:mts_end_digest") . "</td>";
					$gen_table .= "<td>" . digest_readable_time($general["mts_end_digest"] - $general["mts_start_digest"]) . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:groups") . "</td>";
					$gen_table .= "<td>" . (int) $general["groups"] . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:users") . "</td>";
					$gen_table .= "<td>" . (int) $general["users"] . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:mails") . "</td>";
					$gen_table .= "<td>" . (int) $general["mails"] . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:peak_memory_start") . "</td>";
					$gen_table .= "<td>" . digest_readable_bytes($general["peak_memory_start"]) . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:peak_memory_end") . "</td>";
					$gen_table .= "<td>" . digest_readable_bytes($general["peak_memory_end"]) . "</td>";
					$gen_table .= "</tr>";
					
					$gen_table .= "<tr>";
					$gen_table .= "<td>" . elgg_echo("digest:admin:stats:total_memory") . "</td>";
					$gen_table .= "<td>" . digest_readable_bytes($general["total_memory"]) . "</td>";
					$gen_table .= "</tr>";
						
					$gen_table .= "</table>";
					
					// display interval stats
					$interval_table = "<table class='elgg-table'>";
						
					$interval_table .= "<tr>";
					$interval_table .= "<th>&nbsp;</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:daily") . "</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:weekly") . "</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:fortnightly") . "</th>";
					$interval_table .= "<th>" . elgg_echo("digest:interval:monthly") . "</th>";
					$interval_table .= "</tr>";
						
					// distribution settings
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:distribution") . "</td>";
					$interval_table .= "<td>&nbsp;</td>";
						
					$distribution = digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY);
					if($distribution === "distributed"){
						$dis = elgg_echo("digest:distribution:distributed");
					} else {
						$dis = $day_strings[$distribution];
					}
					$interval_table .= "<td>" . $dis . "</td>";
						
					$distribution = digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY);
					if($distribution === "distributed"){
						$dis = elgg_echo("digest:distribution:distributed");
					} else {
						$dis = elgg_echo("digest:admin:stats:distribution:fortnightly", array($day_strings[$distribution]));
					}
					$interval_table .= "<td>" . $dis . "</td>";
						
					$distribution = digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY);
					if($distribution === "distributed"){
						$dis = elgg_echo("digest:distribution:distributed");
					} else {
						$dis = elgg_echo("digest:admin:stats:distribution:monthly", array($distribution));
					}
					$interval_table .= "<td>" . $dis . "</td>";
					$interval_table .= "</tr>";
						
					// groups processed
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:groups") . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_DAILY]["groups"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_WEEKLY]["groups"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_FORTNIGHTLY]["groups"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_MONTHLY]["groups"] . "</td>";
					$interval_table .= "</tr>";
						
					// users processed
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:users") . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_DAILY]["users"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_WEEKLY]["users"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_FORTNIGHTLY]["users"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_MONTHLY]["users"] . "</td>";
					$interval_table .= "</tr>";
						
					// mails sent
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:mails") . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_DAILY]["mails"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_WEEKLY]["mails"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_FORTNIGHTLY]["mails"] . "</td>";
					$interval_table .= "<td>" . $group_stats[DIGEST_INTERVAL_MONTHLY]["mails"] . "</td>";
					$interval_table .= "</tr>";
						
					// processing time
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:total_time") . "</td>";
					$interval_table .= "<td>" . digest_readable_time($group_stats[DIGEST_INTERVAL_DAILY]["total_time"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_time($group_stats[DIGEST_INTERVAL_WEEKLY]["total_time"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_time($group_stats[DIGEST_INTERVAL_FORTNIGHTLY]["total_time"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_time($group_stats[DIGEST_INTERVAL_MONTHLY]["total_time"]) . "</td>";
					$interval_table .= "</tr>";
						
					// memory used
					$interval_table .= "<tr>";
					$interval_table .= "<td>" . elgg_echo("digest:admin:stats:total_memory") . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($group_stats[DIGEST_INTERVAL_DAILY]["total_memory"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($group_stats[DIGEST_INTERVAL_WEEKLY]["total_memory"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($group_stats[DIGEST_INTERVAL_FORTNIGHTLY]["total_memory"]) . "</td>";
					$interval_table .= "<td>" . digest_readable_bytes($group_stats[DIGEST_INTERVAL_MONTHLY]["total_memory"]) . "</td>";
					$interval_table .= "</tr>";
					
					$interval_table .= "</table>";
						
					echo elgg_view_module("inline", elgg_echo("digest:admin:stats:group:title"), $gen_table . $interval_table);
				}
			}
		}
	}
