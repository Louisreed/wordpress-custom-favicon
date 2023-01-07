<?php
/*
Plugin Name: Wordpress Custom Favicon
Plugin URI: https://github.com/Louisreed/wordpress-custom-favicon
Description: Custom Favicon is a simple and lightweight Wordpress plugin that allows you to easily change the favicon for your site.
Version: 1.1
Author: Louis Reed
Author URI: https://louisreed.co.uk
*/

// Register the plugin settings
function custom_favicon_register_settings() {
    add_option( 'custom_favicon', '', '', 'yes' );
    register_setting( 'custom_favicon_group', 'custom_favicon', 'custom_favicon_callback' );
}
add_action( 'admin_init', 'custom_favicon_register_settings' );

// Add the plugin settings page
function custom_favicon_create_menu() {
    add_options_page( 'Custom Favicon Settings', 'Custom Favicon', 'manage_options', 'custom-favicon', 'custom_favicon_settings_page' );
}
add_action( 'admin_menu', 'custom_favicon_create_menu' );

// Display the plugin settings page
function custom_favicon_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Favicon Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'custom_favicon_group' ); ?>
            <?php do_settings_sections( 'custom_favicon_group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Favicon</th>
                    <td>
                        <input type="text" name="custom_favicon" value="<?php echo esc_attr( get_option('custom_favicon') ); ?>" />
                        <input id="upload_image_button" type="button" class="button" value="Upload Image" data-choose="Choose an Image" data-update="Select" />
                        <br />
                        <p class="description">Enter the URL for the favicon image or use the upload button to select an image from the media library.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Sanitize and validate the input
function custom_favicon_callback( $input ) {
    $input = esc_url( $input );
    return $input;
}

// Add the favicon to the front-end of the site
function custom_favicon_wp_head() {
    $favicon_url = get_option( 'custom_favicon' );
    if ( !empty( $favicon_url ) ) {
        echo '<link rel="shortcut icon" href="' . esc_url( $favicon_url ) . '" />';
    }
}
add_action( 'wp_head', 'custom_favicon_wp_head' );

wp_enqueue_script( 'woocommerce_admin' );
wp_enqueue_script( 'wc-enhanced-select' );
wp_enqueue_style( 'woocommerce_admin_styles' );

function custom_favicon_enqueue_scripts() {
  // Register the script
  wp_register_script( 'custom-favicon', plugin_dir_url( __FILE__ ) . 'wordpress-custom-favicon.js', array( 'wp-media-uploader' ), '1.0', true );

  // Localize the script with some data
  $translation_array = array(
      'input_name' => 'custom_favicon'  // Replace with the name of the input field for the favicon URL
  );
  wp_localize_script( 'custom-favicon', 'custom_favicon_data', $translation_array );

  // Enqueue the script
  wp_enqueue_script( 'custom-favicon' );
}
add_action( 'admin_enqueue_scripts', 'custom_favicon_enqueue_scripts' );




