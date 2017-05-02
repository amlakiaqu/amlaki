$(document).ready(function () {
    function postFilterWatchCallBack(){
        $.ajax({
            "url": Laravel.apis.posts.list,
            "method": "GET",
            "data": $.postFilters,
            "success": function (response) {
                var postsContainerId = Constants.POSTS_CONTAINER_ID;
                createPostsGrid(postsContainerId, response);
            },
            "error": function(jqXHR){console.log(jqXHR);},
            "complete": function (jqXHR, textStatus) {}
        });
    }

    $.postFilters = {
        'query': null,
        'category': null
        // 'filters': {
        //     '_addFilter': function(key, value){
        //         $.postFilters['filters'][key] = value;
        //         watch($.postFilters['filters'], key, postFilterWatchCallBack);
        //     },
        //     '_removeFilter': function(key) {
        //         unwatch($.postFilters.filters, key);
        //         delete $.postFilters.filters[key];
        //     }
        // }
    };

    function initFiltersWithWatcher(category){
        if(!category){return;}
        if($.postFilters['filters']){
            $.each($.postFilters['filters'], function(key,v){
                unwatch($.postFilters['filters'], key, postFilterWatchCallBack);
            });
            unwatch($.postFilters, 'filters', postFilterWatchCallBack);
            delete $.postFilters['filters'];
        }
        var newFilters = {};
        $.each(category.properties, function(index, propertyObject){
            newFilters[propertyObject['code']] = null;
        });
        $.postFilters['filters'] = newFilters;
        watch($.postFilters, 'filters', postFilterWatchCallBack);
    }

    watch($.postFilters, postFilterWatchCallBack);

    $('[data-toggle=offcanvas]').click(function () {
        $('.row-offcanvas').toggleClass('active');
    });

    function searchHandler(e){
        e.preventDefault();
        setTimeout(function(){
            $.postFilters['query'] =  $("#input-search-posts").val() || undefined;
        }, 1000);
    }

    $(document).on('click', '#btn-search-posts', searchHandler);
    $(document).on('keyup', '#input-search-posts', searchHandler);

    $(document).on('change', '#category-filter', function(){
        var categoryCode = $(this).val();
        $.postFilters['category'] = categoryCode || undefined;
        var categories = Storages.localStorage.get('categories');
        var category = _.first(_.filter(categories, function(o){return o.code === categoryCode}));
        generateFilterFields(category);
        initFiltersWithWatcher(category);
    });

    $(document).on('keyup', '.filter-input', function(e){
        e.preventDefault();
        var input = $(this);
        var propertyCode = input.data('property-code');
        var val = input.val();
        setTimeout(function(){
            $.postFilters['filters'][propertyCode] = val;
        }, 1000);
    });
});