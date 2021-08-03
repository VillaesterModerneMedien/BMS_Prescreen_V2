var imNotARobot = function() {
  jQuery('#recaptcha').trigger('click');
};
jQuery(document).ready(function($){

  var fileValidation = true;
  var fileCounter = 0;

  jQuery('body').on('change', function(){
    bodyChange();
  });
  jQuery('#recaptcha').on('click', function(){
    bodyChange();
  });

  function bodyChange(){
    inputValidation();
    recaptchaValidation();

    if(recaptchaValidState && inputValidState && fileValidation){
      jQuery('#sendCandidate').attr('disabled', false);
    }
    else{
      jQuery('#sendCandidate').attr('disabled', true);
    }
  }

  function dragFileToInput(dragContainer,fileInputID, uploadMessage, allowedTypes, maxFiles){
    var target = document.getElementById(dragContainer);
    var body = document.body;
    var fileInput = document.getElementById(fileInputID);

    target.addEventListener('dragover', (e) => {
      e.preventDefault();
      body.classList.add('dragging');
    });

    target.addEventListener('dragleave', () => {
      body.classList.remove('dragging');
    });

    target.addEventListener('drop', (e) => {
      e.preventDefault();
      body.classList.remove('dragging');

      fileInput.files = e.dataTransfer.files;
      var files = fileInput.files;
      var filesArray = Array.from(files);
      fileCounter = filesArray.length;

      filesArray.forEach(function( file, index ) {
        var position = file.name.lastIndexOf('.');
        var length = file.name.length;
        var fileType = file.name.substr(position,length);
        fileType = fileType.replace('.','');
        console.log(file.name, position, length, fileType, fileCounter);
        fileValidation = allowedTypes.includes(fileType);

        var modalElement = jQuery('#validationModal');
        if(!allowedTypes.includes(fileType)){
          jQuery('.warningText').text('Falsches Dateiformat')
          UIkit.modal(modalElement).show();
        }
        else if(fileCounter > maxFiles){
          jQuery('.warningText').text('Beim Lebenslauf ist nur eine Datei erlaubt')
          UIkit.modal(modalElement).show();
        }

        inputValidation();
        recaptchaValidation();

        if(recaptchaValidState && inputValidState && fileValidation && fileCounter < maxFiles){
          jQuery('#sendCandidate').attr('disabled', false);
        }
        else{
          jQuery('#sendCandidate').attr('disabled', true);
        }

      });

      var filenames = '';
      for (i = 0; i < files.length; i++) {
        if(i > 0){
          filenames = filenames + ', ' +  files[i].name;
        }
        else{
          filenames = filenames + files[i].name;
        }
      }

      $('.' + uploadMessage).html(filenames);
    });

  }

  dragFileToInput('uploadAdditional','filesAdditional', 'uploadMessageAdd',['doc','docx', 'pdf', 'rtf'],99);
  dragFileToInput('uploadCV','fileCV', 'uploadMessageCV', ['doc','docx', 'pdf', 'rtf'], 1);


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
    fd.append('file',uploadCV);

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
