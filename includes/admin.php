<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

add_action('admin_menu', 'wp2tumblr_admin_menu');

add_action( 'add_meta_boxes', 'wp2tumblr_post_metabox' );

add_action( 'save_post', 'wp2tumblr_save_post' );
add_action( 'publish_post', 'wp2tumblr_publish_post' );


function wp2tumblr_admin_menu() {
    add_options_page('WP 2 Tumblr Settings', 'WP 2 Tumblr', 'activate_plugins', 'wp2tumblr-options', 'wp2tumblr_settings_page');
    add_submenu_page( 'tools.php', 'WP 2 Tumblr', 'WP 2 Tumblr', 'import', 'wp2tumblr-tools', 'wp2tumblr_tools_page' );
}

function wp2tumblr_settings_page() {
    require_once(WP2TUMBLR_PATH.'/includes/settings_page.php');
}

function wp2tumblr_tools_page() {
    require_once(WP2TUMBLR_PATH.'/includes/tools_page.php');
}

function wp2tumblr_post_metabox() {
    add_meta_box('wp2tumblr_metabox','WP 2 Tumblr','show_post_metabox','post','side');
}

function show_post_metabox( $post ) {
    require_once(WP2TUMBLR_PATH.'/includes/post_metabox.php');
}

function wp2tumblr_save_post( $post_id = 'not set' ) {
    // not supporting pages at the moment
    if ( isset($_POST['post_type']) && $_POST['post_type'] == 'page')
        return;

    // verify if this is an auto save routine.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    //for some reason, this event is triggered for both the revision, and the original post (IDs)
    //so, we only want to take action for one of them, the original post ID
    if ( wp_is_post_revision($post_id) )
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !isset($_POST['wp2tmeta_nonce']) || !wp_verify_nonce($_POST['wp2tmeta_nonce'], wp_hash('wp2tumblr')) )
        return;

    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;

    require_once(WP2TUMBLR_PATH.'/includes/post.php');
    if ( $_POST['save'] == 'Publish to Tumblr Now' ) {
        wp2tumblr_push($post_id, 'published');
    }
    elseif ( $_POST['save'] == 'Push as Draft Now' ) {
        wp2tumblr_push($post_id, 'draft');
    }

}

function wp2tumblr_publish_post( $post_id = 'not set' ) {
    // not supporting pages at the moment
    if ( isset($_POST['post_type']) && $_POST['post_type'] == 'page')
        return;

    // verify if this is an auto save routine.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    //for some reason, this event is triggered for both the revision, and the original post (IDs)
    //so, we only want to take action for one of them, the original post ID
    if ( wp_is_post_revision($post_id) )
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !isset($_POST['wp2tmeta_nonce']) || !wp_verify_nonce($_POST['wp2tmeta_nonce'], wp_hash('wp2tumblr')) )
        return;

    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;

    require_once(WP2TUMBLR_PATH.'/includes/post.php');
    if ( isset($_POST['publish']) && $_POST['publish'] == __('Publish') ) {
        wp2tumblr_push($post_id, $_POST['wp2t_publish_action']);
    }
    elseif ( $_POST['save'] == __('Update') ) {
        //Want to either update same post on Tumblr, or at the very least, avoid posting duplicates to Tumblr
        wp2tumblr_update($post_id, $_POST['wp2t_publish_action']);
    }


}
