<div class="container">	
	<div class="wl-settings">
		<?php
		$args = array(
			'post_type'=> 'page',
			'post_status'=> 'publish',
			'number'=> 0,

		);
		$pages_array = get_pages($args);
		?>
		<form action="" method="post">
			<h3>Redirections</h3>
			<div class="after-login-page">
				<h3>After-Login actions</h3>
				<label for="cars">Redirect After Login</label>
				<select name="wl_login_page" id="wl_login_page">
				  	<option value="">___Select___</option>
					<?php foreach ($pages_array as $key => $value) {?>
					  	<option value="<?php esc_attr_e($value->ID); ?>"<?php if (get_option('wl_login_page')== $value->ID) {esc_attr_e('selected');} ?>><?php esc_attr_e($value->post_title); ?></option>
					 <?php } ?>
				</select>
			</div>
			<div class="after-log-out-page">
				<h3>After-Logout actions</h3>
				<label for="cars">Redirect After Logout</label>
				<select name="wl_logout_page" id="wl_logout_page">
					<option value="">___Select___</option>
				  <?php foreach ($pages_array as $key => $value) { ?>
					  	<option value="<?php esc_attr_e($value->ID); ?>" <?php if (get_option('wl_logout_page')== $value->ID) {esc_attr_e('selected');} ?>><?php esc_attr_e($value->post_title); ?></option>
					 <?php } ?>
				</select>
			</div>
			<div class="wl-popup-settings">
				<h3>Pop up settings</h3>
				<p>If you wish to add login or registration popup and logout button to your site . Add necessary class <strong>wl-btn-login</strong> , <strong>wl-btn-register</strong> , <strong>wl-btn-logout</strong> to button, a or menu item . (eg.<?php $myVar = htmlspecialchars("<a class='wl-btn-login'>login</a>", ENT_QUOTES); echo($myVar); ?> )<br>and to add Login and Registration Form in a page Use shortcode = <strong>[wonder-login-page]</strong></p>
			</div>	
			<div><input type="submit" name="wl_setting_submit" id="wl_setting_form_submit"></div>
		</form>
	</div>
	
</div>	

<?php require(plugin_dir_path(__FILE__).'settings_page_script.php');?>