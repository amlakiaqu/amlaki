window.modalAnimate = function (oldForm, newForm, modalAnimateTime) {
    modalAnimateTime = modalAnimateTime || 300;
    var formsContainerId = Constants.LOGIN_MODAL_FORMS_CONTAINER_ID;
    formsContainerId = "#" + formsContainerId;
    var oldHeight = oldForm.height();
    var newHeight = newForm.height();
    $(formsContainerId).css("height", oldHeight);
    oldForm.fadeToggle(modalAnimateTime, function () {
        $(formsContainerId).animate({height: newHeight}, modalAnimateTime, function () {
            newForm.fadeToggle(modalAnimateTime);
        });
    });
};

window.defaultSetFormHandler = function (event, formCode) {
    var formsContainerId = Constants.LOGIN_MODAL_FORMS_CONTAINER_ID;
    formsContainerId = "#" + formsContainerId;
    var formsContainer = $(formsContainerId);
    var currentFromCode = formsContainer.data("current-form-code");
    var loginForm = $("#" + Constants.LOGIN_FORM_ID);
    var registerForm = $("#" + Constants.REGISTER_FORM_ID);
    var resetPasswordForm = $("#" + Constants.RESET_PASSWORD_FORM_ID);
    if (currentFromCode === formCode) {
        return;
    }

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

    if (currentForm === undefined && newForm !== undefined) {
        var newHeight = newForm.height();
        var modalAnimateTime = 300;
        formsContainer.animate({height: newHeight}, modalAnimateTime, function () {
            newForm.fadeToggle(modalAnimateTime);
        });
        formsContainer.data("current-form-code", formCode);
    } else if (currentForm !== undefined && newForm !== undefined) {
        modalAnimate(currentForm, newForm);
        formsContainer.data("current-form-code", formCode);
    }
};

$(document).ready(function () {
    $(document).on("click", ".btn-show-forget-password-form", function (event) {
        event.preventDefault();
        $('#' + Constants.LOGIN_MODAL_ID).trigger("set-form", Constants.RESET_PASSWORD_FORM_CODE);
    });
    $(document).on("click", ".btn-show-sign-up-form", function (event) {
        event.preventDefault();
        $('#' + Constants.LOGIN_MODAL_ID).trigger("set-form", Constants.REGISTER_FORM_CODE);
    });
    $(document).on("click", ".btn-show-login-form", function (event) {
        event.preventDefault();
        $('#' + Constants.LOGIN_MODAL_ID).trigger("set-form", Constants.LOGIN_FORM_CODE);
    });
});
