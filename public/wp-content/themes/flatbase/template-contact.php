<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Contact
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

$nice_contact_form = apply_filters( 'nice_contact_form', true );

if ( $nice_contact_form ) {
	add_filter( 'nice_load_contact_js', '__return_true', 10 );
}

get_header(); ?>

	<!-- BEGIN #content -->
	<section id="content" <?php nice_content_class( 'main-content' ); ?> role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<div class="nice-contact-form">

				<?php
					/**
					 * @hook nice_before_single_post
					 *
					 * Hook here to add HTML elements before the contents of the post are
					 * displayed.
					 *
					 * @since 2.0
					 *
					 * Hooked here:
					 * @see nice_post_content_title() - 10
					 */
					do_action( 'nice_before_single_post' );
				?>

					<?php

						$nice_google_map = get_option( 'nice_google_map' );

						if ( ! empty ( $nice_google_map ) ): ?>
							<div class="nice-contact-map clearfix">
								<?php echo nice_embed( array ( 'embed' => $nice_google_map, 'width' => 960, 'height' => 300) ); ?>
							</div>
						<?php endif; ?>

					<div class="entry">
						<?php the_content( __( 'Continue reading', 'nicethemes' ) . ' &raquo;' ); ?>
					</div>

					<?php if ( $nice_contact_form ) : ?>
						<div id="node"></div>
						<div id="success"><?php _e( 'Thank you for leaving a message.', 'nicethemes' ); ?></div>

						<form name="nice_contact" id="nice_contact" method="post" >
						<p>
							<label class="display-ie8" for="name" form="nice_contact"><?php _e( 'Your Name', 'nicethemes' ); ?><span class="required">*</span></label>
							<input type="text" id="name" name="name" value="" class="required" placeholder="<?php _e( 'Your Name', 'nicethemes' ); ?>" title="<?php _e( '* Please enter your Full Name', 'nicethemes'); ?>" />
						</p>
						<p>
							<label class="display-ie8" for="subject" form="nice_contact"><?php _e( 'Subject', 'nicethemes' ); ?></label>
							<input type="text" name="subject" id="subject" value="" placeholder="<?php _e( 'Subject', 'nicethemes'); ?>" title="<?php _e( '* Please enter the subject', 'nicethemes'); ?>" />
						</p>
						<p>
							<label class="display-ie8" for="mail" form="nice_contact"><?php _e( 'Your E-Mail', 'nicethemes' ); ?><span class="required">*</span></label>
							<input type="text" name="mail" id="mail" value="" class="required email" placeholder="<?php _e( 'Your E-Mail', 'nicethemes'); ?>" title="<?php _e( '* Please enter your email', 'nicethemes' ); ?>" />
						</p>
						<p>
							<label class="display-ie8" for="message" form="nice_contact"><?php _e( 'Your Message', 'nicethemes' ); ?><span class="required">*</span></label><br />
							<textarea name="message" id="message" class="required" placeholder="<?php _e( 'Your Message', 'nicethemes' ); ?>" title="<?php _e( '* Please enter a message', 'nicethemes'); ?>"></textarea>
						</p>
						<p>
						<input type="submit" value="<?php _e( 'Submit', 'nicethemes' ); ?>" />
						</p>
						</form>
					<?php endif; ?>
				</div>

		<?php endwhile; ?>

		<!-- END #content -->
		</section>

<?php
get_sidebar();
get_footer();