<?php
/**
 * Demosx by NiceThemes.
 *
 * This file contains a class to create the Article Categories widget.
 *
 * @package   Demosx
 * @author    Parti Coop <contact@parti.xyz>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Demosx_ArticleCategories extends WP_Widget {

	/**
	 * Default values for widget instance.
	 *
	 * @since 2.0
	 * @var   array
	 */
	private $instance_defaults = array(
		'title'          => array( '', 'sanitize_text_field' )
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
	* How often the tweets are refreshed (in seconds). Defualt is five minutes.
	*
	* @since 2.0
	*/
	public static $refresh = 300;

	function __construct() {

		$this->alt_option_name = 'demosx_article_categories';
		$this->normalizer      = new Nice_Widget_Normalizer( $this->instance_defaults );

		parent::__construct( 'demosx_article_categories', '(DemosxThemes) Article Categories', array(
			'description' => 'A widget that displays the article categories.',
			'classname'    => 'widget_nice_article_categories widget_demosx_article_categories' )
		);

		add_action( 'save_post', 	array(&$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache' ) );

	} // end __construct()

	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'widget_demosx_article_categories', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		// Normalize instance.
		$instance = $this->normalizer->normalize_instance( $instance );

		ob_start();

		extract( $args );
		$title = $instance['title'];

		?>
			<?php echo $before_widget; ?>
			<?php if ( $title ) { echo $before_title . $title . $after_title; } ?>

			<?php $cat_args = array(
						'taxonomy'     => 'article-category',
						'hierarchical' => true,
						'parent'       => 0,
						'hide_empty'   => true,
						'child_of'     => 0,
						'order'        => 'ASC',
						'orderby'      => 'menu_order',
					);

					$categories = get_terms( $cat_args );
					$current_post_id = get_the_ID();
					global $post;

					echo '<ul>';

					foreach( $categories as $category ) {

						echo '<li><div><span>'. $category->count . '</span><a href="' . get_term_link( $category->slug, $cat_args['taxonomy'] ) . '" title="' . sprintf( __( 'View all posts in %s', 'nicethemes' ), $category->name ) . '" ' . '>' . $category->name.'</a> </div></li> ';

						if( !is_tax('article-category') && $current_post_id && has_term($category->term_id, 'article-category', $current_post_id) ) {
							$cat_post_args = array(
									'numberposts'  => 10,
									'post_type'    => 'article',
									'orderby'      => 'menu_order',
									'order'        => 'ASC'
									);

							$cat_post_args['tax_query'] = array(
									array(
											'taxonomy'  => 'article-category',
											'field'     => 'id',
											'operator'  => 'IN',
											'terms'     => $category->term_id
											)
										);

							$cat_posts = get_posts( $cat_post_args );

							echo '<li class="children"><ul class="category-posts">';

							foreach ( $cat_posts as $post ) : setup_postdata( $post );

								$format = get_post_format();
								if ( $format === false ) { $article_icon = '<i class="fa fa-file-o"></i>'; }
								elseif( $format == 'video') { $article_icon = '<i class="fa fa-youtube-play"></i>'; }

								$class = ( $current_post_id == $post->ID ) ? ' class="active"' : '';
								echo '<li' . $class . '>'. $article_icon . ' <a href="' . get_permalink() . '" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) .'">' . get_the_title() . '</a></li>';

							endforeach;

							echo '</ul></li>';
						}
					}

					echo '</ul>';

					?>

			<?php echo $after_widget; ?>
		<?php

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'widget_demosx_article_categories', $cache, 'widget' );

	}

	function update( $new_instance, $old_instance ) {

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		return $new_instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_demosx_article_categories', 'widget' );
	}

	function form( $instance ) {

		// Normalize instance.
		$instance = $this->normalizer->normalize_instance( $instance );

		$title  = esc_attr( $instance['title'] );

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:','nicethemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>

		<?php
	}
} // end Nice_ArticleCategories classs
