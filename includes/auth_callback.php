<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

require_once(WP2TUMBLR_PATH.'/includes/tumblroauth/tumblroauth.php');

//I see this possibly being an issue if the site is hosted at a subdirectory...
$wp2tdomain = str_replace('http://','',str_replace('http://www.','*.',get_bloginfo('url')));

// Create instance of TumblrOAuth.
// It'll need our Consumer Key and Secret as well as our Request Token and Secret
$tum_oauth = new TumblrOAuth($options->oauth_key(), $options->oauth_secret(), $options->request_token(), $options->request_secret());
// Ok, let's get an Access Token. We'll need to pass along our oauth_verifier which was given to us in the URL.
$access_token = $tum_oauth->getAccessToken($_REQUEST['oauth_verifier']);

if ( !empty($access_token) )
{
    $options->set_access_token($access_token);
}

// We're done with the Request Token and Secret so let's remove those.
$options->set_request_token();
$options->set_request_secret();

// Make sure nothing went wrong.
if (200 == $tum_oauth->http_code) {
    $wp2t_authorize_success = 1;
} else {
    $wp2t_authorize_success = 0;
}

?>
