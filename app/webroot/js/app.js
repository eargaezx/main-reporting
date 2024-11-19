document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '.swal-confirm', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var message = $(this).data("message");
        var action = $(this).attr("href");

        Swal.fire({
            title: "Atención",
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, continuar",
            cancelButtonText: "No, regresar",
            closeOnConfirm: false,
            closeOnCancel: true
        }).then(function () {
            window.location = action;
        });
    });

});

jQuery(document).ready(function () {

    return;
    $("body").fadeIn();

    // Select2
    $(".select2").select2();

    $(".select2-limiting").select2({
        maximumSelectionLength: 2
    });

    $('.selectpicker').selectpicker();


    $(".smart-table").each(function (index) {
        $(this).scrollTableBody({
            rowsToDisplay: $(this).data('paginate-limit')
        });
    });

    $('.date-picker').datepicker();

    $('.date-range-picker').daterangepicker({
        "opens": "left",
        "autoApply": false,
        "showDropdowns": false,
        "autoUpdateInput": false,
        "linkedCalendars": false,
        "buttonClasses": "btn",
        ranges: {
            'Mañana': [moment().add(1, 'days'), moment().add(1, 'days')],
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Esta Semana': [moment().startOf('week'), moment().endOf('week')],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')]
        },
        "locale": {
            "customRangeLabel": "Intervalo Personalizado",
            "cancelLabel": "Limpliar",
            "applyLabel": "Establecer"

        }
    });


    $('.time-range-picker').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 15,
        locale: {
            format: 'HH:mm',
            "customRangeLabel": "Abierto",
            "cancelLabel": "Limpliar",
            "applyLabel": "Establecer"
        },
        ranges: {
            'Cerrado': ['0000-00-00 00:00', '0000-00-00 00:00'],
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
        picker.container.find(".input-mini").hide();

        if (!picker.startDate.isValid() || !picker.endDate.isValid() || $(this).val() == '00:00 - 00:00') {
            $(this).val('CERRADO')
        }

    });


    $('.time-range-picker').on('apply.daterangepicker', function (ev, picker) {
        if (!picker.startDate.isValid() || !picker.endDate.isValid() || $(this).val() == '00:00 - 00:00') {
            $(this).val('CERRADO')
        }
    });

    $('.time-range-picker').on('hide.daterangepicker', function (ev, picker) {
        if (!picker.startDate.isValid() || !picker.endDate.isValid() || $(this).val() == '00:00 - 00:00') {
            $(this).val('CERRADO')
        }
    });


    $('.date-range-picker').on('apply.daterangepicker', function (ev, picker) {
        var start = picker.startDate.format('DD/MM/YYYY');
        var end = picker.endDate.format('DD/MM/YYYY');
        $(this).val(start + ' - ' + end);



        /*if ($(this).hasData('start'))
         $("#" + $(this).data("start")).val(start);
         if ($(this).hasData('end'))
         $("#" + $(this).data("end")).val(end);*/
    });

    $('.date-range-picker').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val("");
    });

    $('.colorpicker-default').colorpicker({
        format: 'hex'
    });

    $('.dropify').dropify({
        messages: {
            'default': 'Arrastra y suelta un archivo aquí o haz clic',
            'replace': 'Arrastre y suelte o haga clic para reemplazar',
            'remove': 'Remover',
            'error': 'Ooops, algo ha ocurrido.'
        },
        error: {
            'fileSize': 'El tamaño del archivo es demasiado grande ({{ value }} max).',
            'minWidth': 'El ancho de la imagen es demasiado pequeño ({{ value }}}px min).',
            'maxWidth': 'El ancho de la imagen es demasiado grande. ({{ value }}}px max).',
            'minHeight': 'La altura de la imagen es demasiado pequeña. ({{ value }}}px min).',
            'maxHeight': 'La altura de la imagen es muy grande ({{ value }}px max).',
            'imageFormat': 'El formato de imagen no está permitido. ({{ value }} solamente).'
        }
    });



    $('.summernote').summernote({
        'code': '',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'hr']],
            ['view', ['codeview']]
        ],
        fontsize: '11',
        width: '100%',
        height: 280, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: false                 // set focus to editable area after initializing summernote
    });

    $('.inline-editor').summernote({
        airMode: true
    });



});