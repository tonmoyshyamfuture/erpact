
<section class="content-header">
    <h1>
        Clients        
    </h1>
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
                    <form role="form" action="<?php echo $this->config->item('base_url'); ?>admin/usersupdate/<?php echo!empty($userslist->id) ? urlencode(base64_encode($userslist->id)) : ''; ?>" method="post">
                        <!-- text input -->
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" readonly class="form-control" name="fname" <?php if (!empty($usersdet->fname)) { ?> value="<?php echo $usersdet->fname; ?>" <?php } ?> placeholder="Enter First Name"/>
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" readonly class="form-control" name="lname" <?php if (!empty($usersdet->lname)) { ?> value="<?php echo $usersdet->lname; ?>" <?php } ?> placeholder="Enter Last Name"/>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" readonly class="form-control" name="username" <?php if (!empty($usersdet->username)) { ?> value="<?php echo $usersdet->username; ?>" <?php } ?> placeholder="Enter Username"/>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" <?php if (!empty($usersdet->lname)) { ?> value="<?php echo $usersdet->email; ?>" readonly="true" <?php } ?> placeholder="Enter Email"/>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" readonly  name="address" placeholder="Enter Address"><?php
                                if (!empty($usersdet->address)) {
                                    echo $usersdet->address;
                                }
                                ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" readonly  name="phone" <?php if (!empty($usersdet->phone)) { ?> value="<?php echo $usersdet->phone; ?>" <?php } ?> placeholder="Enter Phone Number"/>
                        </div>
                        <div class="form-group">
                            <label>Plan Name</label>
                            <input type="text" class="form-control" readonly  name="plan_name" <?php if (!empty($usersdet->plan_name)) { ?> value="<?php echo $usersdet->plan_name; ?>" <?php } ?> placeholder="Enter Plan Name"/>
                        </div>
                        <div class="form-group">
                            <label>Plan Duration</label>
                            <input type="text" class="form-control" readonly  name="plan_duration" <?php if (!empty($usersdet->duration)) { ?> value="<?php echo $usersdet->duration; ?>" <?php } ?> placeholder="Enter Plan Duration"/>
                        </div>
                        <div class="form-group">
                            <label>Plan Expiry Date</label>
                            <input type="text" class="form-control" readonly  name="plan_exp" <?php if (!empty($usersdet->expiry_date)) { ?> value="<?php echo $usersdet->expiry_date; ?>" <?php } ?> placeholder="Enter Plan Expiry Date"/>
                        </div>
                        <div class="form-group">
                            <label>Plan Amount</label>
                            <input type="text" class="form-control" readonly  name="plan_price" <?php if (!empty($usersdet->price)) { ?> value="<?php echo $usersdet->price; ?>" <?php } ?> placeholder="Enter Plan Price"/>
                        </div>
                        <div class="form-group">
                            <label>Profile Image 
                            </label>

                            <div class="clearfix margin-top-10"></div>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" >                                                         
                                    <?php if (!empty($usersdet) && $usersdet->image != '') { ?> 
                                        <img src="<?php echo $this->config->base_url('assets/images/profile_images/' . $usersdet->image); ?>" alt="" height="250" width="200">
                                    <?php } else { ?>
                                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <span class="help-block"></span>

                            <span id="show_image_error1" style="color:#DD1144;"></span>

                        </div>
                        <h3> Products OF User</h3>
                        <ul>
                        <?php 
                        
                        if(!empty($products)){
                           
                            foreach($products as $row){
                             
                            ?>
                        
                            <li><a href="<?php echo $this->config->item('base_url');?>admin/edit-product.html/<?php echo urlencode(base64_encode($row->id));?>" target="_blank"><?=$row->name;?></a></li>
                        <?php
                            }
                         }else{
                        ?>
                            <li>No Product Found</li>
                            
                         <?php } ?>
                        </ul>
                        <div class="footer-button">
                            <a href="<?php echo $this->config->item('base_url'); ?>admin/members.html/" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!--/.col (right) -->
    </div>   <!-- /.row -->
</section><!-- /.content -->
