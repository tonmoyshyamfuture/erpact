<div class="wrapper2">  
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
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
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-cc-visa" aria-hidden="true"></i> <?php echo (isset($sub_voucher->name)) ? $sub_voucher->name : '' ?></h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
     <input type="button" class="btn btn-primary" value="Add Entry" onclick="window.location.href = '<?php echo site_url('admin/sub-voucher-transaction') . '/' . $sub_voucher_id . '/' . $entry_type_id; ?>'" />
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
                            <table id="example00" class="table table-striped fcol-100 lcol-70 col-2-70 col-4-70 col-5-70 col-6-70">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Number</th>                        
                                        <th>Ledger</th>                        
                                        <th>Type</th>
                                        <th>Amount</th>                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_entries as $entry) { ?> 
                                        <tr>
                                            <!-- <td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td> -->
                                            <td><?php echo get_date_format($entry['create_date']); ?></td>
                                            <td><?php echo $entry['entry_no']; ?></td>
                                            <td>
                                                <?php
                                                $led = array();
                                                $devit = json_decode($entry['ledger_ids_by_accounts']);
                                                echo "<strong>Dr </strong>";
                                                for ($i = 0; $i < count($devit->Dr); $i++) {
                                                    echo $devit->Dr[$i];
                                                    if (count($devit->Dr) > 1) {
                                                        echo ' + ';
                                                    }
                                                    break;
                                                }
                                                ?>
                                                /
                                                <?php
                                                echo "<strong>Cr </strong>";
                                                for ($i = 0; $i < count($devit->Cr); $i++) {
                                                    echo $devit->Cr[$i];
                                                    if (count($devit->Cr) > 1) {
                                                        echo ' + ';
                                                    }
                                                    break;
                                                }
                                                ?>
                                            </td>
                                            <!-- <td><?php echo $entry['type']; ?></td> -->
                                            <td><?php
                                                if (isset($entry['sub_voucher_name'])) {
                                                    echo $entry['sub_voucher_name'];
                                                }
                                                ?></td>
                                            <td><?php echo $entry['dr_amount']; ?></td>
                                           
                                            <td>
                                                <div class="dropdown circle">
                                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-ellipsis-v"></i></a>
                                                    <ul class="dropdown-menu tablemenu">
                                                        <li><a href="<?php echo site_url('admin/edit-accounts-entry') . "/" . $entry['id'] . '/' . $entry_type_id; ?>"><span><i class="fa fa-pencil"></i></a></li>
                                                        <li> <a href="<?php echo base_url('accounts/entries/delete') . '/' . $entry['id'] . '/' . $entry_type_id; ?>"><span style="color:#dd4b39;"><i class="fa fa-trash"></i></span></a></li>
                                                    </ul>
                                                </div>
           <!--                                     <a href="<?php //echo site_url('admin/edit-accounts-entry')."/".$entry['id'];   ?>"><span><i class="fa fa-pencil-square"></span></i></a>
                                               <a href="<?php //echo base_url('accounts/entries/delete').'/'.$entry['id'];  ?>"><i class="fa fa-bitbucket-square"></i></a>-->
                                            </td> 
                                        </tr>
<?php } ?>
                                </tbody>                    
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="footer-button">
                            <input type="button" class="btn btn-primary" value="Add Entry" onclick="window.location.href = '<?php echo site_url('admin/sub-vouchar-entry'); ?>'" />
                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>


