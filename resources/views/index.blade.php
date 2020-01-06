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
{{--
                    <li class="nav-item">
                        <a class="nav-link text-white" target="_blank" href="#">Examples</a>
                        <div class=""></div>
                    </li>
--}}
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
                    <div class="w-100 h-75 border"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <p>Do:</p>
                            <p>Upload front-on portraits for best detection. Multple
                            faces are supported.</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <p>Avoid:</p>
                            <p>Portraits with tilted heads.</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <p>Avoid:</p>
                            <p>Too many distracting background objects.</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <p>Don't:</p>
                            <p>Upload non-portait photos such as pictures of objects
                            or animals.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <small>Sponsored Content</small>
                    <div class="w-100 border p-2">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p>Googlyizer is an experiment in facial detection using
                            OpenCV.  It was created in 2015, prior to the rise of
                            Instagram and Snapchat filters, but has since been eclipsed
                            by technologies built in to various mobile apps.</p>
                            <p>It uses Haar Cascades to detect faces and eyes and 
                            will superimpose "filters" based on matched coordinates.
                            It works best when uploading a front-on portrait with 
                            minimal background objects.</p>
                            <p>Uploaded photos are processed by the server and are 
                            purged once the session has expired.</p>
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
        <script src="{{ asset('js/googlyizer.js') }}"></script>
    </body>
</html>
