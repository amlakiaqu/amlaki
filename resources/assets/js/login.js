window.modalAnimate = function (oldForm, newForm, modalAnimateTime) {
    modalAnimateTime = modalAnimateTime || 300;
    var formsContainerId = Constants.LOGIN_MODAL_FORMS_CONTAINER_ID;
    formsContainerId = "#" + formsContainerId;
    var oldHeight = oldForm.height();
    var newHeight = newForm.height();
    $(formsContainerId).css("height",oldHeight);
    oldForm.fadeToggle(modalAnimateTime, function(){
        $(formsContainerId).animate({height: newHeight}, modalAnimateTime, function(){
            newForm.fadeToggle(modalAnimateTime);
        });
    });
}

window.defaultSetFormHandler = function (event, formCode){
  var formsContainerId = Constants.LOGIN_MODAL_FORMS_CONTAINER_ID;
  formsContainerId = "#" + formsContainerId;
  var formsContainer = $(formsContainerId);
  var currentFromCode = formsContainer.data("current-form-code");
  var loginForm = $("#" + Constants.LOGIN_FORM_ID);
  var registerForm = $("#" + Constants.REGISTER_FORM_ID);
  var resetPasswordForm = $("#" + Constants.RESET_PASSWORD_FORM_ID);
  if(currentFromCode == formCode){return;}

  var currentForm = undefined;
  switch (currentFromCode) {
    case Constants.LOGIN_FORM_CODE:
      currentForm = loginForm;
      break;
    case Constants.REGISTER_FORM_CODE:
      currentForm = registerForm;
      break;
    case Constants.RESET_PASSWORD_FORM_CODE:
      currentForm = resetPasswordForm;
      break;
  }

  var newForm = undefined;
  switch (formCode) {
    case Constants.LOGIN_FORM_CODE:
      newForm = loginForm;
      break;
    case Constants.REGISTER_FORM_CODE:
      newForm = registerForm;
      break;
    case Constants.RESET_PASSWORD_FORM_CODE:
      newForm = resetPasswordForm;
      break;
  }

  if(currentForm == undefined && newForm != undefined){
    var newHeight = newForm.height();
    var modalAnimateTime = 300;
    formsContainer.animate({height: newHeight}, modalAnimateTime, function(){
        newForm.fadeToggle(modalAnimateTime);
    });
    formsContainer.data("current-form-code", formCode);
  }else if(currentForm != undefined && newForm != undefined){
    modalAnimate(currentForm, newForm);
    formsContainer.data("current-form-code", formCode);
  }

  // Initialize the Bootstrap form validator
  if(typeof(loginForm.validator) == "function"){
      loginForm.validator();
  }
  if(typeof(registerForm.validator) == "function"){
      registerForm.validator();
  }
  if(typeof(resetPasswordForm.validator) == "function"){
      resetPasswordForm.validator();
  }
}

$(document).ready(function(){
  $(document).on("click", ".btn-show-forget-password-form", function(event){
    event.preventDefault();
    $("#" + Constants.LOGIN_MODAL_ID).trigger("set-form", Constants.RESET_PASSWORD_FORM_CODE);
  });
  $(document).on("click", ".btn-show-sign-up-form", function(event){
    event.preventDefault();
    $("#" + Constants.LOGIN_MODAL_ID).trigger("set-form", Constants.REGISTER_FORM_CODE);
  })
  $(document).on("click", ".btn-show-login-form", function(event){
    event.preventDefault();
    $("#" + Constants.LOGIN_MODAL_ID).trigger("set-form", Constants.LOGIN_FORM_CODE);
  })
});



$(document).ready(function() {
    var loginFormId = '#login-form';
    var lostFormId = '#lost-form';
    var registerFormId = '#register-form';
    var formsContainerId = '#div-forms';
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;

    $("form").submit(function () {
        switch(this.id) {
            case "login-form":
                var $lg_username=$('#login_username').val();
                var $lg_password=$('#login_password').val();
                if ($lg_username == "ERROR") {
                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "error", "glyphicon-remove", "Login error");
                } else {
                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "success", "glyphicon-ok", "Login OK");
                }
                return false;
                break;
            case "lost-form":
                var $ls_email=$('#lost_email').val();
                if ($ls_email == "ERROR") {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "error", "glyphicon-remove", "Send error");
                } else {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "success", "glyphicon-ok", "Send OK");
                }
                return false;
                break;
            case "register-form":
                var $rg_username=$('#register_username').val();
                var $rg_email=$('#register_email').val();
                var $rg_password=$('#register_password').val();
                if ($rg_username == "ERROR") {
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "error", "glyphicon-remove", "Register error");
                } else {
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "success", "glyphicon-ok", "Register OK");
                }
                return false;
                break;
            default:
                return false;
        }
        return false;
    });



    function msgFade ($msgId, $msgText) {
        $msgId.fadeOut($msgAnimateTime, function() {
            $(this).text($msgText).fadeIn($msgAnimateTime);
        });
    }

    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText) {
        var $msgOld = $divTag.text();
        msgFade($textTag, $msgText);
        $divTag.addClass($divClass);
        $iconTag.removeClass("glyphicon-chevron-right");
        $iconTag.addClass($iconClass + " " + $divClass);
        setTimeout(function() {
            msgFade($textTag, $msgOld);
            $divTag.removeClass($divClass);
            $iconTag.addClass("glyphicon-chevron-right");
            $iconTag.removeClass($iconClass + " " + $divClass);
  		}, $msgShowTime);
    }

    $(document).on("click", "#login_register_btn", function(){ modalAnimate($(loginFormId), $(registerFormId)); });
    $(document).on("click", "#register_login_btn", function(){  modalAnimate($(registerFormId), $(loginFormId)); });
    $(document).on("click", "#login_lost_btn", function(){ modalAnimate($(loginFormId), $(lostFormId)); });
    $(document).on("click", "#lost_login_btn", function(){ modalAnimate($(lostFormId), $(loginFormId)); });
    $(document).on("click", "#lost_register_btn", function(){ modalAnimate($(lostFormId), $(registerFormId)); });
    $(document).on("click", "#register_lost_btn", function(){ modalAnimate($(registerFormId), $(lostFormId)); });

});
