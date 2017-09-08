<?php
/**
 * command line interface for starting the digest
 */

if (!isset($argv) || !is_array($argv)) {
	exit('This script can only be run from the commandline');
}

$site_offset = 0;
$site_limit = 0;
$group_offset = 0;
$group_limit = 0;
$timestamp = time();
$secret = '';
$memory_limit = '64M';
$fork_id = 0;

foreach ($argv as $index => $arg) {
	if (($index === 0) || empty($arg)) {
		continue;
	}
	
	list($key, $value) = explode('=', $arg);
	
	switch ($key) {
		case 'host':
			$_SERVER['HTTP_HOST'] = $value;
			break;
		case 'https':
			$_SERVER['HTTPS'] = $value;
			break;
		case 'site_offset':
		case 'site_limit':
		case 'group_offset':
		case 'group_limit':
		case 'timestamp':
		case 'fork_id':
			$value = (int) $value;
			
			if ($value > 0) {
				$$key = $value;
			}
			break;
		default:
			$$key = $value;
			break;
	}
}

if (empty($secret) || (empty($site_limit) && empty($group_limit))) {
	exit('Wrong input to run this script, please provide a site_limit or group_limit and secret.');
}

ini_set('memory_limit', $memory_limit);

$autoload_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php';
if (!file_exists($autoload_path)) {
	exit("Unable to locate {$autoload_path}. Is Elgg installed correctly");
}

require_once($autoload_path);

\Elgg\Application::start();

if (!digest_validate_commandline_secret($secret)) {
	exit(elgg_echo('digest:cli:error:secret'));
}

$params = [
	'site_offset' => $site_offset,
	'site_limit' => $site_limit,
	'group_offset' => $group_offset,
	'group_limit' => $group_limit,
	'timestamp' => $timestamp,
	'fork_id' => $fork_id
];

digest_process($params);
