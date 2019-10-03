<div class="wrapper2">
<section class="content-header">
    <div class="row">
        <div class="col-xs-6">
            <h1> <i class="fa fa-bars"></i> Edit Menu</h1>
        </div>
        <div class="col-xs-6">
            <div class="pull-right">
            <input class="btn btn-primary" type="submit" value="Save"/>
        </div>
            </div>
    </div>          
        </section>
    
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
              <!-- general form elements disabled -->
              <div class="box box-warning">
                <div class="box-body">                    
                  <form role="form" action="<?php echo $this->config->item('base_url');?>admin/editmenu" method="post">
                      <div class="row">
                          <div class="col-md-6 col-md-offset-3">
                    <!-- text input -->                    
                    <!--                     <div class="form-group">
                      <label>Menu Group</label>
                      <select class="form-control" name="m_group" id="m_group">
                        <?php if(!empty($menues)){
                           foreach ($menues as $menu) {
                            ?>
                            <option value="<?= $menu->name ?>"><?= $menu->name; ?></option>
                            <?php     
                           }
                        } ?>
                        
                      </select>
                    </div> -->

                    <div class="form-group">
                      <label> Label</label>
                      <input type="text" class="form-control" name="m_name" value="<?= $menu->label ?>" placeholder=""/>
                    </div>
                    
                    <div class="form-group">
                      <label>URL</label>
                      <input type="text" class="form-control" name="m_url" placeholder="" value="<?= $menu->url ?>"/>
                    </div>
                    
                    <div class="form-group">
                      <label>Icon</label>
                      <input type="text" name="m_icon" value="<?= $menu->icon ?>" class="form-control" placeholder=""/>
                    </div>
                    
                    <div class="form-group">
                      <label>Active</label>
                      <input type="text" class="form-control" name="m_active" placeholder="" value="<?= $menu->active ?>" />
                    </div>
                      <input type="hidden" name="id" value="<?= $id ?>" />
                      <input type="hidden" name="m_status" value="<?= $menu->status ?>" />                    
                      
                    </div>
                    </div>
                      <div class="footer-button">
                      <input class="btn btn-primary" type="submit" value="Save"/>
                    </div>
                  </form>
                    
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
    
</div>