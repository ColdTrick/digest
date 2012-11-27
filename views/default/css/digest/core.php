<?php
	/**
	 * Base CSS for the Digest message
	 */
?>

body {
	background: #f6f6f6;
	color: #333333;
	font: 80%/1.4 "Lucida Grande",Arial,Tahoma,Verdana,sans-serif;
}

a {
	color: #4690d6;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

img {
	border: none;
}

h1,
h2,
h3,
h4 {
	color: #4690d6;
	margin: 0;
}

h1 {
	font-size: 18px;
}

h2 {
	font-size: 16px;
}

h3 {
	font-size: 16px;
}

h4 {
	font-size: 14px;
}

#digest_online {
	font-size: 11px;
	color: #999999;
	text-align: right;
	padding: 10px 20px 0px;
}

#digest_header {
	padding: 10px 30px;
	min-height: 20px;
	
	background: #4690d6;
	
	border-top: 1px solid #dbdbdb;
	border-left: 1px solid #dbdbdb;
	border-bottom: 1px solid #dbdbdb;
	border-right: 1px solid #dbdbdb;
	
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}

#digest_header h1{
	color: #FFFFFF;	
}

#digest_container {
	padding: 20px 0;
	width: 600px;
	margin: 0 auto;
}

#digest_content {
	min-height: 100px;
}

#digest_unsubscribe {
	font-size: 11px;
	color: #999999;
	padding: 20px;
}

#digest_footer {
	padding: 30px;
	background: #F0F0F0;
	
	border-top: 1px solid #FFFFFF;
	border-left: 1px solid #dbdbdb;
	border-bottom: 1px solid #dbdbdb;
	border-right: 1px solid #dbdbdb;
	
	-webkit-border-radius: 0 0 5px 5px;
	-moz-border-radius: 0 0 5px 5px;
	border-radius: 0 0 5px 5px;
}

.digest-footer-quote {
	text-align: center;
	font-size: 20px;
	color: #AFAFAF;
}

.digest-footer-quote table {
	width: 100%;
}

.digest-footer-quote-left {
	vertical-align: top;
	padding-right: 20px;
	
}

.digest-footer-quote-right {
	vertical-align: bottom;
	padding-left: 20px;
}

/* ********************************
	Digest module
********************************* */
.elgg-module-digest {
	background: #FFFFFF;
	padding: 30px;
	
	border-top: 1px solid #FFFFFF;
	border-left: 1px solid #dbdbdb;
	border-bottom: 1px solid #dbdbdb;
	border-right: 1px solid #dbdbdb;
}

.elgg-module-digest .elgg-head {
	padding-bottom: 5px;
	border-bottom: 1px solid #dbdbdb;
}

.elgg-module-digest h1 a,
.elgg-module-digest h2 a,
.elgg-module-digest h3 a {
	text-decoration: none;
}

/* ***************************************
	AVATAR ICONS
*************************************** */
.elgg-avatar {
	position: relative;
	display: inline-block;
}
.elgg-avatar > a > img {
	display: block;
}
.elgg-avatar-tiny > a > img {
	width: 25px;
	height: 25px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 25px;
	-khtml-background-size: 25px;
	-moz-background-size: 25px;
	-o-background-size: 25px;
	background-size: 25px;
}
.elgg-avatar-small > a > img {
	width: 40px;
	height: 40px;
	
	/* remove the border-radius if you don't want rounded avatars in supported browsers */
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	-moz-background-clip:  border;
	background-clip:  border;

	-webkit-background-size: 40px;
	-khtml-background-size: 40px;
	-moz-background-size: 40px;
	-o-background-size: 40px;
	background-size: 40px;
}
.elgg-avatar-medium > a > img {
	width: 100px;
	height: 100px;
}
.elgg-avatar-large > a > img {
	width: 200px;
	height: 200px;
}

/* ***************************************
	ICONS
*************************************** */

.elgg-icon {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;
	width: 16px;
	height: 16px;
	margin: 0 2px;
}

.elgg-icon-arrow-right {
	background-position: 0 -18px;
}

/* *****************************************
	Lists
****************************************** */
.elgg-list {
	list-style: none;
}

/* *****************************************
	Clearfix 
***************************************** */
.clearfix:after,
.elgg-inner:after,
.elgg-head:after,
.elgg-foot:after,
.elgg-image-block:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;	
}

/* ******************************************************
	Fluid width container that does not wrap floats 
******************************************************* */
.elgg-body {
	display: block;
	width: auto;
	word-wrap: break-word;
	overflow: hidden;
	
	/* IE 6, 7 */
	zoom: 1;
	*overflow: visible;
}