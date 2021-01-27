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
			'name'               => __( 'Books', 'wp-book' ),
			'singular_name'      => __( 'Book', 'wp-book' ),
			'add_new'            => __( 'Add New', 'wp-book' ),
			'add_new_item'       => __( 'Add New Book', 'wp-book' ),
			'edit_item'          => __( 'Edit Book', 'wp-book' ),
			'new_item'           => __( 'New Book', 'wp-book' ),
			'all_items'          => __( 'All Book', 'wp-book' ),
			'view_item'          => __( 'View Book', 'wp-book' ),
			'search_items'       => __( 'Search Book', 'wp-book' ),
			'not_found'          => __( 'No books found', 'wp-book' ),
			'not_found_in_trash' => __( 'No books found in the Trash', 'wp-book' ),
			'menu_name'          => __( 'Books', 'wp-book' ),
		);
		$args   = array(
			'labels'        => $labels,
			'description'   => __( 'Holds our books and book specific data', 'wp-book' ),
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor' ),
			'has_archive'   => true,
			'show_in_rest'  => true,
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
			'name'          => __( 'Book Categories', 'wp-book' ),
			'singular_name' => __( 'Book Category', 'wp-book' ),
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rest_base'    => 'book-categories',
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
			'name'          => __( 'Book Tags', 'wp-book' ),
			'singular_name' => __( 'Book Tag', 'wp-book' ),
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => false,
			'show_in_rest' => true,
			'rest_base'    => 'book-tags',
		);

		register_taxonomy( 'Book Tag', array( 'book' ), $args );
	}

	/**
	 * Register Custom Book Meta Box.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_book_meta_box() {
		add_meta_box( 'book_meta_box', __( 'Book Meta', 'wp-book' ), array( $this, 'book_meta_box_content' ), 'book' );
	}

	/**
	 * Adds Custom Book Meta Box Data to Rest.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_add_json() {
		register_rest_field(
			'book',
			'book_meta_data',
			array(
				'get_callback'    => 'get_book_meta_rest',
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	/**
	 * Registers a custom route of books
	 *
	 * @since    1.0.0
	 */
	public function wp_book_custom_route() {
		register_rest_route(
			'custom',
			'/books',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_custom_books' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Adding data to custom route.
	 *
	 * @since    1.0.0
	 */
	public function get_custom_books() {
		$return_array = get_transient( 'book_custom_query_results' );
		if ( false === $return_array ) {
			$args         = array(
				'post_type' => 'book',
			);
			$the_query    = new WP_Query( $args );
			$return_array = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$id         = get_the_ID();
					$categories = array();
					$tags       = array();
					$terms      = get_the_terms( $id, 'Book Category' );
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							array_push( $categories, $term->name );
						}
					}
					$terms = get_the_terms( $id, 'Book Tag' );
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							array_push( $tags, $term->name );
						}
					}
					$array = array(
						'ID'         => $id,
						'title'      => get_the_title(),
						'MetaData'   => get_book_meta( $id, $id, true ),
						'Categories' => $categories,
						'Tags'       => $tags,
					);
					array_push( $return_array, $array );
				}
				set_transient( 'book_custom_query_results', $return_array, HOUR_IN_SECONDS );

			} else {
				return 'Nothing';
			}
		}

		return $return_array;
	}

	/**
	 * Delete transient on book update.
	 *
	 * @since    1.0.0
	 */
	public function wp_book_delete_transient() {
		global $post_type;

		if ( 'book' !== $post_type ) {
			return;
		}
		delete_transient( 'book_custom_query_results' );
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
		<?php esc_html_e( 'Author', 'wp-book' ); ?>: <input type="text" name="AuthorName" value="<?php echo( isset( $array['AuthorName'] ) ? esc_attr( $array['AuthorName'] ) : '' ); ?>"><br><br>
		<?php esc_html_e( 'Price', 'wp-book' ); ?>: <input type="number" name="Price" value="<?php echo( isset( $array['Price'] ) ? esc_attr( $array['Price'] ) : '' ); ?>"><br><br>
		<?php esc_html_e( 'Publisher', 'wp-book' ); ?>: <input type="text" name="Publisher" value="<?php echo( isset( $array['Publisher'] ) ? esc_attr( $array['Publisher'] ) : '' ); ?>"><br><br>
		<?php esc_html_e( 'Year', 'wp-book' ); ?>: <input type="text" name="Year" value="<?php echo( isset( $array['Year'] ) ? esc_attr( $array['Year'] ) : '' ); ?>"><br><br>
		<?php esc_html_e( 'Edition', 'wp-book' ); ?>: <input type="text" name="Edition" value="<?php echo( isset( $array['Edition'] ) ? esc_attr( $array['Edition'] ) : '' ); ?>"><br><br>
		<?php esc_html_e( 'URL', 'wp-book' ); ?>: <input type="text" name="URL" value="<?php echo( isset( $array['URL'] ) ? esc_attr( $array['URL'] ) : '' ); ?>"><br><br>
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
		$args = array(
			'post_type' => 'book',
		);
		if ( empty( $atts ) ) {
			$the_query = new WP_Query( $args );
			$this->display( $the_query );
			return;
		} elseif ( ! empty( $atts['id'] ) ) {
			$args['p'] = $atts['id'];
			$the_query = new WP_Query( $args );
			$this->display( $the_query );
			return;
		} elseif ( ! empty( $atts['category'] ) && ! empty( $atts['tag'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'Book Category',
					'field'    => 'slug',
					'terms'    => $atts['category'],
				),
				array(
					'taxonomy' => 'Book Tag',
					'field'    => 'slug',
					'terms'    => $atts['tag'],
				),
			);
			$the_query         = new WP_Query( $args );
			$this->display( $the_query );
			return;
		} elseif ( ! empty( $atts['category'] ) && empty( $atts['tag'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'Book Category',
					'field'    => 'slug',
					'terms'    => $atts['category'],
				),
			);
			$the_query         = new WP_Query( $args );
			$this->display( $the_query );
			return;
		} elseif ( ! empty( $atts['tag'] ) && empty( $atts['category'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'Book Tag',
					'field'    => 'slug',
					'terms'    => $atts['tag'],
				),
			);
			$the_query         = new WP_Query( $args );
			$this->display( $the_query );
			return;
		}
	}

	/**
	 * Book ShortCode Function
	 *
	 * @since    1.0.0
	 * @param    mixed $the_query display function.
	 */
	public function display( $the_query ) {
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$id    = get_the_ID();
				$title = get_the_title();
				$array = get_book_meta( $id, $id, true );
				echo '<h4>' . esc_html( $title ) . '</h4>';
				echo '<p>Author Name: ' . esc_html( $array['AuthorName'] ) . '</p>';
				echo '<p>Publisher: ' . esc_html( $array['Publisher'] ) . '</p>';
				echo '<p>Price: ' . esc_html( $array['Price'] ) . '</p>';
				echo '<p>Year: ' . esc_html( $array['Year'] ) . '</p>';
				echo '<br>';
			}
		} else {
			echo 'No book found';
		}
	}
	/**
	 * Book Dashboard Widget Render Function
	 *
	 * @since    1.0.0
	 */
	public function wp_book_dashboard_widgets() {
		wp_add_dashboard_widget( 'book_dashboard_widget', __( 'Top 5 Book Categories as per count', 'wp-book' ), array( $this, 'wp_book_dashboard_widgets_render' ) );
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
		add_options_page( __( 'Book Settings', 'wp-book' ), __( 'Book Settings', 'wp-book' ), 'manage_options', 'book-settings', array( $this, 'wp_book_settings_render' ) );
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
		add_settings_section( 'book_settings_section', __( 'Book Settings Section', 'wp-book' ), array( $this, 'book_settings_section_render' ), 'bookSettings' );
		add_settings_field( 'book_settings_field_zero', __( 'Number of books displayed per page', 'wp-book' ), array( $this, 'book_settings_field_zero_render' ), 'bookSettings', 'book_settings_section' );
		add_settings_field( 'book_settings_field_one', __( 'Changing Currency', 'wp-book' ), array( $this, 'book_settings_field_one_render' ), 'bookSettings', 'book_settings_section' );
	}

	/**
	 * Render Book Settings Section
	 *
	 * @since    1.0.0
	 */
	public function book_settings_section_render() {
		echo '<p>';
		esc_html_e( 'Description of Books', 'wp-book' );
		echo '</p>';
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

	/**
	 * Cron after fifteen second
	 *
	 * @since    1.0.0
	 */
	public function fifteen_second_function() {
		require_once ABSPATH . 'wp-content/class-wp-logger.php';
		WP_Logger::logger( 'hello world' );
	}

	/**
	 * Phpmailer for smtp
	 *
	 * @since    1.0.0
	 * @param   mixed $phpmailer    something.
	 */
	public function configure_phpmailer( $phpmailer ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = SMTP_HOST;
		$phpmailer->SMTPAuth   = SMTP_AUTH;
		$phpmailer->Port       = SMTP_PORT;
		$phpmailer->Username   = SMTP_USER;
		$phpmailer->Password   = SMTP_PASS;
		$phpmailer->SMTPSecure = SMTP_SECURE;
		$phpmailer->From       = SMTP_FROM;
		$phpmailer->FromName   = SMTP_NAME;
		require_once ABSPATH . 'wp-content/class-wp-logger.php';
		WP_Logger::logger( 'hi world' );
	}

	public function mail_failed( $error ) {
		require_once ABSPATH . 'wp-content/class-wp-logger.php';
		WP_Logger::logger( $error );
	}
}

