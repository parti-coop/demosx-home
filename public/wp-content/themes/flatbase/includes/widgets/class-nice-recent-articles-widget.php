<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains a class to create the Recent Articles Widget
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Nice_Recent_Articles_Widget' ) ) :
/**
 * Nice_Recent_Articles_Widget
 *
 * This widget shows a list of all the social links entered in the theme options panel.
 *
 * @link http://codex.wordpress.org/Widgets_API
 *
 * @since 2.0
 */
class Nice_Recent_Articles_Widget extends WP_Widget {
	/**
	 * Default values for widget instance.
	 *
	 * @since 2.0
	 * @var   array
	 */
	private $instance_defaults = array(
		'title'  => array( '', 'sanitize_text_field' ),
		'number' => array( 5, 'absint' ),
	);

	/**
	 * Helper object to manage widget cache.
	 *
	 * @since 2.0
	 * @var   Nice_Widget_Cache
	 */
	private $cache;

	/**
	 * Helper object to manage common processes.
	 *
	 * @since 2.0
	 * @var   Nice_Widget_Normalizer
	 */
	private $normalizer;

	/**
	 * How often the widget cache is refreshed (in seconds). Default is five minutes.
	 *
	 * @since 2.0
	 * @var   int
	 */
	public $refresh = 300;

	/**
	 * Initialize widget properties.
	 *
	 * @since 2.0
	 */
	function __construct() {
		$this->refresh         = apply_filters( 'nice_widget_recent_articles_refresh', $this->refresh );
		$this->alt_option_name = 'nice_recent_articles';
		$this->normalizer      = new Nice_Widget_Normalizer( $this->instance_defaults );
		$this->cache           = new Nice_Widget_Cache( 'widget_recent_articles' );

		parent::__construct( 'nice_recent_articles', esc_html__( '(NiceThemes) Recent Articles', 'nicethemes' ), array(
				'description' => esc_html__( 'Your site’s most recent Articles.', 'nicethemes' ),
			)
		);

		add_action( 'switch_theme',               array( $this->cache, 'flush' ) );
		add_action( 'update_option_nice_options', array( $this->cache, 'flush' ) );
	}

	/**
	 * Display the widget in the front end.
	 *
	 * @since 2.0
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

		$title  = esc_attr( $instance['title'] );
		$number = esc_attr( $instance['number'] );


		echo $before_widget; ?>

			<?php if ( $title ) : ?>
				<?php echo $before_title . $title . $after_title; ?>
			<?php endif; ?>

			<ul class="clearfix">

				<?php
				$query = new WP_Query();
				$query->query( array (
									'post_type'           => 'article',
									'posts_per_page'      => $number,
									'ignore_sticky_posts' => true,
									'orderby'             => 'date',
									'order'               => 'DESC'

									)
								);
				?>
				<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
				<li class="clearfix <?php if ( has_post_format( 'video' )) { ?>format-video<?php } else { ?>format-standard<?php } ?>">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
						<span class="meta">
							<span class="nice-views"><i class="fa fa-bullseye"></i> <?php echo get_post_meta( get_the_ID(), '_pageview_count', true ); ?> </span>
							<span class="nice-likes"><i class="fa fa-heart"></i> <span class="like-count"><?php echo nicethemes_likes_count(); ?></span></span>
						</span>
				</li>
				<?php endwhile; endif; ?>

				<?php wp_reset_query(); ?>

			</ul>

			<?php echo $after_widget;
	}

	/**
	 * Flush the widget cache when the widget is updated.
	 *
	 * @since 2.0
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

		$title  = esc_attr( $instance['title'] );
		$number = esc_attr( $instance['number'] );

		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title (optional):', 'nicethemes' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"  value="<?php echo esc_attr( $title ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number:', 'nicethemes' ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('number'); ?>"  value="<?php echo esc_attr( $number ); ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
			</p>

		<?php
	}
}
endif;
