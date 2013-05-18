<?php
//Don't execute if called directly.
if ( !function_exists( 'get_option' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

class WP2TumblrOptions {

    private $blog_hostname; //example: 'greentype.tumblr.com' or 'www.davidsblog.com'
    private $tumblr_user;

    private $oauth_key;
    private $oauth_secret;

    private $request_token;
    private $request_secret;

    private $access_token;
    private $access_token_secret;

    private $publish_action; //defaults to 'draft';

    function __construct() {
        $this->blog_hostname = get_option('wp2tumblr_blog_hostname');
        $this->tumblr_user = get_option('wp2tumblr_tumblr_user');

        $this->oauth_key = get_option('wp2tumblr_oauth_key');
        $this->oauth_secret = get_option('wp2tumblr_oauth_secret');

        $this->request_token = get_option('wp2tumblr_request_token');
        $this->request_secret = get_option('wp2tumblr_request_secret');

        $this->access_token = get_option('wp2tumblr_access_token');
        $this->access_token_secret = get_option('wp2tumblr_access_secret');

        $this->publish_action = get_option('wp2tumblr_publish_action');
    }


    /**
     * Options (Read)
     */
    function blog_hostname() {
        return $this->blog_hostname;
    }

    function tumblr_user() {
        return $this->tumblr_user;
    }

    function oauth_key() {
        return $this->oauth_key;
    }

    function oauth_secret() {
        return $this->oauth_secret;
    }

    function request_token() {
        return $this->request_token;
    }

    function request_secret() {
        return $this->request_secret;
    }

    function access_token() {
        return array('oauth_token' => $this->access_token, 'oauth_token_secret' => $this->access_token_secret);
    }

    function publish_action() {
        return ( !empty($this->publish_action) ) ? $this->publish_action : 'draft';
    }

    /**
     * Update Options
     */
    function set_blog_hostname($value = '') {
        $this->blog_hostname = $value;
        update_option( 'wp2tumblr_blog_hostname', $value );
    }

    function set_oauth_key($value = '') {
        $this->oauth_key = $value;
        update_option( 'wp2tumblr_oauth_key', $value );
    }

    function set_oauth_secret($value = '') {
        $this->oauth_secret = $value;
        update_option( 'wp2tumblr_oauth_secret', $value );
    }

    function set_request_token($value = '') {
        $this->request_token = $value;
        update_option( 'wp2tumblr_request_token', $value );
    }

    function set_request_secret($value = '') {
        $this->request_secret = $value;
        update_option( 'wp2tumblr_request_secret', $value );
    }

    function set_access_token($value = NULL) {
        if ( is_array($value) ) {
            $this->access_token = $value['oauth_token'];
            $this->access_token_secret = $value['oauth_token_secret'];

            update_option( 'wp2tumblr_access_token', $value['oauth_token'] );
            update_option( 'wp2tumblr_access_secret', $value['oauth_token_secret'] );
        }
        else {
            $this->access_token = $value['oauth_token'];
            $this->access_token_secret = $value['oauth_token_secret'];

            update_option( 'wp2tumblr_access_token', '' );
            update_option( 'wp2tumblr_access_secret', '' );
        }
    }

    function set_publish_action($value = '') {
        $action = ($value == 'published') ? 'published' : $this->publish_action();
        $action = ($value == 'draft') ? 'draft' : $action;
        $action = ($value == 'none') ? 'none' : $action;

        $this->publish_action = $action;
        update_option( 'wp2tumblr_publish_action', $action );
    }

}
