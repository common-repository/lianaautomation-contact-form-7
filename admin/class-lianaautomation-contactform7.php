<?php
/**
 * LianaAutomation Contact Form 7 admin panel
 *
 * PHP Version 7.4
 *
 * @package  LianaAutomation
 * @license  https://www.gnu.org/licenses/gpl-3.0-standalone.html GPL-3.0-or-later
 * @link     https://www.lianatech.com
 */

/**
 * LianaAutomation / Contact Form 7 options panel class
 *
 * @package  LianaAutomation
 * @license  https://www.gnu.org/licenses/gpl-3.0-standalone.html GPL-3.0-or-later
 * @link     https://www.lianatech.com
 */
class LianaAutomation_ContactForm7 {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action(
			'admin_menu',
			array( $this, 'lianaautomation_contactform7_add_plugin_page' )
		);

		add_action(
			'admin_init',
			array( $this, 'lianaautomation_contactform7_page_init' )
		);
	}

	/**
	 * Add an admin page
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_add_plugin_page():void {
		global $admin_page_hooks;

		// Only create the top level menu if it doesn't exist.
		if ( ! isset( $admin_page_hooks['lianaautomation'] ) ) {
			add_menu_page(
				'LianaAutomation',
				'LianaAutomation',
				'manage_options',
				'lianaautomation',
				array( $this, 'lianaautomation_contactform7_create_admin_page' ),
				'dashicons-admin-settings',
				65
			);
		}
		add_submenu_page(
			'lianaautomation',
			'Contact Form 7',
			'Contact Form 7',
			'manage_options',
			'lianaautomation-contactform7',
			array( $this, 'lianaautomation_contactform7_create_admin_page' ),
		);

		/*
		 * Remove the duplicate of the top level menu item from the sub menu to make things pretty.
		 */
		remove_submenu_page( 'lianaautomation', 'lianaautomation' );
	}


	/**
	 * Construct an admin page
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_create_admin_page():void {
		$this->lianaautomation_contactform7_options = get_option( 'lianaautomation_contactform7_options' ); ?>
		<div class="wrap">
			<h2>LianaAutomation API Options for Contact Form 7 Tracking</h2>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'lianaautomation_contactform7_option_group' );
				do_settings_sections( 'lianaautomation_contactform7_admin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Init a Contact Form 7 admin page
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_page_init():void {
		register_setting(
			'lianaautomation_contactform7_option_group',
			'lianaautomation_contactform7_options',
			array( $this, 'lianaautomation_contactform7_sanitize' )
		);

		add_settings_section(
			'lianaautomation_contactform7_section',
			'',
			array( $this, 'lianaautomation_contactform7_section_info' ),
			'lianaautomation_contactform7_admin'
		);

		add_settings_field(
			'lianaautomation_contactform7_url',
			'Automation API URL',
			array( $this, 'lianaautomation_contactform7_url_callback' ),
			'lianaautomation_contactform7_admin',
			'lianaautomation_contactform7_section'
		);

		add_settings_field(
			'lianaautomation_contactform7_realm',
			'Automation Realm',
			array( $this, 'lianaautomation_contactform7_realm_callback' ),
			'lianaautomation_contactform7_admin',
			'lianaautomation_contactform7_section'
		);

		add_settings_field(
			'lianaautomation_contactform7_user',
			'Automation User',
			array( $this, 'lianaautomation_contactform7_user_callback' ),
			'lianaautomation_contactform7_admin',
			'lianaautomation_contactform7_section'
		);

		add_settings_field(
			'lianaautomation_contactform7_key',
			'Automation Secret Key',
			array( $this, 'lianaautomation_contactform7_key_callback' ),
			'lianaautomation_contactform7_admin',
			'lianaautomation_contactform7_section'
		);

		add_settings_field(
			'lianaautomation_contactform7_channel',
			'Automation Channel ID',
			array( $this, 'lianaautomation_contactform7_channel_callback' ),
			'lianaautomation_contactform7_admin',
			'lianaautomation_contactform7_section'
		);

		// Status check.
		add_settings_field(
			'lianaautomation_contactform7_status_check',
			'LianaAutomation Connection Check',
			array( $this, 'lianaautomation_contactform7_connection_check_callback' ),
			'lianaautomation_contactform7_admin',
			'lianaautomation_contactform7_section'
		);
	}

	/**
	 * Basic input sanitization function
	 *
	 * @param string $input String to be sanitized.
	 *
	 * @return null
	 */
	public function lianaautomation_contactform7_sanitize( $input ) {
		$sanitary_values = array();

		if ( isset( $input['lianaautomation_url'] ) ) {
			$sanitary_values['lianaautomation_url']
				= sanitize_text_field( $input['lianaautomation_url'] );
		}
		if ( isset( $input['lianaautomation_realm'] ) ) {
			$sanitary_values['lianaautomation_realm']
				= sanitize_text_field( $input['lianaautomation_realm'] );
		}
		if ( isset( $input['lianaautomation_user'] ) ) {
			$sanitary_values['lianaautomation_user']
				= sanitize_text_field( $input['lianaautomation_user'] );
		}
		if ( isset( $input['lianaautomation_key'] ) ) {
			$sanitary_values['lianaautomation_key']
				= sanitize_text_field( $input['lianaautomation_key'] );
		}
		if ( isset( $input['lianaautomation_channel'] ) ) {
			$sanitary_values['lianaautomation_channel']
				= sanitize_text_field( $input['lianaautomation_channel'] );
		}
		return $sanitary_values;
	}

	/**
	 * Section info
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_section_info():void {
		// Generate info text section.
		printf( '<h2>Important CCPA/GDPR privacy compliancy information</h2>' );
		printf( '<p>By entering valid API credentials below, you enable this plugin to send personal information of your site visitors to Liana Technologies Oy.</p>' );
		printf( '<p>In most cases, this plugin <b>must</b> be accompanied by a <i>consent management solution</i>.</p>' );
		printf( '<p>If unsure, do not use this plugin.</p>' );
	}

	/**
	 * Automation URL
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_url_callback():void {
		printf(
			'<input class="regular-text" type="text" '
			. 'name="lianaautomation_contactform7_options[lianaautomation_url]" '
			. 'id="lianaautomation_url" value="%s">',
			isset( $this->lianaautomation_contactform7_options['lianaautomation_url'] )
				? esc_attr( $this->lianaautomation_contactform7_options['lianaautomation_url'] )
				: ''
		);
	}

	/**
	 * Automation Realm
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_realm_callback():void {
		printf(
			'<input class="regular-text" type="text" '
			. 'name="lianaautomation_contactform7_options[lianaautomation_realm]" '
			. 'id="lianaautomation_realm" value="%s">',
			isset( $this->lianaautomation_contactform7_options['lianaautomation_realm'] )
				? esc_attr( $this->lianaautomation_contactform7_options['lianaautomation_realm'] )
				: ''
		);
	}
	/**
	 * Automation User
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_user_callback():void {
		printf(
			'<input class="regular-text" type="text" '
			. 'name="lianaautomation_contactform7_options[lianaautomation_user]" '
			. 'id="lianaautomation_user" value="%s">',
			isset( $this->lianaautomation_contactform7_options['lianaautomation_user'] )
				? esc_attr( $this->lianaautomation_contactform7_options['lianaautomation_user'] )
				: ''
		);
	}

	/**
	 * Automation Key
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_key_callback():void {
		printf(
			'<input class="regular-text" type="text" '
			. 'name="lianaautomation_contactform7_options[lianaautomation_key]" '
			. 'id="lianaautomation_key" value="%s">',
			isset( $this->lianaautomation_contactform7_options['lianaautomation_key'] )
				? esc_attr( $this->lianaautomation_contactform7_options['lianaautomation_key'] )
				: ''
		);
	}

	/**
	 * Automation Channel
	 *
	 * @return void
	 */
	public function lianaautomation_contactform7_channel_callback():void {
		printf(
			'<input class="regular-text" type="text" '
			. 'name="lianaautomation_contactform7_options[lianaautomation_channel]" '
			. 'id="lianaautomation_channel" value="%s">',
			isset( $this->lianaautomation_contactform7_options['lianaautomation_channel'] )
				? esc_attr( $this->lianaautomation_contactform7_options['lianaautomation_channel'] )
				: ''
		);
	}

	/**
	 * LianaAutomation API Status check
	 *
	 * @return string
	 */
	public function lianaautomation_contactform7_connection_check_callback() {

		$return = '💥Fail';

		if ( empty( $this->lianaautomation_contactform7_options['lianaautomation_user'] ) ) {
			echo wp_kses_post( $return );
			return null;
		}
		$user = $this->lianaautomation_contactform7_options['lianaautomation_user'];

		if ( empty( $this->lianaautomation_contactform7_options['lianaautomation_key'] ) ) {
			echo wp_kses_post( $return );
			return null;
		}
		$secret = $this->lianaautomation_contactform7_options['lianaautomation_key'];

		if ( empty( $this->lianaautomation_contactform7_options['lianaautomation_realm'] ) ) {
			echo wp_kses_post( $return );
			return null;
		}
		$realm = $this->lianaautomation_contactform7_options['lianaautomation_realm'];

		if ( empty( $this->lianaautomation_contactform7_options['lianaautomation_url'] ) ) {
			echo wp_kses_post( $return );
			return null;
		}
		$url = $this->lianaautomation_contactform7_options['lianaautomation_url'];

		if ( empty( $this->lianaautomation_contactform7_options['lianaautomation_channel'] ) ) {
			echo wp_kses_post( $return );
			return null;
		}
		$channel = $this->lianaautomation_contactform7_options['lianaautomation_channel'];

		/**
		* General variables
		*/
		$base_path    = 'rest';             // Base path of the api end points.
		$content_type = 'application/json'; // Content will be send as json.
		$method       = 'POST';             // Method is always POST!

		// Import Data!
		$path = 'v1/pingpong';
		$data = array(
			'ping' => 'pong',
		);

		// Encode our body content data.
		$data = wp_json_encode( $data );
		// Get the current datetime in ISO 8601.
		$date = gmdate( 'c' );
		// md5 hash our body content.
		$content_md5 = md5( $data );
		// Create our signature!
		$signature_content = implode(
			"\n",
			array(
				$method,
				$content_md5,
				$content_type,
				$date,
				$data,
				"/{$base_path}/{$path}",
			),
		);

		$signature = hash_hmac( 'sha256', $signature_content, $secret );

		// Create the authorization header value.
		$auth = "{$realm} {$user}:" . $signature;

		// Create our full stream context with all required headers.
		$ctx = stream_context_create(
			array(
				'http' => array(
					'method'  => $method,
					'header'  => implode(
						"\r\n",
						array(
							"Authorization: {$auth}",
							"Date: {$date}",
							"Content-md5: {$content_md5}",
							"Content-Type: {$content_type}",
						)
					),
					'content' => $data,
				),
			)
		);

		// Build full path, open a data stream, and decode the json response.
		$full_path = "{$url}/{$base_path}/{$path}";

		$fp = fopen( $full_path, 'rb', false, $ctx );

		if ( ! $fp ) {
			// API failed to connect!
			echo wp_kses_post( $return );
			return null;
		}

		$response = stream_get_contents( $fp );
		$response = json_decode( $response, true );

		if ( ! empty( $response ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
				// phpcs:disable WordPress.PHP.DevelopmentFunctions
				error_log( print_r( $response, true ) );
				// phpcs:enable
			}
			if ( ! empty( $response['pong'] ) ) {
				$return = '💚 OK';
			}
		}

		echo wp_kses_post( $return );
	}


}
if ( is_admin() ) {
	$lianaautomation_contactform7 = new LianaAutomation_ContactForm7();
}

