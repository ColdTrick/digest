<?php 

	/**
	 * available stats in plugin settings
	 * 
	 * group_digest_[interval]_count => total number of groups
	 * group_digest_[interval]_total_members => total number of group members over all groups
	 * group_digest_[interval]_avg_members => avg members per group
	 * group_digest_[interval]_avg_members_memory => avg memory per group user
	 * group_digest_[interval]_avg_memory => avg memory per group
	 * group_digest_[interval]_run_time => total run time
	 * group_digest_[interval]_send => number of digests send
	 * 
	 */

	$default_mem_usage = 1500;
	$default_time_usage = 0.05;

	$default_interval = elgg_extract("default_interval", $vars, DIGEST_INTERVAL_NONE);
	$intervals = elgg_extract("intervals", $vars);
	$group_interval = elgg_extract("group_interval", $vars); // to predict usage

	$group_stats = array(
		"count" => array(),
		"total_members" => array(),
		"avg_members" => array(),
		"avg_members_memory" => array(),
		"total_members_memory" => array(),
		"avg_memory" => array(),
		"total_memory" => array(),
		"avg_run_time" => array(),
		"run_time" => array(),
		"send" => array()
	);
	
	$group_current = array(
		"count" => array(),
		"total_members" => array(),
		"avg_members" => array(),
		"total_memory" => array(),
		"run_time" => array()
	);
	
	$group_predict = array(
		"count" => array(),
		"total_members" => array(),
		"avg_members" => array(),
		"total_memory" => array(),
		"run_time" => array()
	);
	
	foreach($intervals as $interval){
		// get stats
		$group_stats["count"][$interval] = elgg_get_plugin_setting("group_digest_" . $interval . "_count", "digest");
		$group_stats["total_members"][$interval] = elgg_get_plugin_setting("group_digest_" . $interval . "_total_members", "digest");
		$group_stats["avg_members"][$interval] = round(elgg_get_plugin_setting("group_digest_" . $interval . "_avg_members", "digest"), 2);
		$group_stats["avg_members_memory"][$interval] = round(elgg_get_plugin_setting("group_digest_" . $interval . "_avg_members_memory", "digest"), 2);
		$group_stats["avg_memory"][$interval] = round(elgg_get_plugin_setting("group_digest_" . $interval . "_avg_memory", "digest"), 2);
		$group_stats["run_time"][$interval] = round(elgg_get_plugin_setting("group_digest_" . $interval . "_run_time", "digest"), 2);
		$group_stats["send"][$interval] = elgg_get_plugin_setting("group_digest_" . $interval . "_send", "digest");
		
		// calculate stats
		$group_stats["total_members_memory"][$interval] = ($group_stats["total_members"][$interval] . $group_stats["avg_members_memory"][$interval]);
		$group_stats["total_memory"][$interval] = ($group_stats["count"][$interval] * $group_stats["avg_memory"][$interval]);
		
		if(!empty($group_stats["run_time"][$interval])){
			$group_stats["avg_run_time"][$interval] = round(($group_stats["run_time"][$interval] / $group_stats["count"][$interval]), 2);
		} else {
			$group_stats["avg_run_time"][$interval] = 0;
		}
		
		// get current
		$group_options = array(
			"type" => "group",
			"limit" => false
		);
		
		$total_group_members_count = 0;
		$group_count = 0;
		
		if($groups = elgg_get_entities($group_options)){
			$group_count = count($groups);
			
			foreach($groups as $group){
				if(elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)){
					$include_group_default = false;
					$group_default_interval = $group->digest_interval;
					
					if(empty($group_default_interval)){
						$group_default_interval = $default_interval;
					}
					
					if($group_default_interval == $interval){
						$include_group_default = true;
					}
					
					$group_members = digest_get_users($group->getGUID(), $interval, $include_group_default);
					
					if(!empty($group_members)){
						$total_group_members_count += count($group_members);
					}
				}
			}
		}
		
		if(!empty($group_stats["avg_memory"][$interval])){
			$total_memory = ($group_count * $group_stats["avg_memory"][$interval]);
		} else {
			$total_memory = ($group_count * $default_mem_usage);
		}
		
		if(!empty($group_stats["avg_run_time"][$interval])){
			$run_time = round(($group_count * $group_stats["avg_run_time"][$interval]), 2);
		} else {
			$run_time = round(($group_count * $default_time_usage), 2);
		}
		
		$group_current["count"][$interval] = $group_count;
		$group_current["total_members"][$interval] = $total_group_members_count;
		$group_current["avg_members"][$interval] = round($total_group_members_count / $group_count, 2);
		$group_current["total_memory"][$interval] = $total_memory;
		$group_current["run_time"][$interval] = $run_time;
		
		// get prediction
		if(!empty($group_interval)){
			$total_group_members_count = 0;
			
			if($group_count > 0){
				
				foreach($groups as $group){
					if(elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)){
						$include_group_default = false;
						
						if($interval == $group_interval){
							$include_group_default = true;
						}
						
						$group_members = digest_get_users($group->getGUID(), $interval, $include_group_default);
						
						if(!empty($group_members)){
							$total_group_members_count += count($group_members);
						}
					}
				}
			}
			
			if(!empty($group_stats["avg_memory"][$interval])){
				$total_memory = ($group_count * $group_stats["avg_memory"][$interval]);
			} else {
				$total_memory = ($group_count * $default_mem_usage);
			}
			
			if(!empty($group_stats["avg_run_time"][$interval])){
				$run_time = round(($group_count * $group_stats["avg_run_time"][$interval]), 2);
			} else {
				$run_time = round(($group_count * $default_time_usage), 2);
			}
			
			$group_predict["total_members"][$interval] = $total_group_members_count;
			$group_predict["avg_members"][$interval] = round($total_group_members_count / $group_count, 2);
			$group_predict["total_memory"][$interval] = $total_memory;
			$group_predict["run_time"][$interval] = $run_time;
			
		}
	}

