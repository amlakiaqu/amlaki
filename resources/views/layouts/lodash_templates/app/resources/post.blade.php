<script type="text/template" charset="utf-8" id="post-card-template">
    <div class="col-md-3 col-sm-6 post">
    <span class="thumbnail">
      <img class="img-view-post clickable" src="<% print(post.image.url); %>" alt="<% print(post.image.alt); %>" data-post-id="<% print(post.id); %>">
      <h4><% print(post.title); %></h4>
      <hr class="line">
      <h4> {{ __('Published By') }}: </h4> <a href="javascript:void(0)" class="btn-show-post-user"
                                              data-user-id="<% print(post.user.id); %>"><p class="post-author-name"> <% print(post.user.name); %> </p></a>
      <hr class="line">
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <p class="price"><span class="currency-icon">&#8362;</span> <% print(post.price); %> </p>
        </div>
        <div class="col-md-6 col-sm-6">
          <button class="btn btn-primary pull-right flip btn-view-post"
                  data-post-id="<% print(post.id); %>"> {{ __('View Details') }} </button>
        </div>
      </div>
    </span>
    </div>
</script>
<script charset="utf-8" type="text/template" id="create-post-modal-template">
    <div class="modal fade <% print(modal.class); %>" tabindex="-1" role="dialog" id="<% print(modal.id); %>"
         data-backdrop="<% print(modal.backdrop || true); %>" data-keyboard="<% print(modal.keyboard || true); %>"
         aria-labelledby="<% print(modal.id + 'Label'); %>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="<% print(modal.id + 'Label'); %>">{{ __("Add New Post")  }}</h4>
                </div>
                <div class="modal-body"></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</script>
<script type="text/template" id="post-info-modal-body-template">

</script>
