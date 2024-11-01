<script type="text/javascript">
//options settings script start
jQuery(document).ready(function($) {
    $('#wl_setting_form_submit').on('click', function(e){ 
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            data: { 
                'action': 'handle_wl_settings', //calls wp_ajax_nopriv_ajaxlogin
                'wl_login_page':$('#wl_login_page').val(),
                'wl_logout_page':$('#wl_logout_page').val(),
            },
            success: function(data){
                location.reload();
                
            },
            error: function (data) {
                console.log("error");
            },
        });
    });
});
//options settings script end
</script>