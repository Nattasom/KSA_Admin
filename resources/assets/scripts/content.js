jQuery(document).ready(function () {
    $("#form-content").validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {
            content_title_th: {
                required: true
            },
            thumbnail_th: {
                required: function () {
                    var res = true;
                    var action = $("#form-content").find("[name=action]").val();
                    if (action != "add") {
                        res = false;
                    }

                    return res;
                },
                filesize_max: 30000,  //30 mb
                extension: "gif|png|jpg|jpeg"
            },
            banner_status:{
                required:true
            }
        },

        messages: {
            content_title_th: {
                required: "This field is required."
            },
            thumbnail_th: {
                required: "This field is required."
            },
            content_status: {
                required: "This field is required."
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
function actionForm(form) {
    var formData = new FormData(form);
    $(form).find("[name^='editor']").each(function () {
        formData.append($(this).attr("name"), CKEDITOR.instances[$(this).attr("name")].getData());
    });
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
                if (action == "add") {
                    window.location = $btnback.attr("href");
                } else {
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


