<div class="wrapper2">    
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM -->
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    Widget settings form goes here
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn blue">Save changes</button>
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM -->
    <section class="content-header">
          <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bold"></i> Tracking Details</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        
                    </div>
                </div> 
            </div> 
        </section>
        <!-- Main content --> 
        <section class="content">
          <div class="row">
                    
                    
                
            <div class="col-xs-12">
              <div class="box">               
                <div class="box-body table-fullwidth">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample">
                                    <thead>
                                        <tr role="row">
                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending">
                                                Description
                                            </th>
                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">
                                               &nbsp;
                                            </th>
                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">
                                               &nbsp;
                                            </th>
                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">
                                                &nbsp;
                                            </th>
                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="text-align: right">
                                               &nbsp;
                                            </th>
                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="text-align: right">
                                                No of Used
                                            </th>

                                            <th class="" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" style="text-align: right">
                                                &nbsp;
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="LedgerStatement">
                                       
                                            <tr>
                                                <th style="border-right: none;"><strong><?php echo $ladger_name; ?></strong></th>
                                                <td style="border-right: none;">&nbsp;</td>
                                                <td style="border-right: none;">&nbsp;</td>
                                                <td style="border-right: none;">&nbsp;</td>
                                                <th style="border-right: none; text-align: right;"></th>
                                                <th style="border-right: none; text-align: right;"><strong><?php echo $total_used + 1; ?></strong></th>
                                                <th style="border-right: none;">&nbsp;</th>
                                            </tr>
                                            <?php if(isset($entry_details)){
                                                foreach($entry_details AS $key => $value){ ?>
                                                 <tr>
                                                    <th style="border-right: none;padding-left: 5%;"><?php echo $key; ?></th>
                                                    <td style="border-right: none;">&nbsp;</td>
                                                    <td style="border-right: none;">&nbsp;</td>
                                                    <td style="border-right: none;">&nbsp;</td>
                                                    <th style="border-right: none; text-align: right;"></th>
                                                    <th style="border-right: none; text-align: right;"><?php echo $value; ?></th>
                                                    <th style="border-right: none;">&nbsp;</th>
                                                </tr>
                                            <?php    }
                                            } ?>
                                                <tr>
                                                    <th style="border-right: none;padding-left: 5%"><?php echo 'Opening Balance'; ?></th>
                                                    <td style="border-right: none;">&nbsp;</td>
                                                    <td style="border-right: none;">&nbsp;</td>
                                                    <td style="border-right: none;">&nbsp;</td>
                                                    <th style="border-right: none; text-align: right;"></th>
                                                    <th style="border-right: none; text-align: right;"><?php echo 1; ?></th>
                                                    <th style="border-right: none;">&nbsp;</th>
                                                </tr>
                                           
                                       
                                    </tbody>
                                </table>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">
                        <a href="<?php echo base_url('admin/tracking'); ?>">
                                <button id="register-back-btn" type="button" class="btn">
                                    <i class="m-icon-swapleft"></i> Back </button></a>
                    </div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     
</div>