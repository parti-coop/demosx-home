<?php
/**
 * Flabtase by NiceThemes.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://www.nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_default_social_links' ) ) :
/**
 * Obtain default social links for this theme.
 *
 * @since 1.0.0
 */
function nice_theme_default_social_links() {
	$social_links[] = array(
		'name' => esc_html__( 'Facebook', 'nicethemes' ),
		'id'   => 'nice_facebook',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-facebook',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Twitter', 'nicethemes' ),
		'id'   => 'nice_twitter',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-twitter',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Instagram', 'nicethemes' ),
		'id'   => 'nice_instagram',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-instagram',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Google+', 'nicethemes' ),
		'id'   => 'nice_google',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-google-plus',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Dribbble', 'nicethemes' ),
		'id'   => 'nice_dribbble',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-dribbble',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Vimeo', 'nicethemes' ),
		'id'   => 'nice_vimeo',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-vimeo',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Tumblr', 'nicethemes' ),
		'id'   => 'nice_tumblr',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-tumblr',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Flickr', 'nicethemes' ),
		'id'   => 'nice_flickr',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-flickr',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'YouTube', 'nicethemes' ),
		'id'   => 'nice_youtube',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-youtube',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'LinkedIn', 'nicethemes' ),
		'id'   => 'nice_linkedin',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-linkedin',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Dropbox', 'nicethemes' ),
		'id'   => 'nice_dropbox',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-dropbox',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Foursquare', 'nicethemes' ),
		'id'   => 'nice_foursquare',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-foursquare',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Pinterest', 'nicethemes' ),
		'id'   => 'nice_pinterest',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-pinterest-p',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Quora', 'nicethemes' ),
		'id'   => 'nice_quora',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-quora',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Skype', 'nicethemes' ),
		'id'   => 'nice_skype',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-skype',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Bitbucket', 'nicethemes' ),
		'id'   => 'nice_bitbucket',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-bitbucket',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'GitHub', 'nicethemes' ),
		'id'   => 'nice_github',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-github',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Stack Exchange', 'nicethemes' ),
		'id'   => 'nice_stack_exchange',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-stack-exchange',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Stack Overflow', 'nicethemes' ),
		'id'   => 'nice_stack_overflow',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-stack-overflow',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Trello', 'nicethemes' ),
		'id'   => 'nice_trello',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-trello',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'WordPress', 'nicethemes' ),
		'id'   => 'nice_wordpress',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-wordpress',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Slack', 'nicethemes' ),
		'id'   => 'nice_slack',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-slack',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Spotify', 'nicethemes' ),
		'id'   => 'nice_spotify',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-spotify',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	$social_links[] = array(
		'name' => esc_html__( 'Soundcloud', 'nicethemes' ),
		'id'   => 'nice_soundcloud',
		'std'  => array(
			'url'             => '',
			'icon_class'      => 'fa fa-soundcloud',
			'show_in_menu'    => false,
			'show_in_top_bar' => false,
			'show_in_footer'  => false,
		),
	);

	return apply_filters( 'nice_theme_default_social_links', $social_links );
}
endif;

if ( ! function_exists( 'nice_get_social_links' ) ) :
/**
 * Obtain a list with the current social links.
 *
 * @since  2.0
 *
 * @param  string $section
 *
 * @return array
 */
function nice_get_social_links( $section = '' ) {
	$social_links         = array();
	$default_social_links = nice_theme_default_social_links();

	$social_links_settings = nice_get_option( 'nice_social_links', $default_social_links );

	if ( empty( $social_links_settings ) ) {
		foreach ( $default_social_links as $social_link ) {
			$social_links_settings[ $social_link['id'] ] = $social_link['name'];
		}
	}

	switch ( $section ) {
		case 'top-bar':
			$show_key = 'show_in_top_bar';
			break;
		case 'footer':
			$show_key = 'show_in_footer';
			break;
		default:
			$show_key = 'show_in_menu';
			break;
	}

	foreach ( $social_links_settings as $id => $name ) {
		$social_links[ $id ] = array(
			'id'         => $id,
			'name'       => $name,
			'url'        => nice_get_option( $id . '_url', '' ),
			'icon_class' => nice_get_option( $id . '_icon_class' ),
			'show'       => nice_get_option( $id . '_' . $show_key ),
		);
	}

	return apply_filters( 'nice_social_links', $social_links );
}
endif;

