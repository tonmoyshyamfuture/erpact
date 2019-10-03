<div class="wrapper2">    
    <form method="POST" id="dashboardSettingForm" action="<?php echo base_url('admin/dashboard-setting'); ?>">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                    <h1> <i class="fa fa-bold"></i>Dashboard Settings</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <input class="btn btn-primary" type="submit" value="Save" name="submit"/>                                
                    </div>
                </div> 
            </div>     
        </section>
        <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Dashboard', '/admin/dashboard-setting');
        $this->breadcrumbs->show();
        ?>
    </section>
        <!-- Main content -->
                <section class="content">
                    <div class="row">        
                        <div class="col-md-8">
                            <!-- general form elements disabled -->
                            <div class="box box-warning">
                                <div class="box-body">
                                    <div class="row">

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="total_receivable" value="1" <?php if(!empty($setting) && $setting->total_receivable == 1) echo "checked"; ?>>Total Receivable</label>
										        </div>
										    </div>
										</div>

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="total_payable" value="1" <?php if(!empty($setting) && $setting->total_payable == 1) echo "checked"; ?>>Total Payable</label>
										        </div>
										    </div>
										</div>

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="cash_flow" value="1" <?php if(!empty($setting) && $setting->cash_flow == 1) echo "checked"; ?>>Cash Flow</label>
										        </div>
										    </div>
										</div>

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="fund_flow" value="1" <?php if(!empty($setting) && $setting->fund_flow == 1) echo "checked"; ?>>Fund Flow</label>
										        </div>
										    </div>
										</div>

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="watchlist" value="1" <?php if(!empty($setting) && $setting->watchlist == 1) echo "checked"; ?>>Watchlist</label>
										        </div>
										    </div>
										</div>

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="sales_summary" value="1" <?php if(!empty($setting) && $setting->sales_summary == 1) echo "checked"; ?>>Sales Summary</label>
										        </div>
										    </div>
										</div>

										<div class="col-md-4">
										    <div class="form-group">
										        <div class="checkbox">
										            <label><input type="checkbox" name="purchase_summary" value="1" <?php if(!empty($setting) && $setting->purchase_summary == 1) echo "checked"; ?>>Purchase Summary</label>
										        </div>
										    </div>
										</div>

                                    </div>
                                    <div class="footer-button">
                                        <input name="id" type="hidden"  class="form-control placeholder-no-fix" value="<?php echo (!empty($setting)) ? $setting->id : ''; ?>" />
                                        <button type="submit" class="btn ladda-button btn-primary" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
                                        
                                    </div>                    
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
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
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
    </form>
</div>


<script>
    $(document).ready(function(){


        $("#dashboardSettingForm").submit(function(event) {
            event.preventDefault();
            var l = Ladda.create(document.querySelector('.ladda-button'));
            l.start();
            var form = $(this),
                    url = form.attr('action'),
                    data = form.serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(data) {
                    l.stop();
                    if (data.res == 'error') {
                        Command: toastr["error"](data.message);
                    } else {
                        Command: toastr["success"](data.message);
                    }
                }
            });

        });


    });
</script>