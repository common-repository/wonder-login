<div class="container user_profile_page">
	<?php 
	global $current_user;
	wp_get_current_user();
	$user_id = get_current_user_id(); 
	?>
	<?php if ($current_user->user_login) { ?>
		<div class="mt-4">
			<span class="wonder_user__name fw-"><b>User Name : </b><?php echo esc_html($current_user->user_login); ?></span>
		</div>
	<?php } ?>
	<?php if ($current_user->user_firstname) { ?>
		<div class="mt-2">
			<span class="wonder_user_first_name"><b>First Name : </b><?php echo esc_html($current_user->user_firstname); ?></span>
		</div>
	<?php } ?>
	<?php if ($current_user->user_lastname) { ?>
		<div class="mt-2">
			<span class="wonder_user_last_name"><b>Last Name : </b><?php echo esc_html($current_user->user_lastname); ?></span>
		</div>
	<?php } ?>
	<?php if ($current_user->user_email) { ?>
		<div class="mt-2">
			<span class="wonder_user_email"><b>Email : </b><?php echo esc_html($current_user->user_email); ?></span>
		</div>
	<?php } ?>
	<?php if ($current_user->display_name) { ?>
		<div class="mt-2">
			<span class="wonder_user_email"><b>Display name publicly as : </b><?php echo esc_html($current_user->display_name); ?></span>
		</div>
	<?php } ?>
	<?php if (get_user_meta($user_id,'description',true)) { ?>
		<div class="">
			<span class="wonder_user_bio"><b>Biographical Info : </b><?php echo esc_html(get_user_meta($user_id,'description',true)); ?></span>
			
		</div>
	<?php } ?>
</div>