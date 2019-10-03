<div class="wrapper2">
<section class="content-header">
    <div class="row">
        <div class="col-xs-6">
            <h1> <i class="fa fa-bars"></i> Menu List</h1>
        </div>
        <div class="col-xs-6">
            
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
                  <table id="examplemenu" class="table table-bordered table-striped fcol-50 lcol-70 col-6-70">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>URL</th>
                        <th>Active url</th>
                        <!--<th>Status</th>-->
                        <th></th>
                      </tr>
                    </thead>
                    <tbody id="sortable">
                      <?php
                      $arr=array();
                      if(!empty($menues))
                      {
                        $x = 0;
                       foreach($menues as  $menu)
                       {
 
                          $arr[]=$menu->id;
                          $key=$menu->id;
                       ?>
                        <tr  class="ui-state-default" id="<?php echo $menu->id;?>" data-menuorder="<?php echo $menu->id;?>">
                          <td><?php echo ++$x ; ?></td>
                          <td><?php echo stripslashes($menu->label) ; ?></td>
                          <td><?php echo stripslashes($menu->icon);?></td>
                          <td><?php echo stripslashes($menu->url);?></td>
                          <td><?php echo stripslashes($menu->active); ?></td>
                          <!--<td>
                            <?php
                            //if($menu->status==1)
                            //{
                            ?>
                            <a href="javascript:void(0);" onclick="window.location.href='<?php //echo $this->config->item('base_url');?>admin/menustatus/<?php //echo urlencode(base64_encode($key));?>'"><span style="color: #00bc00;"><i class="fa fa-unlock"></i></span></a>
                            <?php
                            //}
                            //if($menu->status==0)
                            //{
                            ?>
                            <a href="javascript:void(0);" onclick="window.location.href='<?php //echo $this->config->item('base_url');?>admin/menustatus/<?php //echo urlencode(base64_encode($key));?>'"><span style="color: #c90000;"><i class="fa fa-lock"></i></span></a>
                            <?php
                            //}
                            ?>
                          </td>-->
                          <td><a href="<?php echo site_url('admin/edit-menu');?>/<?php echo urlencode(base64_encode($key));?>"><span><i class="fa fa-pencil"></i></span></a>
                          <!--&nbsp;<a href="<?php //echo site_url('admin/delete-menu');?>/<?php //echo urlencode(base64_encode($key));?>"><span><i class="fa fa-times"></i></span></a></td>-->
                        </tr>
                        <?php
                       }
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>URL</th>
                        <th>Active url</th>
                        <!--<th>Status</th>-->
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>

                  <div id="<?php if(!empty($arr)){ echo implode('|',$arr);}?>" class="selectedids" style="display:none"></div>
                </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">
<!--                         <input type="button" class="btn btn-primary" value="Add Menu" onclick="window.location.href='<?php echo site_url('admin/add-menu');?>'" />
 -->                    </div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     
</div>


  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

  <script>
  $('#sortable').sortable({
    axis: 'y',
    update: function (event, ui) {
                           var sable=$(this).sortable("toArray");
                           var list =  sable.join("|"),ids=$(".selectedids").attr("id");
                           var path='<?php echo site_url("admin/sortmenu");?>';
                           $.ajax({   url: path,
                                      type: 'POST',
                                      data: { 
                                                  'section':this.id,              
                                                  'components': list,
                                                  'ids': ids
                                            },
                                       success:function(response)
                                       {
                                       
                                       }     
                                  });
                  }    
});

  </script>