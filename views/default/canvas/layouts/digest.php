<?php 
	/**
	 * $vars['area1'] => subject
	 * $vars['area2'] => digest content
	 * $vars['area3'] => link to online view
	 * $vars['area4'] => unsubscribe link
	 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<base target="_blank" />
		
		<title><?php echo $vars["area1"];?></title>
	</head>
	<body>
		<style type="text/css">
		
			body {
				background: #dedede;
				color: #333333;
				font: 80%/1.4 "Lucida Grande",Verdana,sans-serif;
			}
			
			a {
				color: #4690d6;
			}
			
			table {
				width: 100%;
			}
			
			table a img {
			 	float: left;
			 	margin-right: 5px;
			}
			
			img {
				border: none;
			}
			
			h1,
			h2,
			h3 {
				color: #4690d6;
				margin: 0;
			}
			
			#digest_container {
				padding: 20px 0;
				width: 600px;
				margin: 0 auto;
			}
			
			#digest_wrapper {
				background: #FFFFFF;
				padding: 5px 5px 0 5px;
			}
			
			#digest_content {
				min-height: 100px;
				padding:0 20px 20px;
			}
			
			#digest_content h2 {
				font-size: 16px;
			}
			
			#digest_content h1 a,
			#digest_content h2 a,
			#digest_content h3 a {
				text-decoration: none;
			}
			
			#digest_content h1 a:hover,
			#digest_content h2 a:hover,
			#digest_content h3 a:hover {
				text-decoration: underline;
			}
			
			#digest_content h3 {
				font-size: 14px;
			}
			
			#digest_content .strapline {
				color: #aaaaaa;
				line-height: 1em;
			}
			
			#digest_footer {
				background: #B6B6B6;
				font-size: 11px;
				padding: 20px;
			}
			
			/* ***************************************
				RIVER
			*************************************** */
			#digest_river_item_list div.avatar_menu_button,
			#digest_river_item_list div.sub_menu {
				display: none;
			}
			#river,
			.river_item_list {
				border-top:1px solid #dddddd;
			}
			.river_item p {
				margin:0;
				padding:0 0 0 21px;
				line-height:1.1em;
				min-height:17px;
			}
			.river_item {
				border-bottom:1px solid #dddddd;
				padding:2px 0 2px 0;
			}
			.river_item_time {
				font-size:90%;
				color:#666666;
			}
			/* IE6 fix */
			* html .river_item p { 
				padding:3px 0 3px 20px;
			}
			/* IE7 */
			*:first-child+html .river_item p {
				min-height:17px;
			}
			.river_user_update {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
			}
			.river_object_user_profileupdate {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
			}
			.river_object_user_profileiconupdate {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_profile.gif) no-repeat left -1px;
			}
			.river_object_annotate {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_object_bookmarks_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_bookmarks.gif) no-repeat left -1px;
			}
			.river_object_bookmarks_comment {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_object_status_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_status.gif) no-repeat left -1px;
			}
			.river_object_file_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_files.gif) no-repeat left -1px;
			}
			.river_object_file_update {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_files.gif) no-repeat left -1px;
			}
			.river_object_file_comment {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_object_widget_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_plugin.gif) no-repeat left -1px;
			}
			.river_object_forums_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
			}
			.river_object_forums_update {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
			}
			.river_object_widget_update {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_plugin.gif) no-repeat left -1px;	
			}
			.river_object_blog_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;
			}
			.river_object_blog_update {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;
			}
			.river_object_blog_comment {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_object_forumtopic_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
			}
			.river_user_friend {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_friends.gif) no-repeat left -1px;
			}
			.river_object_relationship_friend_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_friends.gif) no-repeat left -1px;
			}
			.following_icon {
				background: url(<?php echo $vars['url']; ?>mod/riverdashboard/graphics/follow_icon.png) no-repeat scroll left top transparent;
			    height: 40px;
			    margin: 0 2px;
			    width: 20px;
			}
			.river_object_relationship_member_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
			}
			.river_object_thewire_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_thewire.gif) no-repeat left -1px;
			}
			.river_group_join {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
			}
			.river_object_groupforumtopic_annotate {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_object_groupforumtopic_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_forum.gif) no-repeat left -1px;
			}
			.river_object_sitemessage_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_blog.gif) no-repeat left -1px;	
			}
			.river_user_messageboard {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;	
			}
			.river_object_page_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_pages.gif) no-repeat left -1px;
			}
			.river_object_page_top_create {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_pages.gif) no-repeat left -1px;
			}
			.river_object_page_top_comment {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_object_page_comment {
				background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
			}
			.river_content_display {
			    border-left: 1px solid #DDDDDD;
			    font-size: 90%;
			    margin: 4px 0 2px 30px;
			    padding: 2px 10px 0;
			}
			.river_content_display table {
				width: auto;
			}
		
		</style>
		<div id="digest_container">
			<div id="digest_wrapper">
				<div id="digest_header">
					<h1>
						<?php echo $vars["area1"]; ?>
					</h1>
				</div>
				<div id="digest_content">
					<?php echo $vars["area2"]; ?>
				</div>
			</div>
			<div id="digest_footer">
				<?php 
					echo $vars["area3"];
					
					$site_url = elgg_view("output/url", array("href" => $vars["config"]->site->url, "text" => $vars["config"]->site->name));
					$digest_url = elgg_view("output/url", array("href" => $vars["url"] . "pg/digest", "text" => elgg_echo("digest:layout:footer:update")));
					
					$unsubscribe_link = $vars["area4"];
					
					echo sprintf(elgg_echo("digest:layout:footer:info"), $site_url);
					echo "&nbsp;" . $digest_url . "<br />";
					echo elgg_view("output/url", array("href" => $unsubscribe_link, "text" => elgg_echo("digest:layout:footer:unsubscribe")));
				?>
			</div>
		</div>
	
	</body>
</html>