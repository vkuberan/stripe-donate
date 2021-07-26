<?php

namespace VeeKay\StripeDonate\Page;


/**
 * Class StripeSettings
 *
 * @package VeeKay\StripeDonate\Page
 */
class StripeSettings extends AbstractPage implements PageInterface {

	/**
	 * SqlImport constructor.
	 *
	 * @param Database\Importer $dbi
	 */
	public function __construct() {
	}

	/**
	 * @return string
	 */
	public function get_page_title() {

		return esc_html__( 'Stripe API', 'stripe-donate' );
	}

	/**
	 * Return the static slug string.
	 *
	 * @return string
	 */
	public function get_slug() {

		return 'stripe-api';
	}

	/**
	 * Callback function for menu item
	 */
	public function render() {

		require_once dirname(__DIR__) . '/templates/stripe-api.php';
	}

	/**
	 * @return string
	 */
	protected function get_submit_button_title() {

		return esc_html__( 'Update', 'stripe-donate' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {

		// @ToDo: Better handling of large files
		// maybe like here: http://stackoverflow.com/questions/147821/loading-sql-files-from-within-php , answer by user 'gromo'
		$php_upload_error_code = $_FILES[ 'file_to_upload' ][ 'error' ];
		if ( 0 === $php_upload_error_code ) {
			// get file extension
			$ext = strrchr( $_FILES [ 'file_to_upload' ][ 'name' ], '.' );
			// parse file
			$tempfile = $_FILES [ 'file_to_upload' ][ 'tmp_name' ];
			switch ( $ext ) {
				case '.sql':
					// @codingStandardsIgnoreLine
					$sql_source = file_get_contents( $tempfile );
					break;
				case '.gz':
					$sql_source = $this->read_gzfile_into_string( $tempfile );
					break;
				default:
					$this->add_error(
						esc_html__(
							'The file has neither \'.gz\' nor \'.sql\' Extension. Import not possible.',
							'search-and-replace'
						)
					);
					return;
			}

			// call import function
			$success = $this->dbi->import_sql( $sql_source );
			if ( - 1 === $success ) {
				$this->add_error(
					esc_html__(
						'The file does not seem to be a valid SQL file. Import not possible.',
						'search-and-replace'
					)
				);
			} else {
				echo '<div class="updated notice is-dismissible">';
				echo '<p>';
				printf(
				// Translators: %s print the sql source.
					esc_html__(
						'The SQL file was successfully imported. %s SQL queries were performed.',
						'search-and-replace'
					),
					esc_html($success)
				);
				echo '</p></div>';
			}
		} else {
			// show error
			$php_upload_errors = array(
				0 => esc_html__(
					'There is no error, the file uploaded with success',
					'search-and-replace'
				),
				1 => esc_html__(
					'The uploaded file exceeds the upload_max_filesize directive in php.ini',
					'search-and-replace'
				),
				2 => esc_html__(
					'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
					'search-and-replace'
				),
				3 => esc_html__(
					'The uploaded file was only partially uploaded',
					'search-and-replace'
				),
				4 => esc_html__(
					'No file was uploaded.',
					'search-and-replace'
				),
				6 => esc_html__(
					'Missing a temporary folder.',
					'search-and-replace'
				),
				7 => esc_html__(
					'Failed to write file to disk.',
					'search-and-replace' ),
				8 => esc_html__(
					'A PHP extension stopped the file upload.',
					'search-and-replace'
				),
			);

			$this->add_error(
				sprintf(
					// Translators: %s print the error message.
					esc_html__( 'Upload Error: %s', 'search-and-replace' ),
					$php_upload_errors[ $php_upload_error_code ]
				)
			);
		}

	}

}
