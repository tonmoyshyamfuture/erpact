<style>
    .processing{display: none;}
    .branch-list .small-box{ border-radius: 5px;  min-height: 150px;  position: relative;}    
    .content-header{left: 0;}
    .content{margin-top: 70px;}
    @media (max-width:991px) {
        .container-fluidx .rowx .col-xs-6{display: none;}
        .container-fluidx .rowx .col-xs-3{width: 50%;}
        .top_nav .navbar-right{width:auto}
    }
    @media (max-width:767px) {
        .fixed .main-header{position: relative;display: block;width: 100%;height: 50px;}
        .fixed .content-wrapper{padding-top: 0}
        .content-header{position: relative;top:0}
        .container-fluidx .rowx .col-xs-3:first-child{display: none;}
        .container-fluidx .rowx .col-xs-3:last-child{float: right; width: auto}
        .main-header .navbar{float: right; width: auto}
        .main-header .logo{float: left;width:auto}
        .content{margin-top: 30px;}
        .content-header h1{text-align: center;display: block;width: 100%;}
    }
    svg{width:15px; height: 15px;}
</style>
<div class="wrapper2" style="display:block !important;">
    <section class="content-header" style="margin-bottom: 20px!important; margin-top: 1px !important">
    <div class="row">
        <div class="col-xs-12">
            <h1><i data-feather="share-2"></i>&nbsp; Branches of</span> <?php echo $company->company_name; ?></h1>  
        </div>        
        <div class="col-xs-12">
            <?php if($this->session->userdata('admin_uid')==1):?>
         <!--<a href="<?php //echo site_url('admin/add-branch'); ?>" class="btn btn-primary pull-right" style="margin-right:2%;">Add Branch</a>-->
         <!--<a href="<?php //echo site_url('admin/user-access'); ?>" class="btn btn-primary pull-right" style="margin-right:2%;">User Access</a>-->
     <?php endif;?>
        </div>
        
    </div>
</section>    
    
<section class="content">
    <div class="row">
        <div id="branch-list">
    <?php
    foreach ($branch as $i => $row) {
        ?>          
          
            <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="branch container<?= ($i == 0) ? '1' : '2' ?>">
                <a href="javascript:void(0);" data-id="<?= $row->id ?>" class="branch-name">
                    <table class="table">
                        <tr>
                            <td class="branch-title"><?= $row->company_name ?></td>
                        </tr>
                        
                        <tr>
                            <td class="branch-address">
                                <?= $row->street_address ?><br>
                                <!--<?= $row->city ?> - <?= $row->zip ?>
                                <?= $row->state ?>, <?= $row->country ?>-->
                            </td>
                        </tr>
                    </table>                    
                </a>                
            </div>
                </div>
            
        <div class="col-lg-3 col-md-4 col-sm-2 hidden">
            <!-- small box -->
            <div class="small-box <?= ($i == 0) ? 'bg-green' : 'bg-aqua' ?>">
                <div class="inner text-center">
                    <h4><?= $row->company_name ?></h4>
                    <p><?= $row->street_address ?></p>
                    <p><?= $row->city ?> - <?= $row->zip ?></p>
                    <p><?= $row->state ?>, <?= $row->country ?></p>                    
                </div>
                <div class="icon">
                    <?php if($i == 0){ ?>
                    <i class="fa fa-pie-chart"></i>                    
                    <?php } else { ?>
                    <i class="fa fa-bar-chart"></i>
                    <?php } ?>
                    
                </div>
                <a href="javascript:void(0);" data-id="<?= $row->id ?>" class="small-box-footer branch-name">View Details <i class="fa fa-arrow-circle-right"></i><i class="fa fa-2x fa-spinner fa-spin pull-right processing"></i></a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
        
        
        
        
    </div>
</section>
</div>

<script>
    $("#branch-list").on("click", ".branch-name", function() {
        $(this).find('.processing').css('display','block');
        var self=$(this);
        var branch_id = self.attr('data-id');
        $.ajax({
            method: "POST",
            url: "<?php echo site_url('admin/set_branch'); ?>",
            data: {branch_id: branch_id},
            dataType: 'json'
        }).done(function(data) {
           self.find('.processing').css('display','none');
            if (data.res == 'success') {
                window.location.href = data.url;
            } else {
                alert(data.message);
            }
        });
    });
</script>