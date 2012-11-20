<?php

	/**
	* Shows the activity of the current group in the Digest
	*
	*/

	$user = elgg_extract("user", $vars, elgg_get_logged_in_user_entity());
	$group = elgg_extract("group", $vars);
	$ts_lower = (int) elgg_extract("ts_lower", $vars);
	$ts_upper = (int) elgg_extract("ts_upper", $vars);
	
	$dbprefix = get_config("dbprefix");
	$group_guid = $group->getGUID();

	$offset = 0;
	$limit = 25;

	// retrieve recent group activity
	$sql = "SELECT r.*";
	$sql .= " FROM " . $dbprefix . "river r";
	$sql .= " INNER JOIN " . $dbprefix . "entities AS e ON r.object_guid = e.guid"; // river event -> object
	$sql .= " WHERE (e.container_guid = $group_guid OR r.object_guid = $group_guid)"; // filter by group
	$sql .= " AND r.posted BETWEEN " . $ts_lower . " AND " . $ts_upper; // filter interval
	$sql .= " AND " . get_access_sql_suffix("e"); // filter access
	$sql .= " ORDER BY posted DESC";
	$sql .= " LIMIT " . $offset . "," . $limit;

	$items = get_data($sql, "elgg_row_to_elgg_river_item");

	if (!empty($items)) {
		$title = elgg_view("output/url", array("text" => elgg_echo("groups:activity"), "href" => $group->getURL()));
		
		$options = array(
			"list_class" => "elgg-list-river elgg-river",
			"items" => $items,
			"pagination" => false
		);
		
		$content = elgg_view("page/components/list", $options);
		
		echo elgg_view_module("digest", $title, $content);
	}

	unset($items);
