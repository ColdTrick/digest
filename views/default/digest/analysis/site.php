<?php 

	/**
	 * available stats in plugin settings
	 * 
	 * site_digest_[interval]_members => members processed in last run
	 * site_digest_[interval]_avg_memory => avg memory used per member
	 * site_digest_[interval]_run_time => total time to send site digest
	 * site_digest_[interval]_send => total members who received a digest
	 * 
	 */

	$default_mem_usage = 1500;
	$default_time_usage = 0.05;

	$default_interval = $vars["default_interval"];
	$intervals = $vars["intervals"];
	$site_interval = $vars["site_interval"]; // to predict usage

	$site_stats = array(
		"members" => array(),
		"avg_memory" => array(),
		"total_memory" => array(),
		"avg_run_time" => array(),
		"run_time" => array(),
		"send" => array()
	);
	
	$site_current = array(
		"members" => array(),
		"total_memory" => array(),
		"run_time" => array()
	);
	
	$site_predict = array(
		"members" => array(),
		"total_memory" => array(),
		"run_time" => array()
	);
	
	foreach($intervals as $interval){
		// get stats
		$site_stats["members"][$interval] = get_plugin_setting("site_digest_" . $interval . "_members", "digest");
		$site_stats["avg_memory"][$interval] = round(get_plugin_setting("site_digest_" . $interval . "_avg_memory", "digest"), 2);
		$site_stats["run_time"][$interval] = round(get_plugin_setting("site_digest_" . $interval . "_run_time", "digest"), 2);
		$site_stats["send"][$interval] = get_plugin_setting("site_digest_" . $interval . "_send", "digest");
		
		// calculate stats
		$site_stats["total_memory"][$interval] = ($site_stats["members"][$interval] * $site_stats["avg_memory"][$interval]);
		
		if(!empty($site_stats["run_time"][$interval])){
			$site_stats["avg_run_time"][$interval] = round(($site_stats["run_time"][$interval] / $site_stats["members"][$interval]), 2);
		} else {
			$site_stats["avg_run_time"][$interval] = 0;
		}
		
		// get current
		$default = false;
		if($interval == $default_interval){
			$default = true;
		}
		
		$members = digest_get_users($vars["config"]->site_guid, $interval, $default);
		
		if(!empty($members)){
			$members_count = count($members);
		} else {
			$members_count = 0;
		}
		
		if(!empty($site_stats["avg_memory"][$interval])){
			$total_memory = ($members_count * $site_stats["avg_memory"][$interval]);
		} else {
			$total_memory = ($members_count * $default_mem_usage);
		}
		
		if(!empty($site_stats["avg_run_time"][$interval])){
			$run_time = round(($members_count * $site_stats["avg_run_time"][$interval]), 2);
		} else {
			$run_time = round(($members_count * $default_time_usage), 2);
		}
		
		$site_current["members"][$interval] = $members_count;
		$site_current["total_memory"][$interval] = $total_memory;
		$site_current["run_time"][$interval] = $run_time;
		
		// get prediction
		if(!empty($site_interval)){
			$default = false;
			if($interval == $site_interval){
				$default = true;
			}
			
			$members = digest_get_users($vars["config"]->site_guid, $interval, $default);
			
			if(!empty($members)){
				$members_count = count($members);
			} else {
				$members_count = 0;
			}
			
			if(!empty($site_stats["avg_memory"][$interval])){
				$total_memory = ($members_count * $site_stats["avg_memory"][$interval]);
			} else {
				$total_memory = ($members_count * $default_mem_usage);
			}
			
			if(!empty($site_stats["avg_run_time"][$interval])){
				$run_time = round(($members_count * $site_stats["avg_run_time"][$interval]), 2);
			} else {
				$run_time = round(($members_count * $default_time_usage), 2);
			}
			
			$site_predict["members"][$interval] = $members_count;
			$site_predict["total_memory"][$interval] = $total_memory;
			$site_predict["run_time"][$interval] = $run_time;
		}
	}

