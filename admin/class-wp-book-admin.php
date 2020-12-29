<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.author.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Author <author@authormail.com>
 */
class Wp_Book_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'wp_book_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wp_book_settings_init') );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-book-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-book-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Book Settings menu
	 *
	 * @since    1.0.0
	 */
	public function wp_book_admin_menu() {
		add_options_page( 'Book Settings', 'Book Settings', 'manage_options', 'book-settings', array( $this, 'wp_book_settings_render' ) );
	}

	/**
	 * Render Settings menu
	 *
	 * @since    1.0.0
	 */
	public function wp_book_settings_render() {
		?>
			<form action="options.php" method="post">
				<h1>Book Settings Page</h1>
				<?php
				settings_fields( 'bookSettings' );
				do_settings_sections( 'bookSettings' );
				submit_button();
				?>
			</form>
		<?php
	}

	/**
	 * Initialize settings
	 *
	 * @since    1.0.0
	 */
	public function wp_book_settings_init() {
		register_setting( 'bookSettings', 'book_settings' );
		add_settings_section( 'book_settings_section', 'Book Settings Section', array( $this, 'book_settings_section_render' ), 'bookSettings' );
		add_settings_field( 'book_settings_field_zero', 'Changing currency', array( $this, 'book_settings_field_zero_render' ), 'bookSettings', 'book_settings_section' );
		add_settings_field( 'book_settings_field_one', 'Number of books displayed per page', array( $this, 'book_settings_field_one_render' ), 'bookSettings', 'book_settings_section' );
	}

	/**
	 * Render Book Settings Section
	 *
	 * @since    1.0.0
	 */
	public function book_settings_section_render() {
		echo '<p>Description of settings</p>';
	}

	/**
	 * Render Book Settings field 0
	 *
	 * @since    1.0.0
	 */
	public function book_settings_field_zero_render() {
		$options = get_option( 'book_settings' );
		?>
		<input type="text" name='book_settings[book_settings_field_zero]' value="<?php echo $options['book_settings_field_zero']; ?>">
		<?php
	}

	/**
	 * Render Book Settings field 1
	 *
	 * @since    1.0.0
	 */
	public function book_settings_field_one_render() {
		$options = get_option( 'book_settings' );
		?>
		<select name="book_settings[book_settings_field_one]">
			<option value='1' <?php selected( $options['book_settings_field_one'], 1 ); ?>>$</option>
			<option value='2' <?php selected( $options['book_settings_field_one'], 2 ); ?>>Rs.</option>
		</select>
		<?php
	}

}
