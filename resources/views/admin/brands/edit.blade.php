@extends('layouts.admin.app')

@section('content')
    <h1>Edit Brand</h1>

    <form method="POST" action="{{ route('admin.brands.update', $brand) }}">
        @csrf
        @method('PUT')

        @include('admin.brands.form', ['brand' => $brand, 'action' => 'edit'])
        <div class="form-group text-right mt-4">
            <button type="reset" class="btn btn-secondary mr-2">
                <i class="fas fa-undo mr-1"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Create Brand
            </button>
        </div>
    </form>
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
        // Prevent auto-discovery, we'll instantiate manually
        Dropzone.autoDiscover = false;
        // Initialize the Dropzone for logo upload
        const logo = "{{ $brand->logo_path ? env('STORAGE_URL') . '/' . $brand->logo_path : '' }}";
        const favicon = "{{ $brand->favicon_path ? env('STORAGE_URL') . '/' . $brand->favicon_path : '' }}";
        const existingLogo = {
            url: logo ? logo : '', // replace with your actual URL
            name: 'logo',

        };
        const existingFavicon = {
            url: favicon ? favicon : '', // replace with your actual URL
            name: 'favicon.jpg',

        };

        const dz = new Dropzone("#logo-dropzone", {
            url: "{{ route('admin.brand.updateMedia', ['brand' => $brand->id, 'type' => 'logo']) }}", // your upload endpoint
            maxFiles: 1,
            acceptedFiles: "image/*",
            paramName: 'logo',
            addRemoveLinks: false,
            dictRemoveFile: "Remove",
            previewTemplate: `
            <div class="dz-preview dz-file-preview p-2 border rounded mb-2">
                <div class="dz-details mb-2">
                    <div class="mt-2"><img data-dz-thumbnail class="img-fluid"/></div>
                </div>
                <div class="dz-progress mb-2"><span class="dz-upload" data-dz-uploadprogress></span></div>
                <div class="dz-error-message text-danger mb-2"><span data-dz-errormessage></span></div>
                <div class="dz-remove-existing text-center mt-2" >
                    <button class="btn btn-danger" data-dz-remove  id="remove-logo-btn">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                    </div>
            </div>`,

            init: function() {
                const dropzone = this;

                // 1) Prepopulate if we have an existing image
                if (existingLogo && existingLogo.url) {
                    const mockFile = {
                        name: existingLogo.name,
                        size: existingLogo.size
                    };
                    dropzone.emit("addedfile", mockFile);
                    dropzone.emit("thumbnail", mockFile, existingLogo.url);
                    dropzone.emit("complete", mockFile);
                    dropzone.files.push(mockFile);
                }

                const file = dropzone.files[dropzone.files.length - 1];
                // 2) When a file is removed, call your delete API
                dropzone.on("removedfile",
                    (file) => {
                        if (!file.xhr) {
                            removeMedia(file, 'logo');
                        }
                    });


                // 3) Ensure only one file stays in the dropzone
                dropzone.on("maxfilesexceeded", function(file) {
                    this.removeFile(file);
                    alert("You can upload only one image.");
                });

                document.querySelector("#remove-logo-btn").addEventListener("click", function() {
                    if (file) {
                        dropzone.removeFile(file);
                        removeMedia(file, 'logo');
                    }
                });
            }
        });




        const dzf = new Dropzone("#favicon-dropzone", {
            url: "{{ route('admin.brand.updateMedia', ['brand' => $brand->id, 'type' => 'favicon']) }}", // your upload endpoint
            maxFiles: 1,
            paramName: 'favicon',
            acceptedFiles: "image/*",
            addRemoveLinks: false,
            dictRemoveFile: "Remove",
            previewTemplate: `
            <div class="dz-preview dz-file-preview p-2 border rounded mb-2">
                <div class="dz-details mb-2">
                    <div class="mt-2"><img data-dz-thumbnail class="img-fluid"/></div>
                </div>
                <div class="dz-progress mb-2"><span class="dz-upload" data-dz-uploadprogress></span></div>
                <div class="dz-error-message text-danger mb-2"><span data-dz-errormessage></span></div>
                <div class="dz-remove-existing text-center mt-2" >
                    <button class="btn btn-danger" data-dz-remove id="remove-favicon-btn">
                      Remove
                    </button>
                </div>
            </div>`,

            init: function() {
                const dropzone = this;

                // 1) Prepopulate if we have an existing image
                if (existingFavicon && existingFavicon.url) {
                    const mockFile = {
                        name: existingFavicon.name,
                        size: existingFavicon.size
                    };
                    dropzone.emit("addedfile", mockFile);
                    dropzone.emit("thumbnail", mockFile, existingFavicon.url);
                    dropzone.emit("complete", mockFile);
                    dropzone.files.push(mockFile);
                }

                // 2) When a file is removed, call your delete API
                // Note: Ensure the file name matches what your backend expects
                // define file
                const file = dropzone.files[dropzone.files.length - 1];
                dropzone.on("removedfile",
                    (file) => {
                        if (!file.xhr) {
                            removeMedia(file, 'favicon');
                        }
                    }
                );

                // 3) Ensure only one file stays in the dropzone
                dropzone.on("maxfilesexceeded", function(file) {
                    this.removeFile(file);
                    alert("You can upload only one image.");
                });

                document.querySelector("#remove-favicon-btn").addEventListener("click", function() {
                    if (file) {
                        dropzone.removeFile(file);
                        removeMedia(file, 'favicon');
                    }
                });
            }
        });


        function removeMedia(file, type) {
            // You can send file.name or any identifier your backend expects
            fetch("{{ url('admin/brand/' . $brand->id . '/media/') }}/" + type, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",

                    },
                    body: JSON.stringify({
                        filename: file.name,
                        _method: "DELETE"

                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error("Delete failed");
                    console.log("Image deleted");
                })
                .catch(err => {
                    console.error(err);
                    alert("Failed to delete image on server.");
                });
        }
    </script>
@endsection
