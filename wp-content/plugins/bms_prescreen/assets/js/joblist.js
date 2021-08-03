jQuery(document).ready(function($){

  var searchArray = [];
  var clickedValue;
  var searchString = '';
  var searchStringSelects = '';
  var searchStringText = '';

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
                responsive: true,
                fixedColumns: true,
                paging: false,
                dom: 'lrtip',
                columnDefs: [

                ],
                "language": {
                    "sEmptyTable":   	"Keine Daten in der Tabelle vorhanden",
                    "sInfo":         	"_START_ bis _END_ von _TOTAL_ Einträgen",
                    "sInfoEmpty":    	"0 bis 0 von 0 Einträgen",
                    "sInfoFiltered": 	"(gefiltert von _MAX_ Einträgen)",
                    "sInfoPostFix":  	"",
                    "sInfoThousands":  	".",
                    "sLengthMenu":   	"_MENU_ Einträge anzeigen",
                    "sLoadingRecords": 	"Wird geladen...",
                    "sProcessing":   	"Bitte warten...",
                    "sSearch":       	"Suchen",
                    "sZeroRecords":  	"Keine Einträge vorhanden.",
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
            var numRows = table.rows( ).count();
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

            /**********************************/
            // Check or uncheck
            // Fill search array
            /**********************************/

            $('.filterSelect').on('change', function () {
                var id = $(this).data('id');
                var value = $(this).val();

                var bereich = $('#Bereich').val();
                var unternehmen = $('#Unternehmen').val();
                var standort = $('#Standort').val();

                searchStringSelects = '';
                var selectArray = [bereich, unternehmen, standort];
                var counter = 0;

                selectArray.forEach(function( value ) {
                    if(value != ''){
                        counter = counter + 1;
                        if(counter === 2 || counter === 3){
                            searchStringSelects = searchStringSelects + '|';
                        }
                        searchStringSelects = searchStringSelects + '(' + value + ')';
                    }
                });

                setSearchString('', false);
            });

            // Set search on Suche search

            $('#Suche').on("keyup change click", function(e) {

                var value = $(this).val();

                setSearchString(value, true);
            })

            function setSearchString(value, searchState){

                // Search via Select

                if(value !== '' && searchState === false){
                    if($('#Suche').val() !== ''){
                        searchStringText = '(' + $('#Suche').val() + ')|';
                    }
                }

                // Search via textfield
                if(searchState === true && value !== ''){
                    searchStringText = '(' + value + ')';
                }

                if(searchState === true && value === ''){
                    searchStringText = '';
                }

                searchString = '(' + searchStringText  + searchStringSelects + ')';

                searchString = searchString.replace(')|)', '))');
                searchString = searchString.replace(')(', ')|(');

                // Set search

                $('#searchPhrase').val(searchString);
                //var test = $('#searchPhrase').val();
                console.log('Suchen' + searchString);
                table.search(searchString, true).draw(true);
                var rowCount = document.getElementById('joblistTable').rows.length - 1;
                jQuery('.joblistResults').html('Ergebnisse: ' + rowCount);
            }

            // TR on click visit link

            $('body').on('click','tr',  function(){
                var link = $(this).data('link');
                window.open(link, "_self");
            })
        }

    });



});
