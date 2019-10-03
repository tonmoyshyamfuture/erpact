<div class="wrapper2">    
    <?php echo form_open(base_url('accounts/entries/edit_parent_vobcher'), array('target' => '_parent', 'id' => 'edit_subvoucher')); ?>
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i><?php echo 'Edit Parent Voucher'; ?> </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <a href="<?php echo site_url('admin/vouchers'); ?>" class="btn btn-default" >Discard</a>
                    <input class="btn btn-primary" type="submit" value="Save"/>
                </div>
            </div> 
        </div>     
    </section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Vouchers', '/admin/vouchers');
        $this->breadcrumbs->push('Update', '/admin/edit-parent-voucher');
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">        
            <div class="col-md-8">            
                <div class="box box-warning">
                    <div class="box-body">      
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Voucher Name</label>
                                <input name="type" id="type" type="text" placeholder="Voucher Name " class="form-control placeholder-no-fix" value="<?php echo $entry_types['type'] ?>" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sub Voucher Code</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input  <?php if ($sub_voucher_code_status['voucher_no_status'] == '1') echo 'readonly'; ?>  name="entry_code" id="sub_voucher_no" type="text" placeholder="suffix code" class="form-control placeholder-no-fix" value="<?php
                                        if (isset($entry_types)) {
                                            echo $entry_types['entry_code'];
                                        }
                                        ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Method of Numbering</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input name="transaction_no_status" id="entry_no_manual" type="radio" <?php if ($entry_types['transaction_no_status'] == '2') echo 'checked'; ?>  value="2"  /> Manual
                                        <input name="transaction_no_status" id="entry_no_auto" type="radio" <?php if ($entry_types['transaction_no_status'] == '1') echo 'checked'; ?>   value="1" /> Auto
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                            <label>Title</label> 
                            <input class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $entry_types['title'] ?>">
                            </div>
                            <div class="col-md-4 form-group">
                            <label>Sub-title</label> 
                            <input class="form-control" name="sub_title" id="sub_title" placeholder="Sub-title" value="<?php echo $entry_types['sub_title'] ?>">
                            </div>
                            <div class="col-md-4 form-group">
                            <label>Declaration</label> 
                            <input class="form-control" name="declaration" id="declaration" placeholder="Declaration" value="<?php echo $entry_types['declaration'] ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12" id="number_formating" style="display: <?php if ($entry_types['transaction_no_status'] == '2') echo 'none'; ?>">
                                <label>Numbering Format</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <select class="form-control" name="interval">
                                            <option value="">Select type</option>
                                            <option value="1" <?php if($entry_types['reset_interval'] == 1) {echo "selected";} ?>>Month</option>
                                            <option value="3 month" <?php if($entry_types['reset_interval'] == 3) {echo "selected";} ?>>3 Month</option>
                                            <option value="6 month" <?php if($entry_types['reset_interval'] == 6) {echo "selected";} ?>>6 Month</option>
                                            <option value="12" <?php if($entry_types['reset_interval'] == 12) {echo "selected";} ?>>Year</option>
                                        </select>
                                        <input name="strating_date"  id="strating_date" type="hidden" placeholder="Date" class="form-control date-picker placeholder-no-fix" value="<?php $strating_date = explode('-', $entry_types['strating_date']);
                                        echo $strating_date[1] . '/' . $strating_date[2] . '/' . $strating_date[0]
                                        ?>" />
                                    </div>
                                    <div class="col-md-3"> 
                                        <input name="prefix_entry_no" onkeyup="number_formate()"  id="prefix_entry_no" type="text" placeholder="Prefix" class="form-control placeholder-no-fix" value="<?php
                                        if (isset($entry_types)) {
                                            echo $entry_types['prefix_entry_no'];
                                        }
                                        ?>" <?php // if ($entry_types['transaction_no_status'] == '1') echo 'required'; ?> />
                                    </div>
                                    <div class="col-md-3"> 
                                        <input name="strating_no" onkeyup="number_formate()" onchange="number_formate()" required="required" id="strating_no" type="number" placeholder="How many Decimal No" min="1" class="form-control placeholder-no-fix" value="<?php
                                        if (isset($entry_types)) {
                                            echo $entry_types['starting_entry_no'];
                                        }
                                        ?>" <?php if ($entry_types['transaction_no_status'] == '1') echo 'required'; ?> />
                                    </div>
                                    <div class="col-md-3"> 
                                        <input name="suffix_entry_no" onkeyup="number_formate()"  id="suffix_entry_no" type="text" placeholder="Suffix" class="form-control placeholder-no-fix" value="<?php
                                        if (isset($entry_types)) {
                                            echo $entry_types['suffix_entry_no'];
                                        }
                                        ?>" <?php // if ($entry_types['transaction_no_status'] == '1') echo 'required'; ?> />
                                    </div>

                                </div>                       
                            </div>
                            <div class="form-group col-md-12" id="number_formating" style="display: <?php if ($entry_types['transaction_no_status'] == '2') echo 'none'; ?>">
                                <label id="eg">e.g: </label>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <span class="Existence_alert" style="color: red;"></span>
                        <div class="footer-button">
                            <input  type="hidden" name="id" value="<?php echo $entry_types['id'] ?>"/>
                            <input class="btn btn-primary" type="submit" value="Save"/>
                            <a href="<?php echo site_url('admin/vouchers'); ?>" class="btn btn-default" >Discard</a>
                        </div>                    
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (right) -->
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
        </div>   <!-- /.row -->
    </section><!-- /.content -->
<?php echo form_close(); ?>
</div>

<script>
    function number_formate() {
        var prefix = $('#prefix_entry_no').val() || '';
        var suffix = $('#suffix_entry_no').val() || '';
        var strating_no = parseInt($('#strating_no').val()) || 0;
        var fromate = 'e.g : ';
        var number = '';
        for (var i = 0; i < strating_no; i++) {
            number += '0';
        }
        var fromate = fromate + prefix + number + suffix;
        //alert(fromate);
        $('#eg').text('');
        $('#eg').text(fromate);

    }


    $(document).ready(function() {
        $("input[name='transaction_no_status']").click(function() {
            var method = $("input[name='transaction_no_status']:checked").val();
            if(method == 1){
//                $("#prefix_entry_no").attr('required', true);
                $("#strating_no").attr('required', true);
//                $("#suffix_entry_no").attr('required', true);
            }else{
//                $("#prefix_entry_no").attr('required', false);
                $("#strating_no").attr('required', false);
//                $("#suffix_entry_no").attr('required', false);
            }
        });

        $("#edit_subvoucher").validate({
          rules:{
            "sub_voucher": "required",
            "entry_type_id": "required",
            "title": "required",
          },
          messages:{
            "sub_voucher": "Please enter voucher name",
            "entry_type_id": "Please select parent voucher",
            "title": "Please enter title",
          }
        });

        $("#type, #title").on('keyup', function() {
            if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
            }
        });

    });
</script>