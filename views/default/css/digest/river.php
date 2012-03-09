<?php
	/**
	 * River / activity CSS for Digest message
	 */
?>

/* ***************************************
	River
*************************************** */
.elgg-river {
	padding: 0;
	margin: 0;
}

.elgg-river-item .elgg-image {
	float: left;
	margin-right: 5px;
}

.elgg-river-item .elgg-avatar > .elgg-menu,
.elgg-river-responses,
.elgg-menu-river {
	display: none;
}

.elgg-list-river > li {
	border-bottom: 1px solid #CCC;
	min-height: 55px;
}
.elgg-river-item {
	padding: 7px 0;
}
.elgg-river-item .elgg-pict {
	margin-right: 20px;
}
.elgg-river-timestamp {
	color: #666;
	font-size: 85%;
	font-style: italic;
	line-height: 1.2em;
}

.elgg-river-attachments,
.elgg-river-message,
.elgg-river-content {
	border-left: 1px solid #CCC;
	font-size: 85%;
	line-height: 1.5em;
	margin: 8px 0 5px 0;
	padding-left: 5px;
}
.elgg-river-attachments .elgg-avatar,
.elgg-river-attachments .elgg-icon {
	float: left;
}
.elgg-river-layout .elgg-input-dropdown {
	float: right;
	margin: 10px 0;
}

.elgg-river-comments-tab {
	display: block;
	background-color: #EEE;
	color: #4690D6;
	margin-top: 5px;
	width: auto;
	float: right;
	font-size: 85%;
	padding: 1px 7px;
	
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}

.elgg-river-comments {
	margin: 0;
	border-top: none;
}
.elgg-river-comments li:first-child {
	-webkit-border-radius: 5px 0 0;
	-moz-border-radius: 5px 0 0;
	border-radius: 5px 0 0;
}
.elgg-river-comments li:last-child {
	-webkit-border-radius: 0 0 5px 5px;
	-moz-border-radius-bottomleft: 0 0 5px 5px;
	border-radius-bottomleft: 0 0 5px 5px;
}
.elgg-river-comments li {
	background-color: #EEE;
	border-bottom: none;
	padding: 4px;
	margin-bottom: 2px;
}
.elgg-river-comments .elgg-media {
	padding: 0;
}
.elgg-river-more {
	background-color: #EEE;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	padding: 2px 4px;
	font-size: 85%;
	margin-bottom: 2px;
}

.elgg-river-item form {
	background-color: #EEE;
	padding: 4px;
	
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	
	height: 30px;
}
.elgg-river-item input[type=text] {
	width: 80%;
}
.elgg-river-item input[type=submit] {
	margin: 0 0 0 10px;
}
