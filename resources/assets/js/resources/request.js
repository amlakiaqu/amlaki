$(document).ready(function(){
    $(document).on('click', '.btn-delete-request', function(e){
        e.preventDefault();
        var btn = $(this);
        var requestId =  btn.data('request-id');
        bootbox.confirm({
            title: Laravel.strings.delete_request_confirm_modal.title,
            message: Laravel.strings.delete_request_confirm_modal.message,
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> ' + Laravel.strings.delete_request_confirm_modal.btn.cancel
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> ' + Laravel.strings.delete_request_confirm_modal.btn.confirm,
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result === true){
                    var url = renderUrl(requestId, Laravel.apis.requests.delete);
                    var method = "DELETE";
                    $.ajax({
                        "url": url,
                        "method": method,
                        "success": function(response){
                            var message = response.message;
                            var type = "success";
                            var allowDismiss = true;
                            var delay = 2500;
                            generateNotification(message, type, allowDismiss, delay);
                            $('.modal').modal("hide");
                        },
                        "error": function(jqXHR){console.log(jqXHR);}
                    });
                }
            }
        });
    });

    $(document).on('click', ".btn-show-user-requests", function(e){
        e.preventDefault();
        var userId = $(this).data('user-id');
        var url = renderUrl(userId, Laravel.apis.users.getRequests);
        $.ajax({
            "url": url,
            "method": "GET",
            "success": function(response){
                var userInfo = response;
                var containerId = Constants.MODALS_CONTAINER_ID;
                var modalTemplateId = Constants.MODAL_TEMPLATE_ID;
                var modalId = Constants.USER_REQUESTS_MODAL_ID;
                var data = {
                    "modal": {
                        "id": modalId,
                        "class": "auto-destroy",
                        'title': Laravel.strings.user_requests_modal.modal.title + ' ' + userInfo.name,
                        "body": '<table id="user-requests-data-table" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>',
                        "footer": ''
                    }
                };
                _.renderTemplate(modalTemplateId, data, containerId, true);
                var dataSet = [];
                $.each(userInfo.requests, function(index, request){
                    var propertiesDLHtml = "<dl>";
                    $.each(request.properties, function(index, property){
                        propertiesDLHtml += '<dt>' + property.title + '</dt>';
                        var value = property.value;
                        if(value === "ALL"){
                            value = Laravel.strings.user_requests.types.ALL;
                        } else if (property.value_type === "NUMBER"){
                            value = parseInt(value).toLocaleString('en');
                            if(property.extra_settings.currency){
                                var currencyCode = property.extra_settings.currency;
                                var currency = getCurrencySymbolHtmlCode(currencyCode);
                                if(currency){
                                    value = currency + ' ' + value;
                                }
                            }
                        }
                        propertiesDLHtml += '<dd>' + value + '</dd>';
                    });
                    propertiesDLHtml += "</dl>";
                    dataSet.push([request.id, request.category.name, propertiesDLHtml]);
                });

                $('#user-requests-data-table').DataTable( {
                    data: dataSet,
                    "bFilter": false,
                    "bLengthChange": false,
                    "pageLength": 15,
                    columns: [
                        { "title": "id" },
                        { "title": Laravel.strings.user_requests_modal.table_columns_titles.category},
                        { "title": Laravel.strings.user_requests_modal.table_columns_titles.properties},
                        {
                            data: null,
                            defaultContent: ''
                        }
                    ],
                    columnDefs: [
                        { "targets": [0,1,2,3], "orderable": false },
                        { "sClass": "center", "aTargets": [ 1, 3 ] },
                        {
                            targets: 0,
                            visible: false
                        },
                        {
                            targets: -1,
                            render: function(data, type, row){
                                return '<a href="javascript:void(0);" class="btn btn-warning btn-edit-request" data-request-id="' + data[0] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>' +
                                    ' <a href="javascript:void(0);" class="btn btn-danger btn-delete-request" data-request-id="' + data[0] + '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                            }
                        }
                    ],
                    language: {
                        "url": Laravel.locale === "ar" ? null: Laravel.assets.dataTables.lang.default,
                        "sProcessing":   "جارٍ التحميل...",
                        "sLengthMenu":   "أظهر _MENU_ مدخلات",
                        "sZeroRecords":  "لا يوجد متابعات",
                        "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                        "sInfoEmpty":    "لا يوجد متابعات",
                        "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                        "sInfoPostFix":  "",
                        "sSearch":       "ابحث:",
                        "sUrl":          "",
                        "oPaginate": {
                            "sFirst":    "الأول",
                            "sPrevious": "السابق",
                            "sNext":     "التالي",
                            "sLast":     "الأخير"
                        }
                    }
                } );
                $("#" + modalId).modal('show');
            },
            "error": function(jqXHR){console.log(jqXHR);}
        });
    });

    $(document).on('click', "#btn-add-request", function(){
        function create_request_handler(category) {
            var submitButtonAttributes = '';
            submitButtonAttributes += 'class="btn btn-success btn-block" type="submit"';
            var buttonTemplateData = {
                "attributes": submitButtonAttributes,
                "text": Laravel.strings.create_request_modal.btn.submit,
                'icon': 'fa fa-plus'
            };
            var formAttributes = "";
            formAttributes += 'data-category-id="{0}" '.format(category.id);
            var formProperties = {
                "id": Constants.CREATE_REQUEST_FORM_ID,
                "url": Laravel.apis.requests.store,
                "method": "POST",
                "classes": "",
                "button_template_data": buttonTemplateData,
                "extra_attrs": formAttributes,
                "skip_required": true
            };

            var schema = [];
            schema.push({
                "code": "category_id",
                "value_type": "HIDDEN",
                "title": "",
                "required": 1,
                "id": 0,
                "extra_settings": {
                    "default": category.id,
                    "hint": ""
                }
            });

            if (category && category.properties) {
                $.each(category.properties, function (index, property) {
                    schema.push(property);
                });
            }

            var renderedForm = generateForm(schema, formProperties);
            var modalId = Constants.CREATE_REQUEST_MODAL_ID;
            var templateId = Constants.MODAL_TEMPLATE_ID;
            var helpMessageHtml = '<p class="bg-info"><i class="fa fa-info" aria-hidden="true" style="margin-right: 6px;font-size: 16px;margin-left: 5px;"></i> ' + Laravel.strings.create_request_modal.help_message + '</p> ';
            var data = {
                "modal": {
                    "id": modalId,
                    "title": Laravel.strings.create_request_modal.modal_title,
                    "class": "auto-destroy",
                    "backdrop": "static",
                    "keyboard": "false",
                    "body": helpMessageHtml + renderedForm,
                    "no_footer": true,
                    "large": false
                }
            };
            var distSelector = "#" + Constants.MODALS_CONTAINER_ID;
            var append = true;
            _.renderTemplate(templateId, data, distSelector, append);
            $(".modal").modal("hide");
            $("#" + modalId).modal("show");
        }

        var categoriesOptions = [{"text": Laravel.strings.post_category_type_modal.default_item_text, "value": ""}];
        $.each(Storages.localStorage.get('categories'), function(index, category){
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
                    return false;
                }else if(categoryId === ""){
                    // The confirm button click but without selecting value
                    return false;
                }else{
                    var category = _.find(Storages.localStorage.get('categories'), {id: parseInt(categoryId)});
                    if(category){
                        create_request_handler(category);
                    }
                }
            }
        });
    });

    $(document).on('click', ".btn-edit-request", function(e){
        e.preventDefault();
        var requestId = $(this).data('request-id');
        var editRequestHandler = function(response){
            var category = response.category;
            var submitButtonAttributes = '';
            submitButtonAttributes += 'class="btn btn-warning btn-block" type="submit"';
            var buttonTemplateData = {
                "attributes": submitButtonAttributes,
                "text": Laravel.strings.create_request_modal.btn.edit,
                'icon': 'fa fa-edit'
            };
            var formAttributes = "";
            formAttributes += 'data-category-id="{0}" '.format(category.id);
            formAttributes += 'data-request-id="{0}" '.format(requestId);
            var formProperties = {
                "id": Constants.UPDATE_REQUEST_FORM_ID,
                "url": Laravel.apis.requests.update.format(requestId),
                "method": "PUT",
                "classes": "",
                "button_template_data": buttonTemplateData,
                "extra_attrs": formAttributes,
                "skip_required": true
            };

            var schema = [];
            schema.push({
                "code": "category_id",
                "value_type": "HIDDEN",
                "title": "",
                "required": 1,
                "id": 0,
                "extra_settings": {
                    "default": category.id,
                    "hint": ""
                }
            });
            var requestProperties = response.properties;
            console.log('requestProperties', requestProperties);
            if (category && category.properties) {
                $.each(category.properties, function (index, property) {
                    var requestProperty = _.filter(requestProperties, function(o){return o.id === property.id})[0];
                    console.log('  -- requestProperty', requestProperty);
                    if(requestProperty){
                        property.input_value = requestProperty.input_value;
                    }
                    schema.push(property);
                });
            }
            console.log('schema', schema);

            var renderedForm = generateForm(schema, formProperties);
            var modalId = Constants.UPDATE_REQUEST_MODAL_ID;
            var templateId = Constants.MODAL_TEMPLATE_ID;
            var helpMessageHtml = '<p class="bg-info"><i class="fa fa-info" aria-hidden="true" style="margin-right: 6px;font-size: 16px;margin-left: 5px;"></i> ' + Laravel.strings.create_request_modal.help_message + '</p> ';
            var data = {
                "modal": {
                    "id": modalId,
                    "title": Laravel.strings.create_request_modal.modal_title,
                    "class": "auto-destroy",
                    "backdrop": "static",
                    "keyboard": "false",
                    "body": helpMessageHtml + renderedForm,
                    "no_footer": true,
                    "large": false
                }
            };
            var distSelector = "#" + Constants.MODALS_CONTAINER_ID;
            var append = true;
            _.renderTemplate(templateId, data, distSelector, append);
            $(".modal").modal("hide");
            $("#" + modalId).modal("show");
        };

        $.ajax({
            "url": Laravel.apis.requests.get.format(requestId),
            "method": "GET",
            "success": editRequestHandler,
            "error": function(jqXHR){console.log(jqXHR)}
        });
    });

    $(document).on('submit', '#{0}'.format(Constants.CREATE_REQUEST_FORM_ID) , function(e){
        e.preventDefault();
        var data = $(this).serializeArray().asMap('name', 'value');
        $.each(data, function(key, value){
            if(value === ""){
                data[key] = null;
            }
        });
        $.ajax({
            "url": Laravel.apis.requests.store,
            "method": "POST",
            "dataType": 'json',
            "data": JSON.stringify(data),
            "success": function(response){
                var message = response.message;
                var type = "success";
                var allowDismiss = true;
                var delay = 2500;
                generateNotification(message, type, allowDismiss, delay);
                $('.modal').modal('hide');
            },
            "error": function(jqXHR){console.log(jqXHR)}
        });
    });

    $(document).on('submit', '#{0}'.format(Constants.UPDATE_REQUEST_FORM_ID), function(e){
        e.preventDefault();
        var requestId = $(this).data('request-id');
        var data = $(this).serializeArray().asMap('name', 'value');
        $.each(data, function(key, value){
            if(value === ""){
                data[key] = null;
            }
        });
        $.ajax({
            "url": Laravel.apis.requests.update.format(requestId),
            "method": "PUT",
            "dataType": 'json',
            "data": JSON.stringify(data),
            "success": function(response){
                var message = response.message;
                var type = "success";
                var allowDismiss = true;
                var delay = 2500;
                generateNotification(message, type, allowDismiss, delay);
                $('.modal').modal('hide');
            },
            "error": function(jqXHR){console.log(jqXHR)}
        });
    });

});
