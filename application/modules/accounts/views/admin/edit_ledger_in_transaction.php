<div class="form-body">
    <div class="row">
        <input name="id" type="hidden"  value="<?php echo (isset($ledger->id)) ? $ledger->id : '' ?>" /> 
        <div class="col-md-6">
            <div class="form-group">
                <label>Group</label>                                        
                <div class="input-group input-block">
                    <input type="text" id="group_name1" name="" placeholder="Group Name" maxlength="50" class="form-control" value="<?php echo count($ledger) > 0 ? $ledger->group_name : ""; ?>" >
                    <input id="group_id" name="group_id" value="<?php echo (isset($ledger->group_id)) ? $ledger->group_id : ''; ?>" type="hidden">
                    <input id="contact_required" name="contact_required" value="<?php echo (isset($contacts->id))?1:0;?>" type="hidden">                                   
                    <span class="input-group-addon btn input-block btn-primary" id="addGroupBtn" style="height: 40px;">
                        <i class="fa fa-plus"></i>
                    </span>                                        
                </div>
                <span class="errorMessage"></span>
            </div>
        </div>                                
        <div class="col-md-6">
            <div class="form-group input-block">
                <label>Ledger Name</label>
                <input type="text" class="form-control" id="ladger_name" name="ladger_name" placeholder="Ledger Name" maxlength="50" value="<?php echo (isset($ledger->ladger_name)) ? $ledger->ladger_name : '' ?>" autocomplete="off">                
                <span class="errorMessage"></span>
            </div>   
        </div>

        <!-- bank details section -->
        <div id="bank_details_section" style="<?php echo ($ledger->group_id ==10) ? '' : 'display: none'; ?>">
            <div class="col-md-6">
                <div class="form-group input-block">
                    <label>Bank Name</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" value="<?php echo (isset($bank_details->bank_name)) ? $bank_details->bank_name : '' ?>"  autocomplete="off">
                </div>   
            </div>
            <div class="col-md-6">
                <div class="form-group input-block">
                    <label>Branch Name</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Branch Name" value="<?php echo (isset($bank_details->branch_name)) ? $bank_details->branch_name : '' ?>"  autocomplete="off">
                </div>   
            </div>
            <div class="col-md-6">
                <div class="form-group input-block">
                    <label>Account No</label>
                    <input type="text" class="form-control" id="acc_no" name="acc_no" placeholder="Account No" value="<?php echo (isset($bank_details->acc_no)) ? $bank_details->acc_no : '' ?>"  autocomplete="off">
                </div>   
            </div>
            <div class="col-md-6">
                <div class="form-group input-block">
                    <label>IFSC Code</label>
                    <input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="IFSC Code" value="<?php echo (isset($bank_details->ifsc)) ? $bank_details->ifsc : '' ?>" autocomplete="off">
                </div>   
            </div>
            <div class="col-md-12">
                <div class="form-group input-block">
                    <label>Bank Address</label>
                    <textarea type="text" class="form-control" id="bank_address" name="bank_address" placeholder="Bank Address"><?php echo (isset($bank_details->bank_address)) ? $bank_details->bank_address : '' ?></textarea>
                </div>   
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Tracking</label><br>
                <div class="radio-inline">
                    <label>
                        <input name="tracking_status" id="tracking_status" type="radio"   <?php
                        if (count($ledger) > 0) {
                            if ($ledger->tracking_status == '1') {
                                echo 'checked';
                            }
                        }
                        ?>  value="1" /> Yes</label>
                    </div>
                    <div class="radio-inline">
                        <label><input name="tracking_status" id="tracking_status" type="radio"  <?php
                        if (count($ledger) > 0) {
                            if ($ledger->tracking_status == '2') {
                                echo 'checked';
                            }
                        } else {
                            echo 'checked';
                        }
                        ?>    value="2" /> No</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">       
                    <label>Bill Wise Details</label><br> 
                    <div class="radio-inline">
                        <label>
                            <input name="bill_details_status" id="bill_details_status" type="radio"   <?php
                            if (count($ledger) > 0) {
                                if ($ledger->bill_details_status == '1') {
                                    echo 'checked';
                                }
                            }
                            ?>  value="1" /> Yes
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                            <input name="bill_details_status" id="bill_details_status" type="radio"  <?php
                            if (count($ledger) > 0) {
                                if ($ledger->bill_details_status == '2') {
                                    echo 'checked';
                                }
                            } else {
                                echo 'checked';
                            }
                            ?>    value="2" /> No
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">       
                    <label>Service Activated</label><br> 
                    <div class="radio-inline">
                        <label>
                            <input name="service_status" id="service_status" type="radio"   <?php
                            if (count($ledger) > 0) {
                                if ($ledger->service_status == '1') {
                                    echo 'checked';
                                }
                            }
                            ?>  value="1" /> Yes</label>
                        </div>
                        <div class="radio-inline">
                            <label>
                                <input name="service_status" id="service_status" type="radio"  <?php
                                if (count($ledger) > 0) {
                                    if ($ledger->service_status == '2') {
                                        echo 'checked';
                                    }
                                } else {
                                    echo 'checked';
                                }
                                ?>    value="2" /> No</label>
                            </div>
                        </div>
                    </div>



                    <?php
                    if ($ledger_code_status != '1')
                    {
                        ?>
                        <div class="col-md-4"> 
                            <div class="form-group">
                                <?php
                                if ($ledger_code_status != '1') {

                                    echo '<label>Ledger Code</label>';
                                }
                                ?>

                                <input type="<?php if ($ledger_code_status == '1') { echo 'hidden'; } else {  echo 'text'; } ?>"  name="ledger_code" id="ledger_code" value="<?php echo count($ledger) > 0 ? $ledger->ledger_code : '' ?>" class="form-control" placeholder="Ledger Code" />                                    

                            </div>
                        </div>
                        <?php
                    }
                    ?>



                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Opening Balance</label>
                            <div class="row">
                                <div class="col-xs-12 input-block">
                                    <input type="text" id="account"  name="account" placeholder="Dr/Cr" value="<?php echo isset($ledger->account) ? $ledger->account : ""; ?>" class="form-control debitTags account_type" style="display: inline-block; width:28%;">
                                    <input type="text" name="opening_balance" id="opening_balance" autocomplete="off" class="form-control" value="<?php echo (isset($ledger->balance)) ? $ledger->balance : '' ?>" placeholder="Balance" style="display: inline-block; width:72%; margin-left: -4px">
                                    <span class="errorMessage"></span>
                                </div>                                            
                            </div>                                        
                        </div>
                    </div>

                    <div class="col-md-8 credit_limit_div" style="display:<?php echo ((count($ledger) > 0) && ($ledger->bill_details_status == '1' )) ? 'block' : 'none'; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Credit Days</label> 
                                    <input type="number" min="0" class="form-control onlyNumeric" name="credit_date" placeholder="Credit days" value="<?php echo isset($ledger->credit_date) ? $ledger->credit_date : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Credit Limit</label> 
                                    <input type="number" min="0" class="form-control onlyNumeric" name="credit_limit" placeholder="Credit limit" value="<?php echo isset($ledger->credit_limit) ? $ledger->credit_limit : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix">
                            
                        <div id="contact_section" style="<?php echo ($ledger->group_id == 15 || $ledger->group_id == 23) ? '' : 'display: none'; ?>">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Select Contact</label> 
                                    <div class="input-group input-block">
                                        <input type="text" class="form-control contact"  placeholder="Select Contact" autocomplete="off" value="<?php echo (isset($contacts->company_name))?$contacts->company_name:'';?>">
                                        <span class="input-group-addon btn input-block btn-primary" id="addContactBtn" style="height: 40px;">
                                            <i class="fa fa-plus"></i>
                                        </span> 
                                        <input type="hidden" name="contact_id" id="contact_id" value="<?php echo (isset($contacts->id))?$contacts->id:'';?>">
                                        <span class="errorMessage"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5"></div>
                        </div>
                    </div>
                </div>
            </div>

            