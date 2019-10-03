
        <section class="content-header">
          <h1>
            Contact Us Messages List
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <!--<h3 class="box-title">User List</h3>-->
                  <!--<input type="button" class="btn btn-primary" value="Add User" onclick="window.location.href='<?php echo $this->config->item('base_url');?>admin/add-users.html'" />-->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>

                        <th>Date</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(!empty($list))
                      {
                        $x = 0;
                       foreach($list as $contact)
                       {
                              $cls = "";
                              if($contact->reply_status == 1)
                                  $cls = "active";

                          
                       ?>
                        <tr class="<?= $cls; ?>">
                          <td><?php echo ++$x ; ?></td>
                          <td><?php echo stripslashes($contact->name) ; ?></td>
                          <td><?php echo stripslashes($contact->email);?></td>

                          <td><?php echo date('jS M Y h:i:s A',strtotime($contact->creation_date));?></td>
                          <td><?php if($contact->reply_status == 0){ ?><a href="<?php echo $this->config->item('base_url');?>admin/reply-contact.html/<?php echo urlencode(base64_encode($contact->id));?>"><span><i class="fa fa-reply"></i></span></a> <?php } else { ?> <a href="<?php echo $this->config->item('base_url');?>admin/reply-view.html/<?php echo urlencode(base64_encode($contact->id));?>"><span><i class="fa fa-eye"></i></span></a> <?php } ?>&nbsp;<a href="<?php echo $this->config->item('base_url');?>admin/delete-contact.html/<?php echo urlencode(base64_encode($contact->id));?>"><span><i class="fa fa-times"></i></span></a></td>
                        </tr>
                        <?php
                       }
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>

                        <th>Date</th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     