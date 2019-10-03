<div class="wrapper2">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-bars"></i> Edit Menu</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <button class="btn btn-danger"><i class="fa fa-times"></i></button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>  
            
        </section>
        <!-- Main content -->
        <section class="content">  
            
              <div class="box">
                  <div class="box-body">
                      <div class="wrapper-navigation">
                          <div class="box-header">
                              <h3>Menu Title</h3>                                                                 
                          </div>
                          <div class="clearfix"></div>
                            <div class="box-body">
                                <input type="text" class="form-control" placeholder="Give a name of the menu">
                                <div class="help-block">e.g. Main Menu, Left Menu, Footer Menu etc.</div>
                          </div>                                                    
                          
              </div>
                      
                      <div class="wrapper-navigation">                          
                          <div class="box-header">
                              <div class="pull-left">
                                  <h3>Menu Items</h3>
                              </div>
                              <div class="pull-right">
                                  <a class="link2 appendMenuItem"> <i class="fa fa-plus" ></i>  Add Item</a>
                              </div>
                                   
                          </div>
                          <div class="clearfix"></div>                          
                            <div class="box-body addEditMenuItem">
                                
                                <div class="row">
                                    <div class="col-xs-4"><label>Name</label></div>
                                    <div class="col-xs-3"><label>Link</label>
                                    </div>
                                </div>                                                             
                                <div class="row">
                                    <div class="col-xs-4"><input type="text" class="form-control" placeholder="Menu name"></div>
                                    <div class="col-xs-3">
                                        <select class="form-control ">
                                            <option>Select</option>                                            
                                        </select>
                                    </div>
                                    <div class="col-xs-3">
                                    <select class="form-control "> 
                                            <option>Select</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-2">
                                        <button class="btn btn-warning disabled  pull-right"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                              
                          </div>
                          
              </div>
              
                </div>
                  <div class="box-footer">
                      <div class="footer-button">
                       <input class="btn btn-primary" value="Save" type="submit">
                        </div>
                  </div>
              </div>
            
        </section><!-- /.content -->
     
</div>

<script>
    // append new menu item 
$(document).ready(function(){
    var menudata = '<div class="row">'
+'<div class="col-xs-4"><input type="text" class="form-control" placeholder="Menu name"></div>'
+'<div class="col-xs-3">'
    +'<select class="form-control ">'
        +'<option>Select</option>'
    +'</select>'
+'</div>'
+'<div class="col-xs-3">'
+'<select class="form-control ">'
        +'<option>Select</option>'
    +'</select>'
+'</div>'
+'<div class="col-xs-2">'
    +'<button class="btn btn-warning deletethis pull-right"><i class="fa fa-trash-o"></i></button>'
+'</div>'
+'</div>'; 

    $(".appendMenuItem").click(function(){
        $(".addEditMenuItem").append(menudata);
    });
});
// delete menu item 
$(document).on('click', '.deletethis', function() {
    $(this).closest('.row').remove();
});

</script>