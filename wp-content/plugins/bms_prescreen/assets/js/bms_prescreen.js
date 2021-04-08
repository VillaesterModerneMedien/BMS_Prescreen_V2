jQuery(document).ready(function($){


  // preventing page from redirecting
  $("html").on("dragover", function(e) {
    e.preventDefault();
    e.stopPropagation();
    $("h1").text("Drag here");
  });

  $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

  // Drag enter
  $('.upload-area').on('dragenter', function (e) {
    e.stopPropagation();
    e.preventDefault();
    $("h1").text("Drop");
  });

  // Drag over
  $('.upload-area').on('dragover', function (e) {
    e.stopPropagation();
    e.preventDefault();
    $("h1").text("Drop");
  });

  // Drop
  $('.upload-area').on('drop', function (e) {
    e.stopPropagation();
    e.preventDefault();

    $("h1").text("Upload");

    var file = e.originalEvent.dataTransfer.files;
    var fd = new FormData();

    fd.append('file', file[0]);

    uploadData(fd);
  });

  // Open file selector on div click
  $("#uploadfile").click(function(){
    $("#file").click();
  });

  // file selected
  $("#file").change(function(){
    var fd = new FormData();

    var files = $('#file')[0].files[0];

    fd.append('file',files);

    uploadData(fd);
  });

  /*
  $('#myfile').on('change', function(e){
    e.preventDefault();
    console.log('aaaa');
    var fd = new FormData();
    var file = $(document).find('#myfile');
    //var caption = $(this).find('input[name=img_caption]');
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    //var individual_capt = caption.val();
    //fd.append("caption", individual_capt);
    fd.append('action', 'fiu_upload_file');

    $.ajax({
      type: 'POST',
      url: fiuajax.ajaxurl,
      data: fd,
      contentType: false,
      processData: false,
      success: function(response){

        console.log(response);
      }
    });
  });

*/

  // Sending AJAX request and upload file

  var ajax_url = plugin_ajax_object.ajax_url;

  // Fetch All records (AJAX request without parameter)

/*
  $.ajax({
    url: ajax_url,
    type: 'post',
    data: data,
    dataType: 'json',
    success: function(response){
      // Add new rows to table
      createTableRows(response);
    }
  });
*/

  function uploadData(formdata){
    var data = {
      'action': 'writeCandidate'
    };
    console.log(data);
    console.log(formdata);
    $.post({
      url: 'http://bms.test/wp-admin/admin-ajax.php?action=writeCandidate',
      data: formdata,
      contentType: false,
      processData: false,

      success: function(response){
        addThumbnail(response);
      }
    });
  }

// Added thumbnail
  function addThumbnail(data){
    $("#uploadfile h1").remove();
    var len = $("#uploadfile div.thumbnail").length;

    var num = Number(len);
    num = num + 1;

    var name = data.formdata.name;
    var size = convertSize(data.formdata.size);
    var src = data.formdata.src;


    // Creating an thumbnail
    $("#uploadfile").append('<div id="thumbnail_'+num+'" class="thumbnail"></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');
    $("#thumbnail_"+num).append('<span class="size">'+size+'<span>');

  }

// Bytes conversion
  function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
  }


  //alert('bms_prescreen');
  //alert('koko');
  // AJAX url
  /*
  var ajax_url = plugin_ajax_object.ajax_url;

  // Fetch All records (AJAX request without parameter)
  var data = {
    'action': 'employeeList'
  };

  $.ajax({
    url: ajax_url,
    type: 'post',
    data: data,
    dataType: 'json',
    success: function(response){
      // Add new rows to table
      createTableRows(response);
    }
  });

  // Search record
  $('#search').keyup(function(){
    var searchText = $(this).val();

    // Fetch filtered records (AJAX request with parameter)
    var data = {
      'action': 'searchEmployeeList',
      'searchText': searchText
    };

    $.ajax({
      url: ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response){
        // Add new rows to table
        createTableRows(response);
      }
    });
  });

  // Add table rows by reading response object
  function createTableRows(response){
    $('#empTable tbody').empty();

    var len = response.length;
    var sno = 0;
    for(var i=0; i<len; i++){
      var id = response[i].id;
      var emp_name = response[i].emp_name;
      var email = response[i].email;
      var salary = response[i].salary;
      var gender = response[i].gender;
      var city = response[i].city;

      // Add <tr>
      var tr = "<tr>";
      tr += "<td>"+ (++sno) +"</td>";
      tr += "<td>"+ emp_name +"</td>";
      tr += "<td>"+ email +"</td>";
      tr += "<td>"+ salary +"</td>";
      tr += "<td>"+ gender +"</td>";
      tr += "<td>"+ city +"</td>";
      tr += "<tr>";

      $("#empTable tbody").append(tr);
    }
  }


   */
});
