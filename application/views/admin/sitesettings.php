<div class="wrapper2">
    <form  enctype="multipart/form-data" id="site_settings" name="site_settings" role="form" action="<?php echo $this->config->item('base_url');?>admin/updatesettings" method="post">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1><span class="lnr lnr-cog"></span> General Settings</h1>  
                </div> 
		<?php
		$permission=admin_users_permission('E','site-settings',$rtype=FALSE);
		if($permission)
		{
		?>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <input class="btn btn-primary" type="button" value="Save" onclick="$('#site_settings').submit();"/> 
                    </div>
                </div>
		<?php
		}
		?>
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
                  
                      
                      <div class="row">
                          <div class="col-md-6">
                              <!-- text input -->
                                <div class="form-group">
                                    <label>Site Name </label><span title="This is your Store Name, will be displayed in the front end" data-toggle="tooltip"  data-placement="left"  class="tool-tip fa fa-info"></span>
                                  <input type="text" class="form-control" name="sitename" value="<?php echo get_setting_by_name('sitename');?>" placeholder="Enter Site Name"/>
                                </div>
                                <div class="form-group">
                                <label>Account Email</label>
                                <input type="text" class="form-control" name="acc_email" value="<?php echo get_setting_by_name('acc_email');?>" placeholder="Enter Account Email"/>
                      
                              </div>
                            
                              
                              <div class="form-group">
                                <label>Customer Email</label>
                                <input type="text" class="form-control" name="cust_email" value="<?php echo get_setting_by_name('cust_email');?>" placeholder="Enter Customer Email"/>
                              </div>
                            
                              <!-- <div class="form-group">
                                <label>Login Background color</label>
                                <input type="text" class="form-control color" name="logback_color" value="<?php echo $sitesettings->logback_color;?>" placeholder="Enter Login Background Color"/>
                                <input type="hidden" name="old_logback_color" value="<?php echo get_setting_by_name('logback_color');?>"/>
                              </div>
                              <div class="row">
                              
                              <div class="form-group col-sm-6">
                                <label>Base Color</label>
                                <input type="text" class="form-control color" name="base_color" value="<?php echo $sitesettings->base_color;?>" placeholder="Enter Base Color"/>
                                <input type="hidden" name="old_base_color" value="<?php echo get_setting_by_name('base_color');?>"/>
                              </div>
                              <div class="form-group col-sm-6">
                                <label>Contrast Color</label>
                                <input type="text" class="form-control color" name="contrast_color" value="<?php echo $sitesettings->contrast_color;?>" placeholder="Enter Contrast Color"/>
                                <input type="hidden" name="old_contrast_color" value="<?php echo get_setting_by_name('contrast_color');?>"/>
                              </div> -->
                          </div>
                          <div class="col-md-6">                              
                              <div class="form-group">                                  
                                  <label>Store Logo</label>
                                  <span title="Add your Store Logo, click on Browse... button below" data-toggle="tooltip" data-placement="left" class="tool-tip fa fa-info"></span>
                                  <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse&hellip; <input type="file" name="sitelogo" id="sitelogo" class=""  placeholder="Enter Site Logo"/>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>                               
                               <div class="uploadedimg">
                               <?php 
                                  $img=get_setting_by_name('sitelogo');
                                  if(!empty($img)){
                               ?>
                                   <img src="<?php echo $this->config->item('base_url');?>/assets/images/<?php echo $img;?>" />

                               <input type="hidden" name="oldsitelogo" id="<?=$img;?>" class="form-control"  placeholder="Enter Site Logo"/>

                               <?php } ?>
                               </div>
                              </div>

                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">                                  
                                  <label>Store Favicon</label>
                                  <span title="Add your Store favicon, click on Browse... button below" data-toggle="tooltip" data-placement="left" class="tool-tip fa fa-info"></span>
                                  <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse&hellip; <input type="file" name="favicon" id="favicon" class=""  placeholder="Enter favicon"/>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>                               
                               <div class="uploadedimg">
                               <?php 
                                  $img=get_setting_by_name('favicon');
                                  if(!empty($img)){
                               ?>
                                   <img src="<?php echo $this->config->item('base_url');?>/assets/images/<?php echo $img;?>" />

                               <input type="hidden" name="oldfavicon" id="<?=$img;?>" class="form-control"  placeholder="Enter favicon"/>

                               <?php } ?>
                               </div>
                              </div>
                            
                          </div>
                          <div class="col-md-6">                              
                              <div class="form-group">                                  
                                  <label>Invoice Logo</label>
                                  <span title="Add your invoice logo, click on Browse... button below" data-toggle="tooltip" data-placement="left" class="tool-tip fa fa-info"></span>
                                  <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse&hellip; <input type="file" name="invoicelogo" id="invoicelogo" class=""  placeholder="Enter Invoice Logo"/>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>                               
                               <div class="uploadedimg">
                               <?php 
                                  $img=get_setting_by_name('invoicelogo');
                                  if(!empty($img)){
                               ?>
                                   <img src="<?php echo $this->config->item('base_url');?>/assets/images/<?php echo $img;?>" />

                               <input type="hidden" name="oldinvoicelogo" id="<?=$img;?>" class="form-control"  placeholder="Enter Invoice Logo"/>

                               <?php } ?>
                               </div>
                              </div>

                          </div>
                      </div>
                      <hr>
                      <div class="row">     
                          <h3 class="sub-heading">Social Links </h3>
                    <div class="form-group col-md-6">
                      <label>Facebook Link</label>
                      <input type="text" class="form-control" name="fb_link" value="<?php echo get_setting_by_name('fb_link');?>" placeholder="Enter Facebook Link"/>
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label>Twitter Link</label>
                      <input type="text" class="form-control" name="tw_link" placeholder="Enter Twitter Link" value="<?php echo get_setting_by_name('tw_link');?>"/>
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label>Google Plus</label>
                      <input type="text" name="gp_link" value="<?php echo get_setting_by_name('gp_link');?>" class="form-control" placeholder="Enter Google Plus Link"/>
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label>Linkedin Link</label>
                      <input type="text" class="form-control" name="li_link" placeholder="Enter Linkedin Link" value="<?php echo get_setting_by_name('li_link');?>" />
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label>Instagram Link</label>
                      <input type="text" class="form-control" name="ins_link" placeholder="Enter Instagram Link" value="<?php echo get_setting_by_name('ins_link');?>"/>
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label>Pinterest Link</label>
                      <input type="text" class="form-control" name="pin_link" placeholder="Enter Pinterest" value="<?php echo get_setting_by_name('pin_link');?>"/>
                    </div>
                          <div class="clearfix"></div>
                    <div class="form-group col-md-6">
                        
                      <div class="radio-inline">
                        <?php $sstatus = get_setting_by_name('online_status'); ?>
                        <label>
                          <input type="radio" class="minimal" name="online_status" value="1" <?php if($sstatus ==1) {  ?> checked="true" <?php } ?>>
                          Site Online
                        </label>
                      </div>
                      <div class="radio-inline">
                        <label>
                          <input type="radio" class="minimal" name="online_status" value="0" <?php if($sstatus ==0) {  ?> checked="true" <?php } ?>>
                          Site Offline
                        </label>
                      </div>
                      <span title="Site status" data-toggle="tooltip"  data-placement="left"  class="tool-tip fa fa-info"></span>
                      
                        </div>
                    </div>
		      <?php
			$permission=admin_users_permission('E','site-settings',$rtype=FALSE);
			if($permission)
			{
		      ?>
                      <div class="footer-button">
                        <input class="btn btn-primary" type="submit" value="Save"/>  
                        <input type="hidden" name="redirect_url" value="admin/sitesettings"/>  
                      </div>
		      <?php
			}
		       ?>                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
        </form>
        </div>
        <script>
          $("document").ready(function(){
            $("#site_settings").validate({
              rules:{
                sitename:"required",
                fb_link:{ 
                  "required":true,
                  "url":true
                },
                tw_link:{
                  "required":true,
                  "url":true
                },
                gp_link:{
                  "required":true,
                  "url":true
                },
                li_link:{
                  "required":true,
                  "url":true
                },
                ins_link:{
                  "required":true,
                  "url":true
                },
                pin_link:{
                  "required":true,
                  "url":true
                },
                online_status:"required"
              },
              messages:{
                sitename:"Please Enter Site Name..!!",
                fb_link:{
                  "required":"Please Enter Facebook Link..!!",
                  "url":"Please Enter Valid Facebook Link..!!"
                },
                tw_link:{
                  "required":"Please Enter Twitter Link..!!",
                  "url":"Please Enter Valid Twitter Link..!!"
                },
                gp_link:{
                  "required":"Please Enter Google Plus Link..!!",
                  "url":"Please Enter Valid Google Plus Link..!!"
                },
                li_link:{
                  "required":"Please Enter Linkedin Link..!!",
                  "url":"Please Enter Valid Linkedin Link..!!"
                },
                ins_link:{
                  "required":"Please Enter Instagram Link..!!",
                  "url":"Please Enter Valid Instagram Link..!!"
                },
                pin_link:{
                  "required":"Please Enter Pinterest Link..!!",
                  "url":"Please Enter Valid Pinterest Link..!!"
                },
                online_status:"Please Select Online Status..!!"
              }
            });
          });
          
        </script>
        <style>
          .error{
            color: #dd0000;
          }
        </style>

    
