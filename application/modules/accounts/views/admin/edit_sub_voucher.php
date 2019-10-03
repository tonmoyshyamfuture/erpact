<div class="wrapper2">    
    <?php echo form_open(base_url('accounts/entries/sub_voucher_update'), array('target' => '_parent', 'id' => 'add_group')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bold"></i><?php echo 'Edit Sub Voucher'; ?> </h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <?php /*<input class="btn btn-default" onclick="javascript:location.href='<?php echo site_url('admin/blog-posts/');?>'" value="Discard" type="reset">*/ ?>
                        <input class="btn btn-primary" type="submit" value="Save"/>
                    </div>
                </div> 
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
                        
                    <div class="form-group">
                        <label>Entry Type</label>
                        
                        <select name="entry_type_id" class="form-control select2me">
                            <?php if(isset($entry_types)){

                                foreach ($entry_types as $entry_type) {
                                    if($entry_type['id'] == $parent_type_id){
                                         echo '<option selected value="'.$entry_type['id'].'">'.$entry_type['type'].' </option>';
                                         continue;
                                    }
                                    echo '<option value="'.$entry_type['id'].'">'.$entry_type['type'].' </option>';
                                }
                            } ?>
                            
                        </select>
                    </div>                           
                    <div class="form-group">
                        <label>Sub Voucher Name</label>
                        <input name="sub_voucher" id="sub_voucher" type="text" placeholder="Sub Voucher Name " class="form-control placeholder-no-fix" value="<?php if(isset($sub_voucher)){ echo $sub_voucher['sub_voucher'];} ?>" />
                    </div>
                    <span class="Existence_alert" style="color: red;"></span>
                    
                    <div class="form-group">
                       <label>Sub Voucher Code</label>
                       <div class="row">
                       <div class="col-md-12">
                           <input  <?php if($sub_voucher_code_status['voucher_no_status'] == '1') echo 'readonly'; ?>  name="sub_voucher_no" id="sub_voucher_no" type="text" placeholder="suffix code" class="form-control placeholder-no-fix" value="<?php if(isset($sub_voucher)){ echo $sub_voucher['sub_voucher_no'];} ?>" />
                       </div>
                     </div>
                    </div>
                    <div class="form-group  ">
                        <label>Method of Numbering</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input name="entry_no" id="entry_no_manual" type="radio" <?php if($sub_voucher['entry_no_status'] == '2') echo 'checked'; ?>  value="2"  /> Manual
                                <input name="entry_no" id="entry_no_auto" type="radio" <?php if($sub_voucher['entry_no_status'] == '1') echo 'checked'; ?>   value="1" /> Auto
                        </div>
                        </div>
                    </div> 
                   
                    <div class="form-group" id="number_formating" style="display: <?php if($sub_voucher['entry_no_status'] == '2') echo 'none'; ?>">
                       <label>Numbering Format</label>
                       <div class="row">
                            <div class="col-md-3">
                                 <select class="form-control" name="interval">
                                    <option value="">Select type</option>
                                    <option value="month" <?php if($sub_voucher['reset_interval'] == 1) { echo "selected"; } ?>>Month</option>
                                    <option value="3 month" <?php if($sub_voucher['reset_interval'] == 3) { echo "selected"; } ?>>3 Month</option>
                                    <option value="6 month" <?php if($sub_voucher['reset_interval'] == 6) { echo "selected"; } ?>>6 Month</option>
                                    <option value="year"  <?php if($sub_voucher['reset_interval'] == 12) { echo "selected"; } ?>>Year</option>
                                </select>
                                <input name="strating_date"  id="strating_date" type="hidden" placeholder="Date" class="form-control date-picker placeholder-no-fix" value="<?php $strating_date= explode('-',$sub_voucher['strating_date']);echo $strating_date[1].'/'.$strating_date[2].'/'.$strating_date[0] ?>" />
                            </div>
                            <div class="col-md-3"> 
                                <input name="prefix_entry_no" onkeyup="number_formate()"  id="prefix_entry_no" type="text" placeholder="Prefix" class="form-control placeholder-no-fix" value="<?php echo $sub_voucher['prefix_entry_no']; ?>" />
                            </div>
                           <div class="col-md-3"> 
                                <input name="strating_no" onkeyup="number_formate()" onchange="number_formate()" required="required" id="strating_no" type="number" placeholder="How many Decimal No" class="form-control placeholder-no-fix" value="<?php echo $sub_voucher['starting_entry_no']; ?>" />
                            </div>
                            <div class="col-md-3"> 
                                <input name="suffix_entry_no" onkeyup="number_formate()"  id="suffix_entry_no" type="text" placeholder="Suffix" class="form-control placeholder-no-fix" value="<?php echo $sub_voucher['prefix_entry_no']; ?>" />
                            </div>
                            
                      </div>
                       <h4><label id="eg">e.g: </label></h4>
                    </div>
                    <span class="Existence_alert" style="color: red;"></span>
                    <div class="footer-button">
                        <input name="entry_no_status" id="entry_no_status" type="hidden" placeholder="suffix code" class="form-control placeholder-no-fix" value="<?php if(isset($sub_voucher)){ echo $sub_voucher['entry_no_status'];} ?>" />
                        <input name="id" id="sub_voucher_no" type="hidden" placeholder="suffix code" class="form-control placeholder-no-fix" value="<?php if(isset($sub_voucher)){ echo $sub_voucher['id'];} ?>" />
                        <input class="btn btn-primary" type="submit" value="Save"/>
                    </div>                    
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!--/.col (right) -->
    </div>   <!-- /.row -->
</section><!-- /.content -->
<?php echo form_close(); ?>
</div>

<script>
    function number_formate(){
        var prefix = $('#prefix_entry_no').val() || '';
        var suffix = $('#suffix_entry_no').val() || '';
        var strating_no = parseInt($('#strating_no').val()) || 0;
        var fromate = 'e.g : ';
        var number = '';
        for(var i = 0 ; i < strating_no ; i++){
            number += '0';
        }
        var fromate = fromate+prefix+number+suffix;
        //alert(fromate);
        $('#eg').text('');
        $('#eg').text(fromate);
        
    }
</script>