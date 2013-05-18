<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

require_once(WP2TUMBLR_PATH.'/includes/options.php');
$options = new WP2TumblrOptions();

//need to put a better check here.
//was thinking about api.tumblr.com/v2/blog/{base-hostname}/posts/queue?limit=1
if ( $options->access_token() != '' && $options->blog_hostname() != '' ) {

$action = $options->publish_action();
$published_checked = ($action == 'published') ? 'checked="checked"' : '';
$draft_checked = ($action == 'draft') ? 'checked="checked"' : '';
$none_checked = ($action == 'none') ? 'checked="checked"' : '';

// Use nonce for verification
//wp_nonce_field( plugin_basename( __FILE__ ), 'wp2tmeta_nonce' );
?>
<input type="hidden" name="wp2tmeta_nonce" id="wp2tmeta_nonce" value="<?=wp_create_nonce(wp_hash('wp2tumblr'));?>" />
<strong>Action on Publish:</strong>
<div id="tumblr-push-select">
    <input type="radio" name="wp2t_publish_action" class="tumblr-on-publish" id="tumblr-on-publish-published" value="published" <?=$published_checked?>> <label for="tumblr-on-publish-published">Push to Tumblr as Published</label>
    <br><input type="radio" name="wp2t_publish_action" class="tumblr-on-publish" id="tumblr-on-publish-draft" value="draft" <?=$draft_checked?>> <label for="tumblr-on-publish-draft">Push as Draft</label>
    <br><input type="radio" name="wp2t_publish_action" class="tumblr-on-publish" id="tumblr-on-publish-none" value="none" <?=$none_checked?>> <label for="tumblr-on-publish-none">No Action</label>
    <br>
</div>
<hr />
<strong>Or:</strong>
<br /><input type="submit" class="button-primary" name="save" value="Publish to Tumblr Now" />
<input type="submit" class="button-primary" name="save" value="Push as Draft Now" />

<?
}
else { ?>
<strong>WARNING:</strong> The WP 2 Tumblr plugin does not appear to be authenticated with your Tumblr account. Please check your settings.

<? } //end-else
?>
