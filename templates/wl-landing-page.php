
<div class="container my-5">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Register', 'wonder-login' ); ?></h3>
    <?php endif; ?>
    <div class="cd-from-holder-btn">
        <button class="btn btn-primary wl-btn-register-01">Sign Up</button>
        <button class="btn btn-primary wl-btn-login-01">Log In</button>
    </div>
    <div class="cd-from-container-01">
        <div class="cd-from-holder-01">
           <!--  <div class="cd-step-hoder">
                <div class="cd-close-pannel-01">X</div>
            </div> -->
            <?php if (get_option( 'users_can_register' ) ) { ?>
                <?php if ( !is_user_logged_in() ) { ?> 
                    <form role="form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" id="myForm">
                        <div class="row setup-content wonder-login-reg-step-1" id="step-1">
                            <div class="col-xs-6 col-md-offset-3">
                                <div class="col-md-12">
                                    <h3 class="text-center">Sign Up</h3>
                                    <div class="error-msg " style="color: red;"></div>
                                    <div class="success-msg " style="color: green;"></div>
                                    <div class="cd-input-from"> 
                                        <input maxlength="100" type="email" class="" id="register-email" placeholder="Enter Email" autocomplete="">
                                    </div>

                                    <div class="cd-input-from">
                                        <input maxlength="100" type="password" required class="" placeholder="Enter Password" id="register-psw" autocomplete="">
                                    </div>
                                    <button class="btn btn-primary nextBtn_1 btn-lg pull-right w-100 my-3 step-1-submit register-free-member" type="button" id="next-01" name="step-1-submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php }else{
                ?><h4 class="text-center">You are logged in , please logout to sign up.</h4>
                    <div class="wl-btn-logout-01">
                             <button class="btn btn-primary  wl-btn-logout ">Logout</button>
                         </div>
                <?php } ?>
            <?php }else{ ?><h4 class="text-center">Registering new users is currently not allowed.</h4><?php } ?>
            <!-- <h4 class="text-center">Registering new users is currently not allowed.</h4> -->    
            
        </div>
    </div>
    <div class="cd_login_popup-01">
        <div class="cd-from-holder-01">
            <!-- <div class="cd-close-pannel-01">X</div> -->
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
                 
                <?php if ( !is_user_logged_in() ) { ?>
                <form id="login" action="login" method="post">
                    <h3 class="text-center">Login</h3>
                    <p class="status"></p>
                    <input id="username" type="text" name="username">
                    <input id="password" type="password" name="password" autocomplete="off">
                    <input class="submit_button" type="submit" value="Login" name="submit" id="wl-submit">
                    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                </form>
                <a class="forgot-password wl-forgot-password" href="<?php echo esc_url(wp_lostpassword_url()); ?>" alt="<?php esc_attr_e( 'Lost Password', 'textdomain' ); ?>">
                    <?php _e( 'Forgot your password?', 'wonder-login' ); ?>
                    </a>
                 <?php }else{
                    ?><h4 class="text-center">You are already logged in</h4>
                    <div class="wl-btn-logout-01">
                         <button class="btn btn-primary  wl-btn-logout ">Logout</button>
                     </div>
                    <?php } ?> 
            </div>
        </div>
    </div>     
</div>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
   <!--  <script>
        
    </script> -->
    <?php require(plugin_dir_path(__FILE__).'register-form-script.php');?>

   