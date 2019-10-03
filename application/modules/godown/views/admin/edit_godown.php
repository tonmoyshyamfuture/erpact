    <form method="POST" id="godown_add_form" action="<?php echo base_url('admin/godown-add.aspx') . "/" . $editGodown['id']; ?>">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Godown</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Parent</label>
                    <select name="parent_id" id="" class="form-control">
                        <option value="">Select Godown</option>
                        <?php foreach ($godowns as $key => $value): ?>
                            <option value="<?php echo $value['id']; ?>" <?php if(isset($editGodown) && ($editGodown['parent_id'] == $value['id'])) { echo "selected"; } ?>><?php echo $value['name']; ?></option>                
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" type="text"  class="form-control placeholder-no-fix" placeholder="Name" value="<?php echo (!empty(set_value('name'))) ? set_value('name') :((isset($editGodown)) ? $editGodown['name'] : ''); ?>" id="godown_name" autocomplete="off"/>
                    <?php echo form_error('name', '<div style="color:red;">', '</div>'); ?>
                </div>
            </div>            
            <div class="col-md-12">
                <div class="form-group">
                    <label>Address</label>
                    <!-- <textarea name="address" value="" class="form-control" placeholder="Address"><?php echo ((isset($editGodown)) ? $editGodown['address'] : ''); ?></textarea> -->
                    <input type="text" name="address" value="<?php echo ((isset($editGodown)) ? $editGodown['address'] : ''); ?>" placeholder="Address" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input name="id" type="hidden"  class="form-control placeholder-no-fix" value="<?php echo (isset($editGodown)) ? $editGodown['id'] : ''; ?>" />
        <button type="submit" class="btn ladda-button godownModalSubmit" data-color="blue" data-style="expand-right" data-size="xs">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>