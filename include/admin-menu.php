<?php
/**
 * File: kfp-form-base/include/admin-menu.php
 *
 * @package kfp_form_base
 */

defined( 'ABSPATH' ) || die();

add_action( 'admin_menu', 'kpf_form_base_menu' );
/**
 * Agrega el menú de administración del plugin al escritorio de WordPress
 *
 * @return void
 */
function kpf_form_base_menu() {
	add_menu_page(
		'Tickets Soporte',
		'Tickets',
		'manage_options',
		'kpf_form_base_menu',
		'kpf_form_base_admin',
		'dashicons-feedback',
		75
	);
}

/**
 * Agrega el panel de administración del plugin al escritorio
 *
 * @return void
 */
function kpf_form_base_admin() {
	global $wpdb;
	$tabla_ticket = $wpdb->prefix . 'ticket';
	$tickets      = $wpdb->get_results( "SELECT * FROM $tabla_ticket", OBJECT );

	$html  = '<div class="wrap"><h1>Lista de tickets</h1>';
	$html .= '<div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=kpf_form_base_menu' );
	$html .= '&accion=kfp_form_base_descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'kfp_form_base_descarga_csv' ) . '">Descargar fichero CSV</a></div><br>';
	$html .= '<table class="wp-list-table widefat fixed striped">';
	$html .= '<thead><tr><th>Asunto</th><th>Categoría</th><th>Descripción</th>';
	$html .= '<th></th></tr></thead>';
	$html .= '<tbody id="the-list">';
	foreach ( $tickets as $ticket ) {
		$asunto           = esc_textarea( $ticket->asunto );
		$descripcion      = esc_textarea( $ticket->descripcion );
		$ticket_categoria = get_term_by( 'id', $ticket->categoria_id, 'kfp-ticket-categoria' );
		$categoria        = $ticket_categoria->name;

		$html .= "<tr><td>$asunto</td><td>$categoria</td><td>$descripcion</td>";
		$html .= "<td><a href='#' data-ticket_id='$ticket->id' class='ticket-borrar'>Borrar</a></td></tr>";
	}
	$html .= '</tbody></table></div>';
	$html .= '<br><div class="dashicons-before dashicons-admin-page"><a href="' . admin_url( 'admin.php?page=kpf_form_base_menu' );
	$html .= '&accion=kfp_form_base_descarga_csv&_wpnonce=';
	$html .= wp_create_nonce( 'kfp_form_base_descarga_csv' ) . '">Descargar fichero CSV</a></div>';
	echo $html;
}

add_action( 'admin_enqueue_scripts', 'kpf_form_base_admin_scripts' );
/**
 * Agrega el script que borra mediante AJAX las tickets desde el panel de administración
 *
 * @return void
 */
function kpf_form_base_admin_scripts() {
	wp_register_script(
		'kfp-form-base-admin',
		KFP_FORM_BASE_URL . 'assets/kfp-admin-scripts.js',
		array( 'jquery' ),
		KFP_FORM_BASE_VERSION,
		false
	);
	wp_localize_script(
		'kfp-form-base-admin',
		'ajax_object',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'kfp_ticket_borrar_' . admin_url( 'admin-ajax.php' ) ),
		)
	);
	wp_enqueue_script( 'kfp-form-base-admin' );

}

// Prepara los hooks para borrar tickets con ajax.
add_action( 'wp_ajax_kfp_ticket_borrar', 'kfp_ticket_borrar' );
/**
 * Borra el ticket seleccionado
 *
 * @return void
 */
function kfp_ticket_borrar() {
	global $wpdb;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
			'kfp_ticket_borrar_' . admin_url( 'admin-ajax.php' )
			)
		) {
		$ticket_id = filter_input( INPUT_POST, 'ticket_id', FILTER_SANITIZE_NUMBER_INT );
		$tabla_ticket = $wpdb->prefix . 'ticket';
		$wpdb->delete( $tabla_ticket, array( 'id' => $ticket_id )  , array( '%d' ) );
		echo '1';
		die();
	} else {
		echo '-1';
		die( 'Error de seguridad' );
	}
}
