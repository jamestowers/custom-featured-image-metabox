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

		add_settings_section(
			'general',
			__( 'General', $this->plugin_slug ),
			'',
			$this->plugin_slug
		);

		add_settings_field(
			'title',
			__( 'Title Text', $this->plugin_slug ),
			array( $this, 'title_callback' ),
			$this->plugin_slug,
			'general'
		);

		add_settings_field(
			'instruction',
			__( 'Instruction', $this->plugin_slug ),
			array( $this, 'instruction_callback' ),
			$this->plugin_slug,
			'general'
		);

		add_settings_field(
			'link_text',
			__( 'Link Text', $this->plugin_slug ),
			array( $this, 'link_text_callback' ),
			$this->plugin_slug,
			'general'
		);

		add_settings_field(
			'button_text',
			__( 'Button Text', $this->plugin_slug ),
			array( $this, 'button_text_callback' ),
			$this->plugin_slug,
			'general'
		);

		register_setting(
			$this->plugin_slug,
			$this->plugin_slug
		);

	} // end admin_init

	/**
	 * Provides default values for the plugin settings.
	 *
	 * @return  array<string> Default settings
	 */
	public function default_settings() {

		$defaults = array(
			'title' => 'Post Featured Image',
			'instruction' => '',
			'link_text' => '',
			'button_text' => '',
		);

		return apply_filters( 'default_settings', $defaults );

	} // end default_settings

	public function title_callback() {

		$options = get_option( $this->plugin_slug );
		$value  = isset( $options['title'] ) ? $options['title'] : '';

		$html = '<input type"text" id="title" name="' . $this->plugin_slug . '[title]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . __( 'Enter your custom title for Featured Image Metabox.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end title_callback

	public function instruction_callback() {

		$options = get_option( $this->plugin_slug );
		$value  = isset( $options['instruction'] ) ? $options['instruction'] : '';

		$html = '<input type"text" id="instruction" name="' . $this->plugin_slug . '[instruction]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . __( 'Enter the instruction for Featured Image, like the image dimensions.', $this->plugin_slug ) . '</p>';

		echo $html;

	} // end instruction_callback

	public function link_text_callback() {

		$options = get_option( $this->plugin_slug );
		$value  = isset( $options['link_text'] ) ? $options['link_text'] : '';

		$html = '<input type"text" id="link_text" name="' . $this->plugin_slug . '[link_text]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . sprintf( __( 'Enter the custom link text to replace the "%s".', $this->plugin_slug ), __( 'Set featured image' ) ) . '</p>';

		echo $html;

	} // end link_text_callback

	public function button_text_callback() {

		$options = get_option( $this->plugin_slug );
		$value  = isset( $options['button_text'] ) ? $options['button_text'] : '';

		$html = '<input type"text" id="button_text" name="' . $this->plugin_slug . '[button_text]" value="' . $value . '" class="regular-text" />';
		$html .= '<p class="description">' . sprintf( __( 'Enter the custom button text to replace the "%s".', $this->plugin_slug ), __( 'Set featured image' ) ) . '</p>';

		echo $html;

	} // end button_text_callback
}

Custom_Featured_Image_Metabox_Settings::get_instance();
?>