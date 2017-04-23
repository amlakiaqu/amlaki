<script type="text/template" id="user-info-modal-body-template">
    <table class="table table-striped">
        <% if(data.userInfo) { %>
            <% jq.each(data.userInfo, function(index, infoObject) { %>
                <tr>
                    <td> <% print(infoObject.title); %> </td>
                    <td style="text-align: center;"> <% print(infoObject.value); %> </td>
                </tr>
            <% }); %>
        <% } %>
    </table>
</script>