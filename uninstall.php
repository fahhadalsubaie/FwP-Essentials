<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Deletes plugin options from the database.
delete_option( 'disable_xmlrpc' );
delete_option( 'disable_core_updates' );
delete_option( 'disable_themes_updates' );
delete_option( 'disable_plugins_updates' );
delete_option( 'disable_email_sending' );
delete_option( 'enable_debug_mode' );