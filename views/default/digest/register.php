<?php

$setting = digest_get_default_site_interval();
if (!empty($setting) && ($setting != DIGEST_INTERVAL_NONE)) {
?>
<br />
<input type="checkbox" name="digest_site" class="elgg-input-checkbox" value="yes" /> <?php echo elgg_echo("digest:register:enable");

}
