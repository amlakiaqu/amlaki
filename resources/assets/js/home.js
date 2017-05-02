$(document).ready(function () {
    var postsContainerId = Constants.POSTS_CONTAINER_ID;
    var postsContainer = $("#{0}.init".format(postsContainerId));
    $.postsContainer = postsContainer;
    if (postsContainer[0]) {
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
    }
    getCategories();
    viewPost(getUrlParameter('post_id'));
});
