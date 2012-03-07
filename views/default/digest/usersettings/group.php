<?php 
	
	if($user = elgg_get_logged_in_user_entity()){
		$group = elgg_get_page_owner_entity();
	
		if(elgg_trigger_plugin_hook("digest", "group", array("group" => $group), true)){
			if($group->isMember($user->getGUID())){
				$group_interval = $group->digest_interval;
				
				if(empty($group_interval)){
					$group_interval = digest_get_default_group_interval();
				}
				
				$user_group_interval = $user->getPrivateSetting("digest_" . $group->getGUID());
				
				if(empty($user_group_interval)){
					$user_group_interval = DIGEST_INTERVAL_DEFAULT;
				}
				
				// build form
				$form_body = elgg_view("input/hidden", array("name" => "user_guid", "value" => $user->getGUID()));
				
				$form_body .= "&nbsp;<select name='digest[" . $group->getGUID() . "]' id='digest_usersettings_group_select'>\n";
				
				$form_body .= "<option value='" . DIGEST_INTERVAL_NONE . "' class='digest_interval_disabled'";
				if($user_group_interval == DIGEST_INTERVAL_NONE){
					$form_body .= " selected='selected'";
				}
				$form_body .= ">" . elgg_echo("digest:interval:none") . "</option>\n";
				
				$form_body .= "<option value='" . DIGEST_INTERVAL_DEFAULT . "'";
				if($user_group_interval == DIGEST_INTERVAL_DEFAULT){
					$form_body .= " selected='selected'";
				}
				$form_body .= ">" . elgg_echo("digest:interval:default", array(elgg_echo("digest:interval:" . $group_interval))) . "</option>\n";
				
				$form_body .= "<option value='" . DIGEST_INTERVAL_DAILY . "'";
				if($user_group_interval == DIGEST_INTERVAL_DAILY){
					$form_body .= " selected='selected'";
				}
				$form_body .= ">" . elgg_echo("digest:interval:daily") . "</option>\n";
				
				$form_body .= "<option value='" . DIGEST_INTERVAL_WEEKLY . "'";
				if($user_group_interval == DIGEST_INTERVAL_WEEKLY){
					$form_body .= " selected='selected'";
				}
				$form_body .= ">" . elgg_echo("digest:interval:weekly") . "</option>\n";
				
				$form_body .= "<option value='" . DIGEST_INTERVAL_FORTNIGHTLY . "'";
				if($user_group_interval == DIGEST_INTERVAL_FORNIGHTLY){
					$form_body .= " selected='selected'";
				}
				$form_body .= ">" . elgg_echo("digest:interval:fortnightly") . "</option>\n";
				
				$form_body .= "<option value='" . DIGEST_INTERVAL_MONTHLY . "'";
				if($user_group_interval == DIGEST_INTERVAL_MONTHLY){
					$form_body .= " selected='selected'";
				}
				$form_body .= ">" . elgg_echo("digest:interval:monthly") . "</option>\n";
				
				$form_body .= "</select>\n";
				
				$form = elgg_view("input/form", array("body" => $form_body,
														"action" => $vars["url"] . "action/digest/update/usersettings"));
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#digest_usersettings_group_select').change(function(){
			$(this).parent('form').submit();
		});
	});
</script>

<div id="owner_block_digest_group" title="<?php echo elgg_echo("digest:usersettings:group:setting"); ?>">
	<?php 
	 	echo elgg_view("output/url", array("href" => $vars["url"] . "pg/digest/user/" . $user->username, "text" => elgg_view_icon("info") . elgg_echo("digest:usersettings:group:more")));
		echo "<br />";
	
		echo $form; 
	 ?>
</div>
<?php 
			}
		} 
	}
	