<?php
/**
 * Plugin Name:  KFP Form Base
 * Plugin URI:   https://github.com/kungfupress/kfp-form-base
 * Description:  Una buena base para hacer un formulario completamente personalizado en WordPress sin plugins de terceros. Inserta el shortcode [kfp_form_base]
 * Version:      0.1.0
 * Author:       KungFuPress
 * Author URI:   https://kungfupress.com/
 * PHP Version:  5.6
 *
 * @package  kfp_form_base
 */

defined( 'ABSPATH' ) || die();

// Constantes que afectan a todos los ficheros del plugin.
define( 'KFP_FORM_BASE_PLUGIN_FILE', __FILE__ );
define( 'KFP_FORM_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'KFP_FORM_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'KFP_FORM_BASE_VERSION', '0.1.0' );

// Crea tabla.
require_once KFP_FORM_BASE_DIR . 'include/crea-tablas.php';
// Crea y rellena taxonomías.
require_once KFP_FORM_BASE_DIR . 'include/crea-taxonomias.php';
// Agrega shortcode [kfp_form_base] para mostrar formulario.
require_once KFP_FORM_BASE_DIR . 'include/shortcode-form.php';
// Agrega función para que admin-post.php capture el envío de un nuevo taller desde un formulario.
require_once KFP_FORM_BASE_DIR . 'include/graba-form.php';
// Panel con lista de registros en el escritorio.
require_once KFP_FORM_BASE_DIR . 'include/admin-menu.php';
// Módulo para descargar las registros existentes.
require_once KFP_FORM_BASE_DIR . 'include/descarga-registros.php';
