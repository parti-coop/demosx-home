<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains a class to create the Social widget.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Nice_Social' ) ) :
/**
 * Nice_Social_Widget
 *
 * This widget shows a list of all the social links entered in the theme options panel.
 *
 * @link http://codex.wordpress.org/Widgets_API
 *
 * @since 1.0.0
 */
class Nice_Social extends WP_Widget {
	/**
	 * Default values for widget instance.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	private $instance_defaults = array(
		'title' => array( '', 'sanitize_text_field' ),
	);

	/**
	 * Helper object to manage widget cache.
	 *
	 * @since 1.0.0
	 * @var   Nice_Widget_Cache
	 */
	private $cache;

	/**
	 * Helper object to manage common processes.
	 *
	 * @since 1.0.0
	 * @var   Nice_Widget_Normalizer
	 */
	private $normalizer;

	/**
	 * How often the widget cache is refreshed (in seconds). Default is five minutes.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $refresh = 300;

	/**
	 * Initialize widget properties.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		$this->refresh         = apply_filters( 'nice_widget_social_refresh', $this->refresh );
		$this->alt_option_name = 'nice_social';
		$this->normalizer      = new Nice_Widget_Normalizer( $this->instance_defaults );
		$this->cache           = new Nice_Widget_Cache( 'widget_nice_social' );

		parent::__construct( 'nice_social', esc_html__( '(NiceThemes) Social Widget', 'nicethemes' ), array(
				'description' => esc_html__( 'Add your social links with this widget. (Note: To set the social links you have to do it on the theme options panel.)', 'nicethemes' ),
			)
		);

		add_action( 'switch_theme',               array( $this->cache, 'flush' ) );
		add_action( 'update_option_nice_options', array( $this->cache, 'flush' ) );
	}

	/**
	 * Display the widget in the front end.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Current properties of the widget.
	 */
	function widget( $args, $instance ) {
		// If widget cache is found, print it and return early.
		if ( $cache = $this->cache->get() ) {
			echo $cache;

			return;
		}

		// Normalize instance.
		$instance = $this->normalizer->normalize_instance( $instance );

		extract( $args );
		extract( $instance );

		$title = $instance['title'];

		$target_blank = nice_bool( nice_get_option( 'nice_social_links_new_tab' ) );
		$social_links = nice_get_social_links();

		if ( ! empty( $social_links ) ) :

			echo $before_widget; ?>

			<?php if ( $title ) : ?>
				<?php echo $before_title . $title . $after_title; ?>
			<?php endif; ?>

				<!-- <div class="social-links clearfix">

					<ul id="social">
					</ul>

				</div> -->

			<div class="social-links">
				<ul id="social-<?php echo esc_attr( $widget_id ); ?>" class="social-links-container">
					<?php foreach ( $social_links as $social_link ) : ?>
						<?php if ( esc_url( $social_link['url'] ) ) : ?>
							<li id="<?php echo sanitize_title( $social_link['name'] ); ?>" class="<?php echo sanitize_title( $social_link['name'] ); ?>"><a href="<?php echo esc_url( $social_link['url'] ); ?>"<?php echo $target_blank ? ' target="_blank"' : ''; ?> title="<?php echo esc_attr( $social_link['name'] ); ?>"><i class="<?php echo esc_attr( $social_link['icon_class'] ); ?>"></i></a></li>
						<?php endif; ?>

					<?php endforeach; ?>
				</ul>
			</div>

			<?php echo $after_widget;

		endif;
	}

	/**
	 * Flush the widget cache when the widget is updated.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$this->cache->flush();

		// Normalize instance.
		$new_instance = $this->normalizer->normalize_instance( $new_instance );

		return $new_instance;
	}

	/**
	 * Display widget form in "Appearance > Widgets".
	 *
	 * @since  1.0
	 *
	 * @param  array       $instance
	 * @return string|void
	 */
	function form( $instance ) {
		// Normalize instance.
		$instance = $this->normalizer->normalize_instance( $instance );

		extract( $instance );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title (optional):', 'nicethemes' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"  value="<?php echo esc_attr( $title ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" />
			</p>
		<?php
	}
}
endif;
