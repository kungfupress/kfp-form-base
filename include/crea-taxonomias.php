<?php
/**
 * File: kfp-form-base/include/crea-taxonomias.php
 *
 * @package kfp_form_base
 */

defined( 'ABSPATH' ) || die();

register_activation_hook( __FILE__, 'kfp_taxonomia_categoria' );
add_action( 'init', 'kfp_taxonomia_categoria', 0 );
/**
 * Registra la taxonomía con lo mínimo indispensable
 *
 * @return void
 */
function kfp_taxonomia_categoria() {
	$args = array(
		'label'             => 'Categoría Ticket',
		'hierarchical'      => false,
		'show_admin_column' => true,
	);
	register_taxonomy( 'kfp-ticket-categoria', array(), $args );
}

add_action( 'init', 'kfp_categoria_agregar', 1 );
/**
 * Agrega unas categorías iniciales
 *
 * @return void
 */
function kfp_categoria_agregar() {
	$categorias = array(
		'Error',
		'Soporte',
		'Sugerencia',
	);
	foreach ( $categorias as $categoria ) {
		wp_insert_term( $categoria, 'kfp-ticket-categoria' );
	}
}
