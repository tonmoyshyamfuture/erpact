<div class="wrapper2">
        <section class="content-header">
          <h1>
              <i class="fa fa-upload"></i> Upload Theme
          </h1>
        </section>

        <!-- Main content -->
        <section class="content"> 
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <!--<h3 class="box-title">User List</h3>-->
                  <!--<input type="button" class="btn btn-primary" value="Add User" onclick="window.location.href='<?php //echo $this->config->item('base_url');?>admin/add-users.html'" />-->
                </div><!-- /.box-header -->
                <div class="box-body table-fullwidth">
                    <div class="empty-data">
                      <form action="<?= site_url('admin/theme/upload') ?>" method="post" enctype="multipart/form-data">
                        <h2>Upload your themes from here</h2>
                        <p class="lead">Select your theme</p>
                        <input class="form-control" type="file" name="theme" />
                        <p>Only .zip file can be uploaded</p>
                        <select class="form-control" name="category" style="width:30%;margin:0 auto;">
                          <option value="0">--Select Theme Category--</option>
                          <?php array_map(function($category){
                            echo '<option value="'.$category->cid.'">'.$category->category_name.'</option>';
                          },$categories); ?>
                        </select>
                        <p>Select your theme category</p>
                        <p><button type="submit" name="upload" value="ok" class="btn btn-default">Upload</button></p>
                      </form>
                    </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        
</div>

<style type="text/css">
  ﻿@charset "utf-8";
body {
  overflow: hidden;
}

/* Preloader */
#preloader {
  position: fixed;
  top:0;
  left:0;
  right:0;
  bottom:0;
  background-color:#fff; /* change if the mask should be a color other than white */
  z-index:99; /* makes sure it stays on top */
}

#status {
  width:200px;
  height:200px;
  position:absolute;
  left:50%; /* centers the loading animation horizontally on the screen */
  top:50%; /* centers the loading animation vertically on the screen */
  background-image:url('https://media.giphy.com/media/hA9LnXzyZTxmg/giphy.gif'); /* path to your loading animation */
  background-repeat:no-repeat;
  background-position:center;
  background-size: contain;
  margin:-100px 0 0 -100px; /* is width and height divided by two */
}
</style>

<script type="text/javascript">
  function showLoader()
  {
      $(".wrapper2").after('<div id="preloader"><div id="status">&nbsp;</div></div>');
  }

  $(function(){
    $("button[type='submit']").click(function(){
     showLoader(); 
    });
     
  });
</script>   
