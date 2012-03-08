<?php

	/**
	 * This is a wrapped view to make the body of the site digest
	 * 
	 * Plugins can extend this view to make some content available
	 * 
	 * Available in $vars
	 * 		$vars['user'] 		=> the current user for whom we're creating the digest
	 * 		$vars['ts_lower']	=> the lower time limit of the content in this digest
	 * 		$vars['ts_upper']	=> the upper time limit of the content in this digest
	 * 
	 */