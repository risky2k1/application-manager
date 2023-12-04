<script src="{{asset('assets/js/custom/date-picker.js')}}"></script>
<script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>

<script>
    csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    $('.repeater-table').repeater({
        initEmpty: false,

        show: function () {
            $(this).slideDown();
            $('.form-select').select2();
            const collectionDate = document.getElementsByClassName("date-picker");
            for (let i = 0; i < collectionDate.length; i++) {
                new tempusDominus.TempusDominus(collectionDate[i], {
                    useCurrent: false,
                    display: {
                        viewMode: "calendar",
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: false,
                            minutes: false,
                            seconds: false
                        },
                        buttons: {
                            today: true,
                            clear: true,
                            close: true
                        },

                    },
                    localization: {
                        locale: "vi",
                        startOfTheWeek: 1,
                        format: "L"
                    }
                });
            }
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    const id = "#drop_zone_attached_files";
    const dropzone = document.querySelector(id);
    const attachedFiles = {!! json_encode($attachedFiles) !!};
    // set the preview element template
    let previewNode = dropzone.querySelector(".dropzone-item");
    previewNode.id = "";
    let previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    let myDropzone = new Dropzone(id, {
        url: "{{route('applications.upload.attached.files')}}",
        parallelUploads: 20,
        method: 'POST',
        uploadMultiple: true,
        maxFilesize: 5,
        paramName: 'attached_files',
        previewTemplate: previewTemplate,
        previewsContainer: id + " .dropzone-items",
        clickable: id + " .dropzone-select",
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        success: function (file, response) {
            var uploadedFilesInfo = [];

            $.each(response, function (index, value) {
                uploadedFilesInfo.push({
                    'file_path': value.attached_files,
                    'original_file_name': value.original_file_name
                });
            })
            $('#inputFileList').val(JSON.stringify(uploadedFilesInfo));
        },
    });

    myDropzone.on("addedfile", function (file) {
        // Hookup the start button
        const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
        dropzoneItems.forEach(dropzoneItem => {
            dropzoneItem.style.display = '';
        });
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function (progress) {
        const progressBars = dropzone.querySelectorAll('.progress-bar');
        progressBars.forEach(progressBar => {
            progressBar.style.width = progress + "%";
        });
    });

    myDropzone.on("sending", function (file) {
        // Show the total progress bar when upload starts
        const progressBars = dropzone.querySelectorAll('.progress-bar');
        progressBars.forEach(progressBar => {
            progressBar.style.opacity = "1";
        });
    });

    // Hide the total progress bar when nothing"s uploading anymore
    myDropzone.on("complete", function (progress) {
        const progressBars = dropzone.querySelectorAll('.dz-complete');

        setTimeout(function () {
            progressBars.forEach(progressBar => {
                progressBar.querySelector('.progress-bar').style.opacity = "0";
                progressBar.querySelector('.progress').style.opacity = "0";
            });
        }, 300);
    });

</script>
