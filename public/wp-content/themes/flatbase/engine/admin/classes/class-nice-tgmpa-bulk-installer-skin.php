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


if ( ! class_exists( 'Nice_TGMPA_Bulk_Installer_Skin' ) && class_exists( 'TGMPA_Bulk_Installer_Skin' ) ) :
/**
 * Class Nice_TGMPA_Bulk_Installer_Skin
 *
 * Helper class to customize TGMPA's bulk installer skin.
 *
 * @since 2.0
 */
class Nice_TGMPA_Bulk_Installer_Skin extends TGMPA_Bulk_Installer_Skin {

	/** ==========================================================================
	 *  Customized functionality.
	 *  ======================================================================= */

	/**
	 * Allow to disable the process output.
	 *
	 * @param string $string
	 * @param string $package
	 *
	 * @since 2.0
	 */
	public function feedback( $string, $package = null ) {
		if ( ! $this->can_print_output() ) {
			return;
		}

		parent::feedback( $string, $package );
	}

	/**
	 * Allow to disable the process output.
	 *
	 * @since 2.0
	 */
	public function header() {
		if ( ! $this->can_print_output() ) {
			return;
		}

		parent::header();
	}

	/**
	 * Allow to disable the process output.
	 *
	 * @since 2.0
	 */
	public function footer() {
		if ( ! $this->can_print_output() ) {
			return;
		}

		parent::footer();
	}

	/**
	 * Allow to disable the process output.
	 *
	 * @param string|WP_Error $error
	 *
	 * @since 2.0
	 */
	public function error( $error ) {
		if ( ! $this->can_print_output() ) {
			return;
		}

		parent::error( $error );
	}

	/**
	 * Allow to disable the process output.
	 *
	 * @since 2.0
	 */
	public function bulk_header() {
		if ( ! $this->can_print_output() ) {
			return;
		}

		parent::bulk_header();
	}

	/**
	 * @access public
	 */
	public function bulk_footer() {
		if ( ! $this->can_print_output() ) {
			return;
		}

		parent::bulk_footer();
	}

	/**
	 * Allow to disable the process output.
	 *
	 * @param string $title
	 *
	 * @since 2.0
	 */
	public function before( $title = '' ) {
		if ( ! $this->can_print_output() ) {
			$this->flush_output();

			return;
		}

		parent::before( $title );
	}

	/**
	 * Allow to disable the process output.
	 *
	 * @param string $title
	 *
	 * @since 2.0
	 */
	public function after( $title = '' ) {
		if ( ! $this->can_print_output() ) {
			$this->reset();
			$this->flush_output();

			return;
		}

		parent::after( $title );
	}


	/**
	 * Obtain whether or not the output should be printed.
	 *
	 * @since 2.0
	 */
	protected function can_print_output() {
		/**
		 * @hook nice_tgmpa_bulk_installer_skin_print_output
		 *
		 * Hook here to avoid printing the process output.
		 *
		 * @since 2.0
		 */
		return apply_filters( 'nice_tgmpa_bulk_installer_skin_print_output', true );
	}

}
endif;
