<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta name="author" content="">
    
        <title>{{ config("app.name") }}</title>
    
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    
        {{-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> --}}
    </head>
    <body class="bg-gray">
        <div class="container py-2">
            <div class="row">
                <a href="{{ route('index') }}"><img src="{{ asset('images/logo.png') }}" title="googlyizer"></a>
            </div>
        </div>
        <nav class="navbar navbar-expand bg-warning text-white mb-4 py-0">
            <div class="container">
                <ul class="navbar-nav pt-2">
                    <li class="d-block nav-item">
                        <a class="nav-link text-white" href="{{ url('/') }}">Home</a>
                        <div class="menu-selected"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" target="_blank" href="#">Examples</a>
                        <div class=""></div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <h6 class="mb-4">Add Image</h6>
                                            <div class="form-row">
                                                <div class="col-12 mb-4">
                                                    <input type="file" id="image-upload" class="d-none">
                                                    <div class="upload-drop-zone text-center text-muted border" id="drop-zone">
                                                        <small>Click to select image</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <h6 class="mb-4">Options</h6>
                                            <input id="eye-type-input" type="hidden" value="1">
                                            @foreach($eye_types as $eye_type)
                                            <div class="eye-type d-inline-block rounded border mb-2 p-3 @if($loop->first) bg-gray @endif" data-id="{{ $eye_type->id }}">
                                                <img alt="{{ $eye_type->name }}" src="{{ asset($eye_type->filename) }}">
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-12">
                            <button id="googlyize-button" type="button" class="btn btn-lg btn-block btn-info">Googlyize</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <small>Sponsored Content</small>
                    <div class="w-100 h-100 bg-secondary border"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                </div>
                <div class="col-3">
                </div>
                <div class="col-3">
                </div>
                <div class="col-3">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eget ultricies leo.
Sed non metus ac tellus elementum imperdiet in non diam. Cras sollicitudin neque
ac massa mattis, et dignissim augue dictum. Quisque ac suscipit risus, vel sodales
lorem. Vivamus vulputate, nulla at aliquam tincidunt, erat lorem viverra nisi, id
interdum erat est a dui. Morbi quis mollis urna. Nam consequat in diam et vulputate.
Nam et dolor a augue suscipit varius.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer bg-gray text-center w-100 p-4">
            <small class="text-muted">Copyright &copy; 2015-{{ date("Y") }} &nbsp; Googlyizer.com</small>
        </div>
        <div id="googly-modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Googly Eyes Result</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <img id="googly-eyes" class="w-100" alt="Googly Eyes" src="{{ asset('/images/loading.gif' ) }}">
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script>
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
        </script>
    </body>
</html>


