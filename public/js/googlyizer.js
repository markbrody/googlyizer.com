$(function() {
    var csrf = $("meta[name=csrf-token]").attr("content");
    var form_data = new FormData();
    var override = function(evt, callback) {
        evt.preventDefault();
        evt.stopPropagation();
        callback();
    }

    function read_url(input) {
        if (input[0] && input[0].files) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#drop-zone").html('<img height="80%" src="' + e.target.result + '">');
            }
            reader.readAsDataURL(input[0].files[0]);
        }
    }

    $("#googlyize-button").on("click", function() {
        form_data.append("_token", csrf);
        form_data.append("eye_type", $("#eye-type-input").val());
        $("#googly-eyes").attr("src", "/images/loading.gif");
        $("#googly-modal").modal("show");
        $.ajax({
            url: "/upload",
            type: "POST",
            dataType: "json",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.result)
                    $("#googly-eyes").attr("src", response.result);
                else
                    $("#googly-eyes").attr("src", "/images/error.png");
            },
            error: function() {
                $("#googly-eyes").attr("src", "/images/error.png");
            },
        });
    });

    $("#drop-zone").on("click", function() {
        $("#image-upload").click();
    });

    $("#image-upload").change(function(){
        var files = $('#image-upload')[0].files[0];
        form_data.append("image", $("#image-upload")[0].files[0]);
        read_url($("#image-upload"));
    });

    $(".eye-type").on("click", function() {
        $(".eye-type").removeClass("bg-gray");
        $(this).addClass("bg-gray");
        $("#eye-type-input").val($(this).data("id"));
    });

});

