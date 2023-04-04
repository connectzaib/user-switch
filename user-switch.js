/* This is your custom Javascript */
jQuery("document").ready(function($){
    $("#zb-user-login").click(function(){
        $(this).text('Loading').prop("disabled",true);
        user_id = jQuery("#zb-user-select").val();
        $.ajax({
            type : "post",
            dataType : "json",
            url : UOBJ.adminurl + "admin-ajax.php",
            data : { 
                action: "zb_switch_user",
                user_id:user_id
            },
            success:function(response){
                if(response.success){
                    window.location.replace(UOBJ.adminurl);
                }
            },
            error: function(response){
                // console.log(response);
                // alert("Error Occurred")
            }
        });
    })
})