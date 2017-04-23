$(document).ready(function () {

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
        initFormsValidator(formsContainer.attr('id'));
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
        initFormsValidator(formsContainer.attr('id'));
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

    $(document).on('click', '#btn-search-posts', function (e) {
        e.preventDefault();
        var searchQuery = $("#input-search-posts").val();
        var postsContainerId = Constants.POSTS_CONTAINER_ID;
        var btn = $(this);
        if (searchQuery) {
            btn.html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
            $.ajax({
                "url": Laravel.apis.posts.list,
                "method": "GET",
                "data": {"query": searchQuery},
                "success": function (response) {
                    createPostsGrid(postsContainerId, response);
                },
                "error": function (response) {
                    console.log(response);
                },
                "complete": function (jqXHR, textStatus) {
                    btn.html('<i class="fa fa-search" aria-hidden="true"></i>');
                }
            });
        }
    });

    $(document).on('keyup', '#input-search-posts', function(e){
        console.log(e.which);

        if(e.which === 13){
            $('#btn-search-posts').trigger('click');
        }else{
            var val = $(this).val();
            if(!val){
                $.ajax({
                    "url": Laravel.apis.posts.list,
                    "method": "GET",
                    "success": function (response) {
                        createPostsGrid(Constants.POSTS_CONTAINER_ID, response);
                    },
                    "error": function (response) {
                        console.log(response);
                    }
                });
            }
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
                    "error": function (response) {
                        console.log(response);
                    }
                });
            });
        }
    });

    $(document).on('change', '#category-filter', function(){
        var val = $(this).val();
        var data = {};
        if(val){data['category'] = val;}
        $.ajax({
            "url": Laravel.apis.posts.list,
            "method": "GET",
            "data": data,
            "success": function (response) {
                var postsContainerId = Constants.POSTS_CONTAINER_ID;
                createPostsGrid(postsContainerId, response);
            },
            "error": function (response) {
                console.log(response);
            },
            "complete": function (jqXHR, textStatus) {}
        });
    });

});
