<div class="wrapper2">
    <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                  <h1> <i class="fa fa-image"></i> Themes</h1>  
                </div>
                <div class="col-xs-6">
                    <div class="pull-right">
                        <input type="button" class="btn btn-primary" value="Upload Theme" onclick="window.location.href='<?= site_url('admin/theme/upload') ?>'">
                    </div> 
                    </div>
            </div>          
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box box-solid theme">
            <div class="box-body">
                <div class="theme-left">
                    <div class="filter">
                    <h4>Filter by</h4>
                    <select class="form-control filter_data">
                      <?php
                        // category selected
                        $url_first_segment = !empty($this->uri->segment(3)) ? $this->uri->segment(3) : 'all';
                        //theme type selected
                        $url_second_segment = !empty($this->uri->segment(4)) ? $this->uri->segment(4) : 'all';


                        $filters = array('All','Premium','Free');
                        foreach($filters as $filter)
                        {
                          $current_type = $url_second_segment == strtolower($filter) ? 'selected' : '';
                          ?>
                            <option value="<?= site_url('admin/theme').'/'.$url_first_segment.'/'.strtolower($filter); ?>"  <?= $current_type ?> ><?= $filter ?></option>  
                          <?php
                        }
                       ?>
                    </select>
                    
                    <h4>Sort By</h4>
                    <select class="form-control filter_data">
                      <?php

                        $sorters = array('Newest','Featured','Popular','Price High','Price Low');
                        foreach($sorters as $sorter)
                        {
                          ?>
                            <option value="<?= site_url('admin/theme').'/'.$url_first_segment.'/'.$url_second_segment.'/'.str_replace(' ','-',strtolower($sorter)); ?>"><?= $sorter ?></option>  
                          <?php
                        }
                       ?>
                      
                    </select>
                    
                   </div>
                    <h4>Categories</h4>
                     
                    <ul class="themelist">
                <?php
                 $cat_selected = ($url_first_segment == 'all' || empty($url_first_segment)) ? 'active' : '';
                echo '<li class="'.$cat_selected.'"><a href="'.site_url('admin/theme').'">All</a></li>';
                // loop though all the theme categories from database and render the category menu
                array_map(function($category){
                  // category selected
                  $url_first_segment = !empty($this->uri->segment(3)) ? $this->uri->segment(3) : '';
                  //theme type selected
                  $url_second_segment = !empty($this->uri->segment(4)) ? $this->uri->segment(4) : '';

                  $cat_selected = $url_first_segment == $category->category_slug ? 'active' : '';
                  
                  echo '<li class="'.$cat_selected.'" ><a href="'.site_url('admin/theme').'/'.$category->category_slug.'/'.$url_second_segment.'">'.$category->category_name.'</a></li>';
                },$categories);
                 ?>
                    </ul>
                    
                </div>
                <div class="theme-right">
                    <div class="row  mb40">
                        
                <?php
                if($activetheme) 
                {
                  ?>
                    <div class="col-md-4 col-sm-6 col-xs-12 themethumb">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="themethumb-wrapper brdr-grn">                             
                                        <div class="theme-active"></div>
                                            <div class="theme-img"><img  src="<?= theme_url().$activetheme->theme_slug ?>/screenshot.png" /></div>
                                            <div class="clearfix" ></div>
                                            <div class="theme-caption">
                                                <div class="theme-name">                                            
                                                    <?= $activetheme->theme_name ?> <br><small> - by <?= $activetheme->theme_author ?><br><?= $theme->theme_type == 0 ? 'Free' : 'Premium'; ?></small>
                                                    
                                                </div>
                                                <div class="theme-settings">
                                                    <a class="btn btn-default btn-xs" href="" target="_blank"><i class="fa fa-eye"></i></a>
                                                    <br>                                                    
                                                        <div class="theme-action">
                                                            <div class="btn-group dropup ">
                                                                    <a class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                        <i class="fa fa-gear"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu pull-right">                                                                        
                                                                        <li class="dropdown-submenu"><a href=""> <i class="fa fa-pencil"></i> Edit Theme</a></li>
                                                                        <li class="dropdown-submenu"><a href=""> <i class="fa fa-language"></i> Edit Language</a></li>
                                                                        <li class="dropdown-submenu"><a href=""> <i class="fa fa-cloud-download"></i> Download File</a></li>
                                                                        <li class="dropdown-submenu"><a href=""> <i class="fa fa-files-o"></i> Copy Theme</a></li>
                                                                    </ul>
                                                                  </div>
                                                        </div>
                                                </div> 
                                            </div>                                        
                                </div> 
                            </div>                            
                        </div>
                        </div> 
                  <?php
                }

                // loop though all the themes from database and render the theme preview
                array_map(function($theme){
                  if(!file_exists(APPPATH.'../assets/themes/'.$theme->theme_slug) || $theme->active_theme) return;
                  ?>
<!-- theme 1 -->
                        <div class="col-md-4 col-sm-6 col-xs-12 themethumb">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="themethumb-wrapper">
                                        <div class="theme-img"><img src="<?= theme_url().$theme->theme_slug ?>/screenshot.png" /></div>
                                    <div class="clearfix"></div>
                                    <div class="theme-caption">
                                        <div class="theme-name">                                            
                                            <?= $theme->theme_name ?> <br><small> - by <?= $theme->theme_author ?> <br><?= $theme->theme_type == 0 ? 'Free' : 'Premium'; ?></small>                                             
                                        </div>
                                        <div class="theme-settings">
                                            <a class="btn btn-default btn-xs" href="" target="_blank"><i class="fa fa-eye"></i></a><br>
                                            <div class="theme-action">
                                                <div class="btn-group dropup ">
                                                        <a class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-gear"></i>
                                                        </a>
                                                        <ul class="dropdown-menu pull-right">
                                                            <li class="dropdown-submenu"><a><?php if(!$theme->active_theme){ ?>
                                                                    <a href="<?= site_url('admin/theme/activate').'/'.doUrlEncode($theme->tid); ?>"  title="Activate" ><i class="fa fa-plug"></i>Activate</a>
                                                                <?php } ?></a></li>
                                                            <li class="dropdown-submenu"><a href=""> <i class="fa fa-pencil"></i> Edit Theme</a></li>
                                                            <li class="dropdown-submenu"><a href=""> <i class="fa fa-language"></i> Edit Language</a></li>
                                                            <li class="dropdown-submenu"><a href=""> <i class="fa fa-cloud-download"></i> Download File</a></li>
                                                            <li class="dropdown-submenu"><a href=""> <i class="fa fa-files-o"></i> Copy Theme</a></li>
                                                        </ul>
                                                      </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                  <?php
                },$themes);
                if(empty($themes))
                {
                  echo 'Not Theme Found.';
                }
                 ?> 
                                             
                    </div>
                </div>
            </div>
        </div>           
        </section><!-- /.content -->
        
</div>

<div id="viewThemeImage" class="modal fade in" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Theme Title</h4>
            </div>
            <div class="modal-body">
                <img src="../assets/themes/theme-name/screenshot.png">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
  $(function(){
     $(".filter_data").change(function(){
        var val = $(this).val();
        window.location.href = val;        
     });
  });
</script>