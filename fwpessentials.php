<?php

/**
 * Plugin Name:       FwP Essentials
 * Plugin URI:        https://fahhad.io/fwp-essentials/
 * Description:       Plugin that disables XMLRPC, auto updates, and email sending in WordPress (Optional).
 * Version:           1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Fahhad Alsubaie
 * Author URI:        https://fahhad.io/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://fahhad.io/fwp-essentials/
 * Text Domain:       FwP Essentials
 */


// Create custom plugin settings menu
add_action('admin_menu', 'fwpessentials_create_menu');

function fwpessentials_create_menu()
{
	//create new top-level menu
	add_menu_page('FwP Essentials Settings', '<i class="fa fa-barcode"></i> FwP Essentials', 'administrator', __FILE__, 'fwpessentials_settings_page', '', 9999);

	//call register settings function
	add_action('admin_init', 'register_fwpessentials_settings');
}

function register_fwpessentials_settings()
{
	//register FwP settings
	register_setting('fwpessentials-settings-group', 'disable_xmlrpc');
	register_setting('fwpessentials-settings-group', 'disable_core_updates');
	register_setting('fwpessentials-settings-group', 'disable_themes_updates');
	register_setting('fwpessentials-settings-group', 'disable_plugins_updates');
	register_setting('fwpessentials-settings-group', 'disable_email_sending');
}

function fwpessentials_settings_page()
{
?>
	<div class="fwp-wrap">
		<h1 class="fwp-title">FwP Pack Settings</h1>

		<form method="post" action="options.php">
			<?php settings_fields('fwpessentials-settings-group'); ?>
			<?php do_settings_sections('fwpessentials-settings-group'); ?>
			<div class="fwp-form">
				<div class="fwp-form-row">
					<label>Disable XMLRPC</label>
					<input type="checkbox" name="disable_xmlrpc" value="1" <?php checked(get_option('disable_xmlrpc'), 1); ?> />
				</div>
				<div class="fwp-form-row">
					<label>Disable Core WordPress Updates</label>
					<input type="checkbox" name="disable_core_updates" value="1" <?php checked(get_option('disable_core_updates'), 1); ?> />
				</div>
				<div class="fwp-form-row">
					<label>Disable Themes Updates</label>
					<input type="checkbox" name="disable_themes_updates" value="1" <?php checked(get_option('disable_themes_updates'), 1); ?> />
				</div>
				<div class="fwp-form-row">
					<label>Disable Plugins Updates</label>
					<input type="checkbox" name="disable_plugins_updates" value="1" <?php checked(get_option('disable_plugins_updates'), 1); ?> />
				</div>
				<div class="fwp-form-row">
					<label>Disable Email Sending</label>
					<input type="checkbox" name="disable_email_sending" value="1" <?php checked(get_option('disable_email_sending'), 1); ?> />
				</div>
			</div>
			<?php submit_button('Save Changes', 'primary', 'submit', false, array('id' => 'fwp-save-btn')); ?>
		</form>
	</div>
<?php }


// Disable XMLRPC
if (get_option('disable_xmlrpc') == 1) {
	add_filter('xmlrpc_enabled', '__return_false');
}

// Disable core updates
if (get_option('disable_core_updates') == 1) {
	wp_clear_scheduled_hook('wp_version_check');
}

// Disable themes updates
if (get_option('disable_themes_updates') == 1) {
	wp_clear_scheduled_hook('wp_update_themes');
}

// Disable plugins updates
if (get_option('disable_plugins_updates') == 1) {
	wp_clear_scheduled_hook('wp_update_plugins');
}

// Disable email sending
if (get_option('disable_email_sending') == 1) {
	add_filter('wp_mail', '__return_false');
}

function fwpessentials_enqueue_css()
{
	wp_enqueue_style('fwpessentials-css', plugin_dir_url(__FILE__) . 'fwpessentials.css');
}
add_action('admin_enqueue_scripts', 'fwpessentials_enqueue_css');
