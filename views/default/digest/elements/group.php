<?php
	/**
	* This is a wrapper view to make the body of the group digest
	*
	* Plugins can extend this view to make some content available
	*
	* Available in $vars
	* 	$vars['user'] 		=> the current user for whom we're creating the digest
	* 	$vars['group']		=> the current groups for whom we're creating the digest
	* 	$vars['ts_lower']	=> the lower time limit of the content in this digest
	* 	$vars['ts_upper']	=> the upper time limit of the content in this digest
	*
	*/