! function (t) {
    "use strict";

    function e() {
        this.$body = t("body")
    }
    e.prototype.init = function () {
        Dropzone.autoDiscover = !1, t('[data-plugin="dropzone"]').each(function () {
            var e = t(this).attr("action"),
                o = t(this).data("previewsContainer"),
                i = {
                    //url: e,
                    autoProcessQueue: false, // Disable auto processing
                    init: function () {
                        var self = this;
                    
                        // Listen to the addedfile event
                        this.on("addedfile", function (file) {
                            // Create a new input field for the added file
                            var input = $('<input type="file" name="data[Picture][]" style="display: none;">');
                            $(self.element).append(input);

                            // Set the added file to the new input field
                            var fileList = new DataTransfer();
                            fileList.items.add(file);
                            input[0].files = fileList.files;
                        });

                        // Listen to the removedfile event
                        this.on("removedfile", function (file) {
                            // Remove the associated input field when a file is removed
                            $(file.previewElement).prev("input[type=file]").remove();
                        });

                    }
                };
            o && (i.previewsContainer = o);
            var r = t(this).data("uploadPreviewTemplate");
            r && (i.previewTemplate = t(r).html());
            t(this).dropzone(i)
        })
    }, t.FileUpload = new e, t.FileUpload.Constructor = e;
}(window.jQuery),
    function () {
        "use strict";
        window.jQuery.FileUpload.init()
    }(), 0 < $('[data-plugins="dropify"]').length && $('[data-plugins="dropify"]').dropify({
        messages: {
            default: "Drag and drop a file here or click",
            replace: "Drag and drop or click to replace",
            remove: "Remove",
            error: "Ooops, something wrong appended."
        },
        error: {
            fileSize: "The file size is too big (1M max)."
        }
    });