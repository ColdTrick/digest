<?php 

	global $CONFIG;

	$user = get_loggedin_user();
	
	$group = $vars["group"];

	$ts_lower = sanitise_int($vars["ts_lower"]);
	$ts_upper = sanitise_int($vars["ts_upper"]);
	
	// if data returns nothing make sure to echo nothing

	// Everything below this can be customized and extended to your own needs

	if(!empty($group) && ($group instanceof ElggGroup) && is_plugin_enabled("groups")){

		$group_guid = $group->getGUID();
		
		$offset = 0;
		$limit = 25;
		
		// retrieve recent group activity
		$sql = "SELECT {$CONFIG->dbprefix}river.* FROM {$CONFIG->dbprefix}river";
		$sql .= " INNER JOIN {$CONFIG->dbprefix}entities AS e ON {$CONFIG->dbprefix}river.object_guid = e.guid"; // river event -> object
		$sql .= " WHERE (e.container_guid = $group_guid OR {$CONFIG->dbprefix}river.object_guid = $group_guid)"; // filter by group
		$sql .= " AND {$CONFIG->dbprefix}river.posted BETWEEN {$ts_lower} AND {$ts_upper}"; // filter interval
		$sql .= " AND " . get_access_sql_suffix("e"); // filter access
		$sql .= " ORDER BY posted DESC limit {$offset},{$limit}";
		
		$items = get_data($sql);

        if (!empty($items)) {
        	echo elgg_view('river/item/list',array(
												'limit' => $limit,
												'offset' => $offset,
												'items' => $items,
												'pagination' => false
											));						
		}
		
		unset($items);		
		
	}	
?>