jQuery(document).ready(function($){

  /********************************************************************************************************************/
  // Form Value Mappings
  /********************************************************************************************************************/

  $('.slider').on('change', function(){
    setSliderValues();
  });

  function setSliderValues(){
    var sliders = $('.slider');
    sliders.each(function( index ) {
      var id = $( this ).attr('id');
      var sliderValue = $( this ).val();
      var hiddenValue = $( '#' + id + 'hidden' ).data('value');
      $('#' + id + 'hidden').val(hiddenValue)
      console.log(id, hiddenValue, sliderValue);
      $('#' + id + 'hidden').val(hiddenValue + ': ' + sliderValue);
    });
  }
  setSliderValues();

  /********************************************************************************************************************/
  // CV Uploader
  /********************************************************************************************************************/


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

  function uploadData(formdata){
    var data = {
      'action': 'writeCandidate'
    };
    //console.log(data);
    console.log('formData',formdata);


    $.post({
      url: '/wp-admin/admin-ajax.php?action=writeCandidate',
      data: formdata,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $('.preloader').addClass('preloaderVisible');
      },
      success: function(response){
        var data = JSON.parse(response);
        if(data.message !== undefined)
        {
          alert(data.message);
        }
        $('.preloader').removeClass('preloaderVisible');

        console.log('kiki');
        console.log(data.id);
        console.log(data);
        //addThumbnail(response);
      }
    });
  }

  function patchApplication() {

    //console.log(data);
    console.log('formData', formdata);


    $.post({
      url: '/wp-admin/admin-ajax.php?action=patchApplication',
      data: formdata,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $('.preloader').addClass('preloaderVisible');
      },
      success: function (response) {
        var data = JSON.parse(response);
        if (data.message !== undefined) {
          alert(data.message);
        }
        $('.preloader').removeClass('preloaderVisible');

        console.log('kiki');
        console.log(data.id);
        console.log(data);
        //addThumbnail(response);
      }
    });
  }

  /********************************************************************************************************************/
  // Form JS
  /********************************************************************************************************************/

  // Star Rating

  $('.star').on('mouseenter', function(){
    var selectedID = $(this).attr('title');
    var parent = $(this).parent();
    var stars = parent.find('a');
    stars.each(function( index ) {
      index = index + 1;
      if(index <= selectedID){
        $( this ).addClass('rotSelected');
      }
      else{
        $( this ).removeClass('rotSelected');
      }
    });
  })

  $('.star').on('mouseleave', function(){
    $( '.star' ).removeClass('rotSelected');
  })

  $('.star').on('click', function(){
    var selectedID = $(this).attr('title');
    var parent = $(this).parent();
    var fieldID = parent.data('id');
    var stars = parent.find('a');

    var inputField = parent.find('input');
    console.log($(inputField[0]).data('id'));

    stars.each(function( index ) {
      index = index + 1;
      if(index <= selectedID){
        $( this ).addClass('rotChecked');
      }
      else{
        $( this ).removeClass('rotChecked');

      }
    });

    $('#' + fieldID).val($(inputField[0]).data('id') + ': ' + selectedID);
  })

  // Typ Checkboxes
  // Limit max. 3 anclickbar also length + 1

  var limit = 4;
  $('.typeCheckbox').on('change', function(evt) {
    var typeCheckboxes = $('.typeCheckboxes').find(':checked').length;

    if(typeCheckboxes >= limit) {
      this.checked = false;
    }
    else{
      if($(this).prop('checked')){
        $(this).parent().addClass('typChecked');
      }
      else{
        $(this).parent().removeClass('typChecked');
      }
    }
  });

  // Recaptcha Validation

  function recaptchaValidation(){
      var $captcha = $( '#recaptcha' ),
      response = grecaptcha.getResponse();
      //console.log(response.length);

      if (response.length === 0) {
        $( '.msg-error').text( "reCAPTCHA is mandatory" );
        if( !$captcha.hasClass( "error" ) ){
          $captcha.addClass( "error" );
        }
        alert( 'Bitte das reCAPTCHA ausfüllen!' );
      } else {
        $( '.msg-error' ).text('');
        $captcha.removeClass( "error" );
      }
  }

  function inputValidation(){
    var inputText = $('.inputText');
    inputText.each(function( index ) {
      var value = $(this).val();
      if(value.length === 0){
        $(this).next('.warning').addClass('warningVisible');
        alert('Bitte die Pflichtfelder ausfüllen!');
      }
      else{
        $(this).next('.warning').removeClass('warningVisible');
      }
    });
  }
  //inputValidation();

  $('.inputText').on('change', function(){
    var value = $(this).val();
    if(value.length === 0){
      $(this).next('.warning').addClass('warningVisible');
    }
    else{
      $(this).next('.warning').removeClass('warningVisible');
    }
  })

  /********************************************************************************************************************/
  // Ajax Send
  /********************************************************************************************************************/

  var ajax_url = plugin_ajax_object.ajax_url;

  // file selected
  $('#sendCandidate').click(function(e){
    e.preventDefault();
    var form = $('#formTest');
    var fd = new FormData(form[0]);

    //var files = $('#file')[0].files[0];
    //fd.append('file',files);

    recaptchaValidation();
    inputValidation();
    uploadData(fd);
  });
});
