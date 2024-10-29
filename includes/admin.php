<?php

defined( 'ABSPATH' ) || exit;

/**
 * Form submission.
 */
if ( ! empty( $_POST ) ) {
	// Security check.
	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'jn_admin_tailor_save' ) ) exit;

	// Get values.
	$logo_id             = absint( $_POST['jn_admin_tailor_login_logo_id'] );
	$login_color_palette = sanitize_hex_color( $_POST['jn_admin_tailor_login_color'] );
	$login_footer_color  = sanitize_hex_color( $_POST['jn_admin_tailor_login_footer_color'] );
	$login_pattern_url   = sanitize_url( $_POST['jn_admin_tailor_login_pattern_url'] );

	// Save the options.
	update_option( 'jn_admin_tailor_login_logo_id', $logo_id );
	update_option( 'jn_admin_tailor_login_color', $login_color_palette );
	update_option( 'jn_admin_tailor_login_footer_color', $login_footer_color );
	update_option( 'jn_admin_tailor_login_pattern_url', $login_pattern_url );

	// Show success notice.
	add_action( 'admin_notices', function() {
		echo '
			<div class="notice notice-success is-dismissible">
				<p>Settings saved successfully.</p>
			</div>
		';
	} );
}

function jn_admin_tailor_add_settings_page_content() {
	$image_id = get_option( 'jn_admin_tailor_login_logo_id' );
	$image    = wp_get_attachment_image_url( $image_id );

	$login_color_palette = get_option( 'jn_admin_tailor_login_color' );
	$login_footer_color  = get_option( 'jn_admin_tailor_login_footer_color' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ) ?></h1>

		<form action="" method="post">
			<table class="form-table">
				<tr>
					<th scope="row">
						<label>Login page logo</label>
					</th>
					<td>
						<?php if ( $image ) : ?>
							<a href="#" class="jn-admin-tailor-upload">
								<img src="<?php echo esc_url( $image ) ?>" alt="Logo">
							</a>
							<a href="#" class="jn-admin-tailor-remove">Remove logo</a>
							<input type="hidden" name="jn_admin_tailor_login_logo_id" value="<?php echo absint( $image_id ) ?>">

						<?php else : ?>
							<a href="#" class="button jn-admin-tailor-upload">Upload image</a>
							<a href="#" class="jn-admin-tailor-remove" style="display:none">Remove image</a>
							<input type="hidden" name="jn_admin_tailor_login_logo_id" value="">

							<p class="description">Logo size should be <strong>84px</strong> x <strong>84px</strong>.</p>
						<?php endif; ?>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label>Login page color palette</label>
					</th>
					<td>
						<input
							type="text"
							value="<?php echo $login_color_palette ? esc_attr( $login_color_palette ) : '#2271b1' ?>"
							name="jn_admin_tailor_login_color"
							data-default-color="#2271b1" />

						<p class="description">Color for <strong>border, button, input</strong> etc.</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label>Login page footer color</label>
					</th>
					<td>
						<input
							type="text"
							value="<?php echo $login_footer_color ? esc_attr( $login_footer_color ) : '#50575e' ?>"
							name="jn_admin_tailor_login_footer_color"
							data-default-color="#50575e" />

						<p class="description">Color for <strong>lost your password</strong> and <strong>go to website</strong> text.</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label>Login page background</label>
					</th>
					<td>
						<ul class="patterns">
							<?php
							$login_pattern_url = get_option( 'jn_admin_tailor_login_pattern_url', jn_admin_tailor_get_pattern_url( 'default.png' ) );

							$patterns = [
								'default.png',
								'cork-board.png',
								'doodles.png',
								'full-bloom.png',
								'more-leaves.png',
								'moroccan-flower.png',
								'pink-flowers.png',
								'restaurant.png',

								'bar-chart.svg',
								'blue-wave.svg',
								'bubble-fade.svg',
								'bubble.svg',
								'plus-fade.svg',
								'red-wave.svg',
								'shadow-fall.svg',
							];

							foreach( $patterns as $pattern ) : ?>
								<li>
									<?php echo $login_pattern_url === jn_admin_tailor_get_pattern_url( $pattern ) ? '<span>&#10003;</span>' : '' ?>
									<img src="<?php echo esc_url( jn_admin_tailor_get_pattern_url( $pattern ) ) ?>" alt="Pattern">
									<p><?php echo esc_html( jn_admin_tailor_get_pattern_name( $pattern ) ) ?></p>
								</li>
							<?php endforeach;

							do_action( 'jn_admin_tailor_login_pattern_url_list' ) ?>
						</ul>

						<input type="hidden" name="jn_admin_tailor_login_pattern_url" value="<?php echo esc_attr( $login_pattern_url ) ?>">

						<p class="description">Login page background pattern.</p>
					</td>
				</tr>
			</table>

			<?php wp_nonce_field( 'jn_admin_tailor_save' ); ?>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			</p>
		</form>
	<div>
	<?php
}
