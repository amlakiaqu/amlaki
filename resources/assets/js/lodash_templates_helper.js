$(document).ready(function () {
    /**
     * Lodash Templating Helper Function Implementation
     * this method compile and append html to dist element
     * Note: JQuery required
     */

    _.renderTemplate = function (templateId, data, distSelector, append, importJQuery) {
        append = append || false;
        importJQuery = importJQuery || false;
        if (templateId.indexOf("#") < 0) {
            templateId = "#" + templateId;
        }
        var templateHtml = $(templateId).text();
        var compiledTemplate = undefined;
        if(importJQuery){
            compiledTemplate = _.template(templateHtml, { 'imports': { 'jq': jQuery } });
        }else{
            compiledTemplate = _.template(templateHtml);
        }
        var result = compiledTemplate(data);
        if (distSelector) {
            if (distSelector.indexOf("#") < 0) {
                distSelector = "#" + distSelector;
            }
            if (append) {
                $(distSelector).append(result);
            } else {
                $(distSelector).html(result);
            }
        }
        return result;
    }
});
