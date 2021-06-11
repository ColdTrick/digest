<?php

namespace ColdTrick\Digest;

class Cron {
	
	/**
	 * Listen to the cron and check if we need to sent out digests
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void
	 */
	public static function sendDigests(\Elgg\Hook $hook) {
		global $interval_ts_upper;
		
		echo 'Starting Digest processing' . PHP_EOL;
		elgg_log('Starting Digest processing', 'NOTICE');
		
		$interval_ts_upper = (int) $hook->getParam('time', time());
		
		// prepare some settings
		$digest_settings = [
			'timestamp' => $interval_ts_upper,
			'fork_id' => 0,
		];
		
		// rebase the stats
		digest_rebase_stats($interval_ts_upper);
		
		// is multicore support enabled
		$cores = digest_get_number_of_cores();
		if ($cores <= 1) {
			echo '- Using single processing' . PHP_EOL;
			elgg_log('- Using single processing', 'NOTICE');
			
			// procces the digest
			digest_process($digest_settings);
			
			echo 'Done with Digest processing' . PHP_EOL;
			elgg_log('Done with Digest processing', 'NOTICE');
			
			return;
		}
		
		echo '- Using multicore processing' . PHP_EOL;
		elgg_log('- Using multicore processing', 'NOTICE');
			
		// add some settings for the commandline
		$digest_settings['memory_limit'] = ini_get('memory_limit');
		$digest_settings['host'] = $_SERVER['HTTP_HOST'];
		$digest_settings['secret'] = digest_generate_commandline_secret();
		if (isset($_SERVER['HTTPS'])) {
			$digest_settings['https'] = $_SERVER['HTTPS'];
		}
		
		// shoul we include users who have never logged in
		$include_never_logged_in = false;
		if (elgg_get_plugin_setting('include_never_logged_in', 'digest') == 'yes') {
			$include_never_logged_in = true;
		}
		
		// multi core is enabled now try to find out how many users/groups to send per core
		$site_users_count = 0;
		$site_users_interval = 0;
		$group_count = 0;
		$group_interval = 0;
		
		// site digest settings
		if (digest_site_enabled()) {
			$site_intervals = [
				DIGEST_INTERVAL_DEFAULT => digest_get_default_site_interval(),
				DIGEST_INTERVAL_WEEKLY => digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY),
				DIGEST_INTERVAL_FORTNIGHTLY => digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY),
				DIGEST_INTERVAL_MONTHLY => digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY),
				'include_never_logged_in' => $include_never_logged_in,
			];
			
			$site_users = digest_get_site_users($site_intervals);
			$site_users_count = count($site_users);
			
			$site_users_interval = (int) ceil($site_users_count / $cores);
		}
		
		// group digest settings
		if (digest_group_enabled()) {
			$group_count = elgg_get_entities([
				'type' => 'group',
				'count' => true,
			]);
			
			$group_interval = (int) ceil($group_count / $cores);
		}
		
		// start processes
		for ($i = 0; $i < $cores; $i++) {
			$digest_settings['fork_id'] = $i;
			
			if ($site_users_count > 0) {
				$digest_settings['site_offset'] = $site_users_interval * $i;
				$digest_settings['site_limit'] = $site_users_interval;
			}
			
			if ($group_count > 0) {
				$digest_settings['group_offset'] = $group_interval * $i;
				$digest_settings['group_limit'] = $group_interval;
			}
			
			echo "- Starting forkid: {$i}" . PHP_EOL;
			elgg_log("- Starting forkid: {$i}", 'NOTICE');
			
			digest_start_commandline($digest_settings);
		}
		
		echo 'Done with Digest forking' . PHP_EOL;
		elgg_log('Done with Digest forking', 'NOTICE');
	}
}
