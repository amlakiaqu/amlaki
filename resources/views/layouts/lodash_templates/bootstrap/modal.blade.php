<script type="text/template" charset="utf-8" id="bootstrap-modal-template">
  <div class="modal fade" tabindex="-1" role="dialog" id="<% print(modal.id); %>" data-backdrop="<% print(modal.backdrop || true); %>" data-keyboard="<% print(modal.keyboard || true); %>" aria-labelledby="<% print(modal.id + 'Label'); %>">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="<% print(modal.id + 'Label'); %>"><% print(modal.title); %></h4>
        </div>
        <div class="modal-body">
          <% print(modal.body); %>
        </div>
        <div class="modal-footer">
          <% print(modal.footer) %>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</script>