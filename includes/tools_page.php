<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}
require_once(WP2TUMBLR_PATH.'/includes/options.php');
$options = new WP2TumblrOptions();
?>

<div class="wrap">
<div id="icon-tools" class="icon32"><br></div><h2>WP 2 Tumblr</h2>

<h3>Export to Tumblr</h3>

<p class="description">Export existing posts to <?=$options->blog_hostname();?></p>
<p>Sorry, this feature not available in the first release of this plugin.</p>

</div>
