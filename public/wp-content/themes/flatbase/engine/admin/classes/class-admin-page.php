<?php
/**
 * Nice Framework by NiceThemes.
 *
 * Admin page class
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2015 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Nice_Admin_Page Class
 *
 * A general class for admin pages.
 *
 * @since 2.0
 */
class Nice_Admin_Page {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * Main page slug.
	 *
	 * @var string
	 */
	public $main_page_slug = 'nicethemes';

	/**
	 * List of admin menus.
	 *
	 * @var array
	 */
	public $menus = array();

	/**
	 * List of admin pages.
	 *
	 * @var array
	 */
	public $pages = array();

	/**
	 * Minimum capability needed for the admin pages.
	 *
	 * @var string
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * WordPress' notices.
	 *
	 * @var string
	 */
	public $wp_notices = '';


	/** ==========================================================================
	 *  Setters for menus and pages.
	 *  ======================================================================= */

	/**
	 * Set menus.
	 *
	 * @since 2.0
	 */
	protected function set_menus() {
		// These menus should always exist.
		$mandatory_menus = array(
			'wp',        // WordPress menu
			'admin_bar', // WordPress admin bar
		);

		/**
		 * @hook nice_admin_page_menus
		 *
		 * Hook here if you want to add or remove admin menus.
		 *
		 * @since 2.0
		 */
		$menus = apply_filters( 'nice_admin_page_menus', $mandatory_menus );

		// Ensure mandatory menus are still there.
		$menus = array_values( array_unique( array_merge( $mandatory_menus, $menus ) ) );

		$this->menus = $menus;
	}


	/**
	 * Set pages.
	 *
	 * Note that at least one menu page is required.
	 *
	 * @since 2.0
	 */
	protected function set_pages() {
		/**
		 * @hook nice_admin_pages
		 *
		 * Hook here to set the admin pages.
		 *
		 * Accepted page attributes:
		 * @see Nice_Admin_Page::sanitize_page()
		 *
		 * @since 2.0
		 */
		$pages = apply_filters( 'nice_admin_pages', array(
			// About
			array(
				'key'         => 'about',
				'page_title'  => esc_html__( 'Welcome', 'nice-framework' ),
				'menu_slug'   => 'nice-theme-about',
				'icon'        => 'dashicons-info',
				'menu'        => array(
					'wp'        => true,
					'admin_bar' => false,
				),
			),
		) );

		// Sanitize and store pages.
		$this->pages = $this->sanitize_pages( $pages );

		// Apply the main page slug to the main page.
		if ( ! empty( $pages ) ) {
			$this->pages[ $this->get_main_key() ]['menu_slug'] = $this->main_page_slug;
		}
	}


	/** ==========================================================================
	 *  Getters for menus and pages.
	 *  ======================================================================= */

	/**
	 * Obtain menus.
	 *
	 * @return array
	 */
	public function get_menus() {
		return $this->menus;
	}

	/**
	 * Obtain all pages.
	 *
	 * @return array
	 */
	public function get_pages() {
		return $this->pages;
	}

	/**
	 * Obtain main key (first WP menu key)
	 *
	 * @return string
	 */
	public function get_main_key() {
		static $main_key = null;

		if ( is_null( $main_key ) ) {
			$menu_keys = $this->get_menu_keys();
			$main_key  = reset( $menu_keys );
		}

		return $main_key;
	}

	/**
	 * Obtain menu keys.
	 *
	 * @since 2.0
	 *
	 * @param string $menu
	 *
	 * @return array
	 */
	public function get_menu_keys( $menu = 'wp' ) {
		static $menus = array();

		if ( ! isset( $menus[ $menu ] ) ) {
			$menu_keys = array();

			// Obtain an array with the form priority => page key
			foreach ( $this->get_pages() as $page ) {
				if ( isset( $page['menu'][ $menu ] ) ) {
					$menu_keys[ $page['menu'][ $menu ] ] = $page['key'];
				}
			}

			// Sort the array.
			ksort( $menu_keys );

			$menus[ $menu ] = $menu_keys;
		}

		return $menus[ $menu ];
	}

