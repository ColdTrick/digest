<?php

use ColdTrick\Digest\Bootstrap;
use Elgg\Router\Middleware\AdminGatekeeper;
use Elgg\Router\Middleware\Gatekeeper;

define('DIGEST_INTERVAL_NONE', 'none');
define('DIGEST_INTERVAL_DEFAULT', 'default');
define('DIGEST_INTERVAL_DAILY', 'daily');
define('DIGEST_INTERVAL_WEEKLY', 'weekly');
define('DIGEST_INTERVAL_FORTNIGHTLY', 'fortnightly');
define('DIGEST_INTERVAL_MONTHLY', 'monthly');

// Required libs & custom functions
require_once(__DIR__ . '/lib/functions.php');

return [
	'bootstrap' => Bootstrap::class,
	'actions' => [
		'digest/settings/save' => ['access' => 'admin'],
		'digest/reset_stats' => ['access' => 'admin'],
		'digest/usersettings' => [],
		'digest/groupsettings' => [],
	],
	'routes' => [
		'default:digest' => [
			'path' => '/digest/test/{digest?}/{interval?}/{group_guid?}',
			'resource' => 'digest/test',
			'middleware' => [
				AdminGatekeeper::class,
			],
			'defaults' => [
				'digest' => 'site',
				'interval' => DIGEST_INTERVAL_MONTHLY,
			],
		],
		'digest:show' => [
			'path' => '/digest/show',
			'resource' => 'digest/show',
			'middleware' => [
				Gatekeeper::class,
			],
		],
		'digest:unsubscribe' => [
			'path' => '/digest/unsubscribe',
			'resource' => 'digest/unsubscribe',
			'walled' => false,
		],
		'digest:user' => [
			'path' => '/digest/user/{username?}',
			'resource' => 'digest/usersettings',
			'middleware' => [
				Gatekeeper::class,
			],
		],
	],
	'hooks' => [
		'register' => [
			'daily' => [
				'\ColdTrick\Digest\Cron::sendDigests' => [],
			],
			'menu:page' => [
				'\ColdTrick\Digest\Menus::registerPageMenuItems' => [],
			],
			'menu:theme_sandbox' => [
				'\ColdTrick\Digest\Menus::registerThemeSandboxMenuItems' => [],
			],
			'menu:groups:my_status' => [
				'\ColdTrick\Digest\Menus::registerGroupStatusMenuItems' => [],
			],
			'user' => [
				'\ColdTrick\Digest\User::savePreferenceOnRegister' => [],
			],
		],
		'cron' => [
			'daily' => [
				'\ColdTrick\Digest\Cron::sendDigests' => [],
			],
		],
	],
	'events' => [
		'leave' => [
			'group' => [
				'\ColdTrick\Digest\Groups::removeDigestSettingOnLeave' => [],
			],
		],
	],
];

