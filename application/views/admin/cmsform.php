
<section class="content-header">
    <h1>CMS</h1>
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


                    <form role="form" action="<?php echo $this->config->item('base_url'); ?>admin/cmsupdate/<?php echo!empty($cmslist->id) ? urlencode(base64_encode($cmslist->id)) : ''; ?>" method="post" id="cms_form">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" 
                                <?php
                                $metatitle = set_value('title');
                                if (!empty($metatitle)) {
                                    ?>
                                       value="<?php echo set_value('title'); ?>"
                                <?php
                            } else {
                                if (!empty($cmslist->title)) {
                                    ?> 
                                           value="<?php echo $cmslist->title; ?>" 
                                           <?php
                                       }
                                   }
                                   ?> 
                                      placeholder="Enter Title"/>
                            <input type="hidden" name="titlecms" value="<?php echo (!empty($cmslist->title)) ? $cmslist->title : ''?>">
                            <?php echo form_error('title', '<div class="error">', '</div>'); ?>
                         
                        </div>

                        <div class="form-group">
                            <label>Content</label>
                            <textarea class="form-control ckeditor" name="content" placeholder="Enter Content">
                                <?php
                                $metatitle = set_value('content');
                                if (!empty($metatitle)) {
                                    ?>
                                <?php echo html_entity_decode($metatitle, ENT_QUOTES, 'utf-8'); ?>
                                     
                                    <?php
                                } else {
                                    if (!empty($cmslist->content)) {
                                        ?> 
                                         <?php echo html_entity_decode($cmslist->content, ENT_QUOTES, 'utf-8'); ?>
                                        <?php
                                    }
                                }
                                ?> 
                            </textarea>
                                <?php echo form_error('content', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label>Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" <?php
                                $metatitle = set_value('meta_title');
                                if (!empty($metatitle)) {
                                    ?>
                                       value="<?php echo set_value('meta_title'); ?>"
                                <?php
                            } else {
                                if (!empty($cmslist->meta_title)) {
                                    ?> 
                                           value="<?php echo $cmslist->meta_title; ?>" 
                                           <?php
                                       }
                                   }
                                   ?> 
                                   placeholder="Enter Meta Title"/>
                             <input type="hidden" name="metatitlecms" value="<?php echo (!empty($cmslist->meta_title)) ? $cmslist->meta_title : ''?>">
                                   <?php echo form_error('meta_title', '<div class="error">', '</div>'); ?>
                            
                        </div>
                        <div class="form-group">
                            <label>Meta Key</label>
                            <input type="text" class="form-control" name="meta_key" 

                                   <?php
                                   $metatitle = set_value('meta_key');
                                   if (!empty($metatitle)) {
                                       ?>
                                       value="<?php echo set_value('meta_key'); ?>"
    <?php
} else {
    if (!empty($cmslist->meta_key)) {
        ?> 
                                           value="<?php echo $cmslist->meta_key; ?>" 
                                           <?php
                                       }
                                   }
                                   ?> 

                                   placeholder="Enter Meta Key"/>
                            <input type="hidden" name="metakeycms" value="<?php echo (!empty($cmslist->meta_key)) ? $cmslist->meta_key : ''?>">
                                   <?php echo form_error('meta_key', '<div class="error">', '</div>'); ?>
                        </div>
                        <div class="form-group">
                            <label>Meta Description</label>
                            <textarea  name="meta_description" id="meta_description" rows="10"  class="form-control">
                                   <?php
                                   $metatitle = set_value('meta_description');
                                   if (!empty($metatitle)) {
                                       ?>
                              
                                      <?php echo html_entity_decode($metatitle, ENT_QUOTES, 'utf-8'); ?>
                                       <?php
                                   } else {
                                       if (!empty($cmslist->meta_description)) {
                                           ?> 
               <?php echo html_entity_decode($cmslist->meta_description, ENT_QUOTES, 'utf-8'); ?>
                                         
        <?php
    }
}
?> 
                            </textarea>
                                <?php echo form_error('meta_description', '<div class="error">', '</div>'); ?>
                        </div>

                        <div class="footer-button">
                            <input class="btn btn-primary" type="submit" value="Update"/>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!--/.col (right) -->
    </div>   <!-- /.row -->
</section><!-- /.content -->


        <script>
          $("document").ready(function(){
            $("#cms_form").validate({
              ignore: [],
              rules:{
                title:"required",
                content:"required",
                meta_title:"required",
                meta_key:"required",
                meta_description:"required"
              },
              messages:{
                title:"Please Enter Title..!!",
                content:"Please Enter Content..!!",
                meta_title:"Please Enter Meta Title..!!",
                meta_key:"Please Enter Meta Key..!!",
                meta_description:"Please Enter Meta Description..!!"
              }
            });
          });
        </script>
        <style>
          .error{
            color: #dd0000;
          }
        </style>



