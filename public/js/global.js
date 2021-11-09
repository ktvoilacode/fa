
  // auto fadeout for alert message
  $('div.alert').not('.alert-important').delay(6000).fadeOut(350);

  $('.alertclose').on('click',function(){
    $('.alert').hide();
  });

  $(".range").change(function(){
    $value=$(this).val();
    if($value)
    $url = $(this).data('url')+'&range='+$value;
    else
    $url = $(this).data('url');
    window.location = $url;
  });

  // search data
  $('#search').on('keyup',function(){
    $value=$(this).val();
    $url = $(this).data('url');
    $.ajax({
      type : 'get',
      url : $url,
      data:{'search':true,'item':$value},
      success:function(data){
        $('#search-items').html(data);
      }
    });
  });

  $('#search2').on('keyup',function(){
    $value=$(this).val();
    $url = $(this).data('url');
    $.ajax({
      type : 'get',
      url : $url,
      data:{'search':true,'item2':$value},
      success:function(data){
        $('#search-items2').html(data);
      }
    });
  });


  $(document).on('click', '.login_api', function(e){
      e.preventDefault();
      $('.alert').hide();
      $('.spinner-border').show();
      $email = $('input[name="email2"]').val();
      $password = $('input[name="password2"]').val();
      $_token = $('input[name="_token"]').val();

      $url = $(this).data('url');
        $.ajax({
          type : 'post',
          url : $url,
          data:{'email':$email,'password':$password,'_token':$_token},
          success:function(data){
            $('.spinner-border').hide();
            //console.log(data);
            d = JSON.parse(data);
            if(d.error){
              $('.alert-message').html(d.message);
              $('.alert').show();
            }else{
              $('.loginmodal').modal('toggle');
              setTimeout("location.reload(true);", 1);
            }
            
          }
        });
  });

  $(document).on('click', '.otp_submit', function(e){
      e.preventDefault();
      $('.alert').hide();
      $('.spinner-border').show();
      $sms_code = $('input[name="sms_code"]').val();
      $_token = $('input[name="_token"]').val();

      $url = $(this).data('url');
        $.ajax({
          type : 'post',
          url : $url,
          data:{'sms_code':$sms_code,'api':1,'_token':$_token},
          success:function(data){
            $('.spinner-border').hide();
            //console.log(data);
            d = JSON.parse(data);
            if(d.error){
              $('.alert-message').html(d.message);
              $('.alert').show();
            }else{
              $('.loginmodal').modal('toggle');
              setTimeout("location.reload(true);", 1);
            }
            
          }
        });
  });

  $(document).on('click', '.register_api', function(e){
      e.preventDefault();
      $('.spinner-border').show();
      $('.alert').hide();
      //console.log("register api");

      $name = $('input[name="name"]').val();
      $email = $('input[name="email"]').val();
      $phone = $('input[name="phone"]').val();
      $password = $('input[name="password"]').val();
      $repassword = $('input[name="repassword"]').val();
      $_token = $('input[name="_token"]').val();

      $error =0;
      console.log('register api clicked');
      
      if($name.length==0)
      {
        $('.alert-message').html("Kindly enter your name");
        $('.alert').show();
        $error =1;
      }

      if($email.length==0)
      {
        $('.alert-message').html("Kindly enter your email");
        $('.alert').show();
        $error =1;
      }

      if($phone.length==0)
      {
        $('.alert-message').html("Kindly enter the phone number");
        $('.alert').show();
        $error =1;
      }

      if($password!=$repassword)
      {
        $('.alert-message').html("Password and confirm password mismatch");
        $('.alert').show();
        $error =1;
      }

      if($password.length<8){
         $('.alert-message').html("Password cannot be less than 8 characters");
         $('.alert').show();
         $error =1;
      }

      if(!$error){
        $url = $(this).data('url');
        $.ajax({
          type : 'post',
          url : $url,
          data:{'name':$name,'email':$email,'phone':$phone,'password':$password,'_token':$_token},
          success:function(data){
            $('.spinner-border').hide();
            d = JSON.parse(data);
            if(d.error){
              $('.alert-message').html(d.message);
              $('.alert').show();
            }else{
              // $('.register_form').hide();
              // $('.otp_activation').show();
              console.log('here');
              $('.loginmodal').modal('toggle');
              setTimeout("location.reload(true);", 1);
            }
            
          }
        });
      }else{
        $('.spinner-border').hide();
      }
      
  });

  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
  });

  $(function () {
    $('[data-toggle="popover"]').popover()
  });


  $(".hover-border").on({
    mouseenter: function () {
        //stuff to do on mouse enter
        $(this).addClass('border');
    },
    mouseleave: function () {
        //stuff to do on mouse leave
        $(this).removeClass('border');
    }
  });

  $(".hover-bg").on({
    mouseenter: function () {
        //stuff to do on mouse enter
        $(this).addClass('bg-shadow');
    },
    mouseleave: function () {
        //stuff to do on mouse leave
        $(this).removeClass('bg-shadow');
    }
  });

    $(function(){
    $('.btn-error-report').on('click',function(e){
      e.preventDefault();
      $('.spinner-border').show();
      $name = $('input[name="name"]').val();
      $email = $('input[name="email"]').val();
      $phone = $('input[name="phone"]').val();
      $test = $('input[name="test"]').val();
      $qno = $('input[name="qno"]').val();
      $details = $.trim($(".details").val());
      $_token =  $('input[name="_token"]').val();
      $url = $('input[name="url"]').val();

      
      
      $.post( $url, {'name':$name,'email':$email,'qno':$qno,'details':$details,
        '_token':$_token,'phone':$phone,'test':$test},function( data ) {
        $( ".modal-body" ).html( data );
      });
      
    });
      
  });


  $(function(){
    $('.modal-e').hide();
    $('.btn-ajax').on('click',function(e){
      e.preventDefault();
      $('.spinner-border').show();
      var dataString = $('.choose_a_slot').serialize();
      $url = $('input[name="url"]').val();

      $.ajax({
            url: $url,
            type:"POST",
             beforeSend: function (xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                  return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
            },
            data: dataString,
            success:function(data){
                if(data.length < 40)
                {
                    $('.modal-e').show();
                    $(this).hide();
                    $( ".modal-e" ).html( data );
                }else{
                    $('.modal-e').hide();
                    $( ".modal-b" ).html( data );
                }
                $('.spinner-border').hide();
            },error:function(){ 
                alert("error!!!!");
            }
        }); //end of aja    
    });
      
  });

  $('.view-more').on('click',function(e){
        $(this).hide();
          $('.test_block').show();
          e.preventDefault();
  });

  if($('.toast').length){
        $('.toast').toast({autohide:false});
          setTimeout(function () {
          $('.toast').toast('show');
        },2000);
      }


   (function($) {
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 100,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };
})(jQuery);

jQuery(function($) {

        $('.counter').countTo({
            from: 0,
            to: $('.counter').text(),
            speed: 3000,
            refreshInterval: 50,
            onComplete: function(value) {
                console.debug(this);
            }
        });

    });

  
