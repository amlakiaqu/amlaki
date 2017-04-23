window.createLoginModal = function (setFormHandler) {
    var modalId = Constants.LOGIN_MODAL_ID;
    var templateId = Constants.LOGIN_MODAL_TEMPLATE_ID;
    var data = {
        "modal": {
            "id": modalId,
            "class": "auto-destroy",
            "backdrop": "static",
            "keyboard": "false"
        }
    };
    var distSelector = "#" + Constants.MODALS_CONTAINER_ID;
    var append = true;
    _.renderTemplate(templateId, data, distSelector, append);
    modalId = "#" + modalId;
    if (typeof(setFormHandler) === "function") {
        $(modalId).on("set-form", setFormHandler);
    }
};

window.createPostModal = function(url, postId){
    var options = {
        "title": "",
        "message": '<div class="text-center"><i class="fa fa-spin fa-spinner"></i>' + Laravel.strings.general.loading_message + '</div>'
    };

    $.ajax({
        "url": url,
        "method": "GET",
        "success": function (response) {
            if (response.user.id === Laravel.userId) {
                options["buttons"] = {
                    "btn_delete_post": {
                        label: Laravel.strings.post_info_modal.btn_delete_post,
                        className: "btn-danger",
                        callback: function (e) {
                            e.preventDefault();
                            console.log('deleting post', postId);
                            bootbox.confirm({
                                message: Laravel.strings.post_info_modal.delete_post_confirm_message,
                                buttons: {
                                    confirm: {
                                        label: Laravel.strings.post_info_modal.btn_delete_post_confirm_text,
                                        className: 'btn-danger'
                                    },
                                    cancel: {
                                        label: Laravel.strings.post_info_modal.btn_delete_post_cancel_text,
                                        className: 'btn-default'
                                    }
                                },
                                callback: function (result) {
                                    if(result === true){
                                        var url = getPostUrl(postId);
                                        $.ajax({
                                            "url": url,
                                            "method": "DELETE",
                                            "success": function(response){
                                                var message = response.message;
                                                var type = "success";
                                                var allowDismiss = true;
                                                var delay = 3000;
                                                generateNotification(message, type, allowDismiss, delay)
                                            },
                                            "error": function (response) {
                                                console.log(response);
                                                if(response.status === 401){
                                                    var responseData = response.responseJSON;
                                                    var message = responseData.message;
                                                    var type = "danger";
                                                    var allowDismiss = true;
                                                    var delay = 3000;
                                                    generateNotification(message, type, allowDismiss, delay);
                                                }
                                            },
                                            "complete": function(){
                                                dialog.modal("hide");
                                            }
                                        });
                                    }
                                }
                            });
                            return false;
                        }
                    },
                    "btn_edit_post": {
                        label: Laravel.strings.post_info_modal.btn_edit_post,
                        className: "btn-warning",
                        callback: function (e) {
                            e.preventDefault();
                            console.log('editing post', postId);
                            return false;
                        }
                    }
                };
            }
            options["title"] = response.title;
            var templateId = Constants.TABLE_TEMPLATE_ID;
            var tableRows = [];
            tableRows.push({
                "title": Laravel.strings.post_info_modal.category_name_title,
                "value": response.category.name
            });
            tableRows.push({
                "title": Laravel.strings.post_info_modal.advertiser_name_title,
                "value": '<button class="btn btn-link btn-show-post-user" data-user-id="' +  response.user.id + '">' + response.user.name + '</button>'
            });
            if(response.properties){
                $.each(response.properties, function(index, property){
                    tableRows.push({
                        "title": property.title,
                        "value": property.value
                    });
                });
            }
            var data = {
                "tableHeaders": [Laravel.strings.general.property, Laravel.strings.general.description],
                "tableRows": tableRows,
                "class": "center"
            };
            var distSelector = undefined;
            var append = false;
            var importJQuery = true;
            options["message"] = _.renderTemplate(templateId, {"data": data}, distSelector, append, importJQuery);
            var dialog = bootbox.dialog(options);
        },
        "error": function (response) {
            console.log(response);
        }
    });
};

