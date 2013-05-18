<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

require_once(WP2TUMBLR_PATH.'/includes/tumblroauth/tumblroauth.php');
require_once(WP2TUMBLR_PATH.'/includes/options.php');

function wp2tumblr_push($post_id = null, $post_status = 'draft') {
    $options = new WP2TumblrOptions();

    $tumblr_post = get_post($post_id);
    $title = $tumblr_post->post_title;
    $body = $tumblr_post->post_content;

    // Create instance of TumblrOAuth.
    $access_token = $options->access_token();
    $tum_oauth = new TumblrOAuth($options->oauth_key(), $options->oauth_secret(), $access_token['oauth_token'], $access_token['oauth_token_secret']);

    // Make the API call to post!
    $parameters = array('type'=>'text','state'=>$post_status,'format'=>'html','title'=>$title,'body'=>$body);
    $tum_return = $tum_oauth->post('http://api.tumblr.com/v2/blog/'.$options->blog_hostname().'/post',$parameters);
    //add_action('user_admin_notices', 'tumblr_push_success_notice'); //this isn't working for some reason.
}

function wp2tumblr_update($post_id = null, $status = 'published') {
    $tumblr_post = get_post($post_id);
    $title = $tumblr_post->post_title;
    $body = $tumblr_post->post_content;
}

function tumblr_push_success_notice() {
    echo '<div class="updated">
       <p>Post was pushed to '.$options->blog_hostname().'</p>
    </div>';
}

function tumblr_push_failure_notice() {
    echo '<div class="error">
       <p>FAILURE: Post was NOT pushed to '.$options->blog_hostname().'</p>
    </div>';
}
