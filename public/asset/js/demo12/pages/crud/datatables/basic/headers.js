"use strict";
var KTDatatablesBasicHeaders = function () {

    var initTable1 = function () {
        var table = $('#kt_table_1');
        var en = {'sLengthMenu': 'Show _MENU_ enteries',
            'sZeroRecords': 'No record found - sorry',
            'sInfo': 'Showing _START_ to _END_ of _TOTAL_ enteries',
            'sInfoEmpty': 'Showing 0 to 0 of 0 records',
            'sInfoFiltered': '(filtered from _MAX_ total enteries)'
        };

        var es = {'sProcessing': 'Procesando...',
            'sLengthMenu': 'Mostrar _MENU_ entradas',
            'sZeroRecords': 'No se encontró registro - lo siento',
            'sInfo': 'Mostrando desde _START_ hasta _END_ de _TOTAL_ entradas',
            'sInfoEmpty': 'No existen registros',
            'sInfoFiltered': '(filtrado de un total de _MAX_ líneas)',
            'sInfoPostFix': '',
            'sSearch': 'Buscar:',
            'sUrl': ''
        };
        var locale = sessionStorage.getItem('locale');
        var path = window.location.pathname;
        var language='';
        if(locale === 'en' || path.includes('/en'))
        {
            console.log('en');
            language = en;
        }
        else if(locale === 'es' || path.includes('/es')){
            language = es;
            console.log('es');
        }
        else{
            language = en;
            console.log('default');
        }
        // begin first table
        table.DataTable({
            responsive: true,
            'oLanguage': language,
            columnDefs: [
                {
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
            },
            ],

        });
    };
    var initTable2 = function () {
        var table = $('#kt_table_4');
        var en = {'sLengthMenu': 'Show _MENU_ enteries',
            'sZeroRecords': 'No record found - sorry',
            'sInfo': 'Showing _START_ to _END_ of _TOTAL_ enteries',
            'sInfoEmpty': 'Showing 0 to 0 of 0 records',
            'sInfoFiltered': '(filtered from _MAX_ total enteries)'
        };

        var es = {'sProcessing': 'Procesando...',
            'sLengthMenu': 'Mostrar _MENU_ entradas',
            'sZeroRecords': 'No se encontró registro - lo siento',
            'sInfo': 'Mostrando desde _START_ hasta _END_ de _TOTAL_ entradas',
            'sInfoEmpty': 'No existen registros',
            'sInfoFiltered': '(filtrado de un total de _MAX_ líneas)',
            'sInfoPostFix': '',
            'sSearch': 'Buscar:',
            'sUrl': ''
        };
        var locale = sessionStorage.getItem('locale');
        var path = window.location.pathname;
        var language='';
        if(locale === 'en' || path.includes('/en'))
        {
            console.log('en');
            language = en;
        }
        else if(locale === 'es' || path.includes('/es')){
            language = es;
            console.log('es');
        }
        else{
            language = en;
            console.log('default');
        }
        // begin first table
        table.DataTable({
            responsive: true,
            'oLanguage': language,
            columnDefs: [
                {
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
            },
            ],
        });
    };

    return {

        //main function to initiate the module
        init: function () {
            initTable1();
            initTable2();
        },

    };

}();

jQuery(document).ready(function () {
    KTDatatablesBasicHeaders.init();
});