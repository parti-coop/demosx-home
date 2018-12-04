<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Plugins content.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="nice-install-items nice-install-plugins">

	<?php Nice_TGM_Plugin_Activation::$instance->install_plugins_page(); ?>

</div>
