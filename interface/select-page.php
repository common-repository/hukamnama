<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Today's Hukamnama</h2>
	<form method="post" action="options.php"> 
		<?php settings_fields( 'hukamnama' ); ?>
		<h3>Select today's hukamnama below</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="hukamnama_date">Date (MM/DD/YYYY)</label></th>
				<td>
					<input type="date" id="hukamnama_date" name="hukamnama_date" value="<?php echo $current_date; ?>" class="hukamnama-date" max="<?php echo $max_date; ?>" min="<?php echo $min_date; ?>" />
					<div class="spinner"></div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="hukamnama_page">Page</label></th>
				<td>
					<input id="hukamnama_page" name="hukamnama_page" value="<?php echo get_option( 'hukamnama_page_' . date( 'Y-m-d', current_time( 'timestamp' ) ) ); ?>">
					<div class="spinner"></div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="hukamnama_hymn">Hymn</label></th>
				<td>
					<span id="hukamnama_hymn_display"><a href="<?php echo self::get_display_url( date( 'Y-m-d', current_time( 'timestamp' ) ) ); ?>"><?php echo implode(', ', array_keys( get_option( 'hukamnama_hymn_'  . date( 'Y-m-d', current_time( 'timestamp' ) ) ) ? get_option( 'hukamnama_hymn_'  . date( 'Y-m-d', current_time( 'timestamp' ) ) ) : array() ) ); ?></a></span>
				</td>
			</tr>
		</table>
		<div class="hukamnama-finder">
		</div>
		<?php submit_button(); ?>
	</form>
</div>