jQuery(document).ready(function () {
    var navListItems = jQuery('div.setup-panel div a'),
        allWells = jQuery('.setup-content'),
        step_1_content = jQuery('.wonder-login-reg-step-1'),
        allNextBtn = jQuery('.nextBtn'),
        NextBtn_2 = jQuery('.nextBtn_2'),
        allPrevBtn = jQuery('.prevBtn');

    allWells.hide();
    step_1_content.show();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = jQuery(jQuery(this).attr('href')),
            $item = jQuery(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allPrevBtn.click(function () {
        var curStep = jQuery(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            prevStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");
            prevStepWizard.removeAttr('disabled').trigger('click');
    });

    allNextBtn.click(function () {
        var curStep = jQuery(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        jQuery(".cd-input-from").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                jQuery(curInputs[i]).closest(".cd-input-from").addClass("has-error");
            }
        }
        nextStepWizard.removeAttr('disabled').trigger('click');
    });

    jQuery('div.setup-panel div a.btn-primary').trigger('click');
});
jQuery(document).ready(function () {
    if (ajax_object_register_form.loggedin=='true') {
       jQuery(".wl-btn-register").hide();
       jQuery(".wl-btn-login").hide();
        //jQuery(".wl-btn-logout").show();
    }else{
         jQuery(".wl-btn-logout").hide();
    }
    jQuery(".wl-btn-register").click(function () {
        jQuery(".cd-from-container").toggle();
        jQuery(".wl-btn-register-01").click();
        jQuery(".cd-from-holder-01").toggleClass('cd-toggle-login-btn-02');

    });
    jQuery(".wl-btn-login").click(function () {
        jQuery(".cd_login_popup").toggle();
        jQuery(".wl-btn-login-01").click();
        jQuery(".cd-from-holder-01").toggleClass('cd-toggle-login-btn-01');
        /*jQuery(".cd-from-holder-01").hide();
        jQuery(".cd-from-holder-01").show();*/

    });
    jQuery(".cd-close-pannel-01").click(function () {
        jQuery(".cd-from-container").hide();
        jQuery(".cd_login_popup").hide();

    });
});