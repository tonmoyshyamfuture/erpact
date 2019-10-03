<br>
<form id="add-branch-form" method="POST" enctype="multipart/form-data" action="<?php echo site_url('admin/add_branch_ajax'); ?>">
<div class="container content">
    <div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title"> <i class="fa fa-map-marker"></i> Organization</h3>
        <div class="pull-right">
            <a href="<?php echo site_url('admin/accounts-settings'); ?>" class="btn btn-default">Back</a>
                     <button type="submit" class="btn btn-primary ladda-button" data-color="blue" data-style="slide-right">Save</button>
        </div>
    </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2 col-sm-3">
                    <div class="company-logo form-group">
                            <span class="btn btn-default btn-file " style="border-color: #c6d6db; margin-right: 10px; padding: 0;height:auto">
                                <input type="file" name="company_logo" id="company_logo">
                                <img id="logo_img" src="<?php echo base_url() . 'assets/uploads/comp.png'; ?>">
                            </span>
                            <div class="pull-left">
                            </div>
                    </div>
                    </div>                                
                <div class="col-md-10 col-sm-9">
                    <div class="form-group">
                    <div class="row">
                        <label class="col-sm-12">Name of the Branch</label>
                        <div class="col-sm-12">
                            <input class="form-control EntTab" id="company_name" name="company_name" placeholder="Enter your branch name" type="text">
                            <span class="errorMessage"></span>
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
                            <input class="form-control" id="mailing_name" name="mailing_name" placeholder="Mailing Name name" type="text">
                            <span class="errorMessage"></span>
                        </div>                        
                    </div> 
                </div>

                <div class="form-group col-md-12">
                    <div class="row">
                        <label class="col-sm-12">Address </label>
                        <div class="col-sm-12">
                            <div class="row">
                                <!-- <div class="col-xs-4 col-sm-2"><input class="form-control" type="text" placeholder="Appt. No." id="appt_number" name="appt_number"  ></div> -->
                                <div class="col-xs-12 col-sm-12"><input class="form-control" type="text" id="street_address" name="street_address" placeholder="Street Address"></div>
                                <div class="col-md-12"><span class="errorMessage"></span></div>
                            </div>                            
                        </div>                        
                    </div>                                    
                </div>                                

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Country</label>
                        <div class="col-sm-12">
                            <select class="form-control" name="country_id" id="country_id">
                                <option value="">Select</option>
                                <?php
                                if (isset($countries) && !empty($countries)) {
                                    foreach ($countries AS $row) {
                                        ?>
                                        <option value="<?= $row->id; ?>" <?php if($row->id == 101){echo "selected";} ?>><?= $row->name; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="errorMessage"></span>
                        </div>                        
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">State</label>
                        <div class="col-sm-12">
                            <select name="state_id" class="form-control" id="state_id">
                                <option value="">Select State</option>
                                <?php foreach($states as $state) : ?>
                                    <option value="<?php echo $state->id; ?>" data-code="<?php echo $state->state_code; ?>"><?php echo $state->name; ?></option>
                                <?php endforeach; ?>
                            </select>
<span class="errorMessage"></span>
                        </div>
                        
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">City</label>
                        <div class="col-sm-12">
                            <input class="form-control nonNumeric" type="text" placeholder="City Name" id="city_name" name="city_name" ><span class="errorMessage"></span>
                            
                        </div>
                        
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">ZIP/PIN</label>
                        <div class="col-sm-12">
                            <input class="form-control onlyNumeric" placeholder="ZIP/PIN" type="text" id="zip_code" name="zip_code" >
                         <span class="errorMessage"></span></div>
                       
                    </div>
                </div>  
                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Telephone</label>
                        <div class="col-sm-12">
                            <input class="form-control onlyNumeric" placeholder="Telephone" type="tel" id="telephone" name="telephone" >
                         <span class="errorMessage"></span></div>
                       
                    </div>
                </div>  
                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Mobile</label>
                        <div class="col-sm-12">
                            <input class="form-control onlyNumeric" placeholder="Mobile" type="text" id="mobile" name="mobile" maxlength="10" >
                         <span class="errorMessage"></span></div>
                       
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Email</label>
                        <div class="col-sm-12">
                            <input class="form-control" placeholder="Email" type="text" id="email" name="email" >
                         <span class="errorMessage"></span></div>
                       
                    </div>
                </div>                                  
                <div class="col-md-12">
                    <div class="clearfix">
                        <h5>Company Tax Details</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PAN</label>
                                    <input type="text" class="form-control text-uppercase" name="pan" id="pan" placeholder="PAN (10 digit)" maxlength="10">
                                    <span class="errorMessage"></span>
                                </div>   
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">                                    
                                    <label for="consumer-type" class="black">Company Type</label>
                                    
                                    <!-- <label class="radio-inline">
                                        <input type="radio" name="company_type" value="1" checked="">Regular
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="company_type" value="2">Composite
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="company_type" value="3">Unregister
                                    </label> -->
                                    <select class="form-control" name="company_type">
                                        <option value="1">Regular</option>
                                        <option value="2">Composite</option>
                                        <option value="3">Unregister</option>
                                    </select>
                                    <span class="errorMessage"></span>
                                </div>
                            </div>  
                            <div class="col-md-4" id="gstn_section">
                                <div class="form-group">
                                    <label>GSTN No.</label>
                                    <input class="form-control" name="gst" id="gst" placeholder="GSTN No" maxlength="15" autocomplete="off">
                                    <span class="errorMessage" id="gst_error"></span>
                                </div>   
                            </div>

                        </div>

                    </div>   
                </div>
        </div>
    </div>
<div class='box-footer'>
        <div class="footer-button">
            <button type="submit" class="btn btn-primary ladda-button" data-color="blue" data-style="slide-right">Save</button>
        </div>
</div>
</div>  
</div>
    </form> 

<style>
    .errorMessage{line-height: 20px;}
</style>
<script>
    function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#logo_img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#company_logo").change(function(){
    readURL(this);
});
    
    $('#country_id').on('change', function() {
        var country_id = this.value;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('customer_details/admin/getState'); ?>",
            data: 'country_id=' + country_id,
            success: function(result) {
                $("#state_id").html(result);
            }
        });
    });

    $("#add-branch-form").submit(function(event) {
        event.preventDefault();
         var l = Ladda.create(document.querySelector('.ladda-button'));
        l.start();
        var form = $(this),
                url = form.attr('action'),
                data = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData(this),
            dataType: 'json',
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function(data) {
                l.stop();
                $('.errorMessage').html('');
                $('.form-group').removeClass('has-error');
                console.log(data.res);
                if (data.res == 'error') {
                    $.each(data.message, function(index, value) {
                        $('#' + index).closest('.form-group').addClass('has-error');
                        $('#' + index).closest('.form-group').find('.errorMessage').html(value);
                    });

                }else if(data.res == 'save_error'){
                  Command: toastr["error"](data.message);   
                } else {
                    Command: toastr["success"](data.message);
                    window.location.href = data.url;
                }
            }
        });

    });
    
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
