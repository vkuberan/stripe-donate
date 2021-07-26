<?php

namespace VeeKay\StripeDonate\Page;

/**
 * Class AbstractPage
 *
 * @package VeeKay\StripeDonate\Page
 */
abstract class AbstractPage {

	/**
	 * Returns the translated title for the page.
	 *
	 * @return string
	 */
	abstract public function get_page_title();

	/**
	 * By default "Search & Replace". Can be overwritten in child-classes.
	 *
	 * @return string
	 */
	public function get_menu_title() {

		return esc_html__( 'Stripe Donate', 'stripe-donate' );
	}

	/**
	 * @var array
	 */
	protected $errors = array();

	/**
	 * @param string $msg
	 */
	public function add_error( $msg ) {

		$this->errors[] = (string) $msg;
	}

	/**
	 * Echoes the content of the $errors array as formatted HTML if it contains error messages.
	 */
	public function display_errors() {

		if ( count( $this->errors ) < 1 ) {
			return;
		}

		?>
		<div class="error notice is-dismissible">
			<p>
				<strong>
					<?php esc_html_e( 'Errors:', 'stripe-donate' ); ?>
				</strong>
			</p>
			<ul>
				<?php foreach ( $this->errors as $error ) : ?>
					<li><?= esc_html( $error ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php
	}

	/**
	 * Displays the html for the submit button.
	 *
	 * @param string $name
	 */
	public function show_submit_button( $name = 'submit' ) {

		printf(
			'<input type="hidden" name="action" value="%s" />',
			esc_attr( $this->get_slug() )
		);
		submit_button( $this->get_submit_button_title(), 'primary', $name );
		wp_nonce_field( 'stripe-donate-settings', 'stripe-donate' );
	}

	/**
	 * @return string
	 */
	protected function get_submit_button_title() {

		return esc_html__( 'Submit', 'stripe-donate' );
	}

	/**
	 * @return string
	 */
	public function get_slug() {

		return sanitize_title_with_dashes( $this->get_page_title() );
	}
}
