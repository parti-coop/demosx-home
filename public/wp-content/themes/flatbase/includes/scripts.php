<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file includes functions to manage scripts for the theme.
 *
 * @see nice_scripts()
 * @see nice_ie_head_scripts()
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_async_scripts' ) ) :
add_filter( 'nice_async_scripts', 'nice_theme_async_scripts' );
/**
 * List of scripts that can be loaded asynchronously.
 *
 * This list should be used for custom scripts, and only in cases where the
 * loading order isn't an issue. It is not recommended for the main JS file.
 *
 * @since 2.0
 *
 * @param array $scripts
 *
 * @return array
 */
function nice_theme_async_scripts( $scripts = array() ) {
	global $is_IE;

	$scripts = array_merge( $scripts, array(
			'nice-contact-map',
			'nice-full-page-overlay',
		)
	);

	if ( $is_IE ) {
		$scripts[] = 'nice-object-fit-image-scripts';
	}

	return $scripts;
}
endif;

if ( ! function_exists( 'nice_theme_on_demand_scripts' ) ) :
add_filter( 'nice_on_demand_scripts', 'nice_theme_on_demand_scripts' );
/**
 * List of scripts that can be loaded on demand.
 *
 * This list should be used for third-party libraries only. If a script listed
 * as asynchronous or deferred is also in this list, it will ne used only as an
 * on-demand script.
 *
 * @since 2.0
 */
function nice_theme_on_demand_scripts( $scripts = array() ) {
	$scripts = array_merge( $scripts, array(
		'nice-waypoints-source'     => 'waypoints',
		'nice-fancybox-source'      => 'fancybox',
		'nice-superfish-source'     => 'superfish',
		'nice-isotope-source'       => 'isotope',
		'nice-vimeo-player-source'  => 'vimeoPlayer',
		'nice-scrollto-source'      => 'scrollTo',
	) );

	if ( nice_lazyload_images() ) {
		$scripts['nice-lazyload-source'] = 'lazyload';
	}

	return $scripts;
}
endif;

if ( ! function_exists( 'nice_scripts' ) ) :
add_action( 'nice_register_scripts', 'nice_scripts' );
/**
 * Register general scripts.
 *
 * @uses wp_register_script()
 * @uses wp_enqueue_script()
 *
 * @since 1.0.0
 */
