$(document).ready(function () {

    $(document).on('hidden.bs.modal', '.modal', function(e){
        if ($(this).hasClass('auto-destroy') || $(this).hasClass('bootbox')) {
            $(this).off();
            $(this).remove();
            $('body').attr("style", "");
        }

        if($('.modal').css('display') === 'block'){
            $('body').addClass('modal-open');
        }
    });

    $('#btn-show-login-modal').on('click', function (event) {
        event.preventDefault();
        createLoginModal(defaultSetFormHandler);
        var modalId = Constants.LOGIN_MODAL_ID;
        modalId = '#' + modalId;
        var formsContainerId = Constants.LOGIN_MODAL_FORMS_CONTAINER_ID;
        formsContainerId = '#' + formsContainerId;
        var formsContainer = $(formsContainerId);
        formsContainer.data('current-form-code', Constants.LOGIN_FORM_CODE);
        $(modalId).modal('show');
    });

    $('#btn-show-sign-up-modal').on('click', function (event) {
        event.preventDefault();
        createLoginModal(defaultSetFormHandler);
        var modalId = Constants.LOGIN_MODAL_ID;
        modalId = '#' + modalId;
        var formsContainerId = Constants.LOGIN_MODAL_FORMS_CONTAINER_ID;
        formsContainerId = '#' + formsContainerId;
        var formsContainer = $(formsContainerId);
        $(modalId).on('shown.bs.modal', function (e) {
            formsContainer.data('current-form-code', Constants.LOGIN_FORM_CODE);
            $(modalId).trigger('set-form', Constants.REGISTER_FORM_CODE);
        });
        $(modalId).modal('show');
    });

    $(document).on('submit', '#' + Constants.LOGIN_FORM_ID, function (e) {
        if (!e.isDefaultPrevented()) {
            e.preventDefault();
            var form = $(this);
            var url = form.prop('action');
            var method = form.prop('method');
            var data = form.serializeArray().asMap('name', 'value');
            form.find('.form-errors').text('');
            $.ajax({
                "url": url,
                "method": method,
                "data": JSON.stringify(data),
                success: function (response) {
                    // Type: Function( Anything data, String textStatus, jqXHR jqXHR )
                    location.reload();
                },
                error: function (response) {
                    // Type: Function( jqXHR jqXHR, String textStatus, String errorThrown )
                    var responseData = response.responseJSON;
                    if (responseData.hasOwnProperty('username')) {
                        form.find('.form-errors').text(responseData['username']);
                    }
                }
            });
        }
    });

    $(document).on('click', '.btn-view-post, .img-view-post', function(e){
        e.preventDefault();
        var postId = parseInt($(this).data("post-id"));
        var url = getPostUrl(postId);
        if(typeof(postId) === "number" && !isNaN(postId) ){
            createPostModal(url, postId);
        }
    });

    $(document).on('click', ".btn-table-view-post", function(e){
        e.preventDefault();
        $.this = this;
    });

    $(document).on('click', '.btn-show-post-user', function(e){
        e.preventDefault();
        var userId = $(this).data('user-id');
        if(userId){
            var dialog = bootbox.dialog({
                title: Laravel.strings.user_info_modal.modal_title,
                message: '<p><i class="fa fa-spin fa-spinner"></i>' + Laravel.strings.general.loading_message + '</p>',
                className: "auto-destroy"
            });
            dialog.init(function(){
                var url = getUserUrl(userId);
                $.ajax({
                    "url": url,
                    "method": "GET",
                    "success": function (response) {
                        var templateId = Constants.TABLE_TEMPLATE_ID;
                        var data = {
                            "data":{
                                "tdValueClass": "center",
                                "tableRows": [{
                                    "title": Laravel.strings.user_info_modal.user_name_title, "value": response.name || "-"
                                }, {
                                    "title": Laravel.strings.user_info_modal.mobile_number_title, "value": response.phone || "-"
                                }, {
                                    "title": Laravel.strings.user_info_modal.address_title, "value": response.address || "-"
                                }]
                            }
                        };
                        var distSelector = undefined;
                        var append = false;
                        var importJQuery = true;

                        var renderedHtml =  _.renderTemplate(templateId, data, distSelector, append, importJQuery);
                        dialog.find('.bootbox-body').html(renderedHtml);
                    },
                    "error": function(jqXHR){console.log(jqXHR);}
                });
            });
        }
    });

    $(document).on('click', ".btn-show-user-posts", function(e){
        e.preventDefault();
        var userId = $(this).data('user-id');
        var url = getUserPostsUrl(userId);
        $.ajax({
            "url": url,
            "method": "GET",
            "success": function(response){
                var userInfo = response;
                var containerId = Constants.MODALS_CONTAINER_ID;
                var modalTemplateId = Constants.MODAL_TEMPLATE_ID;
                var modalId = Constants.USER_POSTS_MODAL_ID;
                var data = {
                    "modal": {
                        "id": modalId,
                        "class": "auto-destroy",
                        'title': Laravel.strings.user_posts_modal.modal.title + ' ' + userInfo.name,
                        "body": '<table id="user-posts-data-table" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>',
                        "footer": ''
                    }
                };
                _.renderTemplate(modalTemplateId, data, containerId, true);
                var dataSet = [];
                $.each(userInfo.posts, function(index, post){
                    dataSet.push([post.id, post.title, post.category.name, post.created]);
                });

                $('#user-posts-data-table').DataTable( {
                    data: dataSet,
                    "bFilter": true,
                    "bLengthChange": false,
                    "pageLength": 15,
                    columns: [
                        {
                            "title": "id"
                        },
                        { title: Laravel.strings.user_posts_modal.table_columns_titles.title },
                        { title: Laravel.strings.user_posts_modal.table_columns_titles.category },
                        { title: Laravel.strings.user_posts_modal.table_columns_titles.created_at },
                        {
                            data: null,
                            className: "center",
                            defaultContent: ''
                        }
                    ],
                    columnDefs: [
                        {
                            targets: 0,
                            visible: false
                        },
                        {
                            targets: -1,
                            render: function(data, type, row){
                                return '<a href="javascript:void(0);" class="btn-view-post" data-post-id="' + data[0] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>'
                            }
                        }
                    ],
                    language: {
                        "url": Laravel.locale === "ar" ? null: Laravel.assets.dataTables.lang.default,
                        "sProcessing":   "جارٍ التحميل...",
                        "sLengthMenu":   "أظهر _MENU_ مدخلات",
                        "sZeroRecords":  "لا يوجد اعلانات",
                        "sInfo":         "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                        "sInfoEmpty":    "لا يوجد اعلانات",
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
