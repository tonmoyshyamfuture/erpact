<div class="wrapper2">
    <form role="form" action="<?php echo base_url('accounts_settings/admin/settingsmodify'); ?>" method="post" id="settings_form" enctype="multipart/form-data">
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6"><h1><i class="fa fa-cogs"></i> Settings</h1></div>
            <div class="col-xs-6"> 
                <div class="pull-right">
                    <?php if($this->session->userdata('branch_id')==1):?>
                    <a href="<?php echo site_url('admin/add-branch'); ?>" class="btn btn-sm btn-default">Create Branch</a> 
                    <?php endif;?>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button> 
                </div>
            </div>
        </div>
    </section>
        <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Organization', '/admin/accounts-settings');
        $this->breadcrumbs->show();
        ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-map-marker"></i> Organization</h3>                            
                    </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2 col-sm-3">
                                    <div class="company-logo form-group">
                                    <span class="btn btn-default btn-file" style="border-color: #c6d6db; margin-right: 10px; padding: 0;height:auto">
                                        <input type="file" name="company_logo">
                                        <?php
                                        if (isset($settings->logo) && $settings->logo != '') {
                                            ?>                                                
                                            <img src="<?php echo base_url(); ?>assets/uploads/thumbs/<?php echo $settings->logo; ?>">
                                            <?php
                                        }else{
                                        ?>
                                         <img src="<?php echo base_url().'assets/uploads/comp.png'; ?>">
                                        <?php }?>
                                    </span>
                                    <div class="pull-left">
                                                         
                                    </div>
                                    </div>
                                    </div>                                
                                <div class="col-md-10 col-sm-9">
                                    <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12">Name of the Company</label>
                                        <div class="col-sm-12">
                                            <input class="form-control EntTab" id="company_name" name="company_name" value="<?php
                                            if (isset($settings->company_name)) {
                                                echo $settings->company_name;
                                            }
                                            ?>" type="text">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                
                                <div class="row">
                                <div class="form-group col-md-12">
                                    <h5>Mailing & Contact Details</h5>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <label class="col-sm-12">Mailing Name</label>
                                        <div class="col-sm-12">
                                            <input class="form-control EntTab" type="text" id="mailing_name" name="mailing_name" value="<?php
                                            if (isset($settings->mailing_name)) {
                                                echo $settings->mailing_name;
                                            }
                                            ?>" >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Address </label>
                                    <div class="row">                                        
                                        <!-- <div class="col-xs-4 col-sm-2">
                                            <input class="form-control" type="text" placeholder="Appt. No." id="appt_number" name="appt_number" value="<?php
                                            if (isset($settings->appt_number)) {
                                                echo $settings->appt_number;
                                            }
                                            ?>" >                                                                                        
                                        </div> -->
                                        <div class="col-xs-12 col-sm-12">
                                            <input class="form-control" type="text" id="street_address" name="street_address" value="<?php
                                            if (isset($settings->street_address)) {
                                                echo $settings->street_address;
                                            }
                                            ?>" placeholder="Street Address">
                                        </div>
                                    </div>                                    
                                </div>                                

                                <!-- <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">Country</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" type="text" id="country" name="country" value="<?php
                                            if (isset($settings->country)) {
                                                echo $settings->country;
                                            }
                                            ?>" placeholder="Select Country">
                                        </div>
                                    </div>
                                </div> -->
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">Country</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2" name="country_id" style="width:100% !important">
                                                <option selected="selected" >Select</option>
                                                <?php
                                                if (isset($countries) && !empty($countries)) {
                                                    foreach ($countries AS $row) {
                                                        ?>
                                                        <option <?php
                                                        if ($row->id == $settings->country_id) {
                                                            echo 'selected';
                                                        }
                                                        ?> value="<?= $row->id; ?>" ><?= $row->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">State</label>
                                        <div class="col-sm-12">
                                            <select class="form-control select2" id="state_id" name="state_id" style="width:100% !important">
                                                <option selected="selected" >Select</option>
                                                <?php
                                                if (isset($state) && !empty($state)) {
                                                    foreach ($state AS $row) {
                                                        ?>
                                                        <option <?php
                                                        if ($row->id == $settings->state_id) {
                                                            echo 'selected';
                                                        }
                                                        ?> value="<?= $row->id; ?>"  data-code="<?= $row->state_code; ?>" ><?= $row->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">City</label>
                                        <div class="col-sm-12">
                                            <input class="form-control nonNumeric" type="text" id="city_name" name="city_name" value="<?php
                                            if (isset($settings->city_name)) {
                                                echo $settings->city_name;
                                            }
                                            ?>" >
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">ZIP/PIN</label>
                                        <div class="col-sm-12">
                                            <input class="form-control onlyNumeric" type="text" id="zip_code" name="zip_code" value="<?php
                                            if (isset($settings->zip_code)) {
                                                echo $settings->zip_code;
                                            }
                                            ?>" >
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">Telephone</label>
                                        <div class="col-sm-12">
                                            <input class="form-control onlyNumeric" type="tel" id="telephone" name="telephone" value="<?php
                                            if (isset($settings->telephone)) {
                                                echo $settings->telephone;
                                            }
                                            ?>"  >
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">Mobile</label>
                                        <div class="col-sm-12">
                                            <input class="form-control onlyNumeric" type="text" id="mobile" maxlength="10" name="mobile" value="<?php
                                            if (isset($settings->mobile)) {
                                                echo $settings->mobile;
                                            }
                                            ?>" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <label class="col-sm-12">Email</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" type="email" id="email" name="email" value="<?php
                                            if (isset($settings->email)) {
                                                echo $settings->email;
                                            }
                                            ?>" readonly>
                                        </div>
                                    </div>
                                </div>                                  



                                <!--                                 <div class="form-group col-md-12">
                                                                        <div class="row">
                                                                            <label class="col-sm-12"><u><strong>Company Details</strong></u></label>                                        
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group col-md-6">
                                                                        <div class="row">
                                                                        <label class="col-sm-12">Financial Year From</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" class="form-control" id="fyDate"  name="finalcial_year_from" value="<?php
                                if (isset($settings->finalcial_year_from)) {
                                    echo $newDate = date("d/m/Y", strtotime($settings->finalcial_year_from));
                                }
                                ?>" >
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group col-md-6">
                                                                        <div class="row">
                                                                        <label class="col-sm-12">Books beginning From</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" class="form-control" id="bookDate"  name="books_begining_form" value="<?php
                                if (isset($settings->books_begining_form)) {
                                    echo $newDate = date("d/m/Y", strtotime($settings->books_begining_form));
                                }
                                ?>" >
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                        <div class="row">
                                                                            <label class="col-sm-12"><u><strong>Base Currency Information</strong></u></label>                                        
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group col-md-6">
                                                                        <div class="row">
                                                                        <label class="col-sm-12">Base Currency</label>
                                                                        <div class="col-sm-12">
                                                                            <select class="form-control select2" name="base_currency">
                                                                                <option selected="selected" >Select</option>
                                <?php
                                if (isset($currency) && !empty($currency)) {
                                    foreach ($currency AS $row) {
                                        ?>
                                                                                                                                                <option <?php
                                        if ($row->id == $settings->base_currency) {
                                            echo 'selected';
                                        }
                                        ?> value="<?= $row->id; ?>" ><?= $row->currency; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                                                                            </select>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group col-md-6">
                                                                        <div class="row">
                                                                        <label class="col-sm-12">Base Currency Symbol</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" class="form-control" placeholder="e.g: for US Dollar use - fa fa-usd" id="base_currency_symbol" name="base_currency_symbol" value="<?php
                                if (isset($settings->base_currency_symbol)) {
                                    echo $settings->base_currency_symbol;
                                }
                                ?>" >                                            
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group col-md-6">
                                                                        <div class="row">
                                                                        <label class="col-sm-12">Formal name</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" class="form-control" id="formal_name" name="formal_name" value="<?php
                                if (isset($settings->formal_name)) {
                                    echo $settings->formal_name;
                                }
                                ?>" >
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                <div class="form-group col-md-6">
                                                                        <div class="row">
                                                                        <label class="col-sm-12">No. of decimal places</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="number" class="form-control" id="decimal_places" name="decimal_places" value="<?php
                                if (isset($settings->decimal_places)) {
                                    echo $settings->decimal_places;
                                }
                                ?>" >
                                                                        </div>
                                                                        </div>
                                                                    </div> -->
                                <div class="col-md-12">
                                    <div class="clearfix">
                                        <h5>Company Tax Details</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>PAN</label>
                                                    <input type="text" class="form-control" name="pan" placeholder="PAN" value="<?php echo $settings->pan;?>" id="pan" maxlength="10">
                                                </div>   
                                            </div>                                            
                                            <div class=" col-md-4">
                                                <div class="form-group">
                                                    <label for="consumer-type" class="black">Company Type</label>                                              
                                                                                                        
                                                    <!-- <label class="radio-inline">
                                                        <input type="radio" name="company_type" value="1" <?php if( $settings->company_type == 1) { echo "checked"; } ?>>Regular
                                                    </label>
                                                    <label class="radio-inline">
                                                      <input type="radio" name="company_type" value="2" <?php if( $settings->company_type == 2) { echo "checked"; } ?>>Composite
                                                    </label>
                                                    <label class="radio-inline">
                                                      <input type="radio" name="company_type" value="3" <?php if( $settings->company_type == 3) { echo "checked"; } ?>>Unregister
                                                    </label> -->
                                                    <select class="form-control" name="company_type">
                                                        <option value="1" <?php if( $settings->company_type == 1) { echo "selected"; } ?>>Regular</option>
                                                        <option value="2" <?php if( $settings->company_type == 2) { echo "selected"; } ?>>Composite</option>
                                                        <option value="3" <?php if( $settings->company_type == 3) { echo "selected"; } ?>>Unregister</option>
                                                    </select>
                                                    <span class="help-block with-errors"></span>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-4" id="gstn_section" style="display: <?php if($settings->company_type == 3){ echo "none"; }?>">
                                                <div class="form-group">
                                                   <label>GSTN No</label>
                                                    <input class="form-control" name="gst" placeholder="GSTN No" value="<?php echo $settings->gst;?>" id="gst" maxlength="15">
                                                    <span class="errorMessage" id="gst_error"></span>
                                                </div>
                                            </div>
<!--                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GSTN No</label>
                                                    <input class="form-control" name="gst" placeholder="GSTN No" value="<?php echo $settings->gst;?>">
                                                </div>   
                                            </div>-->
<!--                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CIN</label>
                                                    <input class="form-control" name="cin" placeholder="CIN"  value="<?php echo $settings->cin;?>">
                                                </div>   
                                            </div>-->
                                        </div>
<!--                                        <div class="clearfix">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>VAT</label>
                                                    <input class="form-control" name="vat" placeholder="VAT"  value="<?php echo $settings->vat;?>">
                                                </div>   
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CST</label>
                                                    <input class="form-control" name="cst" placeholder="CST" value="<?php echo $settings->cst;?>">
                                                </div>   
                                            </div>
                                        </div>-->
<!--                                        <div class="clearfix">
                                           
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>TIN</label>
                                                    <input class="form-control" name="tan" placeholder="TIN"  value="<?php echo $settings->tan;?>">
                                                </div>   
                                            </div>
                                        </div>-->
<!--                                          <div class="clearfix">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Service Tax</label>
                                                    <input class="form-control" name="service_tax" placeholder="Service Tax"  value="<?php echo $settings->service_tax;?>">
                                                </div>   
                                            </div>
                                          
                                        </div>-->
                                    </div>   
                                </div>
                                <div class="form-group col-md-12">
                                    <h5>Financial Year</h5>                                        
                                </div>
                                <div class="form-group col-md-6 ">
                                    <div class="row">
                                        <label class="col-sm-12">Financial Year From</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                
                                            <input type="text" class="form-control" id="fyDate"  name="finalcial_year_from" value="<?php
                                            if (isset($standardFormat->finalcial_year_from)) {
                                                echo $newDate = date("m/Y", strtotime($standardFormat->finalcial_year_from));
                                            }else{
                                              echo "04/".date("Y");  
                                            }
                                            ?>" >
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-sm-12">Books Beginning From</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                
                                            <input type="text" class="form-control" id="bookDate"  name="books_begining_form" value="<?php
                                            if (isset($standardFormat->books_begining_form)) {
                                                echo $newDate = date("m/Y", strtotime($standardFormat->books_begining_form));
                                            }else{
                                                echo "04/".date("Y");
                                            }
                                            ?>" >
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <h5>Base Currency & Time-zone</h5>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="row">
                                        <label class="col-sm-12">Base Currency</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-inr"></i>
                                                </span>
                                                <select class="form-control" name="base_currency">
                                                    <option selected="selected" >Select</option>
                                                    <?php
                                                    if (isset($currency) && !empty($currency)) {
                                                        foreach ($currency AS $row) {
                                                            ?>
                                                            <option <?php
                                                            if ($row->id == $standardFormat->base_currency) {
                                                                echo 'selected';
                                                            }
                                                            ?> value="<?= $row->id; ?>" ><?= $row->currency; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3 hidden">
                                    <div class="row">
                                        <label class="col-sm-12">Symbol</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" placeholder="e.g: for US Dollar use - fa fa-usd" id="base_currency_symbol" name="base_currency_symbol" value="<?php
                                            if (isset($standardFormat->base_currency_symbol)) {
                                                echo $standardFormat->base_currency_symbol;
                                            }
                                            ?>" >                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="row">
                                        <label class="col-sm-12">Formal name</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="formal_name" name="formal_name" value="<?php
                                            if (isset($standardFormat->formal_name)) {
                                                echo $standardFormat->formal_name;
                                            }
                                            ?>" >
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="row">
                                        <label class="col-sm-12">Decimal places</label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" id="decimal_places" name="decimal_places" value="<?php
                                            if (isset($standardFormat->decimal_places)) {
                                                echo $standardFormat->decimal_places;
                                            }
                                            ?>" >
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-group col-md-3">
                                    <div class="row">
                                        <label class="col-sm-12">Timezone</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="time_zone_id">
                                                <option selected="selected" >Select Time Zone</option>
                                                <?php
                                                if (isset($timezone) && !empty($timezone)) {
                                                    foreach ($timezone AS $row) {
                                                        ?>
                                                        <option <?php
                                                        if (isset($standardFormat->time_zone_id) && $standardFormat->time_zone_id != '') {
                                                            if ($row->id == $standardFormat->time_zone_id) {
                                                                echo 'selected';
                                                            }
                                                        } else {
                                                            if ($row->id == '194') {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?> value="<?= $row->id; ?>" >
                                                                <?= $row->timezone; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                

                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>                    

                </div>
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
    </section> 
    </form>
</div>
<!-- /.content -->

<script>
    $("#fyDate").datepicker({
        dateFormat: 'mm/yy'
    });
    $("#bookDate").datepicker({
        dateFormat: 'mm/yy'
    });


//$(function () {
//  $("#company_type").change(function() {    
//    if($(this).val() == 3){
//        $("#gstn_section").hide();
//    }
//    else {
//        $("#gstn_section").show();
//    }
//  });
//});

    $(document).ready(function(){
        // $("input[type='radio'][name='company_type']").click(function() {
        $("select[name='company_type']").on('change', function() {
            if($(this).val() == 3){ // 3 = unregistered consumer
                $("#gstn_section").hide();
            }else{
                $("#gstn_section").show();
            }
        });


        // SOMNATH - pan checking (1-5  => A-Z, 6-9 => 0-9, 10 => A-Z)
        $("#pan").keypress(function(event) {
            var length = $(this).val().length;
            if (event.keyCode >= 65 && event.keyCode <= 90 && length < 5) {
                // uppercase
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105)) && length >= 5 && length < 9 ) {
                // numeric
            }else if (event.keyCode >= 65 && event.keyCode <= 90 && length == 9) {
                // uppercase
            }else {
                return false;
            }
        });

        $("#gst").keyup(function(event) {
            var length = $(this).val().length;
            var state = $("#state_id").find(':selected').data("code");
            var state_match;

            // FIRST TWO CHAR IS STATE CODE
            if(length >= 2 && state != $(this).val().substr(0, 2)) {
                
                $("#gst").parent("div.form-group").addClass('has-error');
                $("#gst").next().html("State code mismatched");
                state_match = false;
            }else{
                state_match = true;
                $("#gst").parent("div.form-group").removeClass('has-error');
                $("#gst").next().html("");
            }
            

            if (event.keyCode >= 65 && event.keyCode <= 90 && length > 2 && length <= 7) {
                $(this).val($(this).val().toUpperCase());
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105)) && length > 7 && length <= 11 ) {
                
            }else if ( event.keyCode >= 65 && event.keyCode <= 90 && length == 12) {
                $(this).val($(this).val().toUpperCase());
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105)) && length == 13 ) {
                
            }else if (event.keyCode == 90 && length == 14 ) {
                $(this).val($(this).val().toUpperCase());
            }else if (((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 95 && event.keyCode <= 105) || (event.keyCode >= 65 && event.keyCode <= 90)) && length == 15 ) {
                $(this).val($(this).val().toUpperCase());
            }else if(length > 2 && length < 16 && event.keyCode != 8) {
                // return false;
                $(this).val($(this).val().slice(0,-1));
            }
        });



    });

</script>