if ( ! function_exists( 'nice_top_bar_social_shortcode' ) ) :
add_filter( 'nice_top_bar_shortcodes', 'nice_top_bar_social_shortcode' );
/**
 * Add shortcodes to top bar.
 *
 * @since  2.0
 *
 * @param  array $shortcodes
 *
 * @return array
 */
function nice_top_bar_social_shortcode( array $shortcodes = array() ) {
	$shortcodes['social_icons'] = 'nice_top_bar_social_icons_shortcode';
	$shortcodes['social_icon']  = 'nice_top_bar_social_icon_shortcode';

	return $shortcodes;
}
endif;

if ( ! function_exists( 'nice_top_bar_social_icons_shortcode' ) ) :
/**
 * Manage [social_icons] shortcode on top bar.
 *
 * @since 2.0
 */
function nice_top_bar_social_icons_shortcode() {
	return nice_social_links( array(
			'section'     => 'top-bar',
			'echo'        => false,
			'show_always' => false,
			'before'      => '<div id="top-bar-social">',
			'after'       => '</div>',
		)
	);
}
endif;

if ( ! function_exists( 'nice_top_bar_social_icon_shortcode' ) ) :
/**
 * Process social_icon shortcode.
 *
 * @since 2.0
 *
 * @param array $atts
 *
 * @return string
 */
function nice_top_bar_social_icon_shortcode( array $atts = array() ) {
	$icon_class = empty( $atts['icon'] ) ? '' : $atts['icon'];
	$url        = empty( $atts['url'] ) ? '' : $atts['url'];

    ob_start();
    ?>
        <span class="social-icon">
            <?php if ( ! empty( $url ) ) : ?>
            <a href="<?php echo esc_url( $url ); ?>">
            <?php endif; ?>
                <?php if ( isset( $atts['icon'] ) && nice_bool( $atts['icon'] ) ) : ?>
                    <i class="fa <?php echo esc_attr( $icon_class ); ?>"></i>
                <?php endif; ?>
            <?php if ( ! empty( $url ) ) : ?>
            </a>
            <?php endif; ?>
        </span>
    <?php

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
endif;

if ( ! function_exists( 'nice_social_links' ) ) :
/**
 * Print social Links.
 *
 * @since  1.0.0
 *
 * @param  array $args
 *
 * @return string
 */
function nice_social_links( $args = array() ) {
	static $output = array();

	$defaults = apply_filters( 'nice_social_links_default_args', array(
			'section'     => 'header',
			'echo'        => true,
			'before'      => '',
			'after'       => '',
			'show_always' => false,
		)
	);

	$args = wp_parse_args( $args, $defaults );

	if ( empty( $output[ $args['section'] ] ) ) {

		$target_blank = nice_bool( nice_get_option( 'nice_social_links_new_tab' ) );

		$social_links = nice_get_social_links( $args['section'] );

		// Initialize output.
		ob_start();

		if ( ! empty( $social_links ) ) :

			echo $args['before'];

			foreach ( $social_links as $social_link ) :
				if ( ( nice_bool( $args['show_always'] ) || nice_bool( $social_link['show'] ) ) && esc_url( $social_link['url'] ) ) : ?>
					<a href="<?php echo esc_url( $social_link['url'] ); ?>"<?php echo $target_blank ? ' target="_blank"' : ''; ?> title="<?php echo esc_attr( $social_link['name'] ); ?>"><i class="<?php echo esc_attr( $social_link['icon_class'] ); ?>"></i></a>
				<?php endif;
			endforeach;

			echo $args['after'];

		endif;

		$output[ $args['section'] ] = ob_get_contents();
		ob_end_clean();
	}

	if ( $args['echo'] ) {
		echo $output[ $args['section'] ];
	}

	return $output[ $args['section'] ];
}
endif;
