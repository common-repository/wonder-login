
<div class="login-form-container">
    <?php if ( $attributes['show_title'] ) : ?>
    	<!-- Show errors if there are any -->
	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
	    <?php foreach ( $attributes['errors'] as $error ) : ?>
	        <p class="login-error">
	            <?php echo esc_html($error); ?>
	        </p>
	    <?php endforeach; ?>
	<?php endif; ?>
        <h2><?php _e( 'Log In', 'wonder-login' ); ?></h2>
    <?php endif; ?>
	
    <?php
        wp_login_form(
            array(
                'label_username' => __( 'Email/User Name', 'wonder-login' ),
                'label_log_in' => __( 'Log in', 'wonder-login' ),
                'redirect' => $attributes['redirect'],
            )
        );
    ?>
     
    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Forgot your password?', 'wonder-login' ); ?>
    </a>
</div>