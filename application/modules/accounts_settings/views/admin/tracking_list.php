<style>
    .search-filed{

    }
    ul.list{
        list-style: none;
        padding : 0 10px;
        margin : 0;

    }

    ul.list li{
        display : block;
        margin: 0;
        padding : 0 10px;
        background-color: #eee;
        box-shadow: inset 0 1px 0 #fff;
    }



    ul.list .list-bold{
        font-weight : bold;
        line-height: 30px;
    	padding: 8px;
    }

	.table-responsive{
		overflow-x: hidden;
	}
</style>

<div class="wrapper2">    
    <section class="content-header">
          <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bold"></i> Tracking List</h1>  
                </div>
                <!-- <div class="col-xs-6">
                    <div class="pull-right">
                        <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>
                        
                  <input type="button" class="btn btn-primary" value="Add Ledger" onclick="window.location.href='<?php echo site_url('admin/add-accounts-ledger'); ?>'" />
          
                    </div>
                </div>  -->
            </div> 
        </section>
        <!-- Main content --> 
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">                
                <div class="box-body table-fullwidth" style="margin-top: 20px;">
                    <div class="table-responsive">
                  <table class="table  table-striped">
                        <thead>
                        <th>
                            Ledger Name
                        </th>
                        <th>
                            
                        </th>
                        <th>
                           
                        </th>
                        <th style="padding-left:77%">
                            Tracking
                        </th>
                        </thead>
                    </table>
                        <ul class="list">
                            <?php if(count($ledger_list) > 0 ) { 
                                foreach($ledger_list as $key => $value) { ?>
                            <li>
                                <div class="list-bold" style="background: #D0E0F2; width: 100%;"><?php echo $key; ?> &nbsp; &nbsp;(Group)<span style="float: right;
    margin-right: 7%;"></span></div>
                                <ul class="list">
                                    <?php foreach ($value as $val) { ?>
                                    <li>
                                        <table>
                                            <tr>
                                                <td class="name" style="width: 230px; text-align: left;">
                                                     <a href="<?php echo site_url('admin/tracking-details') . "/" . $val['id']; ?>">
                                                        <?php echo $val['ladger_name'].' ('.$val['ledger_code'].')'; ?>
                                                    </a>
                                                </td>
                                                
                                                <td class="name" style="width: 710px; text-align: right;">
                                                    <?php echo $val['total_use']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>
                            <?php }} else { ?>
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
               
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
     
</div>

<script>
    listViewTree.init();
</script>
