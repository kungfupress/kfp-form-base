<?php
/**
 * File: kfp-form-base/include/form-shortcode.php
 *
 * @package kfp_form_base
 */

defined( 'ABSPATH' ) || die();

add_shortcode( 'kfp_form_base', 'kfp_form_base' );
/**
 * Implementa formulario para crear un nuevo taller.
 *
 * @return string
 */
function kfp_form_base() {
	// Trae los categorias existentes a una variable.
	// Esta variable recibirá un array de objetos de tipo taxonomy.
	$categorias = get_terms(
		'kfp-ticket-categoria',
		array(
			'orderby'    => 'term_id',
			'hide_empty' => 0,
		)
	);
	ob_start();
	if ( filter_input( INPUT_GET, 'kfp-form-base-resultado', FILTER_SANITIZE_STRING ) === 'success' ) {
		echo '<h4>Se ha grabado su solicitud correctamente</h4>';
	}
	if ( filter_input( INPUT_GET, 'kfp-form-base-resultado', FILTER_SANITIZE_STRING ) === 'error' ) {
		echo '<h4>Se ha producido un error al grabar su solicitud</h4>';
	}
	?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"
		id="form_solicitud">
		<?php wp_nonce_field( 'kfp-form-base', 'kfp-form-base-nonce' ); ?>
		<input type="hidden" name="action" value="kfp-form-base">
		<div class="form-input">
			<label for="asunto">Asunto</label>
			<input type="text" name="asunto" id="asunto" required>
		</div>
		<div class="form-input">
			<label for="descripcion">Descripción</label>
			<textarea name="descripcion" required="required"></textarea>
		</div>
		<div class="form-input">
			<label for="categoria_id">Categoría</label>
			<select name="categoria_id" required>
				<option value="">Seleccione categoría</option>
				<?php
				foreach ( $categorias as $categoria ) {
					echo(
						'<option value="' . esc_attr( $categoria->term_id ) . '">'
						. esc_attr( $categoria->name ) . '</option>'
					);
				}
				?>
			</select>
		</div>
		<div class="form-input">
			<input type="submit" value="Enviar">
		</div>
	</form>
	<?php
	return ob_get_clean();
}
