<?php 

	$user = elgg_extract("user", $vars);

	$site_guid = get_config("site_guid");
	$site_interval = $user->getPrivateSetting("digest_" . $site_guid);
	
	if(empty($site_interval)){
		$site_interval = digest_get_default_site_interval();
	}
	
?>
<div><?php echo elgg_echo("digest:usersettings:site:description"); ?></div>

<br />

<div>
	<?php echo elgg_echo("digest:usersettings:site:setting"); ?>
	<select name="digest[<?php echo $site_guid; ?>]">
		<option value="<?php echo DIGEST_INTERVAL_NONE; ?>" class='digest_interval_disabled' <?php if($site_interval == DIGEST_INTERVAL_NONE) echo "selected='selected'"; ?>><?php echo elgg_echo("digest:interval:none"); ?></option>
		<option value="<?php echo DIGEST_INTERVAL_DAILY; ?>" <?php if($site_interval == DIGEST_INTERVAL_DAILY) echo "selected='selected'"; ?>><?php echo elgg_echo("digest:interval:daily"); ?></option>
		<option value="<?php echo DIGEST_INTERVAL_WEEKLY; ?>" <?php if($site_interval == DIGEST_INTERVAL_WEEKLY) echo "selected='selected'"; ?>><?php echo elgg_echo("digest:interval:weekly"); ?></option>
		<option value="<?php echo DIGEST_INTERVAL_FORTNIGHTLY; ?>" <?php if($site_interval == DIGEST_INTERVAL_FORTNIGHTLY) echo "selected='selected'"; ?>><?php echo elgg_echo("digest:interval:fortnightly"); ?></option>
		<option value="<?php echo DIGEST_INTERVAL_MONTHLY; ?>" <?php if($site_interval == DIGEST_INTERVAL_MONTHLY) echo "selected='selected'"; ?>><?php echo elgg_echo("digest:interval:monthly"); ?></option>
	</select>
</div>