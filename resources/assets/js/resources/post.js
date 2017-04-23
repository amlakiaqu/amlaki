$(document).ready(function(){

    $(document).on('click', '#btn-create-post', function(e){
        e.preventDefault();
        createPostCreateModal();
    });

    $(document).on('submit', "#" + Constants.CREATE_POST_FORM_ID, function(e){
        e.preventDefault();
        var formData = $(this).serializeArray();
        console.log(formData);

    });
});