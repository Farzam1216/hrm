"use strict";
var KTDatatablesExtensionButtons = function () {

    var initTable2 = function () {

        // begin first table
        var table = $('#kt_table_3').DataTable({
            responsive: true,
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2,3,4,5,6,7]
                    }
            },
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2,3,4,5,6,7]
                    }
            },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2,3,4,5,6,7]
                    }
            }, {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4,5,6,7]
                }
            },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2,3,4,5,6,7]
                    }
            },
            ],
            processing: true,
            // serverSide: true,
        });

        $('#export_print').on('click', function (e) {
            e.preventDefault();
            table.button(0).trigger();
        });

        $('#export_copy').on('click', function (e) {
            e.preventDefault();
            table.button(1).trigger();
        });

        $('#export_excel').on('click', function (e) {
            e.preventDefault();
            table.button(2).trigger();
        });

        $('#export_csv').on('click', function (e) {
            e.preventDefault();
            table.button(3).trigger();
        });

        $('#export_pdf').on('click', function (e) {
            e.preventDefault();
            table.button(4).trigger();
        });

    };

    return {

        //main function to initiate the module
        init: function () {
            initTable2();
        },

    };

}();

jQuery(document).ready(function () {
    KTDatatablesExtensionButtons.init();
});