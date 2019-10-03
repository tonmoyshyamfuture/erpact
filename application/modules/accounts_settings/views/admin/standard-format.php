<div class="wrapper2">
<section class="content-header">
    <div class="row">
        <div class="col-xs-6"><h1><i class="fa fa-cogs"></i> Settings</h1></div>
        <div class="col-xs-6"></div>
    </div>
</section>
<!-- Main content -->

            <!-- Main content -->
            <section class="content">                
                <div class="row">
                    <div class="col-md-8">
                    <form role="form" action="<?php echo base_url('admin/standard-format');?>" method="post" id="settings_form" enctype="multipart/form-data">
                    <div class="box box-solid">
                        <div class="box-header">
                            <h3 class="box-title">Standard Format</h3>                            
                        </div>
                        
                        <div class="box-body">
                            <div class="row">                                
                                <div class="form-group col-md-12">
                                    <h5>Financial Year</h5>                                        
                                    </div>
                                <div class="form-group col-md-6 ">
                                        <div class="row">
                                        <label class="col-sm-12">Financial Year From</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="fyDate"  name="finalcial_year_from" value="<?php if(isset($settings->finalcial_year_from)){ echo $newDate = date("d/m/Y", strtotime($settings->finalcial_year_from)); } ?>" >
                                        </div>
                                        </div>
                                    </div>
                                <div class="form-group col-md-6">
                                        <div class="row">
                                        <label class="col-sm-12">Books Beginning From</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="bookDate"  name="books_begining_form" value="<?php if(isset($settings->books_begining_form)){ echo $newDate = date("d/m/Y", strtotime($settings->books_begining_form));} ?>" >
                                        </div>
                                        </div>
                                    </div>
                                
                                <div class="form-group col-md-12">
                                    <h5>Base Currency Information</h5>
                                    </div>
                                <div class="form-group col-md-4">
                                        <div class="row">
                                        <label class="col-sm-12">Base Currency</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-inr"></i>
                                                </span>
                                                <select class="form-control" name="base_currency">
                                                <option selected="selected" >Select</option>
                                                <?php if(isset($currency) && !empty($currency)){
                                                    foreach($currency AS $row){ ?>
                                                <option <?php if($row->id == $settings->base_currency){ echo 'selected'; }  ?> value="<?= $row->id; ?>" ><?= $row->currency; ?></option>
                                                <?php   }
                                                }?>
                                            </select>
                                            </div>
                                            
                                        </div>
                                        </div>
                                    </div>
                                <div class="form-group col-md-4 hidden">
                                        <div class="row">
                                        <label class="col-sm-12">Symbol</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" placeholder="e.g: for US Dollar use - fa fa-usd" id="base_currency_symbol" name="base_currency_symbol" value="<?php if(isset($settings->base_currency_symbol)){ echo $settings->base_currency_symbol;} ?>" >                                            
                                        </div>
                                        </div>
                                    </div>
                                <div class="form-group col-md-4">
                                        <div class="row">
                                        <label class="col-sm-12">Formal name</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="formal_name" name="formal_name" value="<?php if(isset($settings->formal_name)){ echo $settings->formal_name;} ?>" >
                                        </div>
                                        </div>
                                    </div>
                                <div class="form-group col-md-4">
                                        <div class="row">
                                        <label class="col-sm-12">Decimal places</label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" id="decimal_places" name="decimal_places" value="<?php if(isset($settings->decimal_places)){ echo $settings->decimal_places;} ?>" >
                                        </div>
                                        </div>
                                    </div> 
                                
                                <div class="form-group col-md-12">
                                        <div class="row">
                                        <label class="col-sm-12">Timezone</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="time_zone_id">
                                                <option selected="selected" >Select Time Zone</option>
                                                <?php if(isset($timezone) && !empty($timezone)){
                                                    foreach($timezone AS $row){ ?>
                                                <option <?php if($row->id == $settings->time_zone_id){ echo 'selected'; }  ?> value="<?= $row->id; ?>" ><?= $row->timezone; ?></option>
                                                <?php   }
                                                }?>
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
                        </form>
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
</div>
<!-- /.content -->

<script>
    $("#fyDate").datepicker({
        dateFormat: 'dd/mm/yy'
    });
    $("#bookDate").datepicker({
        dateFormat: 'dd/mm/yy'
    });
     
   
    </script>
 

      



