<div class="wrapper2">
<section class="content-header">
    <div class="row">
        <div class="col-xs-6">
            <h1> <i class="fa fa-plug"></i> Plugins List</h1>
        </div>
        <div class="col-xs-6">
            <div class="pull-right">
            <form id="uplform" action="<?php echo base_url('admin/pluginupload');?>" method="post" enctype="multipart/form-data">                
                    <input type="file" name="pluginfile" id="zipfile" style="display:none;" onchange="checkfileupload()"/>
                    <input type="button" value="Upload Zip" class="btn btn-primary" id="upl" onclick="$('#zipfile').click();" />&nbsp;<span id="plugpath"></span>                    
                    <a href="http://web-code.sketchdemos.com/ProjectBuilder/cartface" class="btn btn-success" target="_blank">Search New Plugins</a>
                  </form>
                
        </div>
            </div>
    </div>          
        </section> 

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">                
                <div class="box-body table-fullwidth table-wrap">
                    <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped col-3-100 col-4-100 col-5-120 lcol-150l">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>Author</th>
                        <th>Installed Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(!empty($plugindet))
                      {
                       foreach($plugindet as $pluginlist)
                       {
                       ?>
                        <tr>
                          <td><?php echo $pluginlist->name;?></td>
                          <td><?php echo $pluginlist->description;?></td>
                          <td><?php echo $pluginlist->version;?></td>
                          <td><?php echo $pluginlist->author;?></td>
                          <td><?php echo date('jS M, Y',strtotime($pluginlist->created_date));?></td>
                          <td style="position:relative;">
                            <?php
                            $findval=array(1,2,3);
                            if(in_array($pluginlist->id, $findval))
                            {
                            ?>
                            <div class="disablediv"></div>
                            <?php
                            }
                            ?>
                            <p class="inst-btn nomargin text-center"><span style="color: #0B9904;">Installed</span> | <a style="color: #0074A2;cursor:pointer;" <?php if(!in_array($pluginlist->id, $findval)) { ?> onclick="deleteplugin('<?php echo $pluginlist->id;?>','<?php echo $pluginlist->status;?>')" <?php } ?>>Uninstall</a></p>
                              <div class="onoffswitch">
                                  <input type="checkbox" <?php if(in_array($pluginlist->id, $findval)) { ?> disabled <?php } ?> name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch<?php echo $pluginlist->id;?>" <?php if($pluginlist->status==1) { ?> checked <?php } ?> onclick="checkstatus('<?php echo $pluginlist->id;?>','<?php echo $pluginlist->status;?>')">
                                  <label class="onoffswitch-label" for="myonoffswitch<?php echo $pluginlist->id;?>">
                                      <span class="onoffswitch-inner"></span>
                                      <span class="onoffswitch-switch"></span>
                                  </label>
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>Author</th>
                        <th>Installed Date</th>
                        <th>Status</th>
                      </tr>
                    </tfoot> 
                  </table>
                        </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">
                        <form id="uplform" action="<?php echo base_url('admin/pluginupload');?>" method="post" enctype="multipart/form-data">                
                            <a href="http://web-code.sketchdemos.com/ProjectBuilder/cartface" class="btn btn-success" target="_blank">Search New Plugins</a>
                    <input type="file" name="pluginfile" id="zipfile" style="display:none;" onchange="checkfileupload()"/>
                    <input type="button" value="Upload Zip" class="btn btn-primary" id="upl" onclick="$('#zipfile').click();" />&nbsp;<span id="plugpath"></span>                    
                  </form>
                        
                    </div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
</div>

<!-- switch css moved to custom.css -->

<script>
function checkfileupload()
{
  var zipfile=$('#zipfile');
  if(zipfile=="")
  {
    alert('Select zip for uploading');
  }
  else
  {
    $('#plugpath').val(zipfile);
    $('#uplform').submit();
  }
}

function deleteplugin(pluginid,status)
{
  if(status==0)
  {
    window.location.href="<?php echo base_url('admin/plugindelete');?>/"+pluginid;
  }
  else
  {
    alert('Please deactivate the plugin before uninstall');
  }
}

function checkstatus(pluginid,status)
{
 window.location.href="<?php echo base_url('admin/pluginstat');?>/"+pluginid+"/"+status; 
}
</script>