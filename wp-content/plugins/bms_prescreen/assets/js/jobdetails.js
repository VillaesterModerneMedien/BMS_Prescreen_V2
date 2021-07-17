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

  var uploadFile;

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
    $('.uploadMessage').text('Datei ablegen zum hochladen.<br><strong>Achtung: Nur doc, docx, pdf und rtf erlaubt.</strong>');
  });

  // Drag over
  $('.upload-area').on('dragover', function (e) {
    e.stopPropagation();
    e.preventDefault();
    $('.uploadMessage').text('Datei ablegen zum hochladen.<br><strong>Achtung: Nur doc, docx, pdf und rtf erlaubt.</strong>');
  });

  // Drop
  $('.upload-area').on('drop', function (e) {
    e.stopPropagation();
    e.preventDefault();
    var fd = new FormData();

    var file = e.originalEvent.dataTransfer.files;

    uploadFile =  file[0];

    $('.uploadMessage').text(file[0].name);
    fd.append('file', file[0]);
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
  });

  /********************************************************************************************************************/
  // Upload Data and send API
  /********************************************************************************************************************/

  function uploadData(formdata){
    var data = {
      'action': 'writeCandidate'
    };

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

        var candidateID = parseInt(data.id);
        var applicationID = parseInt(data.job_applications[0].id);

        jQuery('#candidateID').val(candidateID);
        if(data.message !== undefined)
        {
          alert(data.message);
        }
        formdata.append('candidate_id',candidateID);
        formdata.append('application_id',applicationID);
        $.post({
          url: '/wp-admin/admin-ajax.php?action=patchCandidate',
          data: formdata,
          dataType: 'json',
          contentType: false,
          processData: false,
          success: function(response){
            console.log('candidateResponse', response)
          }
        });
        $('.preloader').removeClass('preloaderVisible');
        //window.location.href = "/";
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
        $( this ).addClass('starSelected');
      }
      else{
        $( this ).removeClass('starSelected');
      }
    });
  })

  $('.star').on('mouseleave', function(){
    $( '.star' ).removeClass('starSelected');
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
        $( this ).addClass('starChecked');
      }
      else{
        $( this ).removeClass('starChecked');

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

  $('.skillCheckbox').on('change', function(evt) {
    var checked = jQuery(this).is(':checked');
    var company = jQuery(this).data('company');
    var checkbox = jQuery(this).next('.customCheckbox');
    if(checked){
      jQuery(checkbox).addClass(company);
    }
    else{
      jQuery(checkbox).removeClass(company);
    }
  });



  // Recaptcha Validation

  var recaptchaValidState;

  function recaptchaValidation(){
      var $captcha = $( '#recaptcha' ),
      response = grecaptcha.getResponse();
      //console.log(response.length);

      if (response.length === 0) {
        $( '.msg-error').text( "reCAPTCHA is mandatory" );
        if( !$captcha.hasClass( "error" ) ){
          $captcha.addClass( "error" );
        }
        //alert( 'Bitte das reCAPTCHA ausfüllen!' );
        recaptchaValidState = false;
      } else {
        $( '.msg-error' ).text('');
        $captcha.removeClass( "error" );
        recaptchaValidState = true;
      }
  }
  var inputValidState;

  function inputValidation(){
    var inputText = $('.inputText');
    inputText.each(function( index ) {
      var value = $(this).val();
      if(value.length === 0){
        $(this).parent().next('.warning').addClass('warningVisible');
        //alert('Bitte die Pflichtfelder ausfüllen!');
        inputValidState = false;
      }
      else{
        $(this).parent().next('.warning').removeClass('warningVisible');
        inputValidState =  true;
      }
    });
  }

  $('.inputText').on('change', function(){
    var value = $(this).val();
    if(value.length === 0){
      $(this).parent().next('.warning').addClass('warningVisible');
    }
    else{
      $(this).parent().next('.warning').removeClass('warningVisible');
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
    inputValidation();
    recaptchaValidation();

    var fd = new FormData(form[0]);
    fd.append('file',uploadFile);

    if(inputValidState && recaptchaValidState){
      uploadData(fd);
    }
    else{
      console.log(inputValidState, recaptchaValidState);
      var modalElement = jQuery('#validationModal');
      if(!inputValidState && !recaptchaValidState){
        jQuery('.warningText').text('Bitte Pflichtfelder und Recpatcha ausfüllen')
      }
      else if(inputValidState && !recaptchaValidState){
        jQuery('.warningText').text('Bitte Recpatcha ausfüllen')
      }
      else if(!inputValidState && recaptchaValidState){
        jQuery('.warningText').text('Bitte Pflichtfelder ausfüllen')
      }
      UIkit.modal(modalElement).show();

      //alert(inputValidState, recaptchaValidState);
    }
  });
});
