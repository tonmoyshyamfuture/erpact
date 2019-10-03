<div class="wrapper2">    
    <?php //echo form_open(base_url('accounts/new_ledger')); ?>
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-bold"></i>Ledger Company Details </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <?php /* <input class="btn btn-default" onclick="javascript:location.href='<?php echo site_url('admin/blog-posts/');?>'" value="Discard" type="reset"> */ ?>
                    <!--<input class="btn btn-primary" type="submit" value="Save"/>-->
                </div>
            </div> 
        </div>     
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box">
        <div class="box-body">
            <div class="form-body">
                <div class="row">
                <div class="form-group col-md-12">
                    <div class="row">
                        <label class="col-sm-12"><u><strong>Mailing Address</strong></u></label>                                        
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Mailing Name</label>
                        <div class="col-sm-12">
                            <input readonly class="form-control EntTab"  type="text" id="mailing_name" name="mailing_name" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['mailing_name'] : '' ?>" >
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Address</label>
                        <div class="col-sm-12">
                            <input readonly class="form-control" type="text" id="street_address" name="street_address" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['street_address'] : '' ?>" >
                        </div>
                    </div>                                    
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Country</label>
                        <div class="col-sm-12">
                            <input readonly class="form-control" type="text" id="country" name="country" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['country'] : '' ?>" >
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">State</label>
                        <div class="col-sm-12">
                            <input readonly class="form-control"  type="text" id="state" name="state" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['state'] : '' ?>" >
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">City</label>
                        <div class="col-sm-12">
                            <input readonly class="form-control"  type="text" id="city_name" name="city_name" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['city_name'] : '' ?>" >
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <label class="col-sm-12">ZIP/PIN</label>
                        <div class="col-sm-12">
                            <input readonly class="form-control"  type="text" id="zip_code" name="zip_code" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['zip_code'] : '' ?>" >
                        </div>
                    </div>
                </div>  
                <div class="form-group col-md-12">
                    <div class="row">
                        <label class="col-sm-12"><u><strong>Tax Information</strong></u></label>                                        
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <label>PAN / IT No</label>
                            <input readonly type="text"  name="pan_it_no" id="pan_it_no" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['pan_it_no'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-4">
                            <label>Sales Tax No</label>
                            <input readonly type="text"  name="sale_tax_no" id="sale_tax_no" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['sale_tax_no'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-4">
                            <label>CST No</label>
                            <input readonly type="text"  name="cst_no" id="cst_no" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['cst_no'] : '' ?>" class="form-control"  />
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="row">
                        <label class="col-sm-12"><u><strong>Contact Details</strong></u></label>                                        
                    </div>
                </div>
                <div class="form-group col-md-12" id="">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Contact Person Name</label>
                            <input readonly type="text"  name="contact_person" id="contact_person" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['contact_person'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-3">
                            <label>E-Mail</label>
                            <input readonly type="text"  name="email" id="email" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['email'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-3">
                            <label>Phone No</label>
                            <input readonly type="text"  name="phone_no" id="phone_no" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['phone_no'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-3">
                            <label>Designation</label>
                            <input readonly type="text"  name="designation" id="designation" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['designation'] : '' ?>" class="form-control"  />
                        </div>
                    </div>
                </div>
        
                <div class="form-group col-md-12">
                    <div class="row">
                        <label class="col-sm-12"><u><strong>Contact Details 2</strong></u></label>                                        
                    </div>
                </div>
                <div class="form-group col-md-12" id="contact_details_two">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Contact Person Name</label>
                            <input readonly type="text"  name="contact_person2" id="contact_person2" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['contact_person2'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-3">
                            <label>E-Mail</label>
                            <input readonly type="text"  name="email2" id="email2" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['email2'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-3">
                            <label>Phone No</label>
                            <input readonly type="text"  name="phone_no2" id="phone_no2" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['phone_no2'] : '' ?>" class="form-control"  />
                        </div>
                        <div class="col-md-3">
                            <label>Designation</label>
                            <input readonly type="text"  name="designation2" id="designation2" value="<?php echo count($ledger_company_details) > 0 ? $ledger_company_details[0]['designation2'] : '' ?>" class="form-control"  />
                        </div>
                    </div>
                </div>
</div>
            </div>
             </div>   <!-- /.row -->
                <div class="box-footer">
            <div class="footer-button">
                <a href="<?php echo base_url('index.php/accounts'); ?>">
                    <button id="register-back-btn" type="button" class="btn">
                        <i class="m-icon-swapleft"></i> Back </button></a>
            </div>
</div>
       
        </div>
    </section><!-- /.content -->
</div>

