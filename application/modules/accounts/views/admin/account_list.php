<style>
    ul.list{
        list-style: none;
        padding : 0; 
        margin : 0;

    }

    ul.list li{
        display : block;
        margin: 0;
        padding : 0;
        background-color: #fff;        
        
    }


    ul.list .list-bold{
        font-weight : bold;
        line-height: 22px;
        padding: 8px 15px; border-top: 1px solid #ddd; 
        display: block;
    }

    .table-responsive{
        overflow-x: hidden;
    }
    .table{margin: 0;}
</style>

<div class="wrapper2">    
    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1> <i class="fa fa-file-text-o"></i> Ledgers</h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>                        
                    <input type="button" class="btn btn-primary" value="Add Ledger" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-ledger'); ?>'" />          
                </div>
            </div> 
        </div> 
    </section>
    <!-- Main content --> 
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Ledger Name : </h3>
                    </div>
                    <div class="box-body table-fullwidth">
                        <div class="table-responsivex">
                            
                            <ul class="list">
                                <?php if (count($ledger_list) > 0) {
                                    foreach ($ledger_list as $key => $value) {
                                        ?>
                                        <li>
                                            <div class="list-bold" style="width: 100%;"><?php echo $key; ?> (Group)</div>
                                            
        <?php foreach ($value as $val) { ?>
                                                    
                                                        <table class="table">
                                                            <tr>
                                                                <td class="name">
                                                                    <a href="<?php echo site_url('admin/company-details-ledger') . "/" . $val['id']; ?>">
            <?php echo $val['ladger_name'] . ' (' . $val['ledger_code'] . ')'; ?>
                                                                    </a>
                                                                </td>
                                                                <?php //if($val['opening_balance'] > 0){$op_account_type = ' (Dr) ';}else{$op_account_type = ' (Cr) ';} ?>
                                                                <!--<td class="name" style="width: 210px; text-align: center;"><?php echo number_format($val['opening_balance'] > 0 ? $val['opening_balance'] : substr($val['opening_balance'], 1), 2); ?>&nbsp;(<?php echo $val['account_type']; ?>)</td>-->
            <?php //if($val['current_balance'] > 0){$account_type = ' (Dr) ';}else{$account_type = ' (Cr) ';} ?>
                                                                <!--<td class="name" style="width: 210px; text-align: right;"><?php echo number_format($val['current_balance'], 2); ?><?php echo $account_type; ?></td>-->
                                                                <td class="name" style="width: 100px; text-align: center;">
                                                                    <a class="btn btn-default btn-xs" href="<?php echo site_url('admin/edit-accounts-ledger') . "/" . $val['id']; ?>"><i class="fa fa-pencil"></i></a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    
        <?php } ?>
                                            
                                        </li>
                                    <?php }
                                } else { ?>
                                    <li> <span class="list-bold">No data available in table</span></li>
<?php } ?>
                            </ul>
                            <!-- PAGINATIONS -->
                            <div class="row">
                                <div class="col-md-5 col-sm-12">&nbsp;</div>
                                <div class="col-md-7 col-sm-12">
                                    <ul class="pagination pull-right" style="margin: 10px;"></ul>
                                </div> 
                            </div><!-- /row -->
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="footer-button">
                            <input type="button" class="btn btn-primary" value="Add Ledger" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-ledger'); ?>'" />
                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div>

<script>
    listViewTree.init();
</script>
