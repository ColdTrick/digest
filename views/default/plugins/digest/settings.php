<?php

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$noyes_options = [
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes"),
];
	
$distribution_options_week = [
	0 => elgg_echo("digest:day:sunday"),
	1 => elgg_echo("digest:day:monday"),
	2 => elgg_echo("digest:day:tuesday"),
	3 => elgg_echo("digest:day:wednesday"),
	4 => elgg_echo("digest:day:thursday"),
	5 => elgg_echo("digest:day:friday"),
	6 => elgg_echo("digest:day:saturday"),
	"distributed" => elgg_echo("digest:distribution:distributed"),
];

$distribution_options_month = array_combine(range(1, 28), range(1, 28)); // both ranges are needed to keep keys and values same value. 28 is max to prevent complex issues with last day of month
$distribution_options_month["distributed"] = elgg_echo("digest:distribution:distributed");

$site_default_checked = [
	DIGEST_INTERVAL_NONE => "",
	DIGEST_INTERVAL_DAILY => "",
	DIGEST_INTERVAL_WEEKLY => "",
	DIGEST_INTERVAL_FORTNIGHTLY => "",
	DIGEST_INTERVAL_MONTHLY => "",
];

$group_default_checked = $site_default_checked;

if ($site_default = $plugin->site_default) {
	$site_default_checked[$site_default] = "checked='checked'";
} else {
	$site_default_checked[DIGEST_INTERVAL_NONE] = "checked='checked'";
}

if ($group_default = $plugin->group_default) {
	$group_default_checked[$group_default] = "checked='checked'";
} else {
	$group_default_checked[DIGEST_INTERVAL_NONE] = "checked='checked'";
}

// Interval Settings
$settings_interval = "<table class='elgg-table-alt mbm'>";
$settings_interval .= "<tr>";
$settings_interval .= "<th>&nbsp;</th>";
$settings_interval .= "<th class='center'>" . elgg_echo("site") . "</th>";
$settings_interval .= "<th class='center'>" . elgg_echo("group") . "</th>";
$settings_interval .= "<th>" . elgg_echo("digest:distribution") . "<span title='" . htmlspecialchars(elgg_echo("digest:distribution:description"), ENT_QUOTES, "UTF-8", false) . "'>" . elgg_view_icon("info", "mlm") .  "</span></th>";
$settings_interval .= "</tr>";

$settings_interval .= "<tr>";
$settings_interval .= "<td>" . elgg_echo("digest:settings:production") . "<span title='" . htmlspecialchars(elgg_echo("digest:settings:production:description"), ENT_QUOTES, "UTF-8", false) . "'>" . elgg_view_icon("info", "mlm") .  "</span></td>";
$settings_interval .= "<td class='center'>" . elgg_view("input/select", array("name" => "params[in_production]", "options_values" => $noyes_options, "value" => $plugin->in_production)) . "</td>";
$settings_interval .= "<td class='center'>" . elgg_view("input/select", array("name" => "params[group_production]", "options_values" => $noyes_options, "value" => $plugin->group_production)) . "</td>";
$settings_interval .= "<td>&nbsp;</td>";
$settings_interval .= "</tr>";

$settings_interval .= "<tr><td colspan='4'>&nbsp;</td></tr>";

$settings_interval .= "<tr><th colspan='4'>" . elgg_echo("digest:settings:interval:default") . "</th></tr>";

$settings_interval .= "<tr>";
$settings_interval .= "<td><span class='plm'>" . elgg_echo("digest:interval:none") . "</span></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_NONE . "' " . $site_default_checked[DIGEST_INTERVAL_NONE] . " title='" . elgg_echo("digest:interval:none") . "'/></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_NONE . "' " . $group_default_checked[DIGEST_INTERVAL_NONE] . " title='" . elgg_echo("digest:interval:none") . "'/></td>";
$settings_interval .= "<td>&nbsp;</td>";
$settings_interval .= "</tr>";

$settings_interval .= "<tr>";
$settings_interval .= "<td><span class='plm'>" . elgg_echo("digest:interval:daily") . "</span></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_DAILY . "' " . $site_default_checked[DIGEST_INTERVAL_DAILY] . " title='" . elgg_echo("digest:interval:daily") . "'/></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_DAILY . "' " . $group_default_checked[DIGEST_INTERVAL_DAILY] . " title='" . elgg_echo("digest:interval:daily") . "'/></td>";
$settings_interval .= "<td>&nbsp;</td>";
$settings_interval .= "</tr>";

