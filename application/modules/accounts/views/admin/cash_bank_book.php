    <form role="form" action="<?php echo base_url('admin/groups-report-details'); ?>" method="get" id="settings_form" enctype="multipart/form-data">                        
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                    <h1><i class="fa fa-cubes"></i>Cash Bank Book</h1>
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <button type="submit" class="btn  btn-primary">Submit</button>   
                    </div>
                </div> 
            </div> 
        </section>        
        <section class="content">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="box">
                    <div class="box-body">
                        <input type="text" class="form-control cash_bank" name="group_name" value="" id="groupfocus" >
                    </div>
                </div>
                </div>
            </div>
        </section>
    </form>

