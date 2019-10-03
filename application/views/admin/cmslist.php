
        <section class="content-header">
          <h1>
            CMS Pages List             
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <!--<h3 class="box-title">CMS Pages List</h3>-->
                  <input type="button" class="btn btn-primary" value="Add CMS Pages" onclick="window.location.href='<?php echo site_url('admin/add-cms');?>'" />
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(!empty($cmsdet))
                      {
                       foreach($cmsdet as $cmslist)
                       {
 $arr=array(13,14,15,16,17,18,19,20);
                       ?>
                        <tr>
                          <td><?php echo $cmslist->title;?></td>
                          <td><?php echo $cmslist->alias;?></td>
                          <td>
                            <?php

			 if(!in_array($cmslist->id, $arr)){ 
                            if($cmslist->on_off==1)
                            {
                            ?>
                            <a href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/cms-status');?>/<?php echo urlencode(base64_encode($cmslist->id));?>'"><span style="color: #00bc00;"><i class="fa fa-unlock"></i></span></a>
                            <?php
                            }
                            if($cmslist->on_off==0)
                            {
                            ?>
                            <a href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/cms-status');?>/<?php echo urlencode(base64_encode($cmslist->id));?>'"><span style="color: #c90000;"><i class="fa fa-lock"></i></span></a>
                            <?php
                            }}else{
                            ?>
 <a href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/cms-status');?>/<?php echo urlencode(base64_encode($cmslist->id));?>'"><span style="color: #00bc00;"><i class="fa fa-unlock"></i></span></a>

<?php } ?>
                          </td>
                          <td><a href="<?php echo site_url('admin/edit-cms');?>/<?php echo urlencode(base64_encode($cmslist->id));?>"><span><i class="fa fa-pencil"></i></span></a>&nbsp;

<?php  if(!in_array($cmslist->id, $arr)){ ?>
<a href="<?php echo site_url('admin/delete-cms');?>/<?php echo urlencode(base64_encode($cmslist->id));?>"><span><i class="fa fa-times"></i></span></a>

<?php } ?>


</td>
                        </tr>
                        <?php
                       }
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     
