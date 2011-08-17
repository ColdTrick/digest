<?php 

	$user = $vars["user"];
	$groups = $vars["groups"];

	if(!empty($groups)){
		$site_group_interval = get_plugin_setting("group_interval", "digest");
		
		if(empty($site_group_interval)){
			$site_group_interval = DIGEST_INTERVAL_NONE;
		}
		
		$group_items = "";
		
		foreach($groups as $group){
			if(trigger_plugin_hook("digest", "group", array("group" => $group), true)){
				$group_interval = $group->digest_interval;
				$user_group_interval = $user->getPrivateSetting("digest_" . $group->getGUID());
				
				if(empty($group_interval)){
					$group_interval = $site_group_interval;
				}
				
				if(empty($user_group_interval)){
					$user_group_interval = DIGEST_INTERVAL_DEFAULT;
				}
				
				$group_items .= "<tr>\n";
				$group_items .= "<td class='digest_table_layout_left'><a href='" . $group->getURL() . "' title='" . strip_tags($group->description) . "'>" . $group->name . "</a></td>\n";
				$group_items .= "<td>";
				
				// begin select
				$group_items .= "<select name='digest[" . $group->getGUID() . "]'>\n";
				$group_items .= "<option value='" . DIGEST_INTERVAL_NONE . "' class='digest_interval_disabled'";
				if($user_group_interval == DIGEST_INTERVAL_NONE){
					$group_items .= " selected='selected'";
				}
				$group_items .= ">" . elgg_echo("digest:interval:none") . "</option>\n";
				
				$group_items .= "<option value='" . DIGEST_INTERVAL_DEFAULT . "'";
				if($user_group_interval == DIGEST_INTERVAL_DEFAULT){
					$group_items .= " selected='selected'";
				}
				$group_items .= ">" . sprintf(elgg_echo("digest:interval:default"), elgg_echo("digest:interval:" . $group_interval)) . "</option>\n";
				
				$group_items .= "<option value='" . DIGEST_INTERVAL_DAILY . "'";
				if($user_group_interval == DIGEST_INTERVAL_DAILY){
					$group_items .= " selected='selected'";
				}
				$group_items .= ">" . elgg_echo("digest:interval:daily") . "</option>\n";
				
				$group_items .= "<option value='" . DIGEST_INTERVAL_WEEKLY . "'";
				if($user_group_interval == DIGEST_INTERVAL_WEEKLY){
					$group_items .= " selected='selected'";
				}
				$group_items .= ">" . elgg_echo("digest:interval:weekly") . "</option>\n";
				
				$group_items .= "<option value='" . DIGEST_INTERVAL_FORTNIGHTLY . "'";
				if($user_group_interval == DIGEST_INTERVAL_FORTNIGHTLY){
					$group_items .= " selected='selected'";
				}
				$group_items .= ">" . elgg_echo("digest:interval:fortnightly") . "</option>\n";
				
				$group_items .= "<option value='" . DIGEST_INTERVAL_MONTHLY . "'";
				if($user_group_interval == DIGEST_INTERVAL_MONTHLY){
					$group_items .= " selected='selected'";
				}
				$group_items .= ">" . elgg_echo("digest:interval:monthly") . "</option>\n";
				
				$group_items .= "</select>\n";
				//end select
				
				$group_items .= "</td>\n";
				$group_items .= "</tr>\n";
			}
		}
		
		if(!empty($group_items)){
			$group_list = "<table class='digest_table_layout'>\n";
			
			$group_list .= "<tr>\n";
			$group_list .= "<th>" . elgg_echo("digest:usersettings:groups:group_header") . "</th>\n";
			$group_list .= "<th>" . elgg_echo("digest:usersettings:groups:setting_header") . "</th>\n";
			$group_list .= "</tr>\n";
			
			$group_list .= $group_items;
			
			$group_list .= "</table>\n";
			
			?>
			<div>
				<h3 class="settings"><?php echo elgg_echo("digest:usersettings:groups:title"); ?></h3>
			
				<div><?php echo elgg_echo("digest:usersettings:groups:description"); ?></div>
				
				<?php echo $group_list; ?>
			</div>
			
			<?php
		}
	}
?>