window.createPostsGrid = function (containerId, posts) {
    var templateId = Constants.POST_CARD_TEMPLATE_ID;
    var postsData = posts["data"];
    var postsRow = $('<div class="row posts-row"></div>');
    if(postsData && postsData.length > 0){
        $.each(postsData, function (index, post) {
            var priceProperty = _.filter(post.properties, function (property) {
                return property.code === "PRICE";
            });
            var price = "0";
            if (priceProperty.length > 0) {
                priceProperty = priceProperty[0];
                if (priceProperty) {
                    price = priceProperty.pivot.value;
                    if(price){
                        price = parseInt(price).toLocaleString('en');
                    }
                }
            }

            var data = {
                "post": {
                    "id": post.id,
                    "title": post.title,
                    "price": price,
                    "image": {
                        "url": post.image || Constants.DEFAULT_IMAGE_URL,
                        "alt": post.title
                    },
                    "user": {
                        "id": post.user.id,
                        "name": post.user.name
                    }
                }
            };
            var result = _.renderTemplate(templateId, data);
            postsRow.append(result);
        });
    }else{
        postsRow.html('<p class="center bold">' + Laravel.strings.general.no_posts + '</p>');
    }

    $("#" + containerId).html(postsRow);
};

window.createPostCreateModal = function () {
    var modalId = Constants.CREATE_POST_MODAL_ID;
    var templateId = Constants.CREATE_POST_MODAL_TEMPLATE_ID;
    var data = {
        "modal": {
            "id": modalId,
            "class": "auto-destroy",
            "backdrop": "static",
            "keyboard": "false",
            "body": "soso",
            "formId": Constants.CREATE_POST_FORM_ID
        }
    };
    var distSelector = "#" + Constants.MODALS_CONTAINER_ID;
    var append = true;
    _.renderTemplate(templateId, data, distSelector, append);
    modalId = "#" + modalId;
    $(modalId).on("show.bs.modal", function() {
        var categories = Storages.localStorage.get('categories');
        if(categories === null){
            getCategories(function(){
                $(modalId).trigger("show.bs.modal");
            });
        }else{
            var categoriesOptions = [{"text": Laravel.strings.post_category_type_modal.default_item_text, "value": ""}];
            $.each(categories, function(index, category){
                categoriesOptions.push({
                    "text": category.name,
                    "value": category.id
                });
            });

            var dialog = bootbox.prompt({
                title: Laravel.strings.post_category_type_modal.modal_title,
                inputType: 'select',
                inputOptions: categoriesOptions,
                buttons: {
                    confirm: {
                        label: Laravel.strings.post_category_type_modal.modal_confirm_button_text,
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-danger hidden'
                    }
                },
                callback: function (categoryId) {
                    if(categoryId === null){
                        // the dismiss button clicked, and the post creation modal should be hidden too
                        $(modalId).modal("hide");
                        return false;
                    }else if(categoryId === ""){
                        // The confirm button click but without selecting value
                        return false;
                    }else{
                        var category = _.find(categories, {id: parseInt(categoryId)});
                        if(category){
                            var formProperties = {
                                "url": Laravel.apis.posts.store,
                                "method": "POST",
                                "classes": ""
                            };
                            generateCreatePostForm("#" + Constants.CREATE_POST_MODAL_ID + " .modal-body", category, formProperties);
                        }
                    }
                }
            });
            if(dialog){
                dialog.on("hidden.bs.modal", function(e){
                    // ensure we don't accidentally intercept hidden events triggered
                    // by children of the current dialog. We shouldn't anymore now BS
                    // namespaces its events; but still worth doing
                    if (e.target === this) {
                        dialog.remove();
                    }

                    if($('.modal.in').css('display') == 'block'){
                        $('body').addClass('modal-open');
                    }
                });
            }
        }
    });
    $(modalId).modal('show');
};

window.initFormsValidator = function (containerId) {
    $.each($("#" + containerId).find('form'), function (i, element) {
        var el = $(element);
        if (el.data('skip-validator') !== "true") {
            el.validator({
                feedback: {
                    success: 'glyphicon-ok',
                    error: 'glyphicon-remove'
                }
            });
        }
    });
};

window.generateNotification = function (message, type, allowDismiss, delay) {
    if (message) {
        type = type || "success";
        allowDismiss = allowDismiss || true;
        // If delay is set higher than 0 then the notification will auto-close after the delay period is up.
        // keep in mind that delay uses milliseconds so 5000 is 5 seconds.
        delay = delay || 0;
        $.notify({
            message: message
        }, {
            // settings
            element: 'body',
            position: null,
            type: type,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 1055,
            delay: delay,
            timer: 1000,
            url_target: '_blank',
            mouse_over: "pause"
        });
    }
};

