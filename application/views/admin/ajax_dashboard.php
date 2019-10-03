    
            <div class="row">
                <div class="col-md-6">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Total Receivable</h3>
                        </div>
                        <div class="details">
                            <div class="row">
                                <div class="col-xs-6">Total Receivable</div>
                                <div class="col-xs-6 text-right"><span><?php echo $this->price_format($total_receivable); ?></span></div>
                                <div class="col-xs-12">
                                    <div class="separator clearfix"></div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-xs-6 border-right">
                                    <p class="text-info lead">Current</p>
                                    <p><span><?php echo $this->price_format($current_receivable); ?></span></p>
                                </div>
                                <div class="col-xs-6">
                                    <p class="text-danger lead">Overdue</p>
                                    <p><span><?php echo $this->price_format($overdue_receivable); ?></span></p>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Total Payable</h3>
                        </div>
                        <div class="details">
                            <div class="row">
                                <div class="col-xs-6">Total Payable</div>
                                <div class="col-xs-6 text-right"><span><?php echo $this->price_format($total_payable); ?></span></div>
                                <div class="col-xs-12">
                                    <div class="separator clearfix"></div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-xs-6 border-right">
                                    <p class="text-info lead">Current</p>
                                    <p><span><?php echo $this->price_format($current_payable); ?></span></p>
                                </div>
                                <div class="col-xs-6">
                                    <p class="text-danger lead">Overdue</p>
                                    <p><span><?php echo $this->price_format($overdue_payable); ?></span></p>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Cash Flow</h3>
                        </div>
                        <div class="details">                        
                            <div class="row text-right"> 
                                <div class="col-xs-12">
                                    <p class="text-info lead">cash as on <?php echo date("d F Y", strtotime($staring_day)) ?></p>
                                    <p><span><?php echo $this->price_format($cash_flow_opening); ?></span></p>
                                    <p class="text-success lead">Incoming</p>
                                    <p><span><?php echo $this->price_format($cash_flow_in); ?></span></p>
                                    <p class="text-danger lead">Outgoing</p>
                                    <p><span><?php echo $this->price_format($cash_flow_out); ?></span></p>
                                    <p class="text-info lead">cash as on <?php echo date("d F Y", strtotime($ending_day)) ?></p>
                                    <p><span><?php echo $this->price_format($cash_net_flow); ?></span></p>                                
                                </div>   

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box-dashboard">
                        <div class="header">
                            <h3>Fund Flow</h3>
                        </div>
                        <div class="details">                        
                            <div class="row text-right">
                                <div class="col-xs-12">
                                    <p class="text-info lead">cash as on <?php echo date("d F Y", strtotime($staring_day)) ?></p>
                                    <p><span><?php echo $this->price_format($fand_flow_opening); ?></span></p>
                                    <p class="text-success lead">Incoming</p>
                                    <p><span><?php echo $this->price_format($fand_flow_in); ?></span></p>
                                    <p class="text-danger lead">Outgoing</p>
                                    <p><span><?php echo $this->price_format($fand_flow_out); ?></span></p>
                                    <p class="text-info lead">cash as on <?php echo date("d F Y", strtotime($ending_day)) ?></p>
                                    <p><span><?php echo $this->price_format($fand_net_flow); ?></span></p>                                
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box-dashboard">
                        <div class="header" style="position: relative">
                            <h3>Watchlist</h3>
                            <form class="form-inline" role="form" action="<?php echo site_url('admin/add-watchlist'); ?>" method="get" id="watchlist-form" style="position: absolute; top:-4px; right:0;">
                                <div class="form-group">
                                    <input type="text" placeholder="Select Group/Ledger" class="form-control" name="group_ledger" id="watchlist-input" style="min-height:28px; height:28px; padding: 5px 10px; line-height: 1; font-size: 90%;width:130px;">
                                </div>                 
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        <div class="details">
                            <div class="row">                            
                                <div class="col-xs-12">
                                    <?php if ($watch_list_arr): ?>
                                    <table class="table table-watchlist">
                                        <thead>
                                            <tr class="text-info lead">
                                                <th>Account</th>
                                                <th>Amount </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($watch_list_arr as $row) {
                                                    ?>
                                                    <tr>
                                                        <?php
                                                        if ($row['type'] == 'group') {
                                                            ?>
                                                            <td style="font-weight:bolder;"><a href="<?php echo site_url('admin/groups-report-details') . '?group_name=' . $row['name']; ?>"><?php echo $row['name'] ?></a></td>
                                                            <?php
                                                        } else if ($row['type'] == 'ledger') {
                                                            ?>
                                                            <td><a href="<?php echo site_url('admin/ledger-statement') . '?ledger_name=' . $row['name']; ?>"><?php echo $row['name'] ?></a></td>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td><?php echo $this->price_format($row['balence']) ?> <?php echo '(' . $row['balance_type'] . ')' ?></td>
                                                        <td><a href="#" onclick="delete_watchlist(this)" class="btn btn-xs btn-danger" data-account-type="<?php echo $row['account_type']; ?>" data-id="<?php echo $row['group_ledger_id']; ?>">Delete</a></td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                    <img src="<?php echo base_url(); ?>assets/images/norecordfound.png" alt="Watchlist" style="height:156px; width: auto; margin: 0 auto" >
                                    <?php endif; ?>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>


<script>
    $(function() {
        $("#watchlist-input").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('admin/getAllGroupLedgerList'); ?>',
                    data: "group=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        if(data.res=='success'){
                            var words = request.term.split(' ');
                            var results = $.grep(data.product, function(name, index) {
                                var sentence = name.label.toLowerCase();

                                return words.every(function(word) {
                                    return sentence.indexOf(word.toLowerCase()) >= 0;
                                });
                            });
                            response(results);
                        }else if(data.res=='error'){
                            Command: toastr["error"](data.message);
                        }
                    },
                    error: function(request, error) {
                        console.log('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                e.preventDefault() // <--- Prevent the value from being inserted.
                $(this).val(ui.item.value);
                $('#watchlist-form').submit();
            }
        }).focus(function() {
             $(this).autocomplete("search", "");
        });
    });
    
    
    function delete_watchlist(obj) {
        var id = $(obj).data('id');
        var account_type = $(obj).data('account-type');
        window.location.href = "<?php echo base_url(); ?>admin/delete_watchlist/"+account_type+"/"+id;
        console.log(account_type);
    }
    
</script>