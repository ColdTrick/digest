<?php
use ColdTrick\Digest\Bootstrap;

define('DIGEST_INTERVAL_NONE', 'none');
define('DIGEST_INTERVAL_DEFAULT', 'default');
define('DIGEST_INTERVAL_DAILY', 'daily');
define('DIGEST_INTERVAL_WEEKLY', 'weekly');
define('DIGEST_INTERVAL_FORTNIGHTLY', 'fortnightly');
define('DIGEST_INTERVAL_MONTHLY', 'monthly');

// Required libs & custom functions
require_once(__DIR__ . '/lib/digest/functions.php');


return [
	// Bootstrap must implement \Elgg\PluginBootstrapInterface
	'bootstrap' => Bootstrap::class,
	
	// Entities: register entity types for search
	'entities' => [
	],
	
	// Actions
	'actions' => [
		'digest/settings/save' => ['access' => 'admin'],
		'digest/reset_stats' => ['access' => 'admin'],
		'digest/usersettings' => [],
		'digest/groupsettings' => [],
	],
	
	// Routes
	'routes' => [
		'default:digest' => [
			'path' => '/digest/test/{digest?}/{interval?}/{group_guid?}',
			//'controller' => '\ColdTrick\Digest\Router::digest',
			'resource' => 'digest/test',
		],
		'digest:show' => [
			'path' => '/digest/show',
			'resource' => 'digest/show',
		],
		'digest:unsubscribe' => [
			'path' => '/digest/unsubscribe',
			'resource' => 'digest/unsubscribe',
		],
		'digest:user' => [
			'path' => '/digest/user/{username?}',
			'resource' => 'digest/usersettings',
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
		'public_pages' => [
			'walled_garden' => [
				'\ColdTrick\Digest\Site::extendWalledGardenPages' => [],
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
	
	
	// Widgets
	'widgets' => [],
	
];

