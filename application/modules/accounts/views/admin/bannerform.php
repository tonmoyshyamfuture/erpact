<div class="wrapper2">
    <form role="form" action="<?php echo base_url('banner/admin/bannermodify');?>" method="post" id="cms_form" enctype="multipart/form-data">
<section class="content-header">
    <div class="row">
        <div class="col-xs-6">
          <h1><i class="fa fa fa-image"></i> Banner Add/Update</h1>
        </div>		
        <div class="col-xs-6">
            <div class="pull-right">
                <input class="btn btn-primary" type="submit" value="Save"/>
            </div>
        </div>		
    </div>     
</section>
<!-- Main content -->
<section class="content">
    <div class="row">        
        <!-- left column -->
        <!-- right column -->
        <div class="col-md-12">
            <!-- general form elements disabled -->
            <div class="box box-warning">
                <div class="box-body">

                        <!-- text input --> 
                        <div class="form-group">
                            <label>Image</label>
                            <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse... <input name="image" type="file">
                                        </span>
                                    </span>
                                    <input class="form-control" readonly="" type="text">
                                </div>
                            
                            <!-- <input type="file" class="form-control" name="image" />-->
                            <?php echo form_error('image', '<div class="error">', '</div>'); ?>
                            <?php
                            if(!empty($bannerdetsingle->image))
                            {
                                ?>
                                <br /><img src="<?php echo base_url('application/modules/banner/uploads/thumb/'.$bannerdetsingle->image);?>" />
                                <?php
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control ckeditor" name="description" placeholder="Enter Description"><?php if(!empty($bannerdetsingle)) { echo $bannerdetsingle->description; } ?></textarea>
                                <?php echo form_error('description', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="footer-button">
                            <input type="hidden" name="bannerid" <?php if(!empty($bannerdetsingle)) { ?> value="<?php echo $bannerdetsingle->id;?>" <?php } ?> />
                            <input class="btn btn-primary" type="submit" value="Save"/>
                        </div>
                    
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!--/.col (right) -->        
    </div>   <!-- /.row -->    
</section><!-- /.content -->
</form>
</div>
        <script>
          $("document").ready(function(){
            $("#cms_form").validate({
              ignore: [],
              rules:{
                image:"required",
                description:"required"
              },
              messages:{
                image:"Please Enter Image..!!",
                content:"Please Enter Description..!!"
              }
            });
          });
        </script>
        <style>
          .error{
            color: #dd0000;
          }
        </style>



