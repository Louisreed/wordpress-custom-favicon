<?php
/*
Plugin Name: Wordpress Custom Favicon
Plugin URI: https://github.com/Louisreed/wordpress-custom-favicon
Description: Custom Favicon is a simple and lightweight Wordpress plugin that allows you to easily change the favicon for your site.
Version: 1.4
Author: Louis Reed
Author URI: https://louisreed.co.uk
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function custom_favicon_menu() {
    add_options_page( 'Custom Favicon', 'Custom Favicon', 'manage_options', 'custom-favicon', 'custom_favicon_options' );
}
add_action( 'admin_menu', 'custom_favicon_menu' );

function custom_favicon_options() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
    }

    // Save the form data if the Save Changes button is clicked
    if ( isset( $_POST['custom_favicon_form_submitted'] ) ) {
        check_admin_referer( 'custom_favicon_form_action', 'custom_favicon_form_nonce_field' );

        $custom_favicon_url = sanitize_text_field( wp_kses_post( $_POST['custom_favicon'] ) );
        update_option( 'custom_favicon_url', $custom_favicon_url );
        ?>
        <div class="updated"><p><strong><?php esc_html_e( 'Changes saved.', 'custom-favicon' ); ?></strong></p></div>
        <?php
    }

// Display the form
?>
<div class="wrap">
    <h2><?php esc_html_e( 'Custom Favicon', 'custom-favicon' ); ?></h2>
    <form name="custom_favicon_form" method="post" action="">
        <?php wp_nonce_field( 'custom_favicon_form_action', 'custom_favicon_form_nonce_field', true, false ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="custom_favicon"><?php esc_html_e( 'Favicon URL', 'custom-favicon' ); ?></label></th>
                <td>
                    <input type="text" name="custom_favicon" id="custom_favicon" value="<?php echo esc_url_raw( get_option( 'custom_favicon_url' ) ); ?>" size="60" />
                    <input id="upload_image_button" type="button" class="button" value="Upload Image" />
                    <p class="description"><?php esc_html_e( 'Enter the URL for the favicon image, or use the upload button to select an image from the media library.', 'custom-favicon' ); ?></p>
                    <?php
                        // Display the image thumbnail if an image URL has been entered
                        $custom_favicon_url = esc_url_raw( get_option( 'custom_favicon_url' ) );
                        if ( ! empty( $custom_favicon_url ) ) {
                            echo '<br /><img src="' . $custom_favicon_url . '" style="max-width:100px;height:auto;margin:10px 0;" />';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="custom_favicon_form_submitted" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'custom-favicon' ); ?>" />
            </p>
        </form>
    </div>
    <?php
}

function custom_favicon_enqueue_scripts() {
  // Enqueue the WordPress media library scripts
  wp_enqueue_media();

  // Register and enqueue the custom script
  wp_register_script( 'custom-favicon', plugin_dir_path( __FILE__ ) . 'custom-favicon.js', array( 'wp-media-uploader' ), '1.0', true );
  wp_enqueue_script( 'custom-favicon' );
}
add_action( 'admin_enqueue_scripts', 'custom_favicon_enqueue_scripts' );

function custom_favicon_head() {
  $custom_favicon_url = esc_url( get_option( 'custom_favicon_url' ) );
  if ( ! empty( $custom_favicon_url ) ) {
      echo '<link rel="shortcut icon" type="image/x-icon" href="' . $custom_favicon_url . '" />';
  }
}
add_action( 'wp_head', 'custom_favicon_head' );

function custom_favicon_admin_head() {
  $custom_favicon_url = esc_url( get_option( 'custom_favicon_url' ) );
  if ( ! empty( $custom_favicon_url ) ) {
      echo '<link rel="shortcut icon" type="image/x-icon" href="' . $custom_favicon_url . '" />';
  }
}
add_action( 'admin_head', 'custom_favicon_admin_head' );

