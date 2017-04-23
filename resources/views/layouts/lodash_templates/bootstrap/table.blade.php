<script type="text/template" id="table-template">
    <table class="table table-striped <% print(data.class); %>">
        <% if(data.tableHeaders) { %>
            <thead>
                <tr>
                    <% jq.each(data.tableHeaders, function(index, headerText) { %>
                    <th><% print(headerText); %></th>
                    <% }); %>
                </tr>
            </thead>
        <% } %>
        <tbody>
        <% if(data.tableRows) { %>
            <% jq.each(data.tableRows, function(index, row) { %>
            <tr>
                <td class="<% print(data.tdTitleClass); %>"> <% print(row.title); %> </td>
                <td class="<% print(data.tdValueClass); %>"> <% print(row.value); %> </td>
            </tr>
            <% }); %>
        <% } else { %>
            <tr style="text-align: center;">
                <% print(data.noDataMessage || '-'); %>
            </tr>
        <% } %>
        </tbody>
    </table>
</script>