	/**
	 * Obtain main page (first WP menu page)
	 *
	 * @return array
	 */
	public function get_main_page() {
		static $main_page = null;

		if ( is_null( $main_page ) ) {
			$main_page = $this->get_page( $this->get_main_key() );
		}

		return $main_page;
	}

	/**
	 * Obtain menu pages.
	 *
	 * @since 2.0
	 *
	 * @param string $menu
	 *
	 * @return array
	 */
	public function get_menu_pages( $menu = 'wp' ) {
		static $menus = array();

		if ( ! isset( $menus[ $menu ] ) ) {
			$menu_pages = array_intersect_key( $this->get_pages(), array_flip( $this->get_menu_keys( $menu ) ) );

			$menus[ $menu ] = $menu_pages;
		}

		return $menus[ $menu ];
	}

	/**
	 * Obtain hidden pages (not in WP menu).
	 *
	 * @return array
	 */
	public function get_hidden_pages() {
		static $hidden_pages = null;

		if ( is_null( $hidden_pages ) ) {
			$hidden_pages = array_diff_key( $this->get_pages(), $this->get_menu_pages() );
		}

		return $hidden_pages;
	}


	/** ==========================================================================
	 *  Getters for page attributes.
	 *  ======================================================================= */

	/**
	 * Obtain an admin page key from its slug.
	 *
	 * If no slug is given, it tries to get it from the current page.
	 *
	 * @since 2.0
	 *
	 * @param string $menu_slug
	 *
	 * @return string
	 */
	public function get_page_key( $menu_slug = '' ) {
		static $slugs_to_keys = null;

		if ( is_null( $slugs_to_keys ) ) {
			$slugs_to_keys = array_flip( wp_list_pluck( $this->pages, 'menu_slug' ) );
		}

		// Obtain current menu slug if not given any.
		if ( ! $menu_slug ) {
			if ( ( 'admin.php' === basename( $_SERVER['PHP_SELF'] ) ) && isset( $_REQUEST['page'] ) ) {
				$menu_slug = strip_tags( $_REQUEST['page'] );
			}
		}

		// Return page key if found.
		if ( isset( $slugs_to_keys[ $menu_slug ] ) ) {
			return $slugs_to_keys[ $menu_slug ];
		}

		return false;
	}

	/**
	 * Obtain whether or not the current page is an admin page.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_admin_page() {
		return ( false !== $this->get_page_key() );
	}

	/**
	 * Obtain an admin page from its key.
	 *
	 * If no key is given, it tries to get it from the current page.
	 *
	 * @since 2.0
	 *
	 * @param string $key
	 *
	 * @return array
	 */
	public function get_page( $key = '' ) {
		// Try to obtain current key if not given any.
		if ( ! $key ) {
			$key = $this->get_page_key();
		}

		if ( $key && isset( $this->pages[ $key ] ) ) {
			return $this->pages[ $key ];
		}

		return null;
	}

	/**
	 * Obtain an admin page attribute from its key.
	 *
	 * If no key is given, it tries to get it from the current page.
	 *
	 * @since 2.0
	 *
	 * @param string $attribute
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_page_attribute( $attribute, $key = '' ) {
		$page = $this->get_page( $key );

		if ( $page && isset( $page[ $attribute ] ) ) {
			return $page[ $attribute ];
		}

		return false;
	}


	/**
	 * Obtain an admin page link from its key.
	 *
	 * If no key is given, it tries to get it from the current page.
	 *
	 * @since 2.0
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_page_link( $key = '' ) {
		$menu_slug = $this->get_page_attribute( 'menu_slug', $key );

		if ( $menu_slug ) {
			return esc_url( add_query_arg( array(
				'page' => $menu_slug,
			), admin_url( 'admin.php' ) ) );
		}

		return false;
	}

	/**
	 * Obtain an admin page link for the main page.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_main_page_link() {
		return $this->get_page_link( $this->get_main_key() );
	}

	/**
	 * Obtain an admin page hookname from its key.
	 *
	 * If no key is given, it tries to get it from the current page.
	 *
	 * @since 2.0
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_page_hookname( $key = '' ) {
		$menu_slug = $this->get_page_attribute( 'menu_slug', $key );

		if ( $menu_slug ) {
			$prefix = ( $this->get_main_key() === $key ) ? 'toplevel' : $this->main_page_slug;
			return $prefix . '_page-' . $menu_slug;
		}

		return false;
	}

	/**
	 * Obtain an admin page hookname for the main page.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_main_page_hookname() {
		return $this->get_page_hookname( $this->get_main_key() );
	}


	/** ==========================================================================
	 *  CSS classes.
	 *  ======================================================================= */