?>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo("digest:analysis:group:title"); ?></h3>
	</div>
	
	<table class="elgg-table">
		<tr>
			<th colspan="2">&nbsp;</th>
			<th><?php echo elgg_view("output/url", array("text" => elgg_echo("digest:interval:daily"), "href" => elgg_get_site_url() . "admin/statistics/digest?group_interval=" . DIGEST_INTERVAL_DAILY)); ?></th>
			<th><?php echo elgg_view("output/url", array("text" => elgg_echo("digest:interval:weekly"), "href" => elgg_get_site_url() . "admin/statistics/digest?group_interval=" . DIGEST_INTERVAL_WEEKLY)); ?></th>
			<th><?php echo elgg_view("output/url", array("text" => elgg_echo("digest:interval:fortnightly"), "href" => elgg_get_site_url() . "admin/statistics/digest?group_interval=" . DIGEST_INTERVAL_FORTNIGHTLY)); ?></th>
			<th><?php echo elgg_view("output/url", array("text" => elgg_echo("digest:interval:monthly"), "href" => elgg_get_site_url() . "admin/statistics/digest?group_interval=" . DIGEST_INTERVAL_MONTHLY)); ?></th>
		</tr>
		<?php 
		
			foreach($group_stats as $stat_name => $stats){
				$current = false;
				$current_row = "";
				$predict = false;
				$predict_row = "";
				
				$sub_count = 1;
				
				if(array_key_exists($stat_name, $group_current) && !empty($group_current[$stat_name])){
					$current = true;
					$sub_count++;
					$current_row .= "<tr>\n";
					$current_row .= "<td>" . elgg_echo("digest:analysis:current") . "</td>\n";
				}
				
				if(array_key_exists($stat_name, $group_predict) && !empty($group_predict[$stat_name])){
					$predict = true;
					$sub_count++;
					$predict_row .= "<tr>\n";
					$predict_row .= "<td>" . elgg_echo("digest_analysis:predict") . "</td>\n";
				}
				
				echo "<tr>\n";
				
				if($sub_count > 1){
					echo "<td rowspan='" . $sub_count . "'>" . elgg_echo("digest:analysis:group:" . $stat_name) . "</td>\n";
					echo "<td>" . elgg_echo("digest:analysis:last_run") . "</td>\n";
				} else {
					echo "<td colspan='2'>" . elgg_echo("digest:analysis:group:" . $stat_name) . "</td>\n";
				}
				
				foreach($stats as $interval => $value){
					if(!empty($value)){
						if(in_array($stat_name, array("avg_members_memory", "total_members_memory", "avg_memory", "total_memory"))){
							$value = digest_readable_bytes($value);
						} elseif(in_array($stat_name, array("avg_run_time", "run_time"))){
							$value = digest_readable_time($value);
						}
					
						echo "<td>" . $value . "</td>\n";
					} else {
						echo "<td>&nbsp;</td>\n";
					}
					
					if($current){
						$cur_val = $group_current[$stat_name][$interval];
						
						if(!empty($cur_val)) {
							if(in_array($stat_name, array("avg_members_memory", "total_members_memory", "avg_memory", "total_memory"))){
								$cur_val = digest_readable_bytes($cur_val);
							} elseif(in_array($stat_name, array("avg_run_time", "run_time"))){
								$cur_val = digest_readable_time($cur_val);
							}
						
							$current_row .= "<td>" . $cur_val . "</td>\n";
						} else {
							$current_row .= "<td>&nbsp;</td>\n";
						}
					}
					
					if($predict){
						$pre_val = $group_predict[$stat_name][$interval];
						
						if(!empty($pre_val)) {
							if(in_array($stat_name, array("avg_members_memory", "total_members_memory", "avg_memory", "total_memory"))){
								$pre_val = digest_readable_bytes($pre_val);
							} elseif(in_array($stat_name, array("avg_run_time", "run_time"))){
								$pre_val = digest_readable_time($pre_val);
							}

							$predict_row .= "<td>" . $pre_val . "</td>\n";
						} else {
							$predict_row .= "<td>&nbsp;</td>\n";
						}
					}
				}
				
				echo "</tr>\n";
				
				if($current){
					echo $current_row . "</tr>\n";
				}
				
				if($predict){
					echo $predict_row . "</tr>\n";
				}
			}
		
		?>
	
	</table>

</div>