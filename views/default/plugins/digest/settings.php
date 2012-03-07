<?php

	$plugin = $vars["entity"];
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
	
	$interval_options = array(
		DIGEST_INTERVAL_NONE => elgg_echo("digest:interval:none"),
		DIGEST_INTERVAL_DAILY => elgg_echo("digest:interval:daily"),
		DIGEST_INTERVAL_WEEKLY => elgg_echo("digest:interval:weekly"),
		DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo("digest:interval:fortnightly"),
		DIGEST_INTERVAL_MONTHLY => elgg_echo("digest:interval:monthly")
	);

?>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo("digest:settings:production:title"); ?></h3>
	</div>
	
	<div><?php echo elgg_echo("digest:settings:production:description"); ?></div>
	<br />
	
	<div>
		<?php 
			echo elgg_echo("digest:settings:production:option"); 
			echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[in_production]", "options_values" => $noyes_options, "value" => $plugin->in_production));
		?>
	</div>
	
	<div>
		<?php 
			echo elgg_echo("digest:settings:production:group_option"); 
			echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[group_production]", "options_values" => $noyes_options, "value" => $plugin->group_production));
		?>
	</div>
</div>

<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo("digest:settings:interval:title"); ?></h3>
	</div>
	
	<div>
		<?php 
			echo elgg_echo("digest:settings:interval:site_default"); 
			echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[site_default]", "options_values" => $interval_options, "value" => $plugin->site_default));
		?>
	</div>
	
	<div>
		<?php 
			echo elgg_echo("digest:settings:interval:group_default"); 
			echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[group_default]", "options_values" => $interval_options, "value" => $plugin->group_default));
		?>
	</div>
	
	<br />
	<div><?php echo elgg_echo("digest:settings:interval:description"); ?></div>
</div>
	
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo("digest:settings:never:title"); ?></h3>
	</div>
	
	<div>
		<?php 
			echo elgg_echo("digest:settings:never:include");
			echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[include_never_logged_in]", "options_values" => $noyes_options, "value" => $plugin->include_never_logged_in));
		?>
	</div>
</div>