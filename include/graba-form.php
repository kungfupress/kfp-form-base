<?php
/**
 * File: kfp-form-base/include/graba-form.php
 *
 * @package kfp_form_base
 */

defined( 'ABSPATH' ) || die();

// Agrega los action hooks para grabar el formulario (el primero para usuarios
// logeados y el otro para el resto)
// Lo que viene tras admin_post_ y admin_post_nopriv_ tiene que coincidir con
// el valor del campo input con nombre "action" del formulario enviado.
add_action( 'admin_post_kfp-form-base', 'kfp_graba_ticket' );
add_action( 'admin_post_nopriv_kfp-form-base', 'kfp_graba_ticket' );
/**
 * Graba los datos enviados por el formulario como un nuevo CPT kfp-taller
 *
 * @return void
 */
function kfp_graba_ticket() {
	global $wpdb;
	// Si viene en $_POST aprovecha uno de los campos que crea wp_nonce para volver al form.
	$url_origen = home_url( '/' );
	if ( ! empty( $_POST['_wp_http_referer'] ) ) {
		$url_origen = esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) );
	}
	// Define condicion de error a priori y si la cosa sale bien cambia a 'success'
	$query_arg = array( 'kfp-form-base-resultado' => 'error' );
	// Comprueba campos requeridos y nonce.
	if ( isset( $_POST['asunto'] )
	&& isset( $_POST['descripcion'] )
	&& isset( $_POST['categoria_id'] )
	&& isset( $_POST['kfp-form-base-nonce'] )
	&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kfp-form-base-nonce'] ) ), 'kfp-form-base' )
	) {
		$asunto          = sanitize_text_field( wp_unslash( $_POST['asunto'] ) );
		$descripcion       = sanitize_text_field( wp_unslash( $_POST['descripcion'] ) );
		$categoria_id = (int) $_POST['categoria_id'];
		$created_at = date('Y-m-d H:i:s');
		$tabla_ticket = $wpdb->prefix . 'ticket';
		$resultado = $wpdb->insert(
			$tabla_ticket,
			array(
				'asunto' => $asunto,
				'descripcion' => $descripcion,
				'categoria_id' => $categoria_id,
				'created_at' => $created_at,
			)
		);
		if ( $resultado ) {
			$query_arg = array( 'kfp-form-base-resultado' => 'success' );
		}
	}
	wp_redirect( esc_url_raw( add_query_arg( $query_arg , $url_origen ) ) );
	exit();
}
