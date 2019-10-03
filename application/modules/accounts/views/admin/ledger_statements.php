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
                  <h1> <i class="fa fa-bold"></i> Ledger Statement</h1>  
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
                    <div class="col-lg-4">
                        <select name="ledger_id" id="statement_type" class="form-control input-mg select2me">
                            <?php foreach ($ledgers as $key => $val) { ?>
                                <option value="<?php echo $key; ?>" name="<?php echo $val; ?>"><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-5">
                            <?php
                            $date = array(
                                'name' => 'from_date',
                                'class' => 'form-control date-picker',
                                'id' => 'from_date',
                                'placeholder' => 'From Date'
                            );
                            echo form_input($date);
                            ?>
                        </div>
                        <div class="col-md-5">
                            <?php
                            $date = array(
                                'name' => 'to_date',
                                'class' => 'form-control date-picker',
                                'id' => 'to_date',
                                'placeholder' => 'To Date'
                            );
                            echo form_input($date);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <input type="button" value="Search" class="btn btn-primary" onclick="searchStatementByDate();">
                        </div>
                    </div>
                
            <div class="col-xs-12">
              <div class="box">               
                <div class="box-body table-fullwidth">
                    <div class="table-responsive" id="LedgerStatement">
                    
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="footer-button">
                    </div>
                </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     
</div>