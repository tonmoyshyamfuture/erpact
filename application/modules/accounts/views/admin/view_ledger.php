<div class="wrapper2">    
    <?php //echo form_open(base_url('accounts/new_ledger')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bold"></i>View Account Ledger </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <?php echo form_hidden('id', count($ledger) > 0 ? $ledger[0]['id'] : 0); ?>
        <?php echo form_hidden('ladger_account_detail_id', count($ledger) > 0 ? $ledger[0]['ladger_account_detail_id'] : 0); ?>
        
            <a href="<?php echo site_url('admin/accounts-groups').'?_tb=2' ?>" class="btn btn-default"> Back</a>
            <?php //print_r($ledger);exit(); ?>
        <?php //if ($ledger[0]['operation_status'] == '1') { 
                    $permissionedit=admin_users_permission('E','ledger',$rtype=FALSE);
                    if($permissionedit)
                    {
            ?>
            <a href="<?php echo site_url('admin/edit-accounts-ledger') . "/" . $ledger[0]['id']; ?>" class="btn btn-info"> Edit</a>
        <?php 
                }
                    //} ?>
        <input type="button" class="btn btn-primary" value="Add" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-ledger'); ?>'" />   
                    </div>
                </div> 
            </div>     
</section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Chart of Accounts', '/admin/accounts-groups');    
        $this->breadcrumbs->push('View Ledger', '/admin/view-accounts-ledger');    
        $this->breadcrumbs->show();
        ?>
    </section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <?php //echo form_open(base_url('index.php/accounts/new_ledger'),array('class' => 'accounts-form', 'enctype'=>'application/json')); ?>
    <div class="box">
   <div class="box-body">        
        <div class="form-body">
            <div class="row">
                
                <div class="col-md-6">
            <div class="form-group">
                <label>Group</label>
                <input disabled="disabled" type="text" name="" placeholder="Group Name" class="form-control group" value="<?php echo count($ledger) > 0 ? $ledger[0]['group_name'] : ""; ?>" onblur="get_group(this.value);"  >
                <input disabled="disabled" id="group_id" name="group_id" value="<?php echo count($ledger) > 0 ? $ledger[0]['group_id'] : ""; ?>" type="hidden">
            </div>
                </div>
                <div class="col-md-6">
            <div class="form-group">
                <label>Ledger Name</label>
                <?php
                $data = array(
                    'name' => 'ladger_name',
                    'id' => 'ladger_name',
                    'class' => 'form-control',
                    'value' => count($ledger) > 0 ? $ledger[0]['ladger_name'] : "",
                    'placeholder' => 'Ledger Name',
                    'onkeypress' => 'return blockSpecialChar(event)',
                    'disabled'=> 'disabled'
                );
                echo form_input($data);
                ?>
            </div>
                <span class="Existence_alert" style="color: red;"></span>    
            </div>
            
            <div class="col-md-6">
            <div class="form-group">                
                <label>Tracking</label><br>                    
                <input disabled="disabled" name="tracking_status" id="tracking_status" type="radio"   <?php if(count($ledger) > 0){ if($ledger[0]['tracking_status'] == '1' ){ echo 'checked';} }else{ echo 'checked'; } ?>  value="1" /> Yes
                <input disabled="disabled" name="tracking_status" id="tracking_status" type="radio"  <?php if(count($ledger) > 0){ if($ledger[0]['tracking_status'] == '2' ){ echo 'checked';} } ?>    value="2" /> No
          </div>
            </div>
                
           <div class="col-md-6">
               <div class="form-group">       
               <label>Bill Wise Details</label><br>                
                <input disabled="disabled" name="bill_details_status" id="bill_details_status" type="radio"   <?php if(count($ledger) > 0){ if($ledger[0]['bill_details_status'] == '1' ){ echo 'checked';} }else{ echo 'checked'; } ?>  value="1" /> Yes
                <input disabled="disabled" name="bill_details_status" id="bill_details_status" type="radio"  <?php if(count($ledger) > 0){ if($ledger[0]['bill_details_status'] == '2' ){ echo 'checked';} } ?>    value="2" /> No
                </div>
           </div>
            <div class="col-md-6">
            <div class="form-group">
                <?php if($ledger_code_status != '1'){ echo '<label>Ledger Code</label>';} ?>

                <input disabled="disabled" type="<?php if($ledger_code_status == '1'){ echo 'hidden';}else{ echo 'text';} ?>"  name="ledger_code" id="ledger_code" value="<?php echo count($ledger) > 0 ? $ledger[0]['ledger_code'] : '' ?>" class="form-control" placeholder="Ledger Code" />
            </div>
            </div>
                <div class="col-md-6">
            <div class="form-group">
                <label>Opening Balance</label>
                <div class="row">
                    <div class="col-xs-3 padding-r-0">
                        <input disabled="disabled" type="text" name="account" placeholder="Dr/Cr" value="<?php echo count($ledger) > 0 ? $ledger[0]['account'] : ""; ?>" class="form-control br-0 debitTags" >
                    </div>
                    <div class="col-xs-9  padding-l-0">
                        <?php
                        $data = array(
                            'name' => 'opening_balance',
                            'id' => 'opening_balance',
                            'class' => 'form-control',
                            'value' => count($ledger) > 0 ? $ledger[0]['balance']< 0 ? substr($ledger[0]['balance'], 1) : $ledger[0]['balance'] : "",
                            'placeholder' => 'Balance',
                            'disabled'=> 'disabled'
                        );
                        echo form_input($data);
                        ?>

                    </div>
                </div>
            </div>
                </div>
            </div>
           
        </div>
        </div>
        <div class="box-footer">
            <?php echo form_hidden('id', count($ledger) > 0 ? $ledger[0]['id'] : 0); ?>
        <?php echo form_hidden('ladger_account_detail_id', count($ledger) > 0 ? $ledger[0]['ladger_account_detail_id'] : 0); ?>
        <div class="footer-button">
            <a href="<?php echo site_url('admin/accounts-groups').'?_tb=2' ?>" class="btn btn-default"> Back</a>
        <?php //if ($ledger[0]['operation_status'] == '1') { 
                    $permissionedit=admin_users_permission('E','ledger',$rtype=FALSE);
                    if($permissionedit)
                    {
            ?>
            <a href="<?php echo site_url('admin/edit-accounts-ledger') . "/" . $ledger[0]['id']; ?>" class="btn btn-info"> Edit</a>
        <?php 
                }
                    //} ?>
        </div>        
        </div>
       
    </div>   <!-- /.row -->+
    <?php //echo form_close(); ?>
    </div>
    
        <div class="col-md-4">
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
</section><!-- /.content -->
<?php //echo form_close(); ?>
</div>

<script type="text/javascript">
        function blockSpecialChar(e) {
            var k = e.keyCode;
            return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8  || k == 32  || (k >= 48 && k <= 57));
        }
</script>

