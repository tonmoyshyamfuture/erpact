<div class="wrapper2">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-image"></i> Banners</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <?php
			$permissionadd=admin_users_permission('A','banner',$rtype=FALSE);
			if($permissionadd)
			{
		  ?>
                  <input type="button" class="btn btn-primary" value="Add Banner" onclick="window.location.href='<?php echo site_url('admin/add-banner');?>'" />
		  <?php
			} 
		  ?>
                    </div>
                </div>
            </div>  
            
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">                
                <div class="box-body table-fullwidth">
                    <div class="table-responsive">
                  <table id="example00" class="table table-bordered table-striped fcol-50 lcol-70">
                    <thead>
                      <tr>
                          <!-- <th><input type="checkbox" class="checkAll checkbox icheck" value=""></th>-->
                        <th></th>
			<th>Source</th>                        
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(!empty($bannerdet))
                      {
                       foreach($bannerdet as $bannerlist)
                       {
                       ?>
                        <tr>
                            <!-- <td><input type="checkbox" class="checkdel" value=""></td>-->
                            <td><a title="Cliek to edit this banner" data-toggle="tooltip" class="<?php echo $editpermission; ?>" href="<?php echo site_url('admin/edit-banner');?>/<?php echo urlencode(base64_encode($bannerlist->id));?>"><img src="<?php echo base_url('application/modules/banner/uploads/thumb/'.$bannerlist->image);?>" /></a></td>
                            <td><a title="View" data-toggle="tooltip"  href="<?php echo base_url('application/modules/banner/uploads/'.$bannerlist->image); ?>" target="_blank"><?php echo base_url('application/modules/banner/uploads/'.$bannerlist->image); ?></a></td>
                          <td>
                              <div class="dropdown circle">
                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i></a>
                                    <ul class="dropdown-menu tablemenu">
                                        <li> 
                            <?php
                            if($bannerlist->status==1)
                            {
                            ?>
                            <a  title="Change status" data-toggle="tooltip" class="<?php echo $statuspermission; ?>" href="javascript:void(0);" onclick="change_status('<?php echo urlencode(base64_encode($bannerlist->id)); ?>','Are you sure to Inactive this Banner?')"><span style="color: #00bc00;"><i class="fa fa-unlock"></i></span></a>
                            <?php
                            }
                            if($bannerlist->status==0)
                            {
                            ?>
                            <a  title="Change status" data-toggle="tooltip" class="<?php echo $statuspermission; ?>" href="javascript:void(0);" onclick="change_status('<?php echo urlencode(base64_encode($bannerlist->id)); ?>','Are you sure to Active this Banner?')"><span style="color: #c90000;"><i class="fa fa-lock"></i></span></a>
                            <?php
                            }
                            ?>                                                      
                            <a title="Delete" data-toggle="tooltip"  class="<?php echo $deletepermission; ?>" href="javascript:void(0);" onclick="delete_record('<?php echo urlencode(base64_encode($bannerlist->id)); ?>','Are you sure to Delete this Banner?')"><span><i class="fa fa-times"></i></span></a>
 </li>
                                    </ul>
                                </div>

</td>
                        </tr>
                        <?php
                       }
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Image</th>
			<th>Source</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                        </div>                    
                </div><!-- /.box-body -->
                <div class="box-footer">
                <div class="footer-button">
                        <input type="button" class="btn btn-primary" value="Add Banner" onclick="window.location.href='<?php echo site_url('admin/add-banner');?>'" />
                    </div>
                    </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     <div id="dialog_status"><div id="dialog_status_msg"></div></div>
</div>
<script type="text/javascript">
  
  function delete_record(id,alertmsg)
  {
      $( "#dialog_status" ).dialog({
        resizable: false,
        height:180,
        modal: true,
        title: 'Confirm!',
        buttons: {
          "Delete": function() {
               location.href="<?php echo base_url('banner/admin/deletebanner');?>/"+id;
          },
          Cancel: function() {
            $( this ).dialog( "close" );
          }
        },
        open : function(){
          $('#dialog_status_msg').html(alertmsg);
        }
      });
  }

  function change_status(id,alertmsg)
  {
      $( "#dialog_status" ).dialog({
        resizable: false,
        height:180,
        modal: true,
        title: 'Confirm!',
        buttons: {
          "Yes": function() {
               location.href="<?php echo base_url('banner/admin/statusbanner');?>/"+id;
          },
          No: function() {
            $( this ).dialog( "close" );
          }
        },
        open : function(){
          $('#dialog_status_msg').html(alertmsg);
        }
      });
  }
</script>