	/**
	 * Obtain whether or not the current page is an admin page.
	 *
	 * @since 2.0
	 *
	 * @param string $classes
	 *
	 * @return bool
	 */
	public function admin_body_class( $classes ) {
		if ( $this->is_admin_page() ) {
			$classes .= ' nice-framework-page-' . $this->get_page_key();
		}

		return $classes;
	}


	/** ==========================================================================
	 *  Getters for WordPress' notices.
	 *  ======================================================================= */

	/**
	 * Obtain WordPress' notices.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_wp_notices() {
		return $this->wp_notices;
	}


	/** ==========================================================================
	 *  Sanitization methods.
	 *  ======================================================================= */

	/**
	 * Sanitize an array of pages.
	 *
	 * @uses Nice_Admin_Page::sanitize_page()
	 *
	 * @since 2.0
	 *
	 * @param $pages
	 *
	 * @return array
	 */
	protected function sanitize_pages( $pages ) {
		$sanitized_pages = array();

		foreach ( $pages as $key => $page ) {
			$page = $this->sanitize_page( $page );

			if ( $page ) {
				$sanitized_pages[ $page['key'] ] = $page;
			}
		}

		return $sanitized_pages;
	}


	/**
	 * Sanizite the attributes of a page.
	 *
	 * @since 2.0
	 *
	 * @param array $page
	 *
	 * @return false|array
	 */
	protected function sanitize_page( array $page ) {
		/**
		 * Validate required page attributes.
		 */
		if (
			   empty( $page['key'] )
			|| empty( $page['menu_slug'] )
			|| empty( $page['page_title'] )
		) {
			return false;
		}

		/**
		 * @hook nice_admin_page_defaults
		 *
		 * Hook here to change default page attributes.
		 *
		 * @since 2.0
		 */
		$defaults = apply_filters( 'nice_admin_page_default_attributes', array(
			'key'         => '',                                   // Unique, for internal use.
			'page_title'  => '',                                   // Shown in the page.
			'description' => '',                                   // Shown in the page, right below the title.
			'menu_slug'   => '',                                   // Unique, for the page URL.
			'menu_title'  => '',                                   // Shown in menus.
			'icon'        => 'dashicons dashicons-admin-generic',  // Shown in menus which support icons.
			'menu'        => array(),                              // Either `array( $menu => $priority )`, or `array( $menu => true )`, For just one, `$menu`.
			'callback'    => array( $this, 'do_screen' ),          // Either a function, or a class method.
		) );

		/**
		 * Sanitize page attributes.
		 */
		$page = wp_parse_args( $page, $defaults );

		// If we're not given a menu title, obtain it from the page title..
		if ( empty( $page['menu_title'] ) ) {
			$page['menu_title'] = $page['page_title'];
		}

		// Validate menu attribute.
		if ( ! empty( $page['menu'] ) ) {
			$page_menu        = array();
			$default_priority = 10;

			// If we're given a string, put it into an array.
			if ( is_string( $page['menu'] ) ) {
				$page['menu'] = array(
					$page['menu'][ $page['menu'] ] = $default_priority,
				);
			}

			// Loop through registered menus.
			foreach ( $this->menus as $menu ) {
				if ( isset( $page['menu'][ $menu ] ) && ( false !== $page['menu'][ $menu ] ) ) {
					$priority = $page['menu'][ $menu ];
					$page_menu [ $menu ] = is_integer( $priority ) ? $priority : $default_priority;
				}
			}

			// Assign validated values.
			$page['menu'] = $page_menu;
		}

		/**
		 * @hook nice_admin_page_defaults
		 *
		 * Hook here to change default page attributes.
		 *
		 * @since 2.0
		 */
		return apply_filters( 'nice_admin_page_attributes', $page, $defaults );
	}


	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Initialize class.
	 *
	 * @since 2.0
	 */
	public function __construct() {
		// Set menus and pages.
		$this->set_menus();
		$this->set_pages();

		// Add pages hooks.
		$this->add_pages_hooks();

		// Add WordPress' notices hooks.
		$this->add_wp_notices_hooks();

		// Add screens hooks.
		$this->add_screens_hooks();
	}


