<div class="wrapper2">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bars"></i> Navigation</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <!-- <button class="btn btn-primary addMainMenu">Add menu </button>-->
                        <input class="btn btn-primary"  value="Add Menu" type="submit" data-href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/frontmenuedit');?>'">
                    </div>
                </div>
            </div>  
            
        </section>
        <!-- Main content -->
        <section class="content">            
              <div class="box">
                  <div class="box-body mainMenuContainer">
                      
                      <!--- set 1 -->
                      <div class="wrapper-navigation">
                          <div class="box-header">
                              <div class="pull-left">
                                  <h3>Main Menu </h3>
                              </div>
                              <div class="pull-right">
                                  <a class="link2" href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/frontmenuedit');?>'"><i class="fa fa-pencil" ></i>  Edit </a>
                              </div>
                                   
                          </div>
                          <div class="clearfix"></div>
                            <div class="box-body">
                            <ul class="todo-list menuitemlist">
                              <li>
                                <!-- drag handle -->
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>                      
                                <span class="text">Home</span>                                            
                                <!-- tools -->
                                <div class="tools">                                  
                                  <i class="fa fa-trash-o"></i>
                                </div>
                              </li>
                              <li>
                                <!-- drag handle -->
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>                      
                                <span class="text">About us</span>                                            
                                <!-- tools -->
                                <div class="tools">                                  
                                  <i class="fa fa-trash-o"></i>
                                </div>
                              </li>
                              <li>
                                <!-- drag handle -->
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>                      
                                <span class="text">Contact Us</span>                                            
                                <!-- tools -->
                                <div class="tools">                                  
                                  <i class="fa fa-trash-o"></i>
                                </div>
                              </li>

                            </ul>
                          </div>
              </div>
                        <!--- /set 1 -->  
                        
                        <!--- set 1 -->
                      <div class="wrapper-navigation">
                          <div class="box-header">
                              <div class="pull-left">
                                  <h3>Side Menu </h3>
                              </div>
                              <div class="pull-right">
                                  <a class="link2" href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/frontmenuedit');?>'"><i class="fa fa-pencil" ></i>  Edit </a>
                              </div>
                                   
                          </div>
                          <div class="clearfix"></div>
                            <div class="box-body">
                            <ul class="todo-list menuitemlist">
                              <li>
                                <!-- drag handle -->
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>                      
                                <span class="text">Home</span>                                            
                                <!-- tools -->
                                <div class="tools">                                  
                                  <i class="fa fa-trash-o"></i>
                                </div>
                              </li>
                              <li>
                                <!-- drag handle -->
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>                      
                                <span class="text">About us</span>                                            
                                <!-- tools -->
                                <div class="tools">                                  
                                  <i class="fa fa-trash-o"></i>
                                </div>
                              </li>
                              <li>
                                <!-- drag handle -->
                                <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>                      
                                <span class="text">Contact Us</span>                                            
                                <!-- tools -->
                                <div class="tools">                                  
                                  <i class="fa fa-trash-o"></i>
                                </div>
                              </li>

                            </ul>
                          </div>
              </div>
                        <!--- /set 1 -->  
                        
                </div>
                  
                  <div class="box-footer">
                      <div class="footer-button">
                          <!-- <button class="btn btn-primary addMainMenu">Add menu </button>-->
                          <input class="btn btn-primary" type="submit" value="Add Menu" data-href="javascript:void(0);" onclick="window.location.href='<?php echo site_url('admin/frontmenuedit');?>'">
                      </div>
                  </div>
              </div>
            
        </section><!-- /.content -->
     
</div>

<script>
    $(function () {
    //jQuery UI sortable for the todo list
    $(".menuitemlist").sortable({
      placeholder: "sort-highlight",
      handle: ".handle",
      forcePlaceholderSize: true,
      zIndex: 999999
    });
});
</script>
<!-- 
<script>    
    $(document).ready(function(){
    var url="'<?php echo site_url('admin/frontmenuedit');?>'";
    var menudataMain = '<div class="wrapper-navigation">'
    +'<div class="box-header">'
        +'<div class="pull-left">'
            +'<h3>Blank Menu </h3>'
        +'</div>'
        +'<div class="pull-right">'
            +'<a class="link2" href="javascript:void(0);" onclick="window.location.href='+url+'"><i class="fa fa-pencil" ></i>  Edit </a>'
        +'</div>'
    +'</div>'
    +'<div class="clearfix"></div>'
      +'<div class="box-body">'
      +'<ul class="todo-list menuitemlist">'
        +'<li>'
      +'    You have not yet created any menu item...'
      +'  </li>'
      +'</ul>'
    +'</div>'
  +'</div>'; 
    $(".addMainMenu").click(function(){
        $(".mainMenuContainer").append(menudataMain);
    });        
});
    </script>
-->