
<div class="container">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Register', 'wonder-login' ); ?></h3>
    <?php endif; ?>
   <!--  <button class="btn btn-primary wl-btn-register ">Sign Up</button>
    <button class="btn btn-primary wl-btn-login">Log In</button> -->
    <div class="cd-from-container">
        <div class="cd-from-holder">
            <div class="cd-step-hoder">
                <div class="cd-close-pannel-01">X</div>
            </div>
            <?php if ( !is_user_logged_in() ) { ?> 
                <form role="form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" id="myForm">
                    <div class="row setup-content wonder-login-reg-step-1" id="step-1">
                        <div class="col-xs-6 col-md-offset-3">
                            <div class="col-md-12">
                                <h3 class="text-center">Sign Up</h3>
                                <!-- <p class="text-center">Register as a Free member</p> -->
                                <div class="error-msg " style="color: red;"></div>
                                <div class="success-msg " style="color: green;"></div>
                                <div class="cd-input-from"> 
                                    <input maxlength="100" type="email" class="" id="register-email" placeholder="Enter Email">
                                </div>

                                <div class="cd-input-from">
                                    <input maxlength="100" type="password" required class="" placeholder="Enter Password" id="register-psw">
                                </div>
                                <button class="btn btn-primary nextBtn_1 btn-lg pull-right w-100 my-3 step-1-submit register-free-member" type="button" id="next-01" name="step-1-submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php }else{
            ?><h4 class="text-center">You are already logged in</h4>

           <?php } ?>
            
        </div>
    </div>
    <div class="cd_login_popup ">
        <div class="cd-from-holder ">
            <div class="cd-close-pannel-01">X</div>
            <div class="col-md-12 login-form-container">
                <?php if ( $attributes['show_title'] ) : ?>
                    <!-- Show errors if there are any -->
                <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
                    <?php foreach ( $attributes['errors'] as $error ) : ?>
                        <p class="login-error">
                            <?php echo esc_html($error); ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
                    <!-- <h3 class="text-center">Log in</h3> -->
                <?php endif; ?>
                <?php if ( !is_user_logged_in() ) { ?>
                    <h3 class="text-center">Log in</h3>
                     <form id="login" action="login" method="post">
                        <!-- <h1>Site Login</h1> -->
                        <p class="status"></p>
                        <!-- <label for="username">Username</label> -->
                        <input id="username" type="text" name="username">
                        <!-- <label for="password">Password</label> -->
                        <input id="password" type="password" name="password" autocomplete="">
                        <input class="submit_button" type="submit" value="Login" name="submit" id="wl-submit">
                        <!-- <a class="close" href="">(close)</a> -->
                        <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                    </form>
                    <a class="forgot-password wl-forgot-password" href="<?php echo esc_url(wp_lostpassword_url()); ?>">
                    <?php _e( 'Forgot your password?', 'wonder-login' ); ?>
                    </a>
                 <?php }else{
                    ?><h4 class="text-center">You are already logged in</h4>

                    <?php } ?> 
                
            </div>
        </div>
    </div>
</div>
    <?php require(plugin_dir_path(__FILE__).'register-form-script.php'); ?>
   