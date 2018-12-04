<?php
/**
 * NiceThemes Framework Plugin Activation
 *
 * @package Nice_Framework
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! class_exists( 'Nice_TGMPA_Bulk_Installer' ) && class_exists( 'TGMPA_Bulk_Installer' ) ) :
/**
 * Class Nice_TGMPA_Bulk_Installer
 *
 * Helper class to customize TGMPA's bulk installer.
 *
 * @since 2.0
 */
class Nice_TGMPA_Bulk_Installer extends TGMPA_Bulk_Installer {

	// Nothing, for now.
}
endif;
