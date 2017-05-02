<script type="text/template" id="input-basic-template">
    <% if(prefixAddon || suffixAddon){print('<div class="input-group">');} %>
        <% if(prefixAddon){print('<div class="input-group-addon">' + prefixAddon + '</div>');} %>
        <input type="<% print(type); %>" class="<% print(classes); %>" name="<% print(name); %>" <% if(value) { %> value="<% print(value); %>" <% } %> <% print(extraAttributes); %> />
        <% if(suffixAddon){print('<div class="input-group-addon">' + suffixAddon + '</div>');} %>
    <% if(prefixAddon || suffixAddon){print('</div>');} %>
</script>
<script type="text/template" id="input-basic-container-template">
<div class="form-group <% print(formGroupClasses); %>">
    <label for="<% print(inputId); %>"><% print(label) %></label>
    <% print(inputHtml); %>
    <p class="help-block <% print(helpBlockClasses); %>"><% print(helpMessage); %></p>
</div>
</script>



<script type="text/template" id="input-color-template"></script>
<script type="text/template" id="input-range-template"></script>
<script type="text/template" id="input-select-template"></script>
<script type="text/template" id="input-multi-select-template"></script>
<script type="text/template" id="input-media-template"></script>
<script type="text/template" id="input-radio-template"></script>
<script type="text/template" id="input-multi-select-checkbox-template"></script>
<script type="text/template" id="input-multi-date-template"></script>
