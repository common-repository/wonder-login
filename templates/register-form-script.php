<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".wl-btn-login-01").click(function(){
            jQuery(".cd-from-container-01").hide();
           jQuery(".cd_login_popup-01").show();
        });
        /*jQuery(".wl-btn-login-01").click(function(){
        });*/
        
        jQuery(".wl-btn-register-01").click(function(){
            jQuery(".cd_login_popup-01").hide();
            jQuery(".cd-from-container-01").show();
        });
        /*jQuery(".wl-btn-register-01").click(function(){
        });*/
    });
    //<!--code for sign up as a free member start -->
    jQuery('.register-free-member').on('click',function(){
        var action = 'register_action';
         var mail_id = jQuery("#register-email").val();
         var passwrd = jQuery("#register-psw").val();
         var ajaxurl='<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
            jQuery.ajax({
                type: 'POST', // Adding Post method
                url: ajaxurl, // Including ajax file
                data: {'action': 'register_action', 'mail_id':mail_id,'passwrd':passwrd}, 
                success: function(data){ // Show returned data using the function.
                    //console.log(data);
                    if (data=='Registration Successful') {
                    jQuery('.success-msg').html(data);
                    jQuery('.error-msg').html(null);
                    jQuery("#member-email").attr('value',mail_id);
                    jQuery('#step-1-confirmation').css("display","none");
                    jQuery(".wonder-login-reg-step-2").attr('disabled',false);
                    NextBtn_1 = jQuery('.nextBtn_1');
                        var curStep = jQuery('.nextBtn_1').closest(".setup-content"),
                            curStepBtn = curStep.attr("id"),
                            nextStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                            curInputs = curStep.find("input[type='text'],input[type='url']"),
                            isValid = true;
                        nextStepWizard.removeAttr('disabled').trigger('click');

                    }else{
                    jQuery('#step-1-confirmation').css("display","block");
                    jQuery(".wonder-login-reg-step-2").attr('disabled',true);
                    jQuery('.error-msg').html(data);    
                    jQuery('.success-msg').html(null);
                    }
                },
                error: function (data) {
                    //console.log('erorr');
                },
            });
    });           
    //<!--code for sign up as a free member end -->
    //code for paid membership registration start
    jQuery('.register-paid-member').on('click',function(){
        //console.log('.register-free-member');
        jQuery('.step-2-error-msg').html('');
        var user_full_name = jQuery.trim(jQuery(".user_full_name").val());
        var user_mobile = jQuery.trim(jQuery(".user_mobile").val());
        var user_wap_num = jQuery.trim(jQuery(".user_wap_num").val());
        var user_address = jQuery.trim(jQuery(".user_address").val());
        var user_email = jQuery.trim(jQuery("#member-email").val());
        var ajaxurl='<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
        if (user_full_name!='' && user_mobile!='' && user_wap_num!='' && user_address!='') {
            jQuery.ajax({
                type: 'POST', // Adding Post method
                url: ajaxurl, // Including ajax file
                data: {'action': 'register_step_2', 'user_full_name':user_full_name,'user_mobile':user_mobile,'user_wap_num':user_wap_num,'user_address':user_address,'user_email':user_email},
                success: function(data){ // Show returned data using the function.
                    //console.log(data);

                    var curStep = jQuery('.nextBtn_2').closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;
                    nextStepWizard.removeAttr('disabled').trigger('click');
                },
                error: function (data) {
                    //console.log('erorr');
                },
            });
        }else{
           jQuery('.step-2-error-msg').html('Please fill all the fields'); 
        }         
    });
    //code for paid membership registration end
</script>