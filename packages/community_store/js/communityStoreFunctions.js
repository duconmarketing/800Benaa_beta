$(function(){
    
    var url = window.location.pathname.toString();

    $('#group-filters li a').each(function(){
        var href = $(this).attr('href');
        if(url == href) {
           $(this).parent().addClass("active");
        }
    });
    
    $("a[data-pane-toggle]").click(function(e){
        e.preventDefault();
        var paneTarget = $(this).attr('href');
        paneTarget = paneTarget.replace('#','');     
        $(".store-pane").removeClass('active');
        $('a[data-pane-toggle]').parent().removeClass('active');
        $('#'+paneTarget).addClass("active");
        $(this).parent().addClass("active");
        localStorage.setItem("selectedTab", paneTarget);
        localStorage.setItem("selectedTabIndex",  $(this).parent().index());
    });
    $(".btn-delete-group").click(function(){
        var groupID = $(this).parent().attr("data-group-id");
        var confirmDelete = confirm('Are you sure?');
        if(confirmDelete == true) {
            var deleteurl = $(".group-list").attr("data-delete-url");
            $.ajax({ 
                url: deleteurl+"/"+groupID,
                success: function() {
                    $("li[data-group-id='"+groupID+"']").remove();
                },
                error: function(){
                    alert("Something went wrong");
                }
            });             
        }
    });
    $(".btn-save-group-name").click(function(){
        var groupID = $(this).parent().attr("data-group-id");
        var saveurl = $(".group-list").attr("data-save-url");
        var gName = $(this).parent().find(".edit-group-name").val();
        $.ajax({ 
            url: saveurl+"/"+groupID,
            data: {gName: gName},
            type: 'post',
            success: function() {
                $("li[data-group-id='"+groupID+"']").find(".group-name").text(gName);
                $("li[data-group-id='"+groupID+"']").find(".btn-edit-group-name,.group-name").show();
                $("li[data-group-id='"+groupID+"']").find(".edit-group-name, .btn-cancel-edit, .btn-save-group-name").attr("style","display: none");
            },
            error: function(){
                alert("something went wrong");
            }
        });    
    });
    $(".btn-edit-group-name").click(function(){
        $(this).parent().find(".btn-edit-group-name,.group-name").hide();
        $(this).parent().find(".edit-group-name, .btn-cancel-edit, .btn-save-group-name").attr("style","display: inline-block !important"); 
    });
    $(".btn-cancel-edit").click(function(){
       $(this).parent().find(".btn-edit-group-name,.group-name").show();
       $(this).parent().find(".edit-group-name, .btn-cancel-edit, .btn-save-group-name").attr("style","display: none");  
    });
    
    $("#btn-delete-order").click(function(e){
        e.preventDefault();
        var url = $(this).attr("href");
        var confirmDelete = confirm($(this).data('confirm-message'));
        if(confirmDelete == true) {
            window.location = url;
        }
    });

    $(".confirm-action").click(function(e){
        e.preventDefault();
        var confirmDelete = confirm($(this).data('confirm-message'));
        if(confirmDelete == true) {
            $(this).closest('form').submit();
        }
    });

    $("#btn-generate-page").click(function(e){
        e.preventDefault();
        var url = $(this).attr("href");
        var pageTemplate = $("#selectPageTemplate").val();
        var confirmDelete = confirm($(this).data('confirm-message'));
        if(confirmDelete == true) {
            window.location = url+'/'+pageTemplate;        
        }
    });

});
var url = window.location.pathname.toString();
var urlArray = url.split('/');
var saveSuccess = urlArray[urlArray.length - 1];
if(saveSuccess != "success" && saveSuccess != "updated" && saveSuccess != "added" || isNaN(parseInt(localStorage.getItem("selectedTabIndex")))){
    localStorage.removeItem("selectedTab");
    localStorage.removeItem("selectedTabIndex");
}
else{
    $(".store-pane").removeClass('active'); 
     $('a[data-pane-toggle]').parent().removeClass('active');
     var paneTarget = localStorage.getItem("selectedTab");
     var paneTargetIndex = parseInt(localStorage.getItem("selectedTabIndex"));
     $('#'+paneTarget).addClass("active");
     $('a[data-pane-toggle]:eq('+paneTargetIndex+')').parent().addClass("active");
}

function updateTaxStates(){
    var countryCode = $("#taxCountry").val();
    var selectedState = $("#savedTaxState").val();
    var stateutility = $("#settings-tax").attr("data-states-utility");
    $.ajax({
       url: stateutility,
       type: 'post',
       data: {country: countryCode, selectedState: selectedState, type: "tax"},
       success: function(states){
           $("#taxState").replaceWith(states);

           if (states.indexOf(" selected ") >= 0) {
               $("#taxState").prepend("<option value=''></option>");
           } else {
               $("#taxState").prepend("<option value='' selected='selected'></option>");
           }
       } 
    });
}
updateTaxStates();
