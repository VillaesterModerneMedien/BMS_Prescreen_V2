jQuery(document).ready(function($){

  var searchArray = [];
  var clickedValue;
  var searchString = '';
  var searchStringSelects = '';
  var searchStringText = '';

  /**********************************/
  // Set Datatable
  /**********************************/

  var table = $('#joblistTable').DataTable({
    responsive: true,
    fixedColumns: true,
    dom: 'lrtip',
    columnDefs: [
      { targets: 0, render: $.fn.dataTable.render.ellipsis(30) },
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

  $('.dropdown input[type="checkbox"]').on('click', function () {
    var id = $(this).data('id');

    var value = $(this).closest('.multiSelect').find('input[type="checkbox"]').val(),
        value = $(this).val();
    var boxType = $(this).data('boxtype');

    // Push values to search array

    searchArray.push($(this).val());


    if ($(this).is(':checked')) {

        // Set clicked values in view
        // Set search array with values from all selects

        clickedValue = value;
        var html = '<span title="' + value + '">' + value + ', </span>';
        $('#' + id + ' .multiSel').append(html);
        $('#' + id + ' .initialOption').hide();
    } else {

        // Remove unchecked values from search Array
        // remove unchecked from view
        clickedValue = value;
        $('span[title="' + value + '"]').remove();

        searchArray = searchArray.filter(function(value, index, arr){
          return value !== clickedValue ;
        });

        var ret = $('#' + id + ' .initialOption');
        $('#' + id + ' .dropdown dt a').append(ret);
    }

    setSearchString(value, false);
  });

  // Set search on position search

  $('#position').on("keyup change", function(e) {

    var value = $(this).val();

    setSearchString(value, true);

  })

  function setSearchString(value, searchState){

    // Search via Select

    if(value !== '' && searchState === false){
      if($('#position').val() !== ''){
        searchStringText = '(' + $('#position').val() + ')|';
      }
      searchStringSelects = searchArray.map(function(value){
        return '(' + value + ')';
      }).join('|');
    }

    // Search via textfield
    if(searchState === true && value !== ''){
      searchStringText = '(' + value + ')';
    }

    if(searchState === true && value === ''){
      searchStringText = '';
    }

    searchString = '(' + searchStringText + searchStringSelects + ')';
    searchString = searchString.replace(')|)', '))');
    searchString = searchString.replace(')(', ')|(');

    // Set search

    $('#searchPhrase').val(searchString);
    table.search(searchString, true).draw(true);
  }

  // TR on click visit link

  $('tr').on('click', function(){
    var link = $(this).data('link');
    window.open(link, "_self");
  })

});
