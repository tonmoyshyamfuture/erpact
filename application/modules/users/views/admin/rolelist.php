<div class="wrapper2">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa  fa-check-square-o"></i> All Roles</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>
                        <?php
			$permissionadd=admin_users_permission('A','users',$rtype=FALSE);
			if($permissionadd)
			{
		?>
                <a  class="btn btn-primary" href="<?php echo site_url('admin/add-role');?>" >Add</a>
              	<?php
			}
		?> 
                    </div>
                </div>
            </div>          
        </section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Role', '/admin/role');
        $this->breadcrumbs->show();
        ?>
    </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-8">
              <div class="box">                
                <div class="box-body table-fullwidth">
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped fcol-50 col-3-100 lcol-70">
                    <thead>
                      <tr>
                          <th><input type="checkbox" class="checkAll checkbox icheck" value=""></th>                        
                        <th>Group Name</th>
                        <th>Role</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(!empty($result))
                      {
                        $x = 0;
                       foreach($result as $key =>$row)
                       {
                          $x++;
                       ?>
                       
                     <tr>
                         <td><input type="checkbox" class="checkdel" value=""></td>                        
                        <td><a  title="Edit Group Name" data-toggle="tooltip" class="<?php echo $editpermission; ?>" href="<?php echo site_url('admin/edit-role');?>/<?php echo urlencode(base64_encode($row->id));?>"><?=$row->group_name;?></a></td>
                        <td>
                            <a  title="View Group Roles"  href="#myModal<?php echo $row->id ?>" data-toggle="modal" data-target="#myModal<?php echo $row->id ?>"><span><i class="fa fa-eye"></i></span></a>
                          <div class="modal fade" id="myModal<?php echo $row->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content modaltable">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"> Group <?php echo $row->group_name;?></h4>
                              </div>
                              <div class="modal-body">

                                  <div class="col-md-12">       <p style="text-transform:capitalize; font-size: 16px;"><?php echo $row->group_desc;?></p></div>

                                <table id="example1" class="table table-bordered table-striped ">
                                  <thead>
                                    <tr>
                                      <th>Module Name</th>
                                      <th>Add</th>
                                      <th>Edit</th>
                                      <th>Delete</th>
                                      <th>View</th>
                                      <th>Status</th>

                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                     
                                      <?php 
                                            $role=json_decode($row->group_role);

                                            if(!empty($role))
                                            {
                                              foreach($role as $key=>$val)
                                              {
                                                $f=array();

                                                 if(is_object($val))
                                                {
                                                  
                                                  foreach($val as $k=>$value)
                                                  {
                                                    
                                                    $f[]=$k;
                                                  }
                                                  
                                                 
                                                }

                                                ?>
                                                 <tr>
                                             <td>  <?php  echo strtoupper(str_replace("-"," ",$key)); ?></td>

                                              <td><?php if(in_array("A",$f)){ ?>  <span><i class="fa fa-check"></i></span>  <?php } else { ?> <span><i class="fa fa-times"></i></span> <?php } ?> </td>
                                              <td><?php if(in_array("E",$f)){ ?>  <span><i class="fa fa-check"></i></span>  <?php } else { ?> <span><i class="fa fa-times"></i></span> <?php } ?> </td>
                                              <td><?php if(in_array("D",$f)){ ?>  <span><i class="fa fa-check"></i></span>  <?php } else { ?> <span><i class="fa fa-times"></i></span> <?php } ?> </td>
                                              <td><?php if(in_array("V",$f)){ ?>  <span><i class="fa fa-check"></i></span>  <?php } else { ?><span><i class="fa fa-times"></i></span> <?php } ?> </td>
                                              <td><?php if(in_array("S",$f)){ ?>  <span><i class="fa fa-check"></i></span>  <?php } else { ?> <span><i class="fa fa-times"></i></span> <?php } ?> </td>
 
                                               </tr> 
                                               <?php
                                               
                                               
                                              }
                                            }
                                          ?>

                                        
                                      
                                  </tbody>
                              </table>

                            </div>
                          </div>
                        </div>
                        </div>
                        </td>
                        <td>
			    <?php
				if($row->id!="1")
				{
			    ?>                            
                            <a class="<?php echo $deletepermission; ?>" href="<?php echo base_url('subadmin/admin/deleteRole');?>/<?php echo urlencode(base64_encode($row->id));?>"><span><i class="fa fa-trash-o"></i></span></a>&nbsp;
			    <?php
				}
			    ?>

                         </td>
                      </tr>
                    

                        <?php
                       }
                      }
                      ?>
                    </tbody>
                    
                  </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">
                        <a  class="btn btn-primary" href="<?php echo site_url('admin/add-role');?>" >Add</a>
                    </div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            <div class="col-md-4">
                    <div class="box settings-help">
                        <div class="box box-solid">
                            <div class="box-header">
                                <h3 class="box-title"> <i class="fa fa-info-circle"></i> Help</h3>
                            </div>                        
                            <div class="box-body">
                                <div class="row">                                
                                    <div class="col-md-12">
                                        coming soon...
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
        
  
</div>
