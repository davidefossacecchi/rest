$(document).ready(function(){
  var method="POST";
  $('button').click(function(event){login(event)});

  //eye candy for the register function
  $('#register').click(function(event){
    event.preventDefault();
    method="PUT";
    $('input[name="password"]').slideDown(function(){
      $('input[name="password_conf"]').slideDown();
      $('#register').slideUp(function(){
        $('#login').slideDown();
        $('#recover').slideDown(function(){
          $('button[type="submit"]').html("Sign Up");
        });
      });
    });
  });

  //eye candy for the login function
  $('#login').click(function(event){
    event.preventDefault();
    method="POST";
    $('input[name="password"]').slideDown(function(){
      $('input[name="password_conf"]').slideUp();
      $('#login').slideUp(function(){
        $('#register').slideDown();
        $('#recover').slideDown(function(){
          $('button[type="submit"]').html("Login");
        });
      });
    });
  });

  //eye candy for the recover function
  $('#recover').click(function(event){
    event.preventDefault();
    method="GET";
    $('input[name="password_conf"]').slideUp(function(){
      $('input[name="password"]').slideUp();
      $('#register').slideDown(function(){
        $('#login').slideDown();
        $('#recover').slideUp(function(){
          $('button[type="submit"]').html("Recover");
        });
      });
    });
  });

  function login(event){
    event.preventDefault();
    if((method == "PUT") && ($("input[name='password']").val()!=$("input[name='password_conf']").val())){
      $("#message").slideUp(function(){
        
        $('#message').removeClass("success").addClass("error");
        $("#message").html("The two password fields don't match");
        $("#message").slideDown();

      });
    }
    else{
      var form = $('form').serialize();
      var jqxhr = $.ajax({
          type: method,
          url:'login.php',
          data: form
      }).success(function(resp){
        $("#message").slideUp(function(){
          if(method == "GET"){
            $('#message').removeClass("error").addClass("success");
            $("#message").html("We have sent an email to you");
            $("#message").slideDown();
          }
          else{
            location.href="manage.php";
          }
        });//location.href=resp;
        
      }).fail(function(data){
        $("#message").slideUp(function(){
          $('#message').removeClass("success").addClass("error");
          switch(jqxhr.status){
            case 404:
              $("#message").html("User not registered");
            break;
            case 400:
              $("#message").html("There is some empty field");
            break;
            case 401:
              $("#message").html("Incorrect username and/or password");
            break;
            case 409:
              $("#message").html("That user is already registered");
            break;
          }
          $("#message").slideDown();
        });
      });
    }
  }
});
