Array.prototype.asMap = function (name, value) {
    return _.reduce(this, function(acc, item) {
        acc[item[name]] = item[value];
        return acc;
    }, {});
};

$( document ).ajaxSend(function( event, request, settings ) {
  request.setRequestHeader('X-CSRF-Token', Laravel.csrfToken);
  request.setRequestHeader('Authorization', 'Bearer ' + Laravel.apiToken);
  request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  request.setRequestHeader('Content-Type', 'application/json');
  request.setRequestHeader('Accept', 'application/json');
  request.setRequestHeader('Accept-Language', Laravel.locale || 'en-US,en;q=0.8');
});

$( document ).ajaxStart(function() {
    $.smkProgressBar({
        element:'body',
        status:'start',
        bgColor: 'rgba(0,0,0,0)',
        barColor: '#3097D1',
        content: ''
    });
});

$( document ).ajaxComplete(function() {
    $.smkProgressBar({
        status:'end'
    });
});

$(document).ready(function(){
    // initialize all tooltips
    $('[data-toggle="tooltip"]').tooltip();

    bootbox.setDefaults({
        local: Laravel.locale
    });

    // Set Default Settings of DataTables
    $.extend( true, $.fn.dataTable.defaults, {
        "searching": true,
        "ordering": true,
        "language": {
            "url": Laravel.assets.dataTables.lang.default
        }
    } );
});


