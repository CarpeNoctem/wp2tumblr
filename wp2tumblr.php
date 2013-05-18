<?php
/**
 * @package wp2tumblr
 * @version 1.0
 */
/*
Plugin Name: WP 2 Tumblr
Plugin URI: https://github.com/CarpeNoctem/wp2tumblr
Description: Another plugin to send WordPress posts to a Tumblr blog. Uses the Tumblr API (v2). Seems to be the most recent. (The two older ones I tried don't work.)
Author: CarpeNoctem
Version: 1.0
Author URI: https://github.com/CarpeNoctem
*/

// Special thanks to the authors of documentation and other WP plugins from which I
// learned and draw from a bit for ideas, best practices, and code snippets.


//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

define('WP2TUMBLR_VERSION', '1.0');
define('WP2TUMBLR_URL', plugin_dir_url( __FILE__ ));
define('WP2TUMBLR_PATH', dirname( __FILE__ ));

if ( is_admin() )
    require_once WP2TUMBLR_PATH . '/includes/admin.php';

?>
