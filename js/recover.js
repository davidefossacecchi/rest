$('button').click(function(event){recover(event)});

function recover(event){
  event.preventDefault();
  
    var form = $('form').serialize();
    var jqxhr = $.ajax({
        type: "POST",
        url:"recover.php",
        data: form
    }).success(function(resp){
      location.href="index.html";
    }).fail(function(data){
      $("#message").slideUp(function(){
        $('#message').removeClass("success").addClass("error");
        switch(jqxhr.status){
          case 400:
            $("#message").html("There is some empty field");
          break;
          case 401:
            $("#message").html("You can't recover that user's password");
          break;
          case 404:
            $("#message").html("User not registered");
          break;
        }
        $("#message").slideDown();
      });
    });

}