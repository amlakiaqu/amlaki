<script type="text/template" charset="utf-8" id="bootstrap-modal-template">
  <div class="modal fade <% print(modal.class); %>" tabindex="-1" role="dialog" id="<% print(modal.id); %>" data-backdrop="<% print(modal.backdrop || true); %>" data-keyboard="<% print(modal.keyboard || true); %>" aria-labelledby="<% print(modal.id + 'Label'); %>">
    <% if(modal.large) { %>
    <div class="modal-dialog modal-lg" role="document">
    <% } else { %>
    <div class="modal-dialog" role="document">
    <% } %>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="<% print(modal.id + 'Label'); %>"><% print(modal.title); %></h4>
        </div>
        <div class="modal-body">
          <% print(modal.body); %>
        </div>
        <% if(!modal.no_footer) {%>
        <div class="modal-footer">
          <% print(modal.footer) %>
          <% if(modal.includeDefaultButtons) { %>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
          <% } %>
        </div>
        <% } %>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</script>
