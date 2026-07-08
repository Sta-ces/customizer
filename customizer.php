<?php
/**
 * Plugin Name: Customizer
 * Plugin URI: https://atelier.staces.be/
 * Description: Add settings in your customizer
 * Version: 1.0.0
 * Author: Cedric Staces
 * Author URI: https://staces.be/
 * Text Domain: stacesbuilder
 */

namespace StacesBuilder\Inc\Customizer;

use StacesBuilder\Inc\Customizer\ST_Slider_Control;

if (!defined('ABSPATH')) { exit; }

// Plugin activation
register_activation_hook(__FILE__, function(){ update_option('stacesbuilder_customizer_activated', true); });
// Deactivating the plugin
register_deactivation_hook(__FILE__, function(){ delete_option('stacesbuilder_customizer_activated'); });

add_action( 'customize_register', function($wp){
	require_once(__DIR__ . '/config/class-slider-control.php');
} );

if(!class_exists('\StacesBuilder\Inc\Customizer\ST_Customizer')){
	class ST_Customizer{
		function __construct(\WP_Customize_Manager $wp, string $name, array $args){
			if ( ! $wp instanceof \WP_Customize_Manager ) return false;
			if(!isset($args['label']) || !isset($args['section'])) return false;
			$args = array_merge([
				'default' => '',
				'transport' => 'refresh',
				'type' => 'text',
				'priority' => 10,
				'settings' => $name
			], $args);
			if(isset($args['refresh'])) $args['transport'] = !!$args['refresh'] ? 'refresh' : 'postMessage';
			if( ! $wp->get_section( $args['section'] ) ){
				$section_args = array_merge([
					'title'	=> _st(ucfirst(trim($args['section']))),
					'priority'	=> 150
				], $args['section_args'] ?? []);
				$wp->add_section($args['section'], $section_args);
			}
			$wp->add_setting(
				$name,
				array(
					'default'		=> $args['default'],
					'transport'		=> $args['transport']
				)
			);
			switch ($args['type']) {
				case 'color': case 'colors':
					$wp->add_control(new \WP_Customize_Color_Control( $wp, $name, $args ));
					break;
				case 'image': case 'images': case 'media':
					$args['mime_type'] = 'image';
					$wp->add_control(new \WP_Customize_Media_Control( $wp, $name, $args ));
					break;
				case 'range':
					$wp->add_control(new ST_Slider_Control($wp, $name, $args));
					break;
				default: $wp->add_control($name, $args); break;
			}
			return true;
		}
	}
}
