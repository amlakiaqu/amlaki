$(document).ready(function(){
  var postsContainerId = Constants.POSTS_CONTAINER_ID;
  var postsContainer = $('#' + postsContainerId + '.init');
  if(postsContainer[0]){
    // createPostsGrid(postsContainerId, posts);
    $.ajax({
      "url": Laravel.apis.posts.list,
      "method": "GET",
      "success": function(response){
        createPostsGrid(postsContainerId, response);
      },
      "error": function(response){
        console.log(response);
      }
    });
  }
    getCategories();
});
