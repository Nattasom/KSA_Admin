jQuery(document).ready(function(){
    $("#form-insurer").validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore:"",
        rules: {
            insurer_name_th: {
                required: true
            },
            insurer_shortname_th: {
                required: true
            },
            insurer_logo: {
                required: function(){
                    var res = true;
                    var action = $("#form-insurer").find("[name=action]").val();
                    if(action!="add"){
                        res = false;
                    }

                    return res;
                },
                filesize_max: 30000,  //30 mb
                extension: "gif|png|jpg|jpeg"
            },
            insurer_status:{
                required: true
            }
        },

        messages: {
            insurer_name: {
                required: "this field is required"
            },
            insurer_shortname: {
                required: "this field is required"
            },
            insurer_logo: {
                required: "this field is required",
                extension: "Please select only image file"
            }, 
            insurer_status: {
                required: "this field is required"
            }
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },

        errorPlacement: function (error, element) {
            if ($(element).attr("type") == "file") {
                error.insertAfter(element.closest('.fileinput'));
            } else {
                error.insertAfter(element.closest('.form-control'));
            }

        },
        submitHandler: function (form, event) {
            event.preventDefault();
            actionForm(form);
            //form.submit();
        }
    });

});
function actionForm(form){
    var formData = new FormData(form);
    var $btn = $("#btn-submit");
    var $btnback = $("#btn-back");
    var $loader = $btn.find("i.loader");
    var url = $(form).find("[name=action_url]").val();
    var action = $(form).find("[name=action]").val();
    $btn.prop("disabled", true);
    $loader.removeClass("hide");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (result) {
            console.log(result);
            if (result.status == "01") { //success
                console.log(action);
                if(action=="add"){
                    window.location = $btnback.attr("href");
                }else{
                    $("#alert-success").removeClass("hide");
                    $("#alert-success").find("span").text(result.message);
                    // window.location.href = $(form).find("[name=current_url]").val()+"/"+$(form).find("[name=insurer_code]").val();
                    $(window).scrollTop(0);
                }
            } else {
                $("#alert-warning").removeClass("hide");
                $("#alert-warning").find("span").text(result.message);
                $(window).scrollTop(0);
            }

        }
    }).always(function () {
        $btn.prop("disabled", false);
        $loader.addClass("hide");
    });
}

