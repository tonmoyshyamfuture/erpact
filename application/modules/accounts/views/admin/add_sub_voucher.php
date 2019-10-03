<div class="wrapper2">    
    <?php echo form_open(base_url('accounts/entries/sub_voucher_save'), array('target' => '_parent', 'id' => 'add_group')); ?>
<section class="content-header">
    <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-list-alt"></i><?php echo !empty($group) ? 'Edit Sub Voucher' : 'Add Sub Voucher'; ?></h1>  
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
        $this->breadcrumbs->push('Add', '/admin/sub-voucher-add');
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
                        <input name="sub_voucher" id="sub_voucher" type="text" placeholder="Sub Voucher Name " class="form-control placeholder-no-fix" value="" required="true" pattern="^[a-zA-Z0-9 ]+$"/>
                    </div>
                    <span class="Existence_alert" style="color: red;"></span>
                    <?php if(isset($sub_voucher_code_status)){
                        if($sub_voucher_code_status['voucher_no_status'] == '2'){ ?>
                            <div class="form-group col-md-4">
                                <label>Code</label>                                
                                <input name="sub_voucher_no" id="sub_voucher_no" type="text" placeholder="Code" class="form-control placeholder-no-fix" value="" />                                    
                            </div>  
                    
                    <?php }
                    } ?>
                     <div class="form-group col-md-4">
                        <label>Parent Voucher Name</label>
                        <select name="entry_type_id" class="form-control select2me" required="true">
                            <option value=""> Select Voucher Name</option>
                            <?php if(isset($entry_types)){

                                foreach ($entry_types as $entry_type) {
                                    echo '<option value="'.$entry_type['id'].'">'.$entry_type['type'].' </option>';
                                }
                            } ?>
                            
                        </select>
                    </div>
                    <div class="form-group  col-md-4">
                        <label>Method of Numbering</label>
                        <div class="row">
                            <div class="col-md-12">
                                <div class='radio-inline'>
                                    <label><input name="entry_no" id="entry_no_manual" type="radio"   value="2" /> Manual</label>
                                </div>
                                <div class='radio-inline'>
                                    <label><input name="entry_no" id="entry_no_auto" type="radio" checked   value="1" /> Auto</label>
                                </div>
                        </div>
                        </div>
                    </div> 
                    </div>
                    <div class="row">
                            <div class="col-md-4 form-group">
                            <label>Title</label> 
                            <input class="form-control" name="title" id="title" placeholder="Title" required="true">
                            </div>
                            <div class="col-md-4 form-group">
                            <label>Sub-title</label> 
                            <input class="form-control" name="sub_title" id="sub_title" placeholder="Sub-title" >
                            </div>
                            <div class="col-md-4 form-group">
                            <label>Declaration</label> 
                            <input class="form-control" name="declaration" id="declaration" placeholder="Declaration" >
                            </div>
                        </div>
                    <div class="row">
                    <div class="form-group  col-md-12" id="number_formating">
                       <label>Numbering Format</label>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="interval">
                                    <option value="">Select type</option>
                                    <option value="month">Month</option>
                                    <option value="3 month">3 Month</option>
                                    <option value="6 month">6 Month</option>
                                    <option value="year" selected="true">Year</option>
                                </select>
                                <input name="strating_date"  id="strating_date" type="hidden" placeholder="Date" class="form-control date-picker placeholder-no-fix" value="" />
                            </div>
                            <div class="col-md-3"> 
                                <input name="prefix_entry_no" onkeyup="number_formate()"  id="prefix_entry_no" type="text" placeholder="Prefix" class="form-control placeholder-no-fix" value="" />
                            </div>
                             <div class="col-md-3"> 
                                 <input name="strating_no" onkeyup="number_formate()" onchange="number_formate()" id="strating_no" type="number" placeholder="How many Decimal No" class="form-control placeholder-no-fix" value="" required="required" min="1" />
                            </div>
                            <div class="col-md-3"> 
                                <input name="suffix_entry_no" onkeyup="number_formate()"  id="suffix_entry_no" type="text" placeholder="Suffix" class="form-control placeholder-no-fix" value="" />
                            </div>                            
                        </div>
                       
                    </div>
                    <div class="form-group  col-md-12">
                       <label id="eg">e.g: </label>                       
                    </div>
                    </div>
                  
                    
                </div>
                <div class="box-footer">                    
                    <div class="footer-button">
                        <span class="pull-left Existence_alert" style="color: red;"></span>                        
                        <input class="btn btn-primary" type="submit" value="Save"/>
                          <a href="<?php echo site_url('admin/vouchers'); ?>" class="btn btn-default" >Discard</a>
                    </div>                    
                </div>
            </div><!-- /.box -->
        </div>
        
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


    $(document).ready(function() {
        $("input[name='entry_no']").click(function() {
            var method = $("input[name='entry_no']:checked").val();
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


        $("#strating_no").on("keyup", function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });

        $("#add_group").validate({
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

        $("#sub_voucher, #title").on('keyup', function() {
            if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
                this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
            }
        });
    });
</script>