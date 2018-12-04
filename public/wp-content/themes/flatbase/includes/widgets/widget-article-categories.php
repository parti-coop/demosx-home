<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains a class to create the Article Categories widget.
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

class Nice_ArticleCategories extends WP_Widget {

	/**
	 * Default values for widget instance.
	 *
	 * @since 2.0
	 * @var   array
	 */
	private $instance_defaults = array(
		'title'          => array( '', 'sanitize_text_field' ),
		'display_subcat' => array( false, 'nice_bool' ),
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

		$this->alt_option_name = 'nice_article_categories';
		$this->normalizer      = new Nice_Widget_Normalizer( $this->instance_defaults );

		parent::__construct( 'nice_article_categories', __( '(NiceThemes) Article Categories', 'nicethemes' ), array(
			'description' => __( 'A widget that displays the article categories.', 'nicethemes' ),
			'classname'    => 'widget_nice_article_categories' )
		);

		add_action( 'save_post', 	array(&$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache' ) );

	} // end __construct()

	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'widget_nice_article_categories', 'widget');

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
		$display_subcat = false;
		if ( isset( $instance['display_subcat'] ) ) $display_subcat = $instance['display_subcat'];

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
						'orderby'      => 'name',
					);

					$categories = get_terms( $cat_args );

					echo '<ul>';

					foreach( $categories as $category ) {

						echo '<li><div><span>'. $category->count . '</span><a href="' . get_term_link( $category->slug, $cat_args['taxonomy'] ) . '" title="' . sprintf( __( 'View all posts in %s', 'nicethemes' ), $category->name ) . '" ' . '>' . $category->name.'</a> </div></li> ';

						if ( $display_subcat ) {

							$term_children = get_term_children( $category->term_id, $cat_args['taxonomy'] );

							if ( $term_children ) {
								foreach( $term_children as $term_child_id ) {
									$term_child = get_term_by( 'id', $term_child_id, $cat_args['taxonomy']);
									echo '<li class="children"><div><span>'. $term_child->count . '</span><a href="' . get_term_link( $term_child->term_id, $cat_args['taxonomy'] )  . '" title="' . sprintf( __( 'View all posts in %s', 'nicethemes' ), $term_child->name ) . '" ' . '>' . $term_child->name.'</a> </div></li> ';
								}
							}
						}

					}

					echo '</ul>';

					?>

			<?php echo $after_widget; ?>
		<?php

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'widget_nice_article_categories', $cache, 'widget' );

	}

	function update( $new_instance, $old_instance ) {

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		return $new_instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_nice_article_categories', 'widget' );
	}

	function form( $instance ) {

		// Normalize instance.
		$instance = $this->normalizer->normalize_instance( $instance );

		$title  = esc_attr( $instance['title'] );

		if ( isset( $instance['display_subcat'] ) ) {
			$display_subcat = esc_attr( $instance['display_subcat'] );
		} else {
			$display_subcat = '';
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:','nicethemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'display_subcat' ); ?>" name="<?php echo $this->get_field_name( 'display_subcat' ); ?>" type="checkbox" <?php checked( $display_subcat ); ?>><?php _e( 'Display Subcategories', 'nicethemes' ); ?></input>
		</p>


		<?php
	}
} // end Nice_ArticleCategories classs
