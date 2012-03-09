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

.elgg-avatar .elgg-icon-hover-menu {
	display: none;
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
.elgg-icon-arrow-left {
	background-position: 0 -0px;
}
.elgg-icon-arrow-right {
	background-position: 0 -18px;
}
.elgg-icon-arrow-two-head {
	background-position: 0 -36px;
}
.elgg-icon-attention:hover {
	background-position: 0 -54px;
}
.elgg-icon-attention {
	background-position: 0 -72px;
}
.elgg-icon-calendar {
	background-position: 0 -90px;
}
.elgg-icon-cell-phone {
	background-position: 0 -108px;
}
.elgg-icon-checkmark:hover {
	background-position: 0 -126px;
}
.elgg-icon-checkmark {
	background-position: 0 -144px;
}
.elgg-icon-clip:hover {
	background-position: 0 -162px;
}
.elgg-icon-clip {
	background-position: 0 -180px;
}
.elgg-icon-cursor-drag-arrow {
	background-position: 0 -198px;
}
.elgg-icon-delete-alt:hover {
	background-position: 0 -216px;
}
.elgg-icon-delete-alt {
	background-position: 0 -234px;
}
.elgg-icon-delete:hover {
	background-position: 0 -252px;
}
.elgg-icon-delete {
	background-position: 0 -270px;
}
.elgg-icon-download:hover {
	background-position: 0 -288px;
}
.elgg-icon-download {
	background-position: 0 -306px;
}
.elgg-icon-eye {
	background-position: 0 -324px;
}
.elgg-icon-facebook {
	background-position: 0 -342px;
}
.elgg-icon-grid:hover {
	background-position: 0 -360px;
}
.elgg-icon-grid {
	background-position: 0 -378px;
}
.elgg-icon-home:hover {
	background-position: 0 -396px;
}
.elgg-icon-home {
	background-position: 0 -414px;
}
.elgg-icon-hover-menu:hover {
	background-position: 0 -432px;
}
.elgg-icon-hover-menu {
	background-position: 0 -450px;
}
.elgg-icon-info:hover {
	background-position: 0 -468px;
}
.elgg-icon-info {
	background-position: 0 -486px;
}
.elgg-icon-link:hover {
	background-position: 0 -504px;
}
.elgg-icon-link {
	background-position: 0 -522px;
}
.elgg-icon-list {
	background-position: 0 -540px;
}
.elgg-icon-lock-closed {
	background-position: 0 -558px;
}
.elgg-icon-lock-open {
	background-position: 0 -576px;
}
.elgg-icon-mail-alt:hover {
	background-position: 0 -594px;
}
.elgg-icon-mail-alt {
	background-position: 0 -612px;
}
.elgg-icon-mail:hover {
	background-position: 0 -630px;
}
.elgg-icon-mail {
	background-position: 0 -648px;
}
.elgg-icon-photo {
	background-position: 0 -666px;
}
.elgg-icon-print-alt {
	background-position: 0 -684px;
}
.elgg-icon-print {
	background-position: 0 -702px;
}
.elgg-icon-push-pin-alt {
	background-position: 0 -720px;
}
.elgg-icon-push-pin {
	background-position: 0 -738px;
}
.elgg-icon-redo {
	background-position: 0 -756px;
}
.elgg-icon-refresh:hover {
	background-position: 0 -774px;
}
.elgg-icon-refresh {
	background-position: 0 -792px;
}
.elgg-icon-round-arrow-left {
	background-position: 0 -810px;
}
.elgg-icon-round-arrow-right {
	background-position: 0 -828px;
}
.elgg-icon-round-checkmark {
	background-position: 0 -846px;
}
.elgg-icon-round-minus {
	background-position: 0 -864px;
}
.elgg-icon-round-plus {
	background-position: 0 -882px;
}
.elgg-icon-rss {
	background-position: 0 -900px;
}
.elgg-icon-search-focus {
	background-position: 0 -918px;
}
.elgg-icon-search {
	background-position: 0 -936px;
}
.elgg-icon-settings-alt:hover {
	background-position: 0 -954px;
}
.elgg-icon-settings-alt {
	background-position: 0 -972px;
}
.elgg-icon-settings {
	background-position: 0 -990px;
}
.elgg-icon-share:hover {
	background-position: 0 -1008px;
}
.elgg-icon-share {
	background-position: 0 -1026px;
}
.elgg-icon-shop-cart:hover {
	background-position: 0 -1044px;
}
.elgg-icon-shop-cart {
	background-position: 0 -1062px;
}
.elgg-icon-speech-bubble-alt:hover {
	background-position: 0 -1080px;
}
.elgg-icon-speech-bubble-alt {
	background-position: 0 -1098px;
}
.elgg-icon-speech-bubble:hover {
	background-position: 0 -1116px;
}
.elgg-icon-speech-bubble {
	background-position: 0 -1134px;
}
.elgg-icon-star-alt {
	background-position: 0 -1152px;
}
.elgg-icon-star-empty:hover {
	background-position: 0 -1170px;
}
.elgg-icon-star-empty {
	background-position: 0 -1188px;
}
.elgg-icon-star:hover {
	background-position: 0 -1206px;
}
.elgg-icon-star {
	background-position: 0 -1224px;
}
.elgg-icon-tag:hover {
	background-position: 0 -1242px;
}
.elgg-icon-tag {
	background-position: 0 -1260px;
}
.elgg-icon-thumbs-down-alt:hover {
	background-position: 0 -1278px;
}
.elgg-icon-thumbs-down:hover,
.elgg-icon-thumbs-down-alt {
	background-position: 0 -1296px;
}
.elgg-icon-thumbs-down {
	background-position: 0 -1314px;
}
.elgg-icon-thumbs-up-alt:hover {
	background-position: 0 -1332px;
}
.elgg-icon-thumbs-up:hover,
.elgg-icon-thumbs-up-alt {
	background-position: 0 -1350px;
}
.elgg-icon-thumbs-up {
	background-position: 0 -1368px;
}
.elgg-icon-trash {
	background-position: 0 -1386px;
}
.elgg-icon-twitter {
	background-position: 0 -1404px;
}
.elgg-icon-undo {
	background-position: 0 -1422px;
}
.elgg-icon-user:hover {
	background-position: 0 -1440px;
}
.elgg-icon-user {
	background-position: 0 -1458px;
}
.elgg-icon-users:hover {
	background-position: 0 -1476px;
}
.elgg-icon-users {
	background-position: 0 -1494px;
}
.elgg-icon-video {
	background-position: 0 -1512px;
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
.elgg-grid:after,
.elgg-layout:after,
.elgg-inner:after,
.elgg-page-header:after,
.elgg-page-footer:after,
.elgg-head:after,
.elgg-foot:after,
.elgg-col:after,
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
.elgg-body,
.elgg-col-last {
	display: block;
	width: auto;
	word-wrap: break-word;
	overflow: hidden;
	
	/* IE 6, 7 */
	zoom: 1;
	*overflow: visible;
}