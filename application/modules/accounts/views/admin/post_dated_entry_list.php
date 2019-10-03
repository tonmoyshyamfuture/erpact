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
                    <h1> <i class="fa fa-list"></i> Post Dated Entry</h1>  
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
                  <table id="example00" class="table table-striped fcol-100 lcol-70 col-2-70 col-4-70 col-5-70 col-6-70 ">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Number</th>                        
                        <th>Ledger</th>
                        <th>Type</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($all_entries as $entry) { ?> 
                            <tr id="entry_<?php echo $entry['id']; ?>">
                                <!-- <td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td> -->
                                <td><?php echo get_date_format($entry['create_date']); ?></td>
                                <td><?php echo $entry['entry_no']; ?></td>
                                <td>
                                    <a href="#<?php echo base_url('admin/trasaction-details') . '.aspx/' . $entry['id']; ?>">
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
                                    </a>
                                </td>
                                <td><?php echo $entry['type']; ?></td>
                                <td><?php echo sprintf('%0.2f', $entry['dr_amount']); ?></td>
                                <td><?php echo sprintf('%0.2f', $entry['cr_amount']); ?></td>
                                <td>
                                    <div class="dropdown circle">
                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i></a>
                                        <ul class="dropdown-menu tablemenu">
                                            <li> 
                                          <a href="javascript:void(0);" title="Delete"  data-id="<?php echo $entry['id']; ?>" class="delete-entry"><span class="text-red"><i class="fa fa-trash" aria-hidden="true"></i></span></a>
                                    </li> 
                                        </ul>
                                    
                                    
                                    </div>
                                    
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>                    
                  </table>
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
<div id="deleteEntryConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('accounts/entries/ajax_delete_post_dated_entry'); ?>" method="post" id="delete-recurring-entry-form">
                <div class="modal-header" style="background: #e68a00;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="" id="delete-entry-id" name="delete_entry_id">
                </div>
                <div class="modal-body">
                     <p style="font-size:16px;">Are you sure to delete this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn ladda-button btn-primary" data-color="red" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(".delete-entry").click(function() {
        var entry_id = $(this).attr("data-id");
        $("#delete-entry-id").val(entry_id);
        $("#deleteEntryConfirm").modal('show');
    });
    $("#delete-recurring-entry-form").submit(function(event) {
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
                if (data.res == 'success') {
                    $('#entry_' + data.entry_id).remove();
                    $("#deleteEntryConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }
            }
        });

    });
</script>

