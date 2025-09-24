@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold text-primary">Create New Brand</h4>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Brands
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.brands.store') }}" id="brandForm"
                            enctype="multipart/form-data">
                            @csrf
                            @include('admin.brands.form', ['brand' => new \App\Models\Admin\Brand(), 'action' => 'create'])

                            <div class="form-group text-right mt-4">
                                <button type="reset" class="btn btn-secondary mr-2">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Create Brand
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.min.css" />
    <style>

    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js">
    </script>


    <script>
        $(document).ready(function() {


            // Logo Dropzone
            Dropzone.autoDiscover = false;
            
            initFileAttachDropzone("#logo-dropzone", "#logo-input");
            initFileAttachDropzone("#favicon-dropzone", "#favicon-input");

        });

        function initFileAttachDropzone(dropzoneId, inputId) {
            const dropzone = new Dropzone(dropzoneId, {
                url: "/file-upload", // This will be overridden by form submission
                autoProcessQueue: false, // Don't upload files immediately
                addRemoveLinks: true, // Show remove links
                parallelUploads: 1, // Allow multiple files
                maxFiles: 1, // Maximum number of files
                acceptedFiles: "image/*", // Only accept images
                dictDefaultMessage: "Drop files here or click to browse",
                dictRemoveFile: "Remove",
                previewTemplate: `
                        <div class="dz-preview dz-file-preview">
                            <div class="dz-details">
                                <div class="dz-filename"><span data-dz-name></span></div>
                                <div class="dz-size" data-dz-size></div>
                                <img data-dz-thumbnail />
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                            <div class="dz-error-message"><span data-dz-errormessage></span></div>
                            <div class="dz-success-mark"></div>
                            <div class="dz-error-mark"></div>
                        </div>
                    `,
                init: function() {
                    this.on("addedfile", function(file) {
                        console.log("File added: ", file.name);
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        document.querySelector(inputId).files = dataTransfer
                            .files;

                        document.querySelector(dropzoneId + ' #dropzone-text').style.display = 'none';
                    });

                    this.on("removedfile", function(file) {
                        console.log("File removed: ", file.name);
                        document.querySelector(inputId).value = '';
                        document.querySelector(dropzoneId + ' #dropzone-text').style.display = 'block';

                    });

                    this.on("error", function(file, message) {
                        console.error("Error with file: ", file.name, message);
                        alert(message);
                    });
                }
            });

            document.querySelector(dropzoneId + ' #upload-button').addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelector(dropzoneId).click();
            });

        }

        // Favicon Dropzone

        // Initialize color picker
    </script>
@endsection
