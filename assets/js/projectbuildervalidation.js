$(function () {
   
  $("#newsletteremail").validate({
      rules: {
        emailnewsletter: {
          required : true,
          email : true,
          remote : {
            url : "<?php echo base_url().'front/checkUniqueSubscriber' ?>",
            method: "post",
            data:{
              field: "emailnewsletter",
              value : function(){ return $("#emailnewsletter").val(); } 
            }   
          }
        },
      },
      messages: {
      
        email: {
           required : "Please enter your email address",
           email : "Please enter a valid email address",
           remote : "Email address already registered"
        }
      },
     errorPlacement: function (error, element) {
        error.insertAfter($("#errornewsletter"));
       }

    });
    
});



