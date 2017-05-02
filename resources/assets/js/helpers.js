window.hideModalsAndRefreshPosts = function(){
    $(".modal").each(function(index, element){
        $(element).modal("hide");
    });
    var postsContainerId = Constants.POSTS_CONTAINER_ID;
    $.ajax({
        "url": Laravel.apis.posts.list,
        "method": "GET",
        "success": function (response) {
            createPostsGrid(postsContainerId, response);
        },
        "error": function (response) {
            console.log(response);
        }
    });
};

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
                                                var delay = 2500;
                                                generateNotification(message, type, allowDismiss, delay);
                                                hideModalsAndRefreshPosts();

                                            },
                                            "error": function (response) {
                                                console.log(response);
                                                if(response.status === 401){
                                                    var responseData = response.responseJSON;
                                                    var message = responseData.message;
                                                    var type = "danger";
                                                    var allowDismiss = true;
                                                    var delay = 0;
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
                            var postId = response.id;
                            var category = response.category;
                            var submitButtonAttributes = '';
                            submitButtonAttributes += 'class="btn btn-warning btn-block" type="submit"';
                            var buttonTemplateData = {
                                "attributes": submitButtonAttributes,
                                "text": Laravel.strings.edit_post_modal.submit_form_button_text,
                                'icon': 'fa fa-edit'
                            };
                            var formAttributes = "";
                            formAttributes += 'data-category-id="' + category.id + '" data-post-id="' + postId+ '" ';
                            var formProperties = {
                                "id": Constants.EDIT_POST_FORM_ID,
                                "url": renderUrl(postId, Laravel.apis.posts.update),
                                "method": "PUT",
                                "classes": "",
                                "button_template_data": buttonTemplateData,
                                "extra_attrs": formAttributes
                            };

                            var schema = [];
                            schema.push({
                                "code": "id",
                                "value_type": "HIDDEN",
                                "title": "",
                                "required": 1,
                                "id": 0,
                                "extra_settings": {
                                    "default": response.id,
                                    "hint": ""
                                }
                            });
                            schema.push({
                                "code": "TITLE",
                                "value_type": "STRING",
                                "title": Laravel.strings.create_post_modal.form.title_field_title,
                                "required": 1,
                                "id": 0,
                                "extra_settings": {
                                    "default": response.title,
                                    "hint": Laravel.strings.create_post_modal.form.title_field_hint,
                                    "data": {
                                        "post-model-property": "true"
                                    }
                                }
                            });
                            if(category && category.properties){
                                $.each(category.properties, function(index, property){
                                    var postProperty = _.filter(response.properties || [], function(o) { return o.code === property.code; });
                                    var value = undefined;
                                    if(postProperty && postProperty.length === 1){
                                        postProperty = postProperty[0];
                                        value = postProperty.value;
                                    }
                                    if(value){
                                        if(!property.extra_settings){property.extra_settings = {};}
                                        property.extra_settings.default = value;
                                    }
                                    schema.push(property);
                                });
                            }
                            var renderedForm = generateForm(schema, formProperties);
                            var modalId = Constants.EDIT_POST_MODAL_ID;
                            var templateId = Constants.MODAL_TEMPLATE_ID;
                            var data = {
                                "modal": {
                                    "id": modalId,
                                    "title": Laravel.strings.edit_post_modal.modal_title + ' "' + response.title + '" ',
                                    "class": "auto-destroy",
                                    "backdrop": "static",
                                    "keyboard": "false",
                                    "body": renderedForm,
                                    "no_footer": true,
                                    "large": false
                                }
                            };
                            var distSelector = "#" + Constants.MODALS_CONTAINER_ID;
                            var append = true;
                            _.renderTemplate(templateId, data, distSelector, append);
                            $(".modal").modal("hide");
                            $("#" + modalId).modal("show");
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

            var postImages = [];
            postImages.push(response.image);
            if(response.medias && _.isArray(response.medias) && response.medias.length > 0){
                $.each(response.medias, function(index, mediaObject){
                    if(mediaObject.type === "IMAGE"){
                        postImages.push(mediaObject.media_url);
                    }
                });
            }

            var imagesHtml = '<div class="post-image-container row">';
            var imageTemplate = '<div class="{imageContainerClass}"><img src="{imageUrl}" style="width: 100%;height: 100%;"></div>';
            if(postImages.length === 1){
                imagesHtml += imageTemplate.format({"imageContainerClass": "col-xs-8 col-xs-offset-2", "imageUrl": postImages[0]});
            }else if(postImages.length === 2){
                $.each(postImages, function(index, imageUrl){
                    imagesHtml += imageTemplate.format({"imageContainerClass": "col-xs-6", "imageUrl": imageUrl});
                });
            }else if(postImages.length >= 3){
                $.each(_.range(0, 3), function(index){
                    imagesHtml += imageTemplate.format({"imageContainerClass": "col-xs-4", "imageUrl": postImages[index]});
                });
            }
            imagesHtml += '</div>';
            var shareUrl = Laravel.pages.home + "?post_id=" + postId;
            var shareButtonId = 'btn-share-post-' + postId;
            options["message"] = imagesHtml + _.renderTemplate(templateId, {"data": data}, distSelector, append, importJQuery) +
                '<div style="text-align: left;"><input style="position: absolute;top: -100%;" id="{id}-target-input" value="{shareUrl}"> <button class="btn btn-link {id}" data-clipboard-target="#{id}-target-input"> <i class="fa fa-share-alt" aria-hidden="true"></i> مشاركة</button> </button></div>'.format({'id': shareButtonId, 'shareUrl': shareUrl});
            options["className"] = "post-info-modal";
            var dialog = bootbox.dialog(options);
            dialog.attr("data-post-id", response.id);
            var clipboard = null;
            $('.post-info-modal').on('shown.bs.modal', function(){
                clipboard = new Clipboard('.' + shareButtonId, {});
                clipboard.on('success', function(e) {
                    e.clearSelection();
                    var message = Laravel.strings.general.copy_to_clipboard_success;
                    var type = "success";
                    var allowDismiss = true;
                    var delay = 2500;
                    generateNotification(message, type, allowDismiss, delay);
                });

                clipboard.on('error', function(e){
                    var message = Laravel.strings.general.copy_to_clipboard_fail;
                    $('#' + shareButtonId + '-target-input').attr('style', '');
                    var type = "danger";
                    var allowDismiss = true;
                    var delay = 3500;
                    generateNotification(message, type, allowDismiss, delay);
                });
            });

            $('.post-info-modal').on('hide.bs.modal', function () {
                if(clipboard){
                    clipboard.destroy();
                }
            });

            // var clipboard = new Clipboard('#{0}'.format(shareButtonId));


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
            "body": "",
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

            bootbox.prompt({
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
        }
    });
    $(modalId).modal('show');
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

window.renderUrl = function(id, urlTemplate){
    var url = undefined;
    if(urlTemplate){
        var compiled = _.template(urlTemplate);
        url = compiled({"id": id});
    }
    return url;
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

window.getUserPostsUrl = function(userId, urlTemplate) {
    var url = undefined;
    userId = parseInt(userId);
    if(!isNaN(userId)){
        urlTemplate = urlTemplate || Laravel.apis.users.getPosts;
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
        case "DATE":
            htmlInputType = "text";
            break;
        case "HIDDEN":
            htmlInputType = "hidden";
            break;
    }
    return htmlInputType;
};

window.generateCreatePostForm = function(containerSelector, category, formProperties) {
    var container = $(containerSelector);
    if(!container || container.length === 0){return;}
    var formId = Constants.CREATE_POST_FORM_ID;
    var renderedProperties = [];
    var properties = [];
    properties.push({
        "code": "TITLE",
        "value_type": "STRING",
        "title": Laravel.strings.create_post_modal.form.title_field_title,
        "required": 1,
        "id": 0,
        "extra_settings": {
            "hint": Laravel.strings.create_post_modal.form.title_field_hint,
            "data": {
                "post-model-property": "true"
            }
        }
    });
    properties = properties.concat(category.properties);
    $.properties = properties;
    $.each(properties, function(index, propertyObject){
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
            var data = "";
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
                if(extraSettings.data){
                    $.each(extraSettings.data, function(key, value){
                        data += ' data-' + key + '="' + value + '" ';
                    });
                }
                if(extraSettings.currency){
                    prefixAddon = getCurrencySymbolHtmlCode(extraSettings.currency);
                }
            }

            if(data && data.length > 0){
                extraAttributes += " " + data + " ";
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
        var postMediaImagesTemplate = "";
        if(Laravel.config.postMediaImageCount > 0){
            $.each(_.range(1, Laravel.config.postMediaImageCount + 1), function(index, count){
                var required = "";
                var inputName = Laravel.config.postMediaImageNamingTemplate.format(count);
                if(Laravel.config.postMediaImageRequiredCount <= count){
                    required = 'required="required"';
                }
                postMediaImagesTemplate += '<div class="image-file-input-container" ><input type="file" class="filestyle" name="{inputName}" accept="image/*" {required}></div>'.
                format({
                    "required": required,
                    "inputName": inputName
                });
            });
        }

        if(postMediaImagesTemplate !== "") {
            postMediaImagesTemplate = '<span class="image-section-help-message">' + Laravel.strings.create_post_modal.form.image_required_help_text + '</span>' + postMediaImagesTemplate;
            postMediaImagesTemplate = '<label>' + Laravel.strings.create_post_modal.form.image_group_label + '</label>' + postMediaImagesTemplate;
        }

        renderedProperties.push(postMediaImagesTemplate);

        /**
         * Create Submit Button
         */
        var submitButtonAttributes = '';
        submitButtonAttributes += 'class="btn btn-success btn-block" type="submit"';
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

        // Initialize the 'filestyle' file inputs
        $('#' + formId).find('input[type="file"].filestyle').filestyle({
            "buttonText": " " + Laravel.strings.create_post_modal.form.file_image_input_button_text,
            "buttonName": "btn-primary",
            "iconName": "glyphicon glyphicon-picture",
            "placeholder": ""
        });
    }
};

window.generateForm = function(schema, formProperties) {
    var formId = formProperties.id;
    var skipRequired = formProperties.skip_required || false;
    var renderedProperties = [];
    $.each(schema, function(index, propertyObject){
        var valueType = propertyObject.value_type;
        var inputType = getHtmlInputTypeByPropertyType(valueType);
        var inputId = formId + "-" + propertyObject.code;
        var inputValue = propertyObject.input_value || undefined;
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
            if(propertyObject.required === 1 && !skipRequired){
                requiredField = true;
                extraAttributes += " required ";
            }
            // if(inputValue){
            //     extraAttributes += ' value="' + inputValue + '" ';
            // }
            if(valueType === "FLOAT"){
                extraAttributes += ' pattern="' + Constants.REGEX_STRING.FLOAT + '" ';
            }
            var classes = "form-control ";
            var data = "";
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
                if(extraSettings.data){
                    $.each(extraSettings.data, function(key, value){
                        data += ' data-' + key + '="' + value + '" ';
                    });
                }
                if(extraSettings.currency){
                    prefixAddon = getCurrencySymbolHtmlCode(extraSettings.currency);
                }
            }

            if(data && data.length > 0){
                extraAttributes += " " + data + " ";
            }

            inputTemplateData = {
                "type": inputType,
                "name": propertyObject.code,
                "value": extraSettings !== undefined ? extraSettings.default || inputValue: "",
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
            if(valueType !== "HIDDEN"){
                var renderedInputContainer = _.renderTemplate(containerTemplateId, inputTemplateContainerData);
            }else{
                renderedInputContainer = inputTemplateContainerData.inputHtml;
            }
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

        var buttonTemplateId = Constants.BUTTON_TEMPLATE_ID;
        var buttonTemplateData = formProperties.button_template_data;
        if(buttonTemplateData && _.size(buttonTemplateData) > 0){
            renderedProperties.push(_.renderTemplate(buttonTemplateId, buttonTemplateData));
        }

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
        if(formProperties.extra_attrs){
            formAttributes += formProperties.extra_attrs;
        }

        var formData = {
            "formAttributes" : formAttributes,
            "formFields": _.join(renderedProperties, ' ')
        };

        return _.renderTemplate(formTemplateId, formData);;
    }
};

window.generateFilterFields = function(category){
    var filterContainerId = Constants.SIDEBAR_FILTERS_CONTAINER_ID;
    console.log('category', category);
    if(!category){
        $("#" + filterContainerId).html('');
    }else{
        var html = "";
        $.each(category.properties, function(index, propertyObject){
            var inputTemplate = '<div class="form-group"><label for="{id}" class="control-label">{label}</label><div class=""><input type="{type}" class="form-control {class}" id="{id}" placeholder="{placeholder}" data-property-code="{propertyCode}"></div></div>';
            var inputTemplateData = {
                "id": "FILTER_" + propertyObject["code"],
                "class": 'filter-input',
                "label": propertyObject["title"],
                "type": "text",
                "placeholder": propertyObject["hint"] || propertyObject["title"],
                "propertyCode": propertyObject["code"]
            };
            html += inputTemplate.format(inputTemplateData);
        });
        $("#" + filterContainerId).html(html);
    }
};