?>
<div class="contentWrapper" id="digest_analysis_site">
	<h3 class="settings"><?php echo elgg_echo("digest:analysis:site:title"); ?></h3>
	
	<table>
		<tr>
			<th colspan="2">&nbsp;</th>
			<th class='digest_analysis_interval'><?php echo elgg_echo("digest:interval:daily"); ?></th>
			<th class='digest_analysis_interval'><?php echo elgg_echo("digest:interval:weekly"); ?></th>
			<th class='digest_analysis_interval'><?php echo elgg_echo("digest:interval:fortnightly"); ?></th>
			<th class='digest_analysis_interval'><?php echo elgg_echo("digest:interval:monthly"); ?></th>
		</tr>
		<?php 
		
			foreach($site_stats as $stat_name => $stats){
				$current = false;
				$current_row = "";
				$predict = false;
				$predict_row = "";
				
				$sub_count = 1;
				
				if(array_key_exists($stat_name, $site_current) && !empty($site_current[$stat_name])){
					$current = true;
					$sub_count++;
					$current_row .= "<tr>\n";
					$current_row .= "<td>&nbsp;</td>\n";
					$current_row .= "<td>" . elgg_echo("digest:analysis:current") . "</td>\n";
				}
				
				if(array_key_exists($stat_name, $site_predict) && !empty($site_predict[$stat_name])){
					$predict = true;
					$sub_count++;
					$predict_row .= "<tr>\n";
					$predict_row .= "<td>&nbsp;</td>\n";
					$predict_row .= "<td>" . elgg_echo("digest_analysis:predict") . "</td>\n";
				}
				
				echo "<tr>\n";
				
				if($sub_count > 1){
					echo "<td>" . elgg_echo("digest:analysis:site:" . $stat_name) . "</td>\n";
					echo "<td>" . elgg_echo("digest:analysis:last_run") . "</td>\n";
				} else {
					echo "<td colspan='2'>" . elgg_echo("digest:analysis:site:" . $stat_name) . "</td>\n";
				}
				
				foreach($stats as $interval => $value){
					if(!empty($value)){
						if(in_array($stat_name, array("avg_memory", "total_memory"))){
							$value = digest_readable_bytes($value);
						} elseif(in_array($stat_name, array("avg_run_time", "run_time"))){
							$value = digest_readable_time($value);
						}
					
						echo "<td class='digest_analysis_interval digest_analysis_interval_value'>" . $value . "</td>\n";
					} else {
						echo "<td class='digest_analysis_interval digest_analysis_interval_value'>&nbsp;</td>\n";
					}
					
					if($current){
						$cur_val = $site_current[$stat_name][$interval];
						
						if(!empty($cur_val)) {
							if(in_array($stat_name, array("avg_memory", "total_memory"))){
								$cur_val = digest_readable_bytes($cur_val);
							} elseif(in_array($stat_name, array("avg_run_time", "run_time"))){
								$cur_val = digest_readable_time($cur_val);
							}
						
							$current_row .= "<td class='digest_analysis_interval digest_analysis_interval_value'>" . $cur_val . "</td>\n";
						} else {
							$current_row .= "<td class='digest_analysis_interval digest_analysis_interval_value'>&nbsp;</td>\n";
						}
					}
					
					if($predict){
						$pre_val = $site_predict[$stat_name][$interval];
						
						if(!empty($pre_val)) {
							if(in_array($stat_name, array("avg_memory", "total_memory"))){
								$pre_val = digest_readable_bytes($pre_val);
							} elseif(in_array($stat_name, array("avg_run_time", "run_time"))){
								$pre_val = digest_readable_time($pre_val);
							}

							$predict_row .= "<td class='digest_analysis_interval digest_analysis_interval_value'>" . $pre_val . "</td>\n";
						} else {
							$predict_row .= "<td class='digest_analysis_interval digest_analysis_interval_value'>&nbsp;</td>\n";
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