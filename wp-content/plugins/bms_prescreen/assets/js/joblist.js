jQuery(document).ready(function($){

    var searchArray = [];
    var clickedValue;
    var searchString = '';
    var searchStringSelects = '';
    var searchStringText = '';
    var numRows = 0;

    /**********************************/
    // Init loading joblist via ajax
    /**********************************/

    $.get({
        url: '/wp-admin/admin-ajax.php?action=getJoblist',
        async: true,
        contentType: 'application/json',
        success: function(response){
            $('.joblistContainer').html(response);

            /**********************************/
            // Set Datatable
            /**********************************/

            var table = $('#joblistTable').DataTable({
                "drawCallback": function( settings ) {
                    var api = this.api();
                    numRows = api.rows( {page:'current'} ).data().length
                },
                responsive: true,
                fixedColumns: true,
                paging: false,
                dom: 'lrtip',
                columnDefs: [

                ],
                "language": {
                    "sEmptyTable":   	"Deine Suche ergab leider keine Treffer.",
                    "sInfo":         	"_START_ bis _END_ von _TOTAL_ Einträgen",
                    "sInfoEmpty":    	"0 bis 0 von 0 Einträgen",
                    "sInfoFiltered": 	"(gefiltert von _MAX_ Einträgen)",
                    "sInfoPostFix":  	"",
                    "sInfoThousands":  	".",
                    "sLengthMenu":   	"_MENU_ Einträge anzeigen",
                    "sLoadingRecords": 	"Wird geladen...",
                    "sProcessing":   	"Bitte warten...",
                    "sSearch":       	"Suchen",
                    "sZeroRecords":  	"Deine Suche ergab leider keine Treffer.",
                    "oPaginate": {
                        "sFirst":    	"Erste",
                        "sPrevious": 	"Zurück",
                        "sNext":     	"Nächste",
                        "sLast":     	"Letzte"
                    },
                    "oAria": {
                        "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
                        "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                    }
                }
            });

            jQuery('.joblistResults').html('Ergebnisse: ' + numRows);

            /**********************************/
            // Dropdowns for select
            /**********************************/

            $('.dropdown dt a').on('click', function () {
                var id = $(this).data('id');
                $('#' + id + ' dd ul').slideToggle('fast');
            });

            $('.dropdown dd ul li a').on('click', function () {
                var id = $(this).data('id');
                $('#' + id + ' dd ul').hide();
            });

            $(document).bind('click', function (e) {
                var $clicked = $(e.target);
                if (!$clicked.parents().hasClass('dropdown')) $('.dropdown dd ul').hide();
            });

            function columnSearch(columnIndex, searchValue){
                // Filter event handler
                table
                    .column(columnIndex)
                    .search(searchValue)
                    .draw();

                jQuery('.joblistResults').html('Ergebnisse: ' + numRows);

            }

            $('#Suche' ).on( 'keyup change', function (e) {
                var searchValue =  $(this).val();
                columnSearch(4,searchValue);
            });
            $('#Bereich' ).on( 'keyup change', function (e) {
                var searchValue =  $(this).val();
                columnSearch(1,searchValue);
            });
            $('#Unternehmen' ).on( 'change', function (e) {
                var searchValue =  $(this).val();
                columnSearch(2,searchValue);
            });
            $('#Standort' ).on( 'change', function (e) {
                var searchValue =  $(this).val();
                columnSearch(3,searchValue);
            });

            // TR on click visit link

            $('body').on('click','tr',  function(){
                var link = $(this).data('link');
                window.open(link, "_self");
            })
        }

    });
});
