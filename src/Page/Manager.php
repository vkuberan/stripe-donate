<?php

namespace VeeKay\StripeDonate\Page;

/**
 * Class Manager
 *
 * @package VeeKay\StripeDonate\Page
 */
class Manager {

	/**
	 * @var PageInterface[]
	 */
	private $pages = [];

	/**
	 * Add a whole new menu for this plugin.
	 */
	public function add_new_menu() {
		add_menu_page(
			'Stripe Donate', 
			'Stripe Donate', 
			'manage_options', 
			'stripe-donate', 
			[$this, 'display_page'],
			'dashicons-admin-page',
			10
		);
	}

	public function display_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		require_once dirname(__DIR__) . '/templates/credits.php';

	}
	/**
	 * Add page.
	 *
	 * @param PageInterface $page
	 */
	public function add_page( PageInterface $page ) {

		$this->pages[ $page->get_slug() ] = $page;
	}

	/**
	 * Handling the POST-Request and save the data.
	 */
	public function save() {

		if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
			return;
		}

		$page = filter_input( INPUT_POST, 'action' );
		if ( '' === $page ) {
			return;
		}

		if ( ! isset( $this->pages[ $page ] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'stripe-donate-settings', 'stripe-donate' ) ) {
			return;
		}

		/** @var PageInterface */
		$this->pages[ $page ]->save();
	}

	/**
	 * Register all Pages.
	 *
	 * @wp-hook admin_menu
	 */
	public function register_pages() {

		$this->add_new_menu();

		foreach ( $this->pages as $slug => $page ) {

			/**
			 * @param string        $cap
			 * @param PageInterface $page
			 */
			$cap = apply_filters( 'insr-capability', 'manage_options', $page );

			add_submenu_page(
				'stripe-donate',
				$page->get_page_title(),
				$page->get_menu_title(),
				$cap,
				$slug,
				[ $this, 'render' ]
			);
		}

		global $submenu;
		$submenu['stripe-donate'][0][0] = 'Home';
	}

	/**
	 * Render all pages and handling save.
	 */
	public function render() {

		$current_page = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : key( $this->pages );

		$output = '<div class="wrap">';
	
		// Set the current page.
		$page = $this->pages[ $current_page ];

		echo '<h1 id="title">' . esc_html__( $page->get_page_title(), 'stripe-donate' ) . '</h1>';
		$this->save();
		$page->display_errors();
		$page->render();
		echo '</div>'; 

	}

	/**
	 * Registers the Plugin stylesheet.
	 *
	 * @wp-hook admin_enqueue_scripts
	 */
	public function register_css() {

		if ( ! $this->is_search_and_replace_admin_page() ) {
			return;
		}

		$suffix = $this->get_script_suffix();
		$url    = ( SEARCH_REPLACE_BASEDIR . '/assets/css/inpsyde-search-replace' . $suffix . '.css' );
		$handle = 'insr-styles';

		wp_register_script( $handle, $url );
		wp_enqueue_style( $handle, $url, [], false, false );
	}

	/**
	 * Registers the Plugin javascript.
	 *
	 * @wp-hook admin_enqueue_scripts
	 */
	public function register_js() {

		if ( ! $this->is_search_and_replace_admin_page() ) {
			return;
		}

		$suffix = $this->get_script_suffix();
		$url    = ( SEARCH_REPLACE_BASEDIR . '/assets/js/inpsyde-search-replace' . $suffix . '.js' );
		$handle = 'insr-js';

		wp_register_script( $handle, $url );
		wp_enqueue_script( $handle, $url, [], false, true );
	}

	/**
	 * Get script suffix to difference between live and debug files.
	 *
	 * @return string
	 */
	private function get_script_suffix() {

		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * Is admin search and replace page
	 *
	 * Check against the current screen in admin,
	 *
	 * @return bool True if current screen is one of the search and replace pages
	 */
	private function is_search_and_replace_admin_page() {

		$current = str_replace( 'tools_page_', '', get_current_screen()->id );

		return array_key_exists( $current, $this->pages );
	}
}
