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
});
