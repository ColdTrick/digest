<?php

	/**
	* This view displays a link to the online version of this digest. 
	* In case the user can't view the Digest correcly in his/her email program
	*
	* Available in $vars
	* 	$vars['user'] 		=> the current user for whom we're creating the digest
	* 	$vars['group'] 		=> (optional) the current group for whom we're creating the digest
	* 	$vars['ts_lower']	=> the lower time limit of the content in this digest
	* 	$vars['ts_upper']	=> the upper time limit of the content in this digest
	* 	$vars['interval']	=> the interval of the current digest
	* 							(as defined in DIGEST_INTERVAL_DAILY, DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY)
	*
	*/

	// make link
	$digest_url = digest_get_online_url($vars);
	
	echo elgg_echo("digest:elements:online", array("<a href='" . $digest_url . "'>", "</a>"));
