<?php

class Custom_Featured_Image_Metabox_Settings {

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $plugin_slug from public plugin class later.
	 *
	 * @since    0.5.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = null;

	/**
	 * Instance of this class.
	 *
	 * @since    0.5.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.5.0
	 */
	private function __construct() {

		$plugin = Custom_Featured_Image_Metabox::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		add_action( 'admin_init', array( $this, 'admin_init' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.5.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function admin_init() {

		if ( false == get_option( $this->plugin_slug ) ) {
			add_option( $this->plugin_slug, $this->default_settings() );
		} // end if

		$post_types = $this->supported_post_types();
		$options = get_option( $this->plugin_slug );

		foreach ( $post_types as $pt ) {
			$post_object = get_post_type_object( $pt );
			$args = array( $pt, $options[$pt] );

			add_settings_section(
				$pt,
				sprintf( __( 'Featured Image Metabox in %s', $this->plugin_slug ), $post_object->labels->name ),
				'',
				$this->plugin_slug
			);

			add_settings_field(
				'title',
				__( 'Title Text', $this->plugin_slug ),
				array( $this, 'title_callback' ),
				$this->plugin_slug,
				$pt,
				$args
			);

			add_settings_field(
				'instruction',
				__( 'Instruction', $this->plugin_slug ),
				array( $this, 'instruction_callback' ),
				$this->plugin_slug,
				$pt,
				$args
			);

			add_settings_field(
				'link_text',
				__( 'Link Text', $this->plugin_slug ),
				array( $this, 'link_text_callback' ),
				$this->plugin_slug,
				$pt,
				$args
			);

			add_settings_field(
				'button_text',
				__( 'Button Text', $this->plugin_slug ),
				array( $this, 'button_text_callback' ),
				$this->plugin_slug,
				$pt,
				$args
			);
		}

		register_setting(
			$this->plugin_slug,
			$this->plugin_slug,
			array( $this, 'validate_inputs' )
		);

	} // end admin_init

	/**
	 * Provides default values for the plugin settings.
	 *
	 * @return  array<string> Default settings
	 */
	public function default_settings() {

		$post_types = $this->supported_post_types();
		$keys = array(
				'title' => '',
				'instruction' => '',
				'link_text' => '',
				'button_text' => '',
			);
		$defaults = array();

		foreach ( $post_types as $pt ) {
			$defaults[$pt] = $keys;
		}

		return apply_filters( 'cfim_default_settings', $defaults );

	} // end default_settings

	/**
	 * Get post types with thumbnail support
	 *
	 * @return array supported post types
	 *
	 * @since 0.6.0
	 */
	public function supported_post_types() {

		$post_types = get_post_types();
		$results = array();

		foreach ( $post_types as $pt ) {
			if ( post_type_supports( $pt, 'thumbnail' ) ) {
				$results[] = $pt;
			}
		}

		return $results;

	} // end supported_post_types

	public function title_callback( $args ) {

		$value  = isset( $args[1]['title'] ) ? $args[1]['title'] : '';

		$html = '<input type"text" id="title" name="' . $this->plugin_slug . '[' . $args[0] . '][title]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . __( 'Enter your custom title for Featured Image Metabox.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end title_callback

	public function instruction_callback( $args ) {

		$value  = isset( $args[1]['instruction'] ) ? $args[1]['instruction'] : '';

		$html = '<input type"text" id="instruction" name="' . $this->plugin_slug . '[' . $args[0] . '][instruction]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . __( 'Enter the instruction for Featured Image, like image dimensions.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end instruction_callback

	public function link_text_callback( $args ) {

		$value  = isset( $args[1]['link_text'] ) ? $args[1]['link_text'] : '';

		$html = '<input type"text" id="link_text" name="' . $this->plugin_slug . '[' . $args[0] . '][link_text]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . sprintf( __( 'Enter the custom link text to replace the "%s".', $this->plugin_slug ), __( 'Set featured image' ) ) . '</p>';

		echo $html;

	} // end link_text_callback

	public function button_text_callback( $args ) {

		$value  = isset( $args[1]['button_text'] ) ? $args[1]['button_text'] : '';

		$html = '<input type"text" id="button_text" name="' . $this->plugin_slug . '[' . $args[0] . '][button_text]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . sprintf( __( 'Enter the custom button text to replace the "%s".', $this->plugin_slug ), __( 'Set featured image' ) ) . '</p>';

		echo $html;

	} // end button_text_callback

	/**
	 * Validate inputs
	 *
	 * @return array Sanitized data
	 *
	 * @since 0.7.0
	 */
	public function validate_inputs( $inputs ) {

		$outputs = array();

		foreach( $inputs as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $k => $v ) {
					$outputs[$key][$k] = sanitize_text_field( $v );
				}
			} else {
				$outputs[$key] = sanitize_text_field( $value );
			}

		}

		return apply_filters( 'cfim_validate_inputs', $outputs, $inputs );

	} // end validate_inputs
}

Custom_Featured_Image_Metabox_Settings::get_instance();
?>