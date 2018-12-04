<?php
/**
 * NiceThemes Execution Context.
 *
 * General functions for different WordPress execution contexts.
 *
 * @package NiceFramework
 * @since   2.0.6
 */
namespace NiceThemes\Execution_Context;

/**
 * Obtain the current execution context.
 *
 * @return Current_Context|null
 *
 * @since 2.0.6
 */
function obtain_context_instance() {
	static $instance = null;

	if ( is_null( $instance ) ) {
		require_once dirname( __FILE__ ) . '/classes/class-current-context.php';

		$instance = new Current_Context( array(
				'is_admin'         => \is_admin(),
				'doing_ajax'       => defined( 'DOING_AJAX' ) && DOING_AJAX,
				'debug'            => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'development_mode' => defined( 'NICETHEMES_DEVELOPMENT_MODE' ) && NICETHEMES_DEVELOPMENT_MODE,
			)
		);
	}

	return $instance;
}

/**
 * Check if we're in admin context.
 *
 * Usually, this function will work as a wrapper for `is_admin`.
 *
 * @see   \is_admin()
 *
 * @since 2.0.6
 *
 * @return bool
 */
function is_admin() {
	return obtain_context_instance()->{'is_admin'};
}

/**
 * Check if we're in AJAX context.
 *
 * @since 2.0.6
 *
 * @return bool
 */
function doing_ajax() {
	return obtain_context_instance()->{'doing_ajax'};
}

/**
 * Check if we're in WordPress debug mode.
 *
 * @since 2.0.6
 *
 * @return bool
 */
function debug() {
	return obtain_context_instance()->{'debug'};
}

/**
 * Check if we're in NiceThemes development mode.
 *
 * @since 2.0.6
 *
 * @return bool
 */
function development_mode() {
	return obtain_context_instance()->{'development_mode'};
}
