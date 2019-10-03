
<div class="wrapper2">    

    <section class="content-header">
        <div class="accounttabs clearfix">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#group" data-toggle="tab"><i class="fa fa-cubes"></i> Groups </a></li>
                <li><a href="#ledger" data-toggle="tab"><i class="fa fa-list"></i> Ledger</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="group">
                    <div class="pull-right btnGroup">
                        <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>                        
                        <input type="button" class="btn btn-primary" value="Add Group" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-groups'); ?>'" />         
                    </div>

                    <div class="content">
                        <div class="row margin-20">            
                            <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
                                <div class="table-scrollable" style="overflow-x: hidden;">
                                    <table class="table table-striped table-bordered dataTable no-footer accountsGroupTable" id="sample_1" role="grid" aria-describedby="sample_1_info">
                                        <thead>
                                            <tr role="row" class="sub-childs">
                                                <th class="sorting_asc accountName" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="
                                                    Account Name
                                                    : activate to sort column ascending" style="width: 100%;">
                                                    Account Name
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table class="table table-striped table-bordered">
                                                        <tbody>
                                                            <?php for ($i = 0; $i < count($tree); $i++) { ?>
                                                                <tr> 
                                                                    <td>
                                                                        <strong><a href="#"><?php echo $tree[$i]['group_name'] . ' (' . $tree[$i]['group_code'] . ')'; ?></a></strong>



                                                                        <?php
                                                                        if (isset($tree[$i]['childs'])) {
                                                                            for ($j = 0; $j < count($tree[$i]['childs']); $j++) {
                                                                                ?> 
                                                                                <ul><!--Start:First Child-->

                                                                                    <li style="list-style: none">
                                                                                        <table class="table table-striped table-bordered">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td ><a href="<?php echo site_url('admin/accounts-group-ledgers') . "/" . $tree[$i]['childs'][$j]['id']; ?>"><?php echo $tree[$i]['childs'][$j]['group_name'] . ' (' . $tree[$i]['childs'][$j]['group_code'] . ')'; ?></a></td>
                                                                                                    <td class="text-right">
                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['id']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </li>
                                                                                    <?php
                                                                                    if (isset($tree[$i]['childs'][$j]['childs'])) {
                                                                                        for ($k = 0; $k < count($tree[$i]['childs'][$j]['childs']); $k++) {
                                                                                            ?>
                                                                                            <li style="list-style: none"> <!--Start:Second Child-->

                                                                                                <ul>

                                                                                                    <li style="list-style: none">
                                                                                                        <table class="table table-striped table-bordered">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td ><?php echo $tree[$i]['childs'][$j]['childs'][$k]['group_name']; ?></td>
                                                                                                                    <td class="text-right" >
                                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['id']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </li>
                                                                                                    <?php
                                                                                                    if (isset($tree[$i]['childs'][$j]['childs'][$k]['childs'])) {
                                                                                                        for ($l = 0; $l < count($tree[$i]['childs'][$j]['childs'][$k]['childs']); $l++) {
                                                                                                            ?> 
                                                                                                            <li style="list-style: none"><!--Start:Third Child-->

                                                                                                                <ul>

                                                                                                                    <li style="list-style: none;">
                                                                                                                        <table class="table table-striped table-bordered">
                                                                                                                            <tbody>
                                                                                                                                <tr>
                                                                                                                                    <td ><?php echo $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['group_name']; ?></td>
                                                                                                                                    <td class="text-right" >
                                                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['id']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            </tbody>
                                                                                                                        </table>
                                                                                                                    </li>
                                                                                                                    <?php
                                                                                                                    if (isset($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'])) {
                                                                                                                        for ($m = 0; $m < count($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs']); $m++) {
                                                                                                                            ?>
                                                                                                                            <li style="list-style: none"><!--Start:Forth Child-->

                                                                                                                                <ul>

                                                                                                                                    <li style="list-style: none">
                                                                                                                                        <table class="table table-striped table-bordered">
                                                                                                                                            <tbody>
                                                                                                                                                <tr>
                                                                                                                                                    <td ><?php echo $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['group_name']; ?></td>
                                                                                                                                                    <td class="text-right" >
                                                                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['group_name']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                                                                    </td>
                                                                                                                                                </tr>
                                                                                                                                            </tbody>
                                                                                                                                        </table>
                                                                                                                                    </li>
                                                                                                                                    <?php
                                                                                                                                    if (isset($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'])) {
                                                                                                                                        for ($n = 0; $n < count($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs']); $n++) {
                                                                                                                                            ?>
                                                                                                                                            <li style="list-style: none"><!--Start:Fifth Child-->
                                                                                                                                                <ul>

                                                                                                                                                    <li style="list-style: none">
                                                                                                                                                        <table class="table table-striped table-bordered">
                                                                                                                                                            <tbody>
                                                                                                                                                                <tr>
                                                                                                                                                                    <td ><?php echo $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$n]['group_name']; ?></td>
                                                                                                                                                                    <td class="text-right" >
                                                                                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$n]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$n]['id']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                                                                                    </td>
                                                                                                                                                                </tr>
                                                                                                                                                            </tbody>
                                                                                                                                                        </table>
                                                                                                                                                    </li>
                                                                                                                                                    <?php
                                                                                                                                                    if (isset($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$n]['childs'])) {
                                                                                                                                                        for ($o = 0; $o < count($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$n]['childs']); $o++) {
                                                                                                                                                            ?>
                                                                                                                                                            <li style="list-style: none"><!--Start:Six Child-->

                                                                                                                                                                <ul>

                                                                                                                                                                    <li style="list-style: none">
                                                                                                                                                                        <table class="table table-striped table-bordered">
                                                                                                                                                                            <tbody>
                                                                                                                                                                                <tr>
                                                                                                                                                                                    <td ><?php echo $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['group_name']; ?></td>
                                                                                                                                                                                    <td class="text-right" >
                                                                                                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['id']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                                                                                                    </td>
                                                                                                                                                                                </tr>
                                                                                                                                                                            </tbody>
                                                                                                                                                                        </table>

                                                                                                                                                                    </li>
                                                                                                                                                                    <?php
                                                                                                                                                                    if (isset($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['childs'])) {
                                                                                                                                                                        for ($p = 0; $p < count($tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['childs']); $p++) {
                                                                                                                                                                            ?>
                                                                                                                                                                            <li style="list-style: none;"><!--Start:Seventh Child-->
                                                                                                                                                                                <ul>
                                                                                                                                                                                    <li style="list-style: none">
                                                                                                                                                                                        <table class="table table-striped table-bordered">
                                                                                                                                                                                            <tbody>
                                                                                                                                                                                                <tr>
                                                                                                                                                                                                    <td ><?php echo $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['childs'][$p]['group_name']; ?></td>
                                                                                                                                                                                                    <td class="text-right" >
                                                                                                                                                                                                        <a href="<?php echo site_url('admin/edit-accounts-groups') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['childs'][$p]['id']; ?>" title="Edit"><i class="fa fa-pencil-square popup"></i></a>&nbsp;&nbsp;
                                                                                                                                                                                                        <a href="<?php echo base_url('accounts/groups/remove') . "/" . $tree[$i]['childs'][$j]['childs'][$k]['childs'][$l]['childs'][$m]['childs'][$o]['childs'][$p]['id']; ?>" title="Delete"><i class="fa fa-bitbucket-square"></i></a>
                                                                                                                                                                                                    </td>
                                                                                                                                                                                                </tr>
                                                                                                                                                                                            </tbody>
                                                                                                                                                                                        </table>
                                                                                                                                                                                    </li>
                                                                                                                                                                                </ul>
                                                                                                                                                                            </li><!--End:Seventh child-->
                                                                                                                                                                            <?php
                                                                                                                                                                        }
                                                                                                                                                                    }
                                                                                                                                                                    ?>
                                                                                                                                                                </ul>
                                                                                                                                                            </li><!--End:Six Child-->
                                                                                                                                                            <?php
                                                                                                                                                        }
                                                                                                                                                    }
                                                                                                                                                    ?>
                                                                                                                                                </ul>
                                                                                                                                            </li><!--End:Fifth Child-->
                                                                                                                                            <?php
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    ?>
                                                                                                                                </ul>  
                                                                                                                            </li><!--End:Forth Child-->
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?> 
                                                                                                                </ul>  
                                                                                                            </li><!--End:Third Child-->
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                    ?>  
                                                                                                </ul>
                                                                                            </li><!--Third:Second Child-->
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    ?> 
                                                                                </ul><!--End:First Child-->
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?> 
                                                                    </td>
                                                                    
                                                                </tr><!--Parent-->
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div><!-- /.col -->
                </div><!-- /.row -->
                </div>

            


            <!-- LEDGER Section ---> 

            <div class="tab-pane fade" id="ledger">
                <div class="pull-right btnGroup">
                    <a href="" class="btn btn-danger hidden checkdelbtn"><i class="fa fa-times"></i></a>                        
                    <input type="button" class="btn btn-primary" value="Add Ledger" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-groups'); ?>'" />         
                </div>


                yyy
            </div>
        </div>

</div>

</section>


</div>
