<?php
/**
 * Plugin Name: Lumina Clinic Core
 * Description: Content types, clinic details, local-business schema, appointment requests and starter content for the Lumina Dental theme.
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Text Domain: lumina
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUMINA_CORE_VERSION', '1.0.0' );
define( 'LUMINA_CORE_DIR', plugin_dir_path( __FILE__ ) );

require_once LUMINA_CORE_DIR . 'includes/settings.php';
require_once LUMINA_CORE_DIR . 'includes/post-types.php';
require_once LUMINA_CORE_DIR . 'includes/schema.php';
require_once LUMINA_CORE_DIR . 'includes/booking.php';
require_once LUMINA_CORE_DIR . 'includes/seed.php';
require_once LUMINA_CORE_DIR . 'includes/seed-extra.php';

register_activation_hook( __FILE__, function () {
	lumina_register_post_types();
	lumina_seed_content();
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
