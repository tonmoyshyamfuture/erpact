
          <style>.daterangepicker{ left: 671px!important;}</style>                     
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                    <h1><i class="fa fa-cubes"></i> List of Ledgers</h1>
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                           
                    </div>
                </div> 
            </div> 
        </section>  
          <section>
        <?php
        $breadcrumbs=array(
            'Home'=>'/admin/dashboard',
            'Report'=>'#',
            'Accounts Book'=>'aa',
            'Ledger Book'=>'/admin/accounts-ledger-statement',
        );
        $this->session->set_userdata('_breadcrumbs',$breadcrumbs);
        foreach ($breadcrumbs as $k=>$b) {
        $this->breadcrumbs->push($k, $b);    
        }
        $this->breadcrumbs->show(); 
        ?>
    </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <div class="box">
                        <div class="box-body" style="padding: 15px;">
                          <form role="form" class="form-inline" action="<?php echo base_url('admin/ledger-statement'); ?>" method="get" id="settings_form" enctype="multipart/form-data"> 
                       <!--  <div class="form-group">
                            <input type="text" placeholder="Select Ledger Name" class="form-control ledger" name="ledger_name" value=""  id="listfocus" >
                        </div>  --> 
                       
                       <table style="width:100%;">
                            <tr>
                                <td><input type="text" placeholder="Select Ledger Name" class="form-control ledger" name="ledger_name" value=""  id="listfocus" maxlength="50" style="width: 100%;"></td>
                                <td width="76"><button type="submit" class="btn btn-primary" style="height: 40px;">Submit</button></td>
                            </tr>
                        </table>
                        </form>
                        </div>
                </div>
                </div>
            </div>
        </section>
<script>
    // $(function () {

    //     $('.pldaterange').daterangepicker({
    //         ranges: {
    //             'Today': [moment(), moment()],
    //             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //             'This Month': [moment().startOf('month'), moment().endOf('month')],
    //             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //         },
    //         startDate: moment().subtract(29, 'days'),
    //         endDate: moment()
    //     }, function (start, end) {
    //         var staring_day = start.format('YYYY-MM-D');
    //         var ending_day = end.format('YYYY-MM-D');
    //         $("#staring_day").val(staring_day);
    //         $("#ending_day").val(ending_day);

    //     });
    // });
    // $('document').ready(function(){
    //     $(".ledger").focus();
    // })
</script>
