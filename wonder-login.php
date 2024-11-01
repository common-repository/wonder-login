<?php
/**
 * Plugin Name:       Wonder Login
 * Description:       A plugin by Codeholic to Register and Login user from frontend.
 * Author:            Codeholic
 * Author URI:        https://codeholic.in/
 * Version:           1.0.1
 * License: GPL2
 */

if (!class_exists('wonder_login_plugin')){
    class wonder_login_plugin {
        //private static $instance = null;
        /**
         * Initializes the plugin.
         *
         * To keep the initialization fast, only add filter and action
         * hooks in the constructor.
         */
        public function __construct() {
            add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
            add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
            add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
            add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
            add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
            add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
            add_shortcode( 'wonder-login-popup-shortcode', array( $this, 'wonder_login_popups' ) );
            add_shortcode( 'wonder-login-page', array( $this, 'wonder_login_page' ) );
            add_shortcode( 'account-info', array( $this, 'account_info_page' ) );
            add_action('admin_menu', array($this,'wonder_login_admin_menu'));
            add_action('wp_enqueue_scripts',array($this,'add_assets'),99);
            add_action( 'wp_ajax_register_action',  array($this,'cd_handle_registration'));
            add_action( 'wp_ajax_nopriv_register_action', array($this,'cd_handle_registration'));
            add_action( 'wp_ajax_nopriv_wonder_login_action', array($this,'wonder_login_action'));
            add_action( 'wp_ajax_wonder_logout_action',  array($this,'wonder_logout_action'));
            add_action( 'user_register', array($this,'add_user_metadata' ));
            add_action( 'profile_update', array($this,'add_user_metadata' ));
            add_action( 'wp_ajax_handle_wl_settings',  array($this,'handle_wl_settings'));
            add_action('wp_head', array($this,'add_popup_shortcode'));
        }

        
       
        
        public static function plugin_activated() {
            $page_definitions = array(
              
                'member-account' => array(
                    'title' => __( 'MY Profile', 'wonder-login' ),
                    'content' => '[account-info]'
                ),
                
                'wonder-login' => array(
                    'title' => __( 'Wonder Login', 'wonder-login' ),
                    'content' => '[wonder-login-page]'
                ),
            
            );
            require(plugin_dir_path(__FILE__).'class/class.CreatePages.php');
            Wonder_login_pages::create_pages($page_definitions);
        }
        public static function plugin_deactivated() {
            /*remove_role('premium_member','Premium_Member');
            remove_role('free_member','Free Member');*/
        }
        //creating menu ="Wonder Login Settings"
        public function wonder_login_admin_menu() {
            add_menu_page('Wonder Login Settings' ,'Wonder Login', 'manage_options', 'wonder_login_setting', array($this,'wonder_login_setting_page'));
           
        }
        public function wonder_login_setting_page(){
            require(plugin_dir_path(__FILE__).'templates/wonder-login-setting-page.php');
        }
        //adding assets files ie. js and css files
        public function add_assets(){
            $wl_login_page_id = get_option('wl_login_page');
            $redirect_url='';
            if($wl_login_page_id){
                $wl_login_page_link = get_permalink($wl_login_page_id);
                if ($wl_login_page_link) {
                        $redirect_url = $wl_login_page_link;
                }else{
                    $redirect_url = home_url();
                }
            }else{
                    $redirect_url = home_url();
                }
            $wl_logout_page_id = get_option('wl_logout_page');
            $redirect_logout_url='';
            if($wl_logout_page_id){
                $wl_logout_page_link = get_permalink($wl_logout_page_id);
                if ($wl_logout_page_link) {
                    $redirect_logout_url = $wl_logout_page_link;
                    //wp_safe_redirect( $wl_logout_page_link );
                }else{
                    $redirect_logout_url = home_url('wonder-login');
                    //wp_safe_redirect( $redirect_logout_url );
                }
            }else{
                $redirect_logout_url = home_url();
                //wp_safe_redirect( $redirect_url );
            }    
            wp_enqueue_style(
                'bootstrap-css',
                plugin_dir_url(__FILE__).'css/bootstrap.min.css',
                array(),
                1,
                'all'
            );
            wp_enqueue_style(
                'register-form',
                plugin_dir_url(__FILE__).'templates/assets/css/register-form.css',
                array(),
                1,
                'all'
            );
            wp_enqueue_style(
                'profile-page',
                plugin_dir_url(__FILE__).'css/style.css',
                array(),
                1,
                'all'
            );
            wp_enqueue_script(
                'custom-js',
                 plugin_dir_url(__FILE__).'js/custom.js',
                 array('jquery'),
                 false,
                 true
            );
            $loggedin='';
            if (is_user_logged_in()) {$loggedin='true';}else{$loggedin='false';}
            wp_localize_script( 'custom-js', 'ajax_login_object',
                array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'redirecturl' => $redirect_url,
                    'redirect_logout_url' => $redirect_logout_url,
                    'loggedin'=> $loggedin,
                    'loadingmessage' => __('Sending user info, please wait...')
                )
            );
            wp_enqueue_script( 'register-form', plugin_dir_url(__FILE__). 'templates/assets/js/register-form.js', array('jquery'), false, true);
            wp_localize_script( 'register-form', 'ajax_object_register_form',
                array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'redirecturl' => $redirect_url,
                    'redirect_logout_url' => $redirect_logout_url,
                    'loggedin'=> $loggedin
                )
            );
        }
        /**
        * Redirect the user to the custom login page instead of wp-login.php.
        */
        function redirect_to_custom_login() {
            if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
                $redirect_to = isset( $_REQUEST['redirect_to'] ) ? sanitize_key($_REQUEST['redirect_to']) : null;
                if ( is_user_logged_in() ) {
                    $this->redirect_logged_in_user(esc_url($redirect_to));
                    exit;
                }
         
                // The rest are redirected to the login page
                $home_url=home_url('wonder-login');
                $login_url = esc_url($home_url);
                if ( ! empty( $redirect_to ) ) {
                    $login_url = add_query_arg( 'redirect_to', esc_url($redirect_to), $login_url );
                }
         
                wp_redirect( $login_url );
                exit;
            }
        }

        function wonder_login_popups ($attributes=null){
            $default_attributes = array( 'show_title' => false );
            $attributes = shortcode_atts( $default_attributes, $attributes );

            return $this->get_template_html( 'wl_popups', $attributes );
            /*if ( is_user_logged_in() ) {
                return __( 'You are already signed in.', 'wonder-login' );
            } elseif ( ! get_option( 'users_can_register' ) ) {
                return __( 'Registering new users is currently not allowed.', 'wonder-login' );
            } else {

            }   */ 
        }
        function wonder_login_action(){
            ///////
            // First check the nonce, if it fails the function will break
            check_ajax_referer( 'ajax-login-nonce', 'security' );

            // Nonce is checked, get the POST data and sign user on
            $info = array();
            $info['user_login'] = sanitize_user($_POST['username']);
            $info['user_password'] = sanitize_text_field($_POST['password']);
            $info['remember'] = true;
            if(!is_user_logged_in()){
                $user_signon = wp_signon( $info, false );
                if ( is_wp_error($user_signon) ){
                    echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
                } else {
                    echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
                }
            }

            die();
        }
        function wonder_login_page ($attributes=null){
            $default_attributes = array( 'show_title' => false );
            $attributes = shortcode_atts( $default_attributes, $attributes );

            /*if ( is_user_logged_in() ) {
                return __( 'You are already signed in.', 'wonder-login' );
            } */
                return $this->get_template_html( 'wl-landing-page', $attributes );
            /*if ( ! get_option( 'users_can_register' ) ) {
                return __( 'Registering new users is currently not allowed.', 'wonder-login' );
            } else {

            }    */
        }
            /**
         * Returns the URL to which the user should be redirected after the (successful) login.
         *
         * @param string           $redirect_to           The redirect destination URL.
         * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
         * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
         *
         * @return string Redirect URL
         */
        public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
            if ( user_can( $user, 'manage_options' ) ) {    
             update_user_meta( $user->ID, 'show_admin_bar_front', 'true' );
            }
            $redirect_url = home_url();

            if ( ! isset( $user->ID ) ) {
                return $redirect_url;
            }    
            // Non-admin users always go to their account page after login
            $wl_login_page_id = get_option('wl_login_page');
            if($wl_login_page_id){
                $wl_login_page_link = get_permalink($wl_login_page_id);
                if ($wl_login_page_link) {
                    if ( $requested_redirect_to == '' ) {
                        $redirect_url = $wl_login_page_link;
                    } else {
                        $redirect_url = $redirect_to;
                    }
                }else{
                    $redirect_url = home_url();
                }
            }else{
                if ( $requested_redirect_to == '' ) {
                    $redirect_url = home_url();
                } else {
                    $redirect_url = $redirect_to;
                }
            }
            return wp_validate_redirect( $redirect_url, home_url() );
        }
        function account_info_page(){
            return $this->get_template_html( 'account_info');
        }
        /**
         * Redirect to custom login page after the user has been logged out.
         */
        public function redirect_after_logout() {
            $wl_logout_page_id = get_option('wl_logout_page');
            if($wl_logout_page_id){
                $wl_logout_page_link = get_permalink($wl_logout_page_id);
                if ($wl_logout_page_link) {
                    wp_safe_redirect( $wl_logout_page_link );
                }else{
                    $redirect_url = home_url('wonder-login');
                    wp_safe_redirect( $redirect_url );
                }
            }else{
                $redirect_url = home_url();
                wp_safe_redirect( $redirect_url );
            }
            exit;
        }
        /**
         * Redirect the user after authentication if there were any errors.
         *
         * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
         * @param string            $username   The user name used to log in.
         * @param string            $password   The password used to log in.
         *
         * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
         */
        public function maybe_redirect_at_authenticate( $user, $username, $password ) {
            // Check if the earlier authenticate filter (most likely,
            // the default WordPress authentication) functions have found errors
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
                if ( is_wp_error( $user ) ) {
                    $error_codes = join( ',', $user->get_error_codes() );
                    global $wp;
                    $login_url = home_url('wonder-login');
                    $login_url = add_query_arg( 'login', $error_codes, $login_url );
                    wp_redirect( $login_url );
                    exit;
                }
            }

            return $user;
        }
        
        /**
         * A shortcode for rendering the login form.
         *
         * @param  array   $attributes  Shortcode attributes.
         * @param  string  $content     The text content for shortcode. Not used.
         *
         * @return string  The shortcode output
         */
        public function render_login_form( $attributes, $content = null ) {
            // Parse shortcode attributes
            $default_attributes = array( 'show_title' => false );
            $attributes = shortcode_atts( $default_attributes, $attributes );
            $show_title = $attributes['show_title'];
         
            if ( is_user_logged_in() ) {
                return __( 'You are already signed in.', 'wonder-login' );
            }
             
            // Pass the redirect parameter to the WordPress login functionality: by default,
            // don't specify a redirect, but if a valid redirect URL has been passed as
            // request parameter, use it.
            $attributes['redirect'] = '';
            if ( isset( $_REQUEST['redirect_to'] ) ) {
                $attributes['redirect'] = wp_validate_redirect( sanitize_key($_REQUEST['redirect_to']), $attributes['redirect'] );
            }
            // Error messages
            $errors = array();
            if ( isset( $_REQUEST['login'] ) ) {
                $error_codes = explode( ',', sanitize_key($_REQUEST['login']) );

                foreach ( $error_codes as $code ) {
                    $errors []= $this->get_error_message( $code );
                }
            }
            $attributes['errors'] = $errors;
            // Check if user just logged out
            $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && sanitize_key($_REQUEST['logged_out']) == true; 
            // Render the login form using an external template
            return $this->get_template_html( 'login_form', $attributes );
        }
        //validate registration function
        function cd_handle_registration(){

            if( $_POST['action'] == 'register_action' ) {
                if (isset($_POST['mail_id'])) {
                   $get_email=sanitize_email($_POST['mail_id']);
                }
                if (isset($_POST['passwrd'])) {
                    $get_password= sanitize_text_field($_POST['passwrd']);
                   
                }
                $error = '';
                 $email = trim($get_email);
                 $pswrd = $get_password;
                if( empty( $_POST['mail_id'] ) )
                 $error .= 'Enter Email Id ';
                 elseif( !filter_var($email, FILTER_VALIDATE_EMAIL) )
                 $error .= 'Enter Valid Email ';
                elseif( empty( $_POST['passwrd'] ) )
                 $error .= 'Password should not be blank ';
                elseif(username_exists($email))
                 $error .= 'Username already exists ';
                if( empty( $error ) && is_email($email) && !username_exists($email) ){

                    $status = wp_create_user(sanitize_email($email), sanitize_text_field($pswrd),sanitize_email($email),);

                    if( is_wp_error($status) ){

                        $msg = '';

                         foreach( $status->errors as $key=>$val ){

                             foreach( $val as $k=>$v ){

                                 $msg = $v;
                                 $wonder_user_id=$user_id;
                             }
                         }

                        echo esc_html($msg);

                     }else{

                        echo esc_html($msg = 'Registration Successful');
                        
                        
                    }

                }
                 else{

                    echo esc_html($error);
                 }
                 die(1);
            }
        }
        function add_user_metadata( $user_id ){

            if( !empty( $_POST['passwrd'] ) && !empty( $_POST['mail_id'] ) ){

                update_user_meta( $user_id, 'mail_id', sanitize_email(trim($_POST['mail_id'])) );
                
            }
            if ( user_can( $user_id, 'manage_options' ) ) {    
             update_user_meta( $user_id, 'show_admin_bar_front', 'true' );
            }else{
             update_user_meta( $user_id, 'show_admin_bar_front', 'false' );

            }
        }
        /**
         * A shortcode for rendering the new user registration form.
         *
         * @param  array   $attributes  Shortcode attributes.
         * @param  string  $content     The text content for shortcode. Not used.
         *
         * @return string  The shortcode output
         */
        public function render_register_form( $attributes, $content = null ) {
            // Parse shortcode attributes
            $default_attributes = array( 'show_title' => false );
            $attributes = shortcode_atts( $default_attributes, $attributes );

            if ( is_user_logged_in() ) {
                return __( 'You are already signed in.', 'wonder-login' );
            } elseif ( ! get_option( 'users_can_register' ) ) {
                return __( 'Registering new users is currently not allowed.', 'wonder-login' );
            } else {
                return $this->get_template_html( 'register_form', $attributes );
            }
        }
        /**
         * Renders the contents of the given template to a string and returns it.
         *
         * @param string $template_name The name of the template to render (without .php)
         * @param array  $attributes    The PHP variables for the template
         *
         * @return string               The contents of the template.
         */
        private function get_template_html( $template_name, $attributes = null ) {
            if ( ! $attributes ) {
                $attributes = array();
            }
         
            ob_start();
         
            do_action( 'wonder_login_before_' . $template_name );
         
            require(plugin_dir_path(__FILE__).'templates/' . $template_name . '.php');
         
            do_action( 'wonder_login_after_' . $template_name );
         
            $html = ob_get_contents();
            ob_end_clean();
         
            return $html;
        }
        /**
         * Redirects the user to the correct page depending on whether he / she
         * is an admin or not.
         *
         * @param string $redirect_to   An optional redirect_to URL for admin users
         */
        private function redirect_logged_in_user( $redirect_to = null ) {
            $user = wp_get_current_user();
            if ( user_can( $user, 'manage_options' ) ) {
                if ( $redirect_to ) {
                    wp_safe_redirect( $redirect_to );
                } else {
                    wp_redirect( admin_url() );
                }
            } else {
                wp_redirect( home_url( 'member-account' ) );
            }
        }
        /**
         * Finds and returns a matching error message for the given error code.
         *
         * @param string $error_code    The error code to look up.
         *
         * @return string               An error message.
         */
        private function get_error_message( $error_code ) {
            switch ( $error_code ) {
                // Login errors

                case 'empty_username':
                    return __( 'You do have an email address, right?', 'wonder-login' );

                case 'empty_password':
                    return __( 'You need to enter a password to login.', 'wonder-login' );

                case 'invalid_username':
                    return __(
                        "We don't have any users with that email address. Maybe you used a different one when signing up?",
                        'wonder-login'
                    );

                case 'incorrect_password':
                    $err = __(
                        "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                        'wonder-login'
                    );
                    return sprintf( $err, wp_lostpassword_url() );

                default:
                    break;
            }

            return __( 'An unknown error occurred. Please try again later.', 'wonder-login' );
        }
        function wonder_logout_action(){
            wp_logout();
            exit;
        }
        function handle_wl_settings(){
            if (isset($_POST['wl_login_page'])) {
                $slected_login_option =$_POST['wl_login_page'];
                if($slected_login_option==''){
                    update_option('wl_login_page','');
                }else{
                        
                        update_option('wl_login_page',absint($slected_login_option));
                            
                }
            }
            if (isset($_POST['wl_logout_page'])) {
                $slected_logout_option =$_POST['wl_logout_page'];
                if($slected_logout_option==''){
                    update_option('wl_logout_page','');
                }else{
                        
                        update_option('wl_logout_page',absint($slected_logout_option));
                        
                }
            }
            
        }
        function add_popup_shortcode(){
            if (get_page_by_path('wonder-login')->ID != get_the_ID()) {
                // code...
                echo do_shortcode('[wonder-login-popup-shortcode]');
            }
        }
    }

    // Initialize the plugin

    $wonder_login_plugin = new wonder_login_plugin();
}
//wonder_login_plugin::getInstance();

register_activation_hook( __FILE__, array( 'wonder_login_plugin', 'plugin_activated' ) );
register_deactivation_hook( __FILE__, array( 'wonder_login_plugin', 'plugin_deactivated' ) );

