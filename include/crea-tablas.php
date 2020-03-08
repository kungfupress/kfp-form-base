<?php
/**
 * File: kfp-form-base/include/crea-tablas.php
 *
 * @package kfp_form_base
 */

defined( 'ABSPATH' ) || die();

// Cuando el plugin se active se crea la tabla si no existe.
register_activation_hook( KFP_FORM_BASE_PLUGIN_FILE, 'kfp_crea_tablas' );

/**
 * Realiza las acciones necesarias para configurar el plugin cuando se activa
 *
 * @return void
 */
function kfp_crea_tablas() {
	global $wpdb; // Este objeto global nos permite trabajar con la BD de WP
	// Crea la tabla si no existe.
	$tabla_ticket    = $wpdb->prefix . 'ticket';
	$charset_collate = $wpdb->get_charset_collate();

	$query = "CREATE TABLE IF NOT EXISTS $tabla_ticket (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		asunto varchar(250) NOT NULL,
		descripcion text NOT NULL,
		categoria_id mediumint(9) NOT NULL,
		created_at datetime NOT NULL,
		UNIQUE (id)
		) $charset_collate;";
	// La función dbDelta que nos permite crear tablas de manera segura se
	// define en el fichero upgrade.php que se incluye a continuación.
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $query );
}
