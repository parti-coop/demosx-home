<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for this theme's footer.
 *
 * Contains footer content and the closing of the #container and #wrapper div elements.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
	<?php if ( ! is_page_template( 'template-home.php' ) ) : ?>
		<!-- END #container -->
		</div>
	<?php endif; ?>

	<section id="call-to-action" class="home-cta-block clearfix light has-cta-button">
		<div class="col-full">
			<div class="cta-wrapper">
				<div class="cta-text">
					<span class="text-nowrap">시민참여 플랫폼 데모스X는</span> <span class="text-nowrap"><strong><a href="https://democracy.seoul.go.kr/">민주주의서울</a></strong>의 기술 경험과 운영 노하우로</span>
					<br>
					<span class="text-nowrap"><strong><a href="http://www.seoul.go.kr/">서울특별시</a></strong>와 <strong><a href="https://partiunion.org">빠띠쿱</a></strong>이</span> <span class="text-nowrap">함께 만들었습니다.</span>
				</div>
				<span class="cta-button-wrapper">
					<a class="cta-button" href="https://democracy.seoul.go.kr/" title="민주주의서울 바로가기" target="_blank">민주주의서울 바로가기</a>
				</span>
		</div>
	</section>

	<footer id="footer" class="site-footer dark site-footer-demosx">
		<div id="footer-widgets" class="col-full text-align-center">
			<div class="widget-section">
				<div class="box widget widget_text" style="margin-bottom: 0;">
					<h4 class="widgettitle">
						<a href="<?php echo home_url( '/' ) ?>" title="데모스X">
							<img src="<?php echo get_stylesheet_directory_uri().'/images/logo.png' ?>" id="default-logo" height="21" alt="데모스X" title="데모스X" class="img-logo img-custom-logo" style="height: 21px;">
						</a>
					</h4>
					<div class="textwidget">
						<p>데모스X는 2018년 행정안전부 주민 체감형 디지털 사회혁신 활성화 사업의 지원으로 제작되었습니다.</p>
						<p><a href="mailto:contact@demosx.org" style="color: #8b989e">문의 contact@demosx.org</a></p>
					</div>
				</div>
			</div>
		</div><!-- /#footer-widgets -->
	</footer>

	</div><!-- END #wrapper -->

	<?php
		/**
		 * @hook nice_after_wrapper
		 *
		 * Hook here to display elements after the `#wrapper` element.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nice_after_wrapper' );
	?>

	<?php wp_footer(); ?>
</body>
</html>
