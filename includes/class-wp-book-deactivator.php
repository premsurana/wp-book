<?php
/**
 * Fired during plugin deactivation
 *
 * @link       www.author.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Author <author@authormail.com>
 */
class Wp_Book_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		require_once ABSPATH . 'wp-content/class-wp-logger.php';
		$abc = wp_mail( 'something@gmail.com', 'Plugin Deactivated', 'Some message' );
		if ( $abc ) {
			WP_Logger::logger( 'true' );
		} else {
			WP_Logger::logger( 'false' );
			global $phpmailer;
			WP_Logger::logger( $phpmailer );
		}

		flush_rewrite_rules();
	}

}
