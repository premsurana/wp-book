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
			'description'                 => __( 'Book Widget', 'wp-book' ),
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

		echo '<h4>';
		esc_html_e( 'Books as per widgets', 'wp-book' );
		echo '</h4>';

		if ( ! isset( $instance['selectedCat'] ) ) {
			return;
		}

		$the_query = new WP_Query(
			array(
				'post_type' => 'book',
				'tax_query' => array(
					array(
						'taxonomy' => 'Book Category',
						'field'    => 'slug',
						'terms'    => $instance['selectedCat'],
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
			esc_html_e( 'No Book Found', 'wp-book' );
		}

	}

	/**
	 * Widget rendering on admin side function
	 *
	 * @since    1.0.0
	 * @param    mixed $instance New Instance Object.
	 */
	public function form( $instance ) {
		$array = get_terms();
		echo '<h2>Book Category: </h2>';
		echo "<select name='" . esc_html( $this->get_field_name( 'selectedCat' ) ) . "' id='" . esc_html( $this->get_field_id( 'selectedCat' ) ) . "'>";
		foreach ( $array as $item ) {
			if ( 'Book Category' === $item->taxonomy ) {
				echo '<option ' . selected( $instance['selectedCat'], $item->name ) . " value='" . esc_html( $item->name ) . "'>" . esc_html( $item->name ) . '</option>';
			}
		}
		echo '</select>';
		echo '<br><br>';
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

		$instance['selectedCat'] = ! empty( $new_instance['selectedCat'] ) ? $new_instance['selectedCat'] : '';
		return $instance;
	}
}
