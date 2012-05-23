<?php

	$params = get_input("params", null, false);
	$plugin_id = get_input("plugin_id");
	
	$plugin = elgg_get_plugin_from_id($plugin_id);
	$plugin_name = $plugin->getManifest()->getName();
	
	if(!empty($plugin)){
		if(!empty($params) && is_array($params)){
			$special_inputs = array(
				"custom_text_site_header",
				"custom_text_site_footer",
				"custom_text_group_header",
				"custom_text_group_footer"
			);
			
			foreach($params as $key => $value){
				if(!in_array($key, $special_inputs)){
					$value = filter_tags($value);
				}
				
				if(!$plugin->setSetting($key, $value)){
					register_error(elgg_echo("plugins:settings:save:fail", array($plugin_name)));
					break;
				}
			}
		} else {
			register_error(elgg_echo("plugins:settings:save:fail", array($plugin_name)));
		}
	} else {
		register_error(elgg_echo("PluginException:InvalidID"));
	}
	
	forward(REFERER);