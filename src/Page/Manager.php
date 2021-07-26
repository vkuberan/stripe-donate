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
   * reference all the pages added to manager
   * 
	 */
	private $pages = [];

	/**
	 * Add a new menu for this plugin.
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

}