window.getPostUrl = function(postId, urlTemplate) {
    var url = undefined;
    postId = parseInt(postId);
    if(!isNaN(postId)){
        urlTemplate = urlTemplate || Laravel.apis.posts.get;
        var compiled = _.template(urlTemplate);
        url = compiled({"postId": postId});
    }
    return url;
};

window.getUserUrl = function(userId, urlTemplate) {
    var url = undefined;
    userId = parseInt(userId);
    if(!isNaN(userId)){
        urlTemplate = urlTemplate || Laravel.apis.users.get;
        var compiled = _.template(urlTemplate);
        url = compiled({"userId": userId});
    }
    return url;
};

window.getCategories = function(callback, params) {
    var categories = Storages.localStorage.get('categories');
    if(!categories){
        $.ajax({
            "url": Laravel.apis.categories.list,
            "method": "GET",
            "success": function(response){
                Storages.localStorage.set("categories", response);
                if(typeof(callback) === "function"){
                    params = params || [];
                    callback.apply(this, params);
                }
            },
            "error": function(response){
                console.log(response);
            }
        });
    }
    return categories;
};

window.getCurrencySymbolHtmlCode = function(currencyCode){
    var htmlCurrencyCode = "";
    switch (currencyCode){
        case "NIS":
            htmlCurrencyCode = '<i class="fa fa-ils" aria-hidden="true"></i>';
            break;
        case "USD":
            htmlCurrencyCode = '<i class="fa fa-usd" aria-hidden="true"></i>';
            break;
    }
    return htmlCurrencyCode;
};

window.getHtmlInputTypeByPropertyType = function(propertyType){
    var htmlInputType = null;
    switch (propertyType) {
        case "STRING":
            htmlInputType = "text";
            break;
        case "NUMBER":
            htmlInputType = "number";
            break;
        case "FLOAT":
            htmlInputType = "text";
            break;
    }
    return htmlInputType;
};

window.generateForm = function(formSchema){
    formSchema = {
        "form": {
            "id": Constants.CREATE_POST_FORM_ID,
            "action": Laravel.apis.posts.store,
            "method": "POST",
            "class": "skip-validation",
            "data": {
                "category-id": "1" // change this
            },
            "submitHandler": null,
            "extraAttributes": "",
            /*
                if form has a file input,
                then the form enctype="multipart/form-data"
                and has-file class will be added to form
                and data-has-file attribute will be added with value 'true' (data-has-file="true")
            */
            "inputs": [{
                "id": "form-1-input-1",
                "name": "form-1-input-1",
                "type": "text",
                "typeCode": "STRING", // if type not one of ["STRING", "NUMBER", "COLOR", "CHECKBOX"] the "type" will be ignored
                "value": "",
                "class": "",
                "placeholder": "this is input 1",
                "required": true,
                "title": "Please fill me!",
                "inputFormGroupClass": "",
                "inputGroupClass": "",
                "inputPrefix": '<i class="fa fa-plus" aria-hidden="true"></i>',
                "inputSuffix": "",
                "extraAttributes": "",
                "label": {
                    "class": "",
                    "text": "Input 1 Label"
                },
                // Params will be used with SELECT/MULTI_SELECT type
                "options": [{"id": "option1", "text": "Option 1", "class": "active"}, {"id": "option2", "text": "Option 2", "class": ""}],
                // if select2Options is null/undefined the select tag will not initialized with select2
                "select2Options": {
                    "minimumResultsForSearch": Infinity,
                    "dir": "rtl",
                    "language": "ar"
                },
                // Params will be used with DATE type
                "datePicketOptions": {
                    "minDate": new Date(),
                    "maxDate": new Date(2018, 1, 1, 0,0,0)
                }
            }]
        }
    };
};

