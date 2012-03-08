<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<base target="_blank" />
		
		<title><?php echo $vars["title"];?></title>
	</head>
	<body>
		<style type="text/css">		
			<?php echo elgg_view("css/digest/core"); ?>
		</style>
		<div id="digest_online">
		</div>
		<div id="digest_container">
			<div>
				<div id="digest_header">
					<?php echo $vars["digest_header"]; ?>
				</div>
				<div id="digest_content">
					<?php echo $vars["content"]; ?>
				</div>
			</div>
			<div id="digest_footer">
				<?php echo $vars["digest_footer"]; ?>
			</div>
			<div id="digest_unsubscribe">
				<?php echo $vars["digest_unsubscribe"]; ?>
			</div>
		</div>
	
	</body>
</html>