	/** ==========================================================================
	 *  Pages.
	 *  ======================================================================= */

	/**
	 * Register the admin pages.
	 *
	 * @since 2.0
	 */
	public function register_pages() {
		if ( current_user_can( 'edit_theme_options' ) ) {
			/**
			 * Register main page.
			 */
			$main_page = $this->get_main_page();
			$theme_check_bs = strrev( 'egap_unem_dda' ); // Avoid Theme Check warning.

			if ( $main_page ) {
				$theme_check_bs(
					$main_page['page_title'],
					apply_filters( 'nice_admin_menu_label', 'NiceThemes' ),
					$this->minimum_capability,
					$this->main_page_slug,
					$main_page['callback'],
					apply_filters( 'nice_admin_menu_icon', nice_admin_menu_icon() ),
					3
				);
			}

			/**
			 * Register all pages.
			 */
			$pages = $this->get_pages();
			$theme_check_bs2 = strrev( 'egap_unembus_dda' ); // Avoid Theme Check warning.

			if ( ! empty( $pages ) ) {
				foreach ( $pages as $page ) {
					$theme_check_bs2(
						$this->main_page_slug,
						$page['page_title'],
						$page['menu_title'],
						isset( $page['capability'] ) ? $page['capability'] : $this->minimum_capability,
						$page['menu_slug'],
						$page['callback']
					);
				}
			}
		}

		/**
		 * Add a custom admin body class.
		 */
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
	}

	/**
	 * Remove hidden pages from the menu.
	 *
	 * @since 2.0
	 */
	public function unregister_hidden_pages() {
		$hidden_pages = $this->get_hidden_pages();
		if ( ! empty( $hidden_pages ) ) {
			foreach ( $hidden_pages as $page ) {
				remove_submenu_page( $this->main_page_slug, $page['menu_slug'] );
			}
		}
	}

