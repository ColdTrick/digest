<?php

$setting = digest_get_default_site_interval();
if (!empty($setting) && ($setting != DIGEST_INTERVAL_NONE)) {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('digest:register:enable'),
		'name' => 'digest_site',
		'value' => 'yes',
		'default' => false,
		'switch' => true,
	]);
}
