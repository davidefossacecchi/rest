$('button').click(function(event){edit(event)});
$('#delete').click(function(event){unregister(event)});

function unregister(event){

  event.preventDefault();
  var jqxhr = $.ajax({
        type: "DELETE",
        url:rest_path
    }).success(function(resp){
      location.href="logout.php";
    }).fail(function(data){
      location.href="logout.php";
    });
}

function edit(event){
  event.preventDefault();
  if($("input[name='password']").val()!=$("input[name='password_conf']").val()){
    $("#message").slideUp(function(){
      
      $('#message').removeClass("success").addClass("error");
      $("#message").html("The two password fields don't match");
      $("#message").slideDown();

    });
  }
  else{
    var form = $('form').serialize();
    var jqxhr = $.ajax({
        type: "PUT",
        url:rest_path,
        data: form
    }).success(function(resp){
      $("#message").slideUp(function(){
        $('#message').removeClass("error").addClass("success");
        $("#message").html("Profile correctly edited");
        $("#message").slideDown();
      });
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
            location.href="index.html";
          break;
          case 409:
            $("#message").html("There is a user with the same email");
          break;
        }
        $("#message").slideDown();
      });
    });
  }
}