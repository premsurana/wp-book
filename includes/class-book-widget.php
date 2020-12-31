<?php
/**
 * The widget functionality of the plugin.
 *
 * @link       www.author.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 */

/**
 * The widget functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes/
 * @author     Author <author@authormail.com>
 */
class Book_Widget extends WP_Widget {

	/**
	 * To construct the widget extending WP_Widget.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$widget_options = array(
			'classname'                   => 'book_widget',
			'description'                 => __( 'Book Widget' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'book_widget', 'Book Widget', $widget_options );
	}

	/**
	 * Widget rendering on client side function
	 *
	 * @since    1.0.0
	 * @param    mixed $args     Arguments.
	 * @param    mixed $instance Instance Object.
	 */
	public function widget( $args, $instance ) {
		?>
			<h4>Books as per widgets</h4><br>
		<?php
		if ( ! isset( $instance['title'] ) ) {
			return;
		}

		$the_query = new WP_Query(
			array(
				'post_type' => 'book',
				'tax_query' => array(
					array(
						'taxonomy' => 'Book Category',
						'field'    => 'slug',
						'terms'    => $instance['title'],
					),
				),
			)
		);
		$flag      = false;
		while ( $the_query->have_posts() ) {
			$flag = true;
			$the_query->the_post();
			echo esc_html( get_the_title() );
			echo '<br>';
		}

		if ( false === $flag ) {
			echo 'no book found';
		}
	}

	/**
	 * Widget rendering on admin side function
	 *
	 * @since    1.0.0
	 * @param    mixed $instance New Instance Object.
	 */
	public function form( $instance ) {

		echo '<br>';
		echo "<input type='text' name='" . esc_html( $this->get_field_name( 'title' ) ) . "' id='" . esc_html( $this->get_field_name( 'id' ) ) . "'>";
		echo '<br>';
	}

	/**
	 * Widget updating on client side
	 *
	 * @since    1.0.0
	 * @param    mixed $new_instance New Instance Object.
	 * @param    mixed $old_instance Old Instance Object.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['title'] = ! empty( $new_instance['title'] ) ? $new_instance['title'] : '';
		return $instance;
	}
}