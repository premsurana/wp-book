<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       www.author.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Author <author@authormail.com>
 */
class Wp_Book_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
		$this->add_action( 'init', $this, 'wp_book_book_post_type' );
		$this->add_action( 'init', $this, 'wp_book_book_category_taxonomy' );
		$this->add_action( 'init', $this, 'wp_book_book_tag_taxonomy' );
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

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
			'menu_name'          => 'Books'
		);
		$args = array(
			'labels'        => $labels,
			'description'   => 'Holds our books and book specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt'),
			'has_archive'   => true,
		);
		register_post_type( 'book', $args );
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
}