$settings_interval .= "<tr>";
$settings_interval .= "<td><span class='plm'>" . elgg_echo("digest:interval:weekly") . "</span></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_WEEKLY . "' " . $site_default_checked[DIGEST_INTERVAL_WEEKLY] . " title='" . elgg_echo("digest:interval:weekly") . "'/></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_WEEKLY . "' " . $group_default_checked[DIGEST_INTERVAL_WEEKLY] . " title='" . elgg_echo("digest:interval:weekly") . "'/></td>";
$settings_interval .= "<td>" . elgg_view("input/select", array("name" => "params[weekly_distribution]", "options_values" => $distribution_options_week, "value" => $plugin->weekly_distribution)) . "</td>";
$settings_interval .= "</tr>";

$settings_interval .= "<tr>";
$settings_interval .= "<td><span class='plm'>" .  elgg_echo("digest:interval:fortnightly") . "</span></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_FORTNIGHTLY . "' " . $site_default_checked[DIGEST_INTERVAL_FORTNIGHTLY] . " title='" . elgg_echo("digest:interval:fortnightly") . "'/></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_FORTNIGHTLY . "' " . $group_default_checked[DIGEST_INTERVAL_FORTNIGHTLY] . " title='" . elgg_echo("digest:interval:fortnightly") . "'/></td>";
$settings_interval .= "<td>" . elgg_view("input/select", array("name" => "params[fortnightly_distribution]", "options_values" => $distribution_options_week, "value" => $plugin->fortnightly_distribution)) . "</td>";
$settings_interval .= "</tr>";

$settings_interval .= "<tr>";
$settings_interval .= "<td><span class='plm'>" .  elgg_echo("digest:interval:monthly") . "</span></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[site_default]' value='" . DIGEST_INTERVAL_MONTHLY . "' " . $site_default_checked[DIGEST_INTERVAL_MONTHLY] . " title='" . elgg_echo("digest:interval:monthly") . "'/></td>";
$settings_interval .= "<td class='center'><input type='radio' name='params[group_default]' value='" . DIGEST_INTERVAL_MONTHLY . "' " . $group_default_checked[DIGEST_INTERVAL_MONTHLY] . " title='" . elgg_echo("digest:interval:monthly") . "'/></td>";
$settings_interval .= "<td>" . elgg_view("input/select", array("name" => "params[monthly_distribution]", "options_values" => $distribution_options_month, "value" => $plugin->monthly_distribution)) . "</td>";
$settings_interval .= "</tr>";

$settings_interval .= "</table>";

// Should we include user who never logged in
$settings_interval .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('digest:settings:never:include'),
	'name' => 'params[include_never_logged_in]',
	'options_values' => $noyes_options,
	'value' => $plugin->include_never_logged_in,
]);

echo elgg_view_message('notice', elgg_echo('digest:settings:notice'), ['title' => false]);

$interval_info = elgg_view_icon('info', [
	'class' => 'mlm',
	'title' => elgg_echo('digest:settings:interval:description'),
]);
echo elgg_view_module('info', elgg_echo('digest:settings:interval:title') . $interval_info, $settings_interval);

// add custom header and footer to every digest
$text_sections = [
	'custom_text_site_header' => elgg_echo('digest:settings:custom_text:site:header'),
	'custom_text_site_footer' => elgg_echo('digest:settings:custom_text:site:footer'),
	'custom_text_group_header' => elgg_echo('digest:settings:custom_text:group:header'),
	'custom_text_group_footer' => elgg_echo('digest:settings:custom_text:group:footer'),
];

$custom_text = '';
foreach ($text_sections as $section_name => $section_label) {
	$custom_text .= elgg_view_field([
		'#type' => 'longtext',
		'#label' => $section_label,
		'name' => "params[{$section_name}]",
		'value' => $plugin->{$section_name},
	]);
}

$custom_text_info = elgg_view_icon('info', [
	'class' => 'mlm',
	'title' => elgg_echo('digest:settings:custom_text:description'),
]);
echo elgg_view_module('info', elgg_echo('digest:settings:custom_text:title') . $custom_text_info, $custom_text);

// multi-core support
if (digest_multi_core_supported()) {
	$multi_core = "<div class='elgg-admin-notices pbn'><p>" . elgg_echo("digest:settings:multi_core:warning") . "</p></div>";
	
	$multi_core .= elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('digest:settings:multi_core:number'),
		'name' => 'params[multi_core]',
		'value' => $plugin->multi_core,
		'options' => [1, 2, 4, 8],
	]);
	
	$multi_core_info = elgg_view_icon('info', [
		'class' => 'mlm',
		'title' => elgg_echo('digest:settings:multi_core:description'),
	]);
	echo elgg_view_module('info', elgg_echo('digest:settings:multi_core:title') . $multi_core_info, $multi_core);
}

// stats
$stats = elgg_view('output/url', [
	'href' => 'action/digest/reset_stats',
	'text' => elgg_echo('digest:settings:stats:reset'),
	'class' => 'elgg-button elgg-button-action',
	'confirm' => true,
]);

echo elgg_view_module('info', elgg_echo('digest:settings:stats:title'), $stats);
