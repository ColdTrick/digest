<?php

namespace ColdTrick\Digest;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::init()
	 */
	public function init() {
		// extend register with subscribe option
		elgg_extend_view('register/extend', 'digest/register');
		
		// extend groups edit screen
		elgg_extend_view('groups/edit', 'digest/groupsettings', 400);
		
		// digest elements
		elgg_extend_view('css/digest/core', 'css/digest/river');
		elgg_extend_view('digest/elements/site', 'digest/elements/site/river');
		elgg_extend_view('digest/elements/group', 'digest/elements/group/river');
		
		elgg_extend_view('admin/statistics', 'admin/statistics/digest');
		
		if (elgg_is_active_plugin('blog')) {
			elgg_extend_view('css/digest/core', 'css/digest/blog');
			elgg_extend_view('digest/elements/site', 'digest/elements/site/blog');
		}
		
		if (elgg_is_active_plugin('groups')) {
			elgg_extend_view('css/digest/core', 'css/digest/groups');
			elgg_extend_view('digest/elements/site', 'digest/elements/site/groups');
		}
		
		if (elgg_is_active_plugin('profile')) {
			elgg_extend_view('css/digest/core', 'css/digest/profile');
			elgg_extend_view('digest/elements/site', 'digest/elements/site/profile');
		}
	}
}
