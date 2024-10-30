<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Settings</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'hukamnama-settings' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="hukamnama_api">Sikher API URL</label></th>
				<td>
					<input id="hukamnama_api" name="hukamnama_api" value="<?php echo get_option( 'hukamnama_api' ); ?>">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="hukamnama_post_id">Page with shortcode</label></th>
				<td>
					<?php wp_dropdown_pages( array(
						'id' => 'hukamnama_post_id',
						'name' => 'hukamnama_post_id',
						'selected' => $hukamnama_post_id,
						'show_option_none' => '-- Any Page --',
						'option_none_value' => '0',
					) ); ?>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>