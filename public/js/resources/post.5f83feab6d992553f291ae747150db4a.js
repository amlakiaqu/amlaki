"use strict";$(document).ready(function(){window.viewPost=function(t){if("number"==typeof(t=parseInt(t))&&!isNaN(t)){var e=getPostUrl(t);createPostModal(e,t)}},$(document).on("click","#btn-create-post",function(t){t.preventDefault(),createPostCreateModal()}),$(document).on("submit","#"+Constants.CREATE_POST_FORM_ID,function(t){t.preventDefault();var e=$(this),a=e.attr("action"),n=e.attr("method").trim(),s=e.serializeArray(),o=s.asMap("name","value");o.category_id=e.data("category-id"),$.ajax({url:a,method:n,data:JSON.stringify(o),success:function(t){var e=t.message;generateNotification(e,"success",!0,2500),hideModalsAndRefreshPosts()},error:function(t){}})}),$(document).on("submit","#"+Constants.EDIT_POST_FORM_ID,function(t){t.preventDefault();var e=$(this),a=e.attr("action"),n=e.attr("method").trim(),s=e.serializeArray().asMap("name","value");$.ajax({url:a,method:n,data:JSON.stringify(s),success:function(t){var e=t.message;generateNotification(e,"success",!0,2500),hideModalsAndRefreshPosts()},error:function(t){}})})});