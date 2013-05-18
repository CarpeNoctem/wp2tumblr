<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

require_once(WP2TUMBLR_PATH.'/includes/tumblroauth/tumblroauth.php');

//I see this possibly being an issue if the site is hosted at a subdirectory...
$wp2tdomain = str_replace('http://','',str_replace('http://www.','*.',get_bloginfo('url')));

// The callback URL is the script that gets called after the user authenticates with tumblr
$auth_callback_url = get_bloginfo('url') . $_SERVER['REQUEST_URI'];

// Let's begin.  First we need a Request Token.  The request token is required to send the user
// to Tumblr's login page.

// Create a new instance of the TumblrOAuth library.  For this step, all we need to give the library is our
// Consumer Key and Consumer Secret
$tum_oauth = new TumblrOAuth($options->oauth_key(), $options->oauth_secret());

// Ask Tumblr for a Request Token.  Specify the Callback URL here too (although this should be optional)
$request_token = $tum_oauth->getRequestToken($auth_callback_url);

// Store the request token and Request Token Secret as the auth callback script will need this
$oauth_token = $request_token['oauth_token'];
$options->set_request_token($request_token['oauth_token']);
$options->set_request_secret($request_token['oauth_token_secret']);

// Check the HTTP Code.  It should be a 200 (OK), if it's anything else then something didn't work.
switch ($tum_oauth->http_code) {
    case 200:
        // Ask Tumblr to give us a special address to their login page
        $tumblr_oauth_url = $tum_oauth->getAuthorizeURL($oauth_token);

        // Redirect the user to the login URL given to us by Tumblr
        //wp_redirect($tumblr_oauth_url); //can't use this, as header info already sent by menu-header.php
        ?>
        <script type="text/javascript">setTimeout(function(){window.location = "<?=$tumblr_oauth_url?>"},500);</script>
        <?
        // That's it for our side.  The user is sent to a Tumblr Login page and
        // asked to authroize our app.  After that, Tumblr sends the user back to
        // our Callback URL  along with some information we need to get
        // an access token.
    break;
default:
    // Give an error message
    echo 'Could not connect to Tumblr. Refresh the page or try again later.';
}

?>
