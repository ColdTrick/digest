<?php 


?>
.digest_table_layout {
	width: 85%;
}

.digest_table_layout td,
.digest_table_layout th {
	white-space: nowrap;
}

.digest_table_layout th {
	font-weight: bold;
	text-align: left;
}

.digest_table_layout_left {
	width: 100%;
}

#digest_usersettings_group_select {
	width: 170px;
	margin: 0 0 0 15px;
}

#owner_block_digest_group {
	padding: 5px 0 0;
}

#owner_block_digest_group a {
	color: #999999;
	padding: 0 0 4px 20px;
	background: url("<?php echo $vars["url"]; ?>_graphics/icon_customise_info.gif") transparent top left no-repeat;
	font-size: 90%;
}

option.digest_interval_disabled {
	color: #B6B6B6;
}

#digest_analysis_site table,
#digest_analysis_group table {
	width: 90%;
}

#digest_analysis_site th,
#digest_analysis_group th {
	font-weight: bold;
}

#digest_analysis_site .digest_analysis_interval,
#digest_analysis_group .digest_analysis_interval {
	width: 90px;
	text-align: right;
}
