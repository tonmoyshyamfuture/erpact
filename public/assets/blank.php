<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-menu.php'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-xs-5">
                <h1><i class="fa fa-list"></i> Title </h1>
            </div>
            <div class="col-xs-7 text-right">
                <div class="btn-wrapper">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" onclick="location.href='trans-receipt-add.php';">Add</button>
                        <button class="btn btn-info" onclick="location.href='';">Edit</button>
                        <button class="btn btn-danger" onclick="location.href='';">Delete</button>                        
                    </div>
                    <button class="btn btn-sm btn-success" onclick="location.href='';">Save</button>
                    <button class="btn btn-sm btn-default" onclick="location.href='';">Cancel</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content"> 
        <div class="box">
            <div class="box-header">
              <h3>header</h3>              
            </div>
            <div class="box-body">
              body
            </div><!-- /.box-body -->
            <div class="box-footer band">
              footer
            </div><!-- /.box-footer -->
          </div><!-- /.box -->
             
    </section>
</div><!-- /.content-wrapper -->

<?php include_once('includes/footer.php'); ?>