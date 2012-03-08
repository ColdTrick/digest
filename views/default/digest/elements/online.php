<?php

$digest_url = elgg_get_site_url() . "digest/show?ts_upper=" . $ts_upper . "&ts_lower=" . $ts_lower . "&interval=monthly";
$digest_online = "<a href='" . $digest_url . "'>" . elgg_echo("digest:message:online") . "</a><br />";