window.generateCreatePostForm = function(containerSelector, category, formProperties) {
    var container = $(containerSelector);
    if(!container || container.length === 0){return;}
    var formId = Constants.CREATE_POST_FORM_ID;
    var renderedProperties = [];
    $.each(category.properties, function(index, propertyObject){
        var valueType = propertyObject.value_type;
        var inputType = getHtmlInputTypeByPropertyType(valueType);
        var inputId = formId + "-" + propertyObject.code;
        var inputTemplateId = undefined;
        var containerTemplateId = undefined;
        var inputTemplateData = undefined;
        var inputTemplateContainerData = undefined;
        if(Constants.BASIC_INPUT_TYPES.indexOf(valueType) > -1){
            inputTemplateId = Constants.BASIC_INPUT_TEMPLATE_ID;
            containerTemplateId = Constants.BASIC_INPUT_CONTAINER_TEMPLATE_ID;
            var extraSettings = propertyObject.extra_settings || undefined;
            var requiredField = false;

            /**
             *
             * properties.type
             * properties.name
             * properties.value
             * properties.classes
             * properties.extraAttributes
             */
            var extraAttributes = "";

            extraAttributes += 'data-property-id="'+ propertyObject.id + '"';

            if(inputId){
                extraAttributes += 'id="' + inputId + '" ';
            }
            if(propertyObject.required === 1){
                requiredField = true;
                extraAttributes += " required ";
            }
            if(valueType === "FLOAT"){
                extraAttributes += ' pattern="' + Constants.REGEX_STRING.FLOAT + '" ';
            }
            var classes = "form-control ";
            var prefixAddon = "";
            var suffixAddon = "";

            if(extraSettings){
                if(extraSettings.hint){
                    extraAttributes += ' placeholder="'+ extraSettings.hint + '" ';
                }
                if(extraSettings.min || typeof(extraSettings.min) === "number"){
                    extraAttributes += ' min="' + extraSettings.min + '" ';
                }
                if(extraSettings.max || typeof(extraSettings.max) === "number"){
                    extraAttributes += ' max="' + extraSettings.max + '" ';
                }
                if(extraSettings.classes){
                    classes += extraSettings.classes;
                }
                if(extraSettings.currency){
                    prefixAddon = getCurrencySymbolHtmlCode(extraSettings.currency);
                }
            }

            inputTemplateData = {
                "type": inputType,
                "name": propertyObject.code,
                "value": extraSettings !== undefined ? extraSettings.default || "": "",
                "classes": classes,
                "extraAttributes": extraAttributes,
                "prefixAddon": prefixAddon,
                "suffixAddon": suffixAddon
            };

            inputTemplateContainerData = {
                "inputId": inputId || "",
                "label": propertyObject.title || propertyObject.code || "",
                "inputHtml": "",
                "helpMessage": extraSettings !== undefined ? extraSettings.help || "": "",
                "helpBlockClasses": "",
                "formGroupClasses": requiredField ? "required": ""
            }
        }

        if(inputTemplateId && containerTemplateId){
            inputTemplateContainerData.inputHtml = _.renderTemplate(inputTemplateId, inputTemplateData);
            var renderedInputContainer = _.renderTemplate(containerTemplateId, inputTemplateContainerData);
            renderedProperties.push(renderedInputContainer);
        }
    });

    /**
     * formAttributes
     * formFields
     */
    if(_.isArray(renderedProperties)&& _.size(renderedProperties) > 0){
        /**
         * Create Submit Button
         */
        var submitButtonAttributes = '';
        submitButtonAttributes += 'class="btn-success btn-block" type="submit"';
        var buttonTemplateId = Constants.BUTTON_TEMPLATE_ID;
        var buttonTemplateData = {
            "attributes": submitButtonAttributes,
            "text": Laravel.strings.create_post_modal.submit_form_button_text,
            'icon': 'fa fa-plus'
        };
        renderedProperties.push(_.renderTemplate(buttonTemplateId, buttonTemplateData));

        var formTemplateId = Constants.FORM_TEMPLATE_ID;
        var formAttributes = "";
        formAttributes += ' id="' + formId + '" ';

        if(formProperties){
            if(formProperties.url){
                formAttributes += ' action=" ' + formProperties.url + '"';
            }
            if(formProperties.method){
                formAttributes += ' method=" ' + formProperties.method + '"';
            }
            if(formProperties.classes){
                formAttributes += ' class=" ' + formProperties.classes + '"';
            }
        }

        formAttributes += 'data-category-id="' + category.id + '"';

        var formData = {
            "formAttributes" : formAttributes,
            "formFields": _.join(renderedProperties, ' ')
        };
        var renderedForm = _.renderTemplate(formTemplateId, formData);
        container.html(renderedForm);
    }
};