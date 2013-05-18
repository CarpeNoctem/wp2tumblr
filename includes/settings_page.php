<?php
//Don't execute if called directly.
if ( !function_exists( 'add_action' ) ) {
    die('Pay no attention to the man behind the curtain...');
}

//display options page

// Would be helpful to provide a link to http://www.tumblr.com/oauth/register
require_once(WP2TUMBLR_PATH.'/includes/options.php');
$options = new WP2TumblrOptions();

$blog_title = '';

//check for form submission and nonce
if ( !empty($_POST) && check_admin_referer('wp2tumblr_settings') ) {
    $keys_updated = false;

    if ( isset($_POST['publish_action']) )
        $options->set_publish_action($_POST['publish_action']);

    if ( isset($_POST['blog_hostname']) && $_POST['blog_hostname'] != $options->blog_hostname() ) {
        $options->set_blog_hostname($_POST['blog_hostname']);
        $keys_updated = true;
    }

    if ( isset($_POST['oauth_key']) && $_POST['oauth_key'] != $options->oauth_key() ) {
        $options->set_oauth_key($_POST['oauth_key']);
        $keys_updated = true;
    }

    if ( isset($_POST['oauth_secret']) && $_POST['oauth_secret'] !=  $options->oauth_secret() ) {
        $options->set_oauth_secret($_POST['oauth_secret']);
        $keys_updated = true;
    }

    if ( $keys_updated && $options->oauth_key() != '' && $options->oauth_secret() != '' ) {
        require_once(WP2TUMBLR_PATH.'/includes/auth_init.php');
    }
}

$auth_msg = '';
if ( !empty($_REQUEST['oauth_verifier']) ) {
    require_once(WP2TUMBLR_PATH.'/includes/auth_callback.php');
    if ( $wp2t_authorize_success == 1 )
        $auth_msg = '<span style="color: green; font-weight: bold;">Plugin authorized successfully!</span>';
    else
        $auth_msg = '<span style="color: red; font-weight: bold;">Plugin not authorized.</span>';

    echo '<script type="text/javascript">setTimeout(function(){window.location="options-general.php?page=wp2tumblr-options"},5000);</script>';
}

?>

<div class="wrap">

    <h2>WP 2 Tumblr Settings</h2>

    <form method="post" action="">
        <?php
        // Add a nonce
        wp_nonce_field('wp2tumblr_settings');
        ?>

        <h3>Tumblr Authorization Details</h3>
        <label for="blog_hostname" style="font-weight: bold;">Blog hostname: </label>
        <input type="text" id="blog_hostname" name="blog_hostname" size="30" value="<?=$options->blog_hostname();?>"/>
        <span style="font-style: italic;">example: 'greentype.tumblr.com' or 'www.davidsblog.com'</span>
        <br />
        <?php if ( !empty($blog_title) ) { ?>
            <span style="font-style: italic;"><b>&quot;</b><?=$blog_title;?><b>&quot;</b></span>
        <? } ?>
        <br />
        <label for="oauth_key" style="font-weight: bold;">OAuth Consumer Key: </label><input type="text" id="oauth_key" name="oauth_key" size="70" value="<?=$options->oauth_key();?>"/>
        <br />
        <label for="wp2tumblr_secret_key" style="font-weight: bold;">Secret Key: </label>
        <input id="wp2tumblr_secret_key" type="password" name="oauth_secret" size="70" value="<?=$options->oauth_secret();?>"/>
        <a href="" onClick="document.getElementById('wp2tumblr_secret_key').type='text'; this.style.display='none'; return false;">Show secret key</a>
        <br />
        <input type="submit" class="button-primary" value="Save Keys and Authenticate" /> <?=$auth_msg?>
    </form>
    <br />
    <form method="post" action="">
        <h3>Other Settings</h3>

        <?php
        // Add a nonce
        wp_nonce_field('wp2tumblr_settings');

        $action = $options->publish_action();
        $published_checked = ($action == 'published') ? 'checked="checked"' : '';
        $draft_checked = ($action == 'draft') ? 'checked="checked"' : '';
        $none_checked = ($action == 'none') ? 'checked="checked"' : '';
        ?>
<span style="font-weight: bold;">Default action on Publish:</span>
<div id="tumblr-push-select">
    <input type="radio" name="publish_action" class="tumblr-on-publish" id="tumblr-on-publish-published" value="published" <?=$published_checked?>> <label for="tumblr-on-publish-published">Push to Tumblr as Published</label>
    <br><input type="radio" name="publish_action" class="tumblr-on-publish" id="tumblr-on-publish-draft" value="draft" <?=$draft_checked?>> <label for="tumblr-on-publish-draft">Push as Draft</label>
    <br><input type="radio" name="publish_action" class="tumblr-on-publish" id="tumblr-on-publish-none" value="none" <?=$none_checked?>> <label for="tumblr-on-publish-none">No Action</label>
    <br>
    </div>
    <br />
    <input type="submit" class="button-primary" value="Save" />
    </form>
</div>
