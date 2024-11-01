//login form js start
jQuery(document).ready(function($) {
    //console.log('custom.j');
    jQuery('#username').attr( "placeholder", "Username" );
    jQuery('#password').attr( "placeholder", "Password" );
    // Show the login dialog box on click
    $('a#show_login').on('click', function(e){
        $('body').prepend('<div class="login_overlay"></div>');
        $('form#login').fadeIn(500);
        $('div.login_overlay, form#login a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('form#login').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    $('form#login').on('submit', function(e){
        $('form#login p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'wonder_login_action', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #username').val(), 
                'password': $('form#login #password').val(), 
                'security': $('form#login #security').val() },
            success: function(data){
                //console.log(data);
                if (data.loggedin == true){
                    $('form#login p.status').text(data.message);
                    document.location.href = ajax_login_object.redirecturl;
                }
            },
            error: function (data) {
                    if (ajax_login_object.loggedin == true){
                         location.reload();
                    }
                    if (data.loggedin == false){

                        $('form#login p.status').text('Wrong username or password.');
                    }else{
                        location.reload();
                    }
                    //console.log('erorr');
                    //console.log(data);
            },
        });
        e.preventDefault();
    });
});
//login form js end
///logout script start
jQuery('.wl-btn-logout').on('click', function(e){
    jQuery.ajax({
        type: 'POST',
        url: ajax_login_object.ajaxurl,
        data: { 
            'action': 'wonder_logout_action', //calls wp_ajax_nopriv_ajaxlogin
        },
        success: function(data){
           document.location.href = ajax_login_object.redirect_logout_url;
        },
        error: function (data) {
            console.log("error");
        },
    });
});
///logout script end