/**
 * Add book meta function
 *
 * @since    1.0.0
 * @param      string $book_id       Book id as post id.
 * @param      string $meta_key    Meta key to pass for meta key table.
 * @param      string $meta_value  Meta value to pass for meta value table.
 * @param      string $unique    Unique can be either true or false.
 */
function add_book_meta( $book_id, $meta_key, $meta_value, $unique = false ) {
	return add_metadata( 'book', $book_id, $meta_key, $meta_value, $unique );
}

/**
 * Delete Book function
 *
 * @since    1.0.0
 * @param      string $book_id       Book id as post id.
 * @param      string $meta_key    Meta key to pass for meta key table.
 * @param      string $meta_value  Meta value to pass for meta value table.
 */
function delete_book_meta( $book_id, $meta_key, $meta_value = '' ) {
	return delete_metadata( 'book', $book_id, $meta_key, $meta_value );
}

/**
 * Delete Book function
 *
 * @since    1.0.0
 * @param      string $book_id       Book id as post id.
 * @param      string $key    Meta key to pass for book meta table.
 * @param      string $single  Meta value to pass for book meta table.
 */
function get_book_meta( $book_id, $key = '', $single = true ) {
	return get_metadata( 'book', $book_id, $key, $single );
}

/**
 * Get book meta in rest api
 *
 * @since    1.0.0
 * @param    object $object Object returned by register_rest_field.
 */
function get_book_meta_rest( $object ) {
	$post_id = $object['id'];
	return get_book_meta( $post_id, $post_id, true );
}

/**
 * Add book meta function
 *
 * @since    1.0.0
 * @param      string $book_id       Book id as post id.
 * @param      string $meta_key    Meta key to pass for meta key table.
 * @param      string $meta_value  Meta value to pass for meta value table.
 * @param      string $prev_value    Previous value to be added.
 */
function update_book_meta( $book_id, $meta_key, $meta_value, $prev_value = '' ) {
	return update_metadata( 'book', $book_id, $meta_key, $meta_value, $prev_value );
}
