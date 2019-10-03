<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>   <?php echo $title;?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php echo $this->config->item('base_url');?>assets/admin/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    <!-- FONT AWESOME -->
    <link href="<?php echo $this->config->item('base_url');?>assets/font-awesome/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->config->item('base_url');?>assets/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>assets/admin/sketch_custom/css/custom.css" type="text/css" />
    <!-- Developer CSS -->
    <link href="<?php echo $this->config->item('base_url');?>assets/developer.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head> 
  <body class="login-page">


   <?php echo $content;?>
   <div style="display:block;text-align:center;">
    <?php
    if (file_exists(APPPATH."modules/language/views/language.php"))
    {
      echo langview();
    }
    ?>
    </div>

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/js/jquery.validate.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo $this->config->item('base_url');?>assets/admin/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
      
      $(document).ready(function(){
        $('.closebutn').click(function(){
          $('.overlay').fadeOut();
          $('.successmessage').fadeOut();
          $('.errormessage').fadeOut();
        });
      });
      
      $(document).ready(function(){
        $('.overlay').delay(1000).fadeOut();
        $('.successmessage').delay(1000).fadeOut();
        $('.errormessage').delay(1000).fadeOut();
      });
    </script>
    <?php
    if (file_exists(APPPATH."modules/language/views/script.php"))
    {
      echo scriptview(); 
    }
    ?>
    <style>
        .goog-te-gadget-simple .goog-te-menu-value span{font-size: 11px !important; margin-right: 5px!important; font-family: inherit !important}
        .goog-te-gadget-simple { background-color: #333 !important;  border-color: #fff !important; }
    </style>

    <link href="<?php echo base_url(); ?>/assets/css/jquery-confirm.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>/assets/js/jquery-confirm.min.js"></script>
    <?php if(!empty($this->session->flashdata('errormessage'))) { ?>
    <script>
     $(document).ready(function(){
              var errormessage="<?php echo  $this->session->flashdata('errormessage') ?>";  
              var popconfim=$.confirm({
                  title: false, // hides the title.
                  cancelButton: false ,// hides the cancel button.
                  confirmButton: false, // hides the confirm button.
                  closeIcon: true, // shows the close icon.
                  content: errormessage,
               });
              $(".jconfirm .jconfirm-box div.content").addClass("error-confirm");

            setTimeout(function(){popconfim.close();},2500);

     });

    </script>

    <?php } ?>


        <?php if(!empty($this->session->flashdata('successmessage'))) { ?>
    <script>
     $(document).ready(function(){
            var successmessage="<?php echo  $this->session->flashdata('successmessage') ?>";
            var popconfim=$.confirm({
                  title: false, // hides the title.
                  cancelButton: false ,// hides the cancel button.
                  confirmButton: false, // hides the confirm button.
                  closeIcon: true, // shows the close icon.
                 content: successmessage,
               });

              $(".jconfirm .jconfirm-box div.content").addClass("success-confirm");

            setTimeout(function(){popconfim.close();},2500);


     });

    </script>

    <?php } ?>

    <script type="text/javascript">

    // override jquery validate plugin defaults
                $.validator.setDefaults({
                    highlight: function(element) {

                        $(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    },
                    errorElement: 'span',
                    errorClass: 'help-block',
                    errorPlacement: function(error, element) {
                        if (element.parent('.form-control').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

        $(function(){
          $("#cf-loginForm").validate({
            rules: {
              'email': {
                required:true,
                email:true
              },
              'password':"required"
            },
            messages : {
              'email': {
                required:"Enter your email address",
                email:"Enter valid email address"
              },
              'password':"Enter your password"
            }
          });
        });

        $(function(){
          $("#froget-password-frm").validate({
            rules: {
              'email': {
                required:true,
                email:true
              }
            },
            messages : {
              'email': {
                required:"Enter your email address",
                email:"Enter valid email address"
              }
            }
          });
        });
    </script>

  </body>
</html>