<?php
/**
 * Plugin Name: Shipping Zone by Drawing addon for WooCommerce Delivery Slots
 * Plugin URI: https://iconicwp.com/products/woocommerce-delivery-slots/
 * Description: Bridge between Shipping Zone by Drawing addon and WooCommerce Delivery Slots by Kadence
 * Version: 1.0.0
 * Author: Kadence WP
 * Author URI: https://www.kadencewp.com/
 * Author Email: support@iconicwp.com
 * WC requires at least: 2.6.14
 * WC tested up to: 4.6.1
 *
 * @package Iconic_WDS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Compatiblity class.
 */
class Iconic_WDS_Compat_Shipping_Zones_By_Drawing {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( __CLASS__, 'hooks' ) );
		register_activation_hook( __FILE__, array( __CLASS__, 'plugin_activated' ) );
	}

	/**
	 * Plugin activated.
	 */
	public static function plugin_activated() {
		delete_transient( 'iconic-wds-shipping-methods' );
	}

	/**
	 * Hooks.
	 */
	public static function hooks() {
		if ( ! class_exists( 'SZBD' ) ) {
			return $shipping_method_options;
		}

		add_filter( 'iconic_wds_zone_based_shipping_method', array( __CLASS__, 'shipping_method_options' ), 10, 3 );
	}

	/**
	 * Add shipping method options.
	 *
	 * @param array            $shipping_method_options
	 * @param object           $method
	 * @param WC_Shipping_Zone $shipping_zone
	 *
	 * @return array
	 */
	public static function shipping_method_options( $shipping_method_options, $method, $shipping_zone ) {

		$class = strtolower( get_class( $method ) );

		if ( 'wc_szbd_shipping_method' !== $class ) {
			return $shipping_method_options;
		}

		$id    = sprintf( '%s:%s', $method->id, $method->get_instance_id() );
		$title = empty( $method->title ) ? ucfirst( $method->id ) : $method->title;

		$shipping_method_options[ $id ] = esc_html( sprintf( '%s: %s', $shipping_zone->get_zone_name(), $title ) );

		return $shipping_method_options;
	}
}

new Iconic_WDS_Compat_Shipping_Zones_By_Drawing();
