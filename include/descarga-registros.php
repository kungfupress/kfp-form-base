<?php
/**
 * File: kfp-form-base-/include/descarga-registros.php
 *
 * @package kfp_form_base
 */

defined( 'ABSPATH' ) || die();

// Agrega el action hook solo si accion=kfp_form_base_descarga_csv.
if ( isset( $_GET['accion'] ) && $_GET['accion'] == 'kfp_form_base_descarga_csv' ) {
	add_action( 'admin_init', 'kfp_form_base_genera_csv' );
}

function kfp_form_base_genera_csv() {
	// Comprueba que el usuario actual tenga permisos suficientes.
	if( !current_user_can( 'manage_options' ) ){
		return false;
	}
	// Comprueba que estamos en el escritorio.
	if( !is_admin() ){
		return false;
	}
	// Comprueba Nonce.
	$nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
	if ( ! wp_verify_nonce( $nonce, 'kfp_form_base_descarga_csv' ) ) {
		die( 'Error de comprobación de seguridad' );
	}
	ob_start();
	$filename = 'kfp-tickets-' . date('YmdHi') . '.csv';

	$fila_titulos = array(
		'Asunto',
		'Descripcion',
		'Categoría',
		'Fecha',
	);
	$filas_datos = array();
	global $wpdb;
	$tabla_ticket = $wpdb->prefix . 'ticket';
	$tickets     = $wpdb->get_results( "SELECT * FROM $tabla_ticket", OBJECT);
	foreach ( $tickets as $ticket ) {
		$tax_categoria = get_term_by( 'id', $ticket->categoria_id, 'kfp-ticket-categoria' );
		$fila = array(
			$ticket->asunto, 
			$ticket->descripcion,
			$tax_categoria->name,
			$ticket->created_at,
		);
		$filas_datos[] = $fila;
	}
	$handler = @fopen( 'php://output', 'w' );
	fprintf( $handler, chr(0xEF) . chr(0xBB) . chr(0xBF) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	fputcsv( $handler, $fila_titulos );
	foreach ( $filas_datos as $fila ) {
		fputcsv( $handler, $fila );
	}
	fclose( $handler );
	ob_end_flush();
	die();
}
