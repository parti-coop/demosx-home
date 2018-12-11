<?php
/**
 * Demosx by NiceThemes.
 *
 * The default template for the homepage content.
 *
 * @package   Demosx
 * @author    Parti Coop <contact@parti.xyz>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<section class="guide-section">
  <div class="container col-full text-align-center guide-header">
    <h1 class="guide-header-title">운영가이드</h1>
    <p class="guide-header-content">
      <span class="text-nowrap">시민참여 플랫폼</span>
      <span class="text-nowrap">DemosX 운영에 관한</span>
      <span class="text-nowrap">모든 노하우를 공개합니다.</span>
    </p>
  </div>
</section>

<section id="knowledgebase" class="home-block clearfix">
  <div class="col-full">
    <?php
    // Display Knowledge Base Articles
    if ( apply_filters( 'nice_homepage_knowledgebase', true ) ) {

    	$number_articles = nice_get_option( '_articles_entries', 5 );

    	nicethemes_knowledgebase( array(
    								'columns'     => 3,
    								'numberposts' => $number_articles,
    								'before'      => '',
    								'after'       => ''
    								)
    							);
    }

    ?>

    <div id="search-wrap">
      <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
        <div class="input" style="position: relative; min-width: 280px; width: 40%; margin-left: auto; margin-right: auto;">
        <input type="text" name="s" id="s" style="width: 100%; border-radius: 8px;" placeholder="운영가이드 검색하기" />
        <input type="image" id="searchsubmit" src="<?php echo get_stylesheet_directory_uri().'/images/icon_magnifier.png' ?>" style="position: absolute; background: transparent; padding: 0 !important; min-width: auto; margin: 0; top: 12px; right: 1em; border: 0; width: 18px; height: 18px;" />
        </div>
      </form>
    </div>
  </div>
</section>
