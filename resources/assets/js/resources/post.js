$(document).ready(function(){

    window.viewPost = function(postId){
        postId = parseInt(postId);
        if(typeof(postId) === "number" && !isNaN(postId) ){
            var url = getPostUrl(postId);
            createPostModal(url, postId);
        }
    };

    $(document).on('click', '#btn-create-post', function(e){
        e.preventDefault();
        createPostCreateModal();
    });

    $(document).on('submit', "#" + Constants.CREATE_POST_FORM_ID, function(e){
        e.preventDefault();
        var form = $(this);
        var formUrl = form.attr("action");
        var formMethod = form.attr("method").trim();
        var formData = form.serializeArray();
        var formDataObject = formData.asMap("name", "value");
        formDataObject["category_id"] = form.data('category-id');
        $.ajax({
            "url": formUrl,
            "method": formMethod,
            "data": JSON.stringify(formDataObject),
            "success": function(response){
                var message = response.message;
                var type = "success";
                var allowDismiss = true;
                var delay = 2500;
                generateNotification(message, type, allowDismiss, delay);
                hideModalsAndRefreshPosts();
            },
            "error": function(jqXHR){console.log(jqXHR);}
        });
    });

    $(document).on('submit', "#" + Constants.EDIT_POST_FORM_ID, function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr("action");
        var method = form.attr("method").trim();
        var data = form.serializeArray().asMap("name", "value");

        $.ajax({
            "url": url,
            "method": method,
            "data": JSON.stringify(data),
            "success": function(response) {
                var message = response.message;
                var type = "success";
                var allowDismiss = true;
                var delay = 2500;
                generateNotification(message, type, allowDismiss, delay);
                hideModalsAndRefreshPosts();
            },
            "error": function(jqXHR){console.log(jqXHR);}
        });

    });
});