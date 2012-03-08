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
			<?php echo elgg_view("page/layouts/digest/core"); ?>
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
					$digest_url = elgg_view("output/url", array("href" => $vars["url"] . "digest", "text" => elgg_echo("digest:layout:footer:update")));
					
					$unsubscribe_link = $vars["area4"];
					
					echo elgg_echo("digest:layout:footer:info", array($site_url));
					echo "&nbsp;" . $digest_url . "<br />";
					echo elgg_view("output/url", array("href" => $unsubscribe_link, "text" => elgg_echo("digest:layout:footer:unsubscribe")));
				?>
			</div>
		</div>
	
	</body>
</html>