function nice_scripts() {
	$on_demand_js = nice_on_demand_js();
	$js_folder    = 'includes/assets/js';

	$nice_scripts_deps = array( 'jquery' );

	/**
	 * Register Pace scripts.
	 *
	 * @since 1.0.0
	 */
	wp_register_script( 'nice-pace-source', nice_get_file_uri( $js_folder . '/pace.js', true ), array(), '1.0.2' );
	$nice_scripts_deps[] = 'nice-pace-source';

	wp_register_script( 'nice-waypoints-source', nice_get_file_uri( $js_folder . '/jquery.waypoints.js', true ), array( 'jquery' ), '4.0.1', true );
	$nice_scripts_deps[] = 'nice-waypoints-source';

	/**
	 * Live Search scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_live_search_scripts', true ) ) {
		wp_register_script( 'nice-livesearch-js', get_template_directory_uri() . '/includes/assets/js/jquery.livesearch.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-livesearch-js');
		add_action( 'wp_head', 'nice_livesearch_js', 10 );
	}

	/**
	 * Register Fancybox scripts.
	 *
	 * @since 1.0.0
	 */
	wp_register_script( 'nice-fancybox-source', nice_get_file_uri( $js_folder . '/jquery.fancybox.js', true ), array( 'jquery' ), '2.1.5', true );
	$nice_scripts_deps[] = 'nice-fancybox-source';

	/**
	 * Register Superfish scripts.
	 *
	 * @since 1.0.0
	 */
	wp_register_script( 'nice-superfish-source', nice_get_file_uri( $js_folder . '/superfish.js', true ), array( 'jquery', 'hoverIntent' ), '1.7.9', true );
	$nice_scripts_deps[] = 'nice-superfish-source';

	/**
	 * Load Isotope scripts.
	 *
	 * @since 1.0.0
	 */
	wp_register_script( 'nice-isotope-source', nice_get_file_uri( $js_folder . '/min/isotope.min.js' ), array( 'jquery' ), '2.2.2', true );
	wp_enqueue_script( 'nice-isotope-source' );
	$nice_scripts_deps[] = 'nice-isotope-source';

	/**
	 * Register Vimeo Player scripts.
	 *
	 * @since 1.0.0
	 */
	wp_register_script( 'nice-vimeo-player-source', nice_get_file_uri( $js_folder . '/player.js', true ), array(), '2.0.1', true );


	/**
	 * Load scripts for scroll.
	 *
	 * @since 1.0.0
	 */
	wp_register_script( 'nice-scrollto-source', nice_get_file_uri( $js_folder . '/jquery.scrollTo.js', true ), array( 'jquery' ), '2.1.2', true );
	$nice_scripts_deps[] = 'nice-scrollto-source';

	/**
	 * Load scripts for LazyLoad.
	 *
	 * @since 1.0.0
	 */
	if ( nice_lazyload_images() ) {
		wp_register_script( 'nice-lazyload-source', nice_get_file_uri( $js_folder . '/lazyload.js', true ), array(), '4.0.4', true );
		$nice_scripts_deps[] = 'nice-lazyload-source';
	}

	// VER DE LOS VIEJOS CUALES FALTAN

	/**
	 * Contact scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_load_contact_js', false ) ) {
		wp_register_script( 'nice-contact-validation', '//ajax.microsoft.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-contact-validation' );
		add_action( 'wp_head', 'nice_contact_js', 10 );
	}

	/**
	 * Register Masonry scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_masonry_scripts', true ) ) {
		$version = version_compare( get_bloginfo( 'version' ), '3.5', '<' );

		if ( $version ) {
			wp_register_script( 'jquery-masonry', get_template_directory_uri() . '/includes/assets/js/jquery.masonry.min.js', array( 'jquery' ) );
		}
		wp_enqueue_script( 'jquery-masonry' );
	}

	/**
	 * More Posts scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_load_more_posts_loader_js', false ) ) {
		add_action( 'wp_footer', 'nice_more_posts_loader_js', 10 );
	}

	/**
	 * Likes scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nicethemes_likes_scripts', true ) ) {
		add_action( 'wp_head', 'nicethemes_likes_js', 10 );
	}

	/**
	 * Register general scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_scripts', true ) ) {
		$data = array(
			'homeURL'       => get_home_url(),
			'adminURL'      => admin_url(),
			'adminAjaxURL'  => admin_url() . 'admin-ajax.php',
			'playNiceNonce' => wp_create_nonce( 'play-nice' ),
			'headerFixed'   => nice_bool( nice_get_option( 'nice_header_fixed' ) ),
			'headerShrink'  => nice_bool( nice_get_option( 'nice_header_shrink' ) ),
			'AjaxCache'     => $on_demand_js,
		);

		// Make sure this argument doesn't get printed if we're not in development mode.
		if ( nice_development_mode() ) {
			$data['devMode'] = true;
		}

		if ( nice_use_minified_files() ) {
			/**
			 * If we're using minified assets, load all theme JS
			 * implementation from a single minified file.
			 *
			 * @since 1.0.0
			 */
			wp_register_script( 'nice-scripts', nice_get_file_uri( $js_folder . '/min/nice-scripts.min.js' ), $nice_scripts_deps, false, true );
			wp_enqueue_script( 'nice-scripts' );
		} else {
			/**
			 * If we're not using minified assets, load all theme
			 * JS implementation from separated files.
			 *
			 * @since 1.0.0
			 */
			wp_register_script( 'nice-scripts', nice_get_file_uri( $js_folder . '/nice-general.js' ), $nice_scripts_deps, false, true );
			wp_enqueue_script( 'nice-scripts' );

			wp_register_script( 'nice-dev', nice_get_file_uri( $js_folder . '/nice-dev.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-dev' );

			wp_register_script( 'nice-lazy-scripts', nice_get_file_uri( $js_folder . '/nice-lazy-scripts.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-lazy-scripts' );


			wp_register_script( 'nice-mobile', nice_get_file_uri( $js_folder . '/nice-mobile.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-mobile' );

			wp_register_script( 'nice-lazyload', nice_get_file_uri( $js_folder . '/nice-lazyload.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-lazyload' );

			wp_register_script( 'nice-throttle', nice_get_file_uri( $js_folder . '/nice-throttle.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-throttle' );

			// Load Fancybox implementation.
			wp_register_script( 'nice-fancybox', nice_get_file_uri( $js_folder . '/nice-fancybox.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-fancybox' );

			// Load Superfish implementation.
			wp_register_script( 'nice-superfish', nice_get_file_uri( $js_folder . '/nice-superfish.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-superfish' );


			// Load custom Isotope implementation.
			wp_register_script( 'nice-isotope-scripts', nice_get_file_uri( $js_folder . '/nice-isotope.js' ), array( 'nice-scripts' ), false, true );
			wp_enqueue_script( 'nice-isotope-scripts' );
		}

		/**
		 * Register and enqueue script for full page loader.
		 */
		wp_register_script( 'nice-full-page-overlay', nice_get_file_uri( $js_folder . '/nice-full-page-overlay.js', true ), array( 'nice-scripts' ), false, true );
		// Only enqueue the script if the full page loader is enabled.
		if ( 'full_page' === nice_get_option('_page_loader') ) {
			wp_enqueue_script( 'nice-full-page-overlay' );
		}

		/**
		 * Register Contact Page Template script for Google Map.

		wp_register_script( 'nice-contact-map', nice_get_file_uri( $js_folder . '/nice-contact-map.js', true ), array( 'nice-scripts' ), false, true );
		// Only enqueue the script in Contact page templates.
		if ( is_page_template( 'page-templates/contact.php' ) && nice_get_option( '_gmaps_api_key' ) && nice_get_option( '_google_map' ) ) {
			wp_enqueue_script( 'nice-contact-map' );
		}*/

		if ( $on_demand_js ) {
			$data['lazyScripts'] = nice_on_demand_scripts();
		}

		wp_localize_script( 'nice-scripts', 'generalData', $data );
	}

	/**
	 * Load polyfill to support object-fit property in IE.
	 *
	 * @since 1.0.0
	 */
	global $is_IE;
	if ( $is_IE ) {
		wp_register_script( 'nice-object-fit-image-scripts', nice_get_file_uri( $js_folder . '/ofi.js', true ), array(), false, true );

		if ( apply_filters( 'nice_ie_object_fit_image_scripts', true ) && $is_IE ) {
			wp_enqueue_script( 'nice-object-fit-image-scripts' );
		}
	}

	/**
	 * Load JavaScript for "load more" functionality conditionally (disabled by default).
	 *
	 * @since 1.0.0

	wp_register_script( 'nice-posts-loader-scripts', nice_get_file_uri( $js_folder . '/nice-posts-loader.js', true ), array( 'jquery' ), false, true );

	if ( apply_filters( 'nice_load_more_posts_scripts', false ) ) {
		$on_scroll = ( 'on_scroll' === nice_get_option( 'nice_masonry_posts_load_method' ) );

		wp_enqueue_script( 'nice-posts-loader-scripts' );

		wp_localize_script( 'nice-posts-loader-scripts', 'postsLoader', array(
				'onScroll'     => $on_scroll,
				'isOriginLeft' => ! is_rtl(),
			)
		);

		wp_playlist_scripts( 'audio' );
		wp_playlist_scripts( 'video' );
	}*/
}
endif;


/*
	OLD
*/

if ( ! is_admin() ):

	// he's watching the theme, let's load js and css scripts :)
	//add_action( 'wp_print_scripts', 'nice_scripts_js' );

endif;


if ( ! function_exists( 'nice_scripts_js' ) ) :
/**
 * nice_scripts_js()
 *
 * register js scripts and enqueue them
 *
 * @since 1.0
 *
 * @uses wp_register_script, wp_enqueue_script
 *
 */
function nice_scripts_js() {

	global $nice_options, $wp_scripts;

	/**
	 * Register general scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_general_scripts', true ) ) {
		wp_register_script( 'nice-general-scripts', get_template_directory_uri() . '/includes/assets/js/nice-general.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-general-scripts' );

		// Localize scripts.
		wp_localize_script( 'nice-general-scripts', 'generalData', array(
				'adminAjaxURL'  => admin_url() . 'admin-ajax.php',
				'playNiceNonce' => wp_create_nonce( 'play-nice' ),
			)
		);
	}

	/**
	 * Register Fancybox scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_fancybox_scripts', true ) ) {
		// Load Fancybox source.
		wp_register_script( 'nice-fancybox-source', get_template_directory_uri() . '/includes/assets/js/jquery.fancybox.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-fancybox-source' );
	}

	/**
	 * Register Masonry scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_masonry_scripts', true ) ) {
		$version = version_compare( get_bloginfo( 'version' ), '3.5', '<' );

		if ( $version ) {
			wp_register_script( 'jquery-masonry', get_template_directory_uri() . '/includes/assets/js/jquery.masonry.min.js', array( 'jquery' ) );
		}
		wp_enqueue_script( 'jquery-masonry' );
	}

	/**
	 * Superfish scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_superfish_scripts', true ) ) {

		wp_register_script( 'nice-superfish', get_template_directory_uri() . '/includes/assets/js/superfish.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-superfish' );
	}

	/**
	 * Register Imagesloaded scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_imagesloaded_scripts', true ) ) {
		wp_register_script( 'nice-imagesloaded', get_template_directory_uri() . '/includes/assets/js/imagesloaded.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-imagesloaded' );
	}

	/**
	 * Load scripts for scroll.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_scroll_scripts', true ) ) {
		wp_register_script( 'nice-scrollto-scripts',
			get_template_directory_uri() . '/includes/assets/js/jquery.scrollTo-min.js',
			array( 'jquery', 'jquery-ui-core' )
		);
		wp_enqueue_script( 'nice-scrollto-scripts' );

		wp_register_script( 'nice-localscroll-scripts',
			get_template_directory_uri() . '/includes/assets/js/jquery.localscroll-min.js',
			array( 'jquery', 'jquery-ui-core' )
		);
		wp_enqueue_script( 'nice-localscroll-scripts' );
	}

	/**
	 * Contact scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_load_contact_js', false ) ) {
		wp_register_script( 'nice-contact-validation', '//ajax.microsoft.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-contact-validation' );
		add_action( 'wp_head', 'nice_contact_js', 10 );
	}

	/**
	 * More Posts scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_load_more_posts_loader_js', false ) ) {
		add_action( 'wp_footer', 'nice_more_posts_loader_js', 10 );
	}

	/**
	 * Likes scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nicethemes_likes_scripts', true ) ) {
		add_action( 'wp_head', 'nicethemes_likes_js', 10 );
	}

	/**
	 * Live Search scripts.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_live_search_scripts', true ) ) {
		wp_register_script( 'nice-livesearch-js', get_template_directory_uri() . '/includes/assets/js/jquery.livesearch.js', array( 'jquery' ) );
		wp_enqueue_script( 'nice-livesearch-js');
		add_action( 'wp_head', 'nice_livesearch_js', 10 );
	}

	do_action( 'nice_scripts_js' );

} // end nice_scripts_js

endif;

if ( ! function_exists( 'nice_ie_head_scripts' ) ) :
add_action( 'wp_head', 'nice_ie_head_scripts' );
/**
 * Add IE conditionals to header.
 *
 * @since 1.0.0
 */
function nice_ie_head_scripts() {
	global $is_IE;

	if ( $is_IE ) {
		$scripts  = '<!--[if lt IE 9]>' . "\n";
		$scripts .= '<script src="' . get_template_directory_uri() . '/includes/assets/js/html5.js" type="text/javascript"></script>' . "\n";
		$scripts .= '<script src="' . get_template_directory_uri() . '/includes/assets/js/respond-IE.js" type="text/javascript"></script>' . "\n";
		$scripts .= '<![endif]-->' . "\n";
		$scripts  = apply_filters( 'nice_ie_head_scripts', $scripts );

		echo $scripts;
	}
}
endif;


if ( ! function_exists( 'nice_livesearch_js' ) ):
/**
 * nice_livesearch_js()
 *
 * initialize the LiveSearch JavaScript
 *
 * @since 1.0.0
 *
 */
function nice_livesearch_js() {
	// being here we can set admin-ajax.php for WP multisite
	?>
	<script type="text/javascript">
	//<![CDATA[
		jQuery(document).ready(function() {
			jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=true&livesearch=true&s='});
		});
	//]]>
	</script>
	<?php

}

endif;


if ( ! function_exists( 'nice_contact_js' ) ) :
/**
 * nice_contact_js()
 *
 * print js for contact form
 *
 * @since 1.0.0
 *
 */
function nice_contact_js() {
	// being here we can set admin-ajax.php for WP multisite
	?>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {

		jQuery('#nice_contact').validate({

			messages: {
					"name":    { "required": "<?php echo esc_js( 'Please enter your name.', 'nicethemes' ); ?>"},
					"mail":    { "required": "<?php echo esc_js( 'Please enter your email address.', 'nicethemes' ); ?>"},
					"message": { "required": "<?php echo esc_js( 'Please enter a message.', 'nicethemes' ); ?>"}
			},

			submitHandler: function(form) {

				var str = jQuery('#nice_contact').serialize();

				jQuery.ajax({
					type: "POST",
					url: "<?php echo admin_url();?>admin-ajax.php",
					data: 'action=nice_contact_form&nonce=<?php echo wp_create_nonce("play-nice");?>&' + str,
					success: function(msg) {
						jQuery("#node").ajaxComplete(function(event, request, settings) {
							if ( msg == 'sent' ) {
								jQuery(".nice-contact-form #node").hide();
								jQuery(".nice-contact-form #success").fadeIn("slow");
							} else {
								result = msg;
								jQuery(".nice-contact-form #node").html(result);
								jQuery(".nice-contact-form #node").fadeIn("slow");
							}
							document.querySelector('#nice_contact').reset();
						});
					}
				});
				return false;
			}
		});
	});
	//]]>
	</script>
	<?php
}
endif;


if ( ! function_exists( 'nicethemes_likes_js' ) ) :
/**
 * nicethemes_likes_js()
 *
 * print js for likes form
 *
 * @since 1.0.0
 *
 */
function nicethemes_likes_js() {

?>
	<script type="text/javascript">
		/* <![CDATA[ */
		jQuery(document).ready(function($) {

			nicethemes_likes_handler();

		});
		/* ]]> */
	</script>
<?php
}
endif;

if ( ! function_exists( 'nice_more_posts_loader_js' ) ) :
/**
 * nice_more_posts_loader_js()
 *
 * print js for masonry blog more posts loader
 *
 * @since 1.0.0
 *
 */
function nice_more_posts_loader_js() {

global $nice_options;
?>

<script type="text/javascript">
	/* <![CDATA[ */

	jQuery(document).ready( function($) {

		/* masonry script */
		var $masonry_container   = jQuery('#masonry-grid');
		var $masonry_item_width  = jQuery('.blog-masonry .grid .masonry-item').outerWidth();
		var $masonry_item_margin = jQuery('.blog-masonry .grid .masonry-item').css('marginRight');

		// initialize Masonry after all images have loaded
		$masonry_container.imagesLoaded( function() {
			$masonry_container.masonry( {
					columnWidth: $masonry_item_width,
					itemSelector: '.blog-masonry .grid .masonry-item',
					gutterWidth: parseInt( $masonry_item_margin ),
					isResizable: true
			});
		});

		jQuery(window).resize(function(){
			var $masonry_item_cur_width = jQuery('.blog-masonry .grid .masonry-item').outerWidth();
			$masonry_item_margin = jQuery('.blog-masonry .grid .masonry-item').css('marginRight');
				$masonry_container.masonry( 'option', {
					columnWidth: $masonry_item_cur_width,
					gutterWidth: parseInt( $masonry_item_margin )
				});
				jQuery($masonry_container).masonry('reload');
		});


			var page = 1;
			var loading = false;
			var $window = jQuery(window);
			var $content = jQuery("#masonry-grid");
			var load_posts = function(){
			jQuery.ajax({
					type: "POST",
					url: "<?php echo admin_url();?>admin-ajax.php",
					data: 'action=nice_more_posts_loader&nonce=<?php echo wp_create_nonce( 'play-nice' );?>&pageNumber=' + page,
						beforeSend : function(){
							if ( page != 1 ) {
								jQuery("#posts-ajax-loader-button").css('visibility', 'hidden');
								jQuery("#posts-ajax-loader").show();
							}
						},
						success	: function(data){
							$data = jQuery(data);
							if( ! $data.hasClass( 'no-more-posts' ) ){
								$data.hide();
								jQuery($masonry_container).append($data).imagesLoaded( function() {
									//jQuery($masonry_container).masonry('reloadItems');
									$masonry_container.masonry( 'appended', $data );

									var $masonry_item_cur_width = jQuery('.blog-masonry .grid .masonry-item').outerWidth();
									$masonry_item_margin = jQuery('.blog-masonry .grid .first').css('marginRight');
									$masonry_container.masonry( 'option', {
										columnWidth: $masonry_item_cur_width,
										gutterWidth: parseInt( $masonry_item_margin )
									});
									jQuery($masonry_container).masonry('reload');


									$data.fadeIn();
									jQuery("#posts-ajax-loader").hide();
									jQuery("#posts-ajax-loader-button").css('visibility', 'visible');
									loading = false;
								});

							} else {
								jQuery("#posts-ajax-loader").hide();
								jQuery("#posts-ajax-loader-button").hide();
								jQuery("#content").append(data);
							}
						},
						error	 : function(jqXHR, textStatus, errorThrown) {
							jQuery("#posts-ajax-loader").hide();
							jQuery("#posts-ajax-loader-button").css('visibility', 'visible');
							console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
						}
				});
			}

			function load_masonry_blog_posts() {
				if ( ! loading ) {
					loading = true;
					page++;
					load_posts();
				}
			}

			<?php if ( ! isset( $nice_options['nice_masonry_posts_load_method'] ) || $nice_options['nice_masonry_posts_load_method'] == 'on_scroll'  ) { ?>
				$window.scroll( function() {
					var content_offset = $content.offset();
					if (  $window.scrollTop() + $window.height()  > ($content.scrollTop() + $content.height() + content_offset.top)) {
						load_masonry_blog_posts();
					}
				});
			<?php } else { ?>

				jQuery("#posts-ajax-loader-button").click(function() {
					load_masonry_blog_posts();
					return false;
				});

			<?php } ?>

		jQuery($masonry_container).masonry();

	});
	/* ]]> */
</script>
<?php

}

endif;