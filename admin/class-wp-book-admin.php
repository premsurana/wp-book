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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
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
	 * Register book post type with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_book_post_type() {
		$labels = array(
			'name'               => _x( 'Books', 'post type general name' ),
			'singular_name'      => _x( 'Book', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'book' ),
			'add_new_item'       => __( 'Add New Book' ),
			'edit_item'          => __( 'Edit Book' ),
			'new_item'           => __( 'New Book' ),
			'all_items'          => __( 'All Book' ),
			'view_item'          => __( 'View Book' ),
			'search_items'       => __( 'Search Book' ),
			'not_found'          => __( 'No books found' ),
			'not_found_in_trash' => __( 'No books found in the Trash' ),
			'menu_name'          => 'Books',
		);
		$args   = array(
			'labels'        => $labels,
			'description'   => 'Holds our books and book specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor' ),
			'has_archive'   => true,
		);
		register_post_type( 'book', $args );
	}

	/**
	 * Integrate BookMeta with wpdb
	 *
	 * @since    1.0.0
	 */
	public function bookmeta_integrate_wpdb() {

		global $wpdb;

		$wpdb->bookmeta = $wpdb->prefix . 'bookmeta';
		$wpdb->tables[] = 'bookmeta';
	}

	/**
	 * Register Book Category Taxonomy with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_book_category_taxonomy() {
		$labels = array(
			'name'          => 'Book Categories',
			'singular_name' => 'Book Category',
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => true,
		);

		register_taxonomy( 'Book Category', array( 'book' ), $args );
	}

	/**
	 * Register Book Tag Taxonomy with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_book_tag_taxonomy() {
		$labels = array(
			'name'          => 'Book Tags',
			'singular_name' => 'Book Tag',
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => false,
		);

		register_taxonomy( 'Book Tag', array( 'book' ), $args );
	}

	/**
	 * Register Custom Book Meta Box.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_book_meta_box() {
		add_meta_box( 'book_meta_box', 'Book Meta', array( $this, 'book_meta_box_content' ), 'book' );
	}

	/**
	 * Render Custom Book Meta Box.
	 *
	 * @since    1.0.0
	 * @param    mixed $post Returns a post as an argument.
	 */
	public function book_meta_box_content( $post ) {
		$array = get_book_meta( $post->ID, $post->ID, true );
		wp_nonce_field( 'action', 'nonce' );
		?>

		<div>
			Author Name: <input type="text" name="AuthorName" value="<?php echo( isset( $array['AuthorName'] ) ? esc_attr( $array['AuthorName'] ) : '' ); ?>"><br><br>
			Price: <input type="number" name="Price" value="<?php echo( isset( $array['Price'] ) ? esc_attr( $array['Price'] ) : '' ); ?>"><br><br>
			Publisher: <input type="text" name="Publisher" value="<?php echo( isset( $array['Publisher'] ) ? esc_attr( $array['Publisher'] ) : '' ); ?>"><br><br>
			Year: <input type="text" name="Year" value="<?php echo( isset( $array['Year'] ) ? esc_attr( $array['Year'] ) : '' ); ?>"><br><br>
			Edition: <input type="text" name="Edition" value="<?php echo( isset( $array['Edition'] ) ? esc_attr( $array['Edition'] ) : '' ); ?>"><br><br>
			URL: <input type="text" name="URL" value="<?php echo( isset( $array['URL'] ) ? esc_attr( $array['URL'] ) : '' ); ?>"><br><br>
		</div>
		<?php
	}

	/**
	 * Save Custom Book Meta.
	 *
	 * @since    1.0.0
	 * @param    mixed $post_id Post object.
	 */
	public function wp_book_save_book( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		$nonce_checker = empty( $_REQUEST['nonce'] ) ? '' : sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) );
		if ( ! wp_verify_nonce( $nonce_checker, 'action' ) ) {
			return;
		}

		$author    = isset( $_POST['AuthorName'] ) ? sanitize_text_field( wp_unslash( $_POST['AuthorName'] ) ) : '';
		$price     = isset( $_POST['Price'] ) ? sanitize_text_field( wp_unslash( $_POST['Price'] ) ) : '';
		$publisher = isset( $_POST['Publisher'] ) ? sanitize_text_field( wp_unslash( $_POST['Publisher'] ) ) : '';
		$year      = isset( $_POST['Year'] ) ? sanitize_text_field( wp_unslash( $_POST['Year'] ) ) : '';
		$edition   = isset( $_POST['Edition'] ) ? sanitize_text_field( wp_unslash( $_POST['Edition'] ) ) : '';
		$url       = isset( $_POST['URL'] ) ? sanitize_text_field( wp_unslash( $_POST['URL'] ) ) : '';

		$array = array(
			'AuthorName' => $author,
			'Price'      => $price,
			'Publisher'  => $publisher,
			'Year'       => $year,
			'Edition'    => $edition,
			'URL'        => $url,
		);

		update_book_meta( $post_id, $post_id, $array );
	}

	/**
	 * Delete Book
	 *
	 * @since    1.0.0
	 */
	public function wp_book_delete_post() {
		global $post_type, $post;

		if ( 'book' !== $post_type ) {
			return;
		}
		delete_book_meta( $post->ID, $post->ID );
	}

	/**
	 * Book ShortCode Function
	 *
	 * @since    1.0.0
	 * @param    mixed $atts Attributes_shortcode.
	 */
	public function wp_book_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'         => '0',
				'authorname' => '',
				'category'   => '',
				'publisher'  => '',
				'year'       => '',
				'tag'        => '',
			),
			$atts
		);

		if ( '0' === $atts['id'] ) {
			return 'No Book Found';
		}

		$array      = get_book_meta( $atts['id'], $atts['id'], true );
		$atts['id'] = (int) $atts['id'];

		if ( ( $atts['authorname'] !== $array['AuthorName'] ) && '' !== $atts['authorname'] ) {
			return 'No Book Found';
		} elseif ( $atts['year'] !== $array['Year'] && '' !== $atts['year'] ) {
			return 'No Book Found';
		} elseif ( $atts['publisher'] !== $array['Publisher'] && '' !== $atts['publisher'] ) {
			return 'No Book Found';
		}

		echo '<pre>';

			$result = array();
			$items  = get_the_terms( $atts['id'], 'Book Category' );
		foreach ( $items as $tag ) {
			echo '<br>';
			if ( $atts['category'] !== $tag->name && '' !== $atts['category'] ) {
				return 'No Book Found';
			}
			array_push( $result, $tag->name );
		}
			echo '<br>';

			$items = get_the_terms( $atts['id'], 'Book Tag' );
		foreach ( $items as $tag ) {
			echo '<br>';
			if ( $atts['tag'] !== $tag->name && '' !== $atts['tag'] ) {
				return 'No Book Found';
			}
			array_push( $result, $tag->name );
		}
			echo esc_attr( print_r( $array ) );
		foreach ( $result as $item ) {
			echo esc_attr( $item ) . '<br>';
		}
		echo '</pre>';
	}

	/**
	 * Book Dashboard Widget Render Function
	 *
	 * @since    1.0.0
	 */
	public function wp_book_dashboard_widgets() {
		wp_add_dashboard_widget( 'book_dashboard_widget', 'Top 5 Book Categories as per count', array( $this, 'wp_book_dashboard_widgets_render' ) );
	}

	/**
	 * Book Dashboard Widget Render Function
	 *
	 * @since    1.0.0
	 */
	public function wp_book_dashboard_widgets_render() {
		$array = get_terms();
		$total = array();
		$count = 0;
		foreach ( $array as $item ) {
			if ( 'Book Category' === $item->taxonomy ) {
				$total[ $item->name ] = $item->count;
			}
		}
		arsort( $total );

		$count = 0;
		foreach ( $total as $param_name => $param_value ) {
			echo '<h4>' . esc_html( $param_name ) . ' ' . esc_html( $param_value ) . '</h4>';
			if ( $count > 3 ) {
				break;
			}
			$count++;
		}
	}

	/**
	 * Calling widget hook and class
	 *
	 * @since    1.0.0
	 */
	public function wp_book_widget() {
		include_once ABSPATH . 'wp-content/plugins/wp-book/includes/class-book-widget.php';
		$widget = new Book_Widget();
		register_widget( $widget );
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
		add_settings_field( 'book_settings_field_zero', 'Number of books displayed per page', array( $this, 'book_settings_field_zero_render' ), 'bookSettings', 'book_settings_section' );
		add_settings_field( 'book_settings_field_one', 'Changing Currency', array( $this, 'book_settings_field_one_render' ), 'bookSettings', 'book_settings_section' );
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
		<input type="number" name='book_settings[book_settings_field_zero]' value="<?php echo esc_attr( $options['book_settings_field_zero'] ); ?>">
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