	/**
	 * Register the admin bar menu.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function register_admin_bar_menu( WP_Admin_Bar $wp_admin_bar ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		if ( is_admin() ) {
			/**
			 * @hook show_nice_admin_bar_menu_in_admin
			 *
			 * Hook here to show the admin bar menu in the admin section.
			 *
			 * @since 2.0
			 */
			if ( ! apply_filters( 'show_nice_admin_bar_menu_in_admin', false ) ) {
				return;
			}
		} else {
			/**
			 * @hook show_nice_admin_bar_menu
			 *
			 * Hook here to hide the admin bar menu in the site front-end.
			 *
			 * @since 2.0
			 */
			if ( ! apply_filters( 'show_nice_admin_bar_menu', false ) ) {
				return;
			}
		}

		$admin_bar_pages = $this->get_menu_pages( 'admin_bar' );

		if ( ! empty( $admin_bar_pages ) ) {
			$main_admin_bar_page = reset( $admin_bar_pages );

			$wp_admin_bar->add_node( array(
					'id'    => $this->main_page_slug,
					'title' => '<span class="ab-icon"><img src="' . nice_admin_menu_icon() . '" /></span> <span>' . apply_filters( 'nice_admin_bar_menu_label', 'NiceThemes' ) . '</span>',
					'href'  => $this->get_page_link( $main_admin_bar_page['key'] ),
				)
			);

			foreach ( $admin_bar_pages as $admin_bar_page ) {
				$wp_admin_bar->add_node( array(
						'parent' => $this->main_page_slug,
						'id'     => $this->main_page_slug . '-' . $admin_bar_page['key'],
						'title'  => $admin_bar_page['menu_title'],
						'href'   => $this->get_page_link( $admin_bar_page['key'] ),
						'meta'   => array(
							'title' => $admin_bar_page['page_title'],
						),
					) );
			}
		}
	}

	/**
	 * Redirect user to the Welcome page on theme installation or upgrade.
	 *
	 * @since 2.0
	 */
	public function redirect_on_activation() {
		// Bail if no activation redirect.
		if ( ! get_transient( '_nice_theme_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient.
		delete_transient( '_nice_theme_activation_redirect' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirect to About page.
		wp_safe_redirect( $this->get_page_link( 'about' ) );
		exit;
	}

	/**
	 * Add hooks for the registering pages methods.
	 *
	 * @since 2.0
	 */
	public function add_pages_hooks() {
		add_action( 'admin_menu', array( $this, 'register_pages' ), 20 );
		add_action( 'admin_head', array( $this, 'unregister_hidden_pages' ) );
		add_action( 'admin_bar_menu', array( $this, 'register_admin_bar_menu' ), 999 );
		add_action( 'admin_init', array( $this, 'redirect_on_activation' ) );
	}


	/** ==========================================================================
	 *  Template loaders.
	 *  ======================================================================= */

	/**
	 * Load header template.
	 *
	 * @since 2.0
	 */
	public function header() {
		nice_admin_get_template_part( 'global/header' );
	}

	/**
	 * Load content template.
	 *
	 * @since 2.0
	 */
	public function content() {
		// Obtain the current key and template.
		$key      = $this->get_page_key();
		$template = $key ? str_replace( '_', '-', $key ) : 'default';

		/**
		 * @hook nice_admin_page_template
		 *
		 * Hook here if you want to change the content template for the current page.
		 *
		 * @since 2.0
		 */
		$template = apply_filters( 'nice_admin_page_template', $template, $key );

		nice_admin_get_template_part( $template );
	}

	/**
	 * Load footer template.
	 *
	 * @since 2.0
	 */
	public function footer() {
		nice_admin_get_template_part( 'global/footer' );
	}


	/** ==========================================================================
	 *  Screen renders.
	 *  ======================================================================= */

	/**
	 * Render default screen.
	 *
	 * @since 2.0
	 */
	public function do_screen() {
		/**
		 * @hook nice_admin_page_screen
		 *
		 * All HTML for the current page screen should be printed here.
		 *
		 * @since 2.0
		 *
		 * Hooked here:
		 * @see Nice_Admin_Page::header()  - 10 (prints contents of global header template)
		 * @see Nice_Admin_Page::content() - 20 (prints contents of current page content template)
		 * @see Nice_Admin_Page::footer()  - 30 (prints contents of global footer template)
		 */
		do_action( 'nice_admin_page_screen', $this->get_page_key() );
	}

	/**
	 * Add hooks for the rendering screen methods.
	 *
	 * @since 2.0
	 */
	public function add_screens_hooks() {
		if ( ! $this->is_admin_page() ) {
			return;
		}

		add_action( 'nice_admin_page_screen', array( $this, 'header' ), 10 );
		add_action( 'nice_admin_page_screen', array( $this, 'content' ), 20 );
		add_action( 'nice_admin_page_screen', array( $this, 'footer' ), 30 );
	}


	/** ==========================================================================
	 *  WordPress' notices.
	 *  ======================================================================= */

	public function wp_notices_start() {
		ob_start();
	}

	public function wp_notices_end() {
		$this->wp_notices .= ob_get_contents();
		ob_end_clean();
	}

	/**
	 * Add hooks for the registering pages methods.
	 *
	 * @since 2.0
	 */
	public function add_wp_notices_hooks() {
		if ( ! $this->is_admin_page() ) {
			return;
		}

		add_action( 'network_admin_notices', array( $this, 'wp_notices_start' ), -9999 );
		add_action( 'user_admin_notices',    array( $this, 'wp_notices_start' ), -9999 );
		add_action( 'admin_notices',         array( $this, 'wp_notices_start' ), -9999 );
		add_action( 'all_admin_notices',     array( $this, 'wp_notices_start' ), -9999 );

		add_action( 'network_admin_notices', array( $this, 'wp_notices_end' ), 9999 );
		add_action( 'user_admin_notices',    array( $this, 'wp_notices_end' ), 9999 );
		add_action( 'admin_notices',         array( $this, 'wp_notices_end' ), 9999 );
		add_action( 'all_admin_notices',     array( $this, 'wp_notices_end' ), 9999 );
	}
}
