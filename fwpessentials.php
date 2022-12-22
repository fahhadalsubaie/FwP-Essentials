<?php

/**
 * Plugin Name:       FwP Essentials
 * Plugin URI:        https://fahhad.io/fwp-essentials/
 * Description:       Control XMLRPC, auto updates, email sending, and debug mode in wordpress.
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
	register_setting('fwpessentials-settings-group', 'enable_debug_mode');
}

function fwpessentials_settings_page()
{
?>
	<div class="fwp-wrap">
		<h1 class="fwp-title">FwP Essentials Settings</h1>

		<form method="post" action="options.php">
			<?php settings_fields('fwpessentials-settings-group'); ?>
			<?php do_settings_sections('fwpessentials-settings-group'); ?>
			<div class="fwp-form">
				<div class="fwp-form-row">
	<label title="Disables XMLRPC protocol in WordPress which makes it more secure from SSRF attacks.">Disable XMLRPC</label>
	<input type="checkbox" name="disable_xmlrpc" value="1" <?php checked(get_option('disable_xmlrpc'), 1); ?> />
</div>
<div class="fwp-form-row">
	<label title="Disables core WordPress updates entirely, and you can update it manually">Disable Core WordPress Auto Updates</label>
	<input type="checkbox" name="disable_core_updates" value="1" <?php checked(get_option('disable_core_updates'), 1); ?> />
</div>
<div class="fwp-form-row">
	<label title="Disables theme updates entirely, and you can update it manually">Disable Themes Auto Updates</label>
	<input type="checkbox" name="disable_themes_updates" value="1" <?php checked(get_option('disable_themes_updates'), 1); ?> />
</div>
<div class="fwp-form-row">
	<label title="Disables plugin updates entirely, and you can update it manually">Disable Plugins Auto Updates</label>
	<input type="checkbox" name="disable_plugins_updates" value="1" <?php checked(get_option('disable_plugins_updates'), 1); ?> />
</div>
<div class="fwp-form-row">
	<label title="Disables email sending in wordPress even for other plugins.">Disable Email Sending</label>
	<input type="checkbox" name="disable_email_sending" value="1" <?php checked(get_option('disable_email_sending'), 1); ?> />
</div>
<div class="fwp-form-row">
	<label title="Enables debug mode, debug logs, and display errors in WordPress, it's not good to leave it on for production websites">Enable Debug Mode</label>
	<input type="checkbox" name="enable_debug_mode" value="1" <?php checked(get_option('enable_debug_mode'), 1); ?> />
</div>

			</div>
			<?php submit_button('Save Changes', 'primary', 'submit', false, array('id' => 'fwp-save-btn')); ?>
		</form>
	</div>
	
	<div class="more-tips">
	    <br><br>
			∘ Make sure you've unchecked 'Enable Debug Mode' once you finish working on it, it's better to keep it disabled on a production website.<br>
			∘ For more wordpress tips and tricks, check my twitter account, or DM me there. <a href="https://twitter.com/FahhadExplains" target="_blank">@FahhadExplains</a>
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

// Enable/Disable Debug Mode
function fwpessentials_enable_debug_mode()
{
	if (get_option('enable_debug_mode')) {
		// Enable debug mode, debug logs, and display errors
		define('WP_DEBUG', true);
		define('WP_DEBUG_LOG', true);
		define('WP_DEBUG_DISPLAY', true);
	} else {
		// Disable debug mode, debug logs, and display errors
		define('WP_DEBUG', false);
		define('WP_DEBUG_LOG', false);
		define('WP_DEBUG_DISPLAY', false);
	}
}
add_action('init', 'fwpessentials_enable_debug_mode');

function fwpessentials_enqueue_css()
{
	wp_enqueue_style('fwpessentials-css', plugin_dir_url(__FILE__) . 'fwpessentials.css');
}
add_action('admin_enqueue_scripts', 'fwpessentials_enqueue_css');
