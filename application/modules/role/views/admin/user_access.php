<br>
<div class="clearfix" style="padding: 10px;">
    <div class="row">
      <a href="<?php echo site_url('admin/branch'); ?>" class="btn btn-default pull-right" style="margin-right:2%;">Back</a>    
    </div>
  
    <form method="POST" id="module-access-form" action="<?php echo site_url('role/admin/save_module_access'); ?>">
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">User:</label>
            <select class="form-control" name="user_id" id="user_id">
                <option value="">Select user</option>
                <?php
                if (count($user) > 0):
                    foreach ($user as $u) {
                        ?>
                        <option value="<?php echo $u->id; ?>" <?php echo ($u->id==$saas_user_id)?'selected':''?>><?php echo $u->fname . ' ' . $u->lname.' ( '.$u->emailid.' )'; ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
        </div>   
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Branch:</label>
            <select class="form-control" id="branch_id" name="branch_id">
                <option value="">Select Branch</option>
                <?php
                if (count($branch) > 0):
                    foreach ($branch as $b) {
                        ?>
                        <option value="<?php echo $b->id; ?>" <?php echo ($b->id==1)?'selected':''?>><?php echo $b->company_name; ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
        </div>  
    </div>
    <table class="table full-width">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Module</th>
                <th><input type="checkbox" class="all-view" > View</th>
                <th><input type="checkbox" class="all-add"> Add</th>
                <th><input type="checkbox" class="all-edit"> Edit</th>  
                <th><input type="checkbox" class="all-delete"> Delete</th>
                <th><input type="checkbox" class="all-all"> All</th>
            </tr>


        </thead>
        <tbody id="dynamic-module">
            <?php
            foreach ($menu as $i => $row) {
                ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo $row->name; ?><input type="hidden" name="module_id[]" value="<?php echo $row->id; ?>"></td>
                    <td><input type="checkbox" class="view" name="view[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->v) && $row->v==1)? 'checked':'';?>></td>
                    <td><input type="checkbox" class="add" name="add[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->a) && $row->a==1)? 'checked':'';?>></td>
                    <td><input type="checkbox" class="edit" name="edit[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->e) && $row->e==1)? 'checked':'';?>></td>
                    <td><input type="checkbox" class="delete" name="delete[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->d) && $row->d==1)? 'checked':'';?>></td>
                    <td><input type="checkbox" class="all"></td>
                </tr>
                <?php
            }
            ?>   
        </tbody>
    </table>
    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-success pull-right ladda-button module-submit-btn" data-color="green" data-style="expand-right" data-size="s">Save</button>
        </div>   
    </div>
    <br>
    <br>
    </form>
</div>  
<script>
    $("body").delegate("#branch_id,#user_id", "change", function() {
        var branch_id = $("#branch_id").val();
        var user_id = $("#user_id").val();
        $.ajax({
            method: "POST",
            url: "<?php echo site_url('role/admin/ajax_get_module'); ?>",
            data: {branch_id: branch_id, user_id: user_id},
            dataType: "json",
        }).done(function(data) {
            if (data.res == 'error') {
                $.each(data.message, function(index, value) {
                    Command: toastr["error"](value);
                });
            } else {
                $("#dynamic-module").html(data.html)
            }
        });
    });

    $("body").delegate(".all-view", "change", function() {
        if (this.checked) {
            $(".view").prop('checked', true);
        } else {
            $(".view").prop('checked', false);
        }
    });
    $("body").delegate(".all-add", "change", function() {
        if (this.checked) {
            $(".add").prop('checked', true);
        } else {
            $(".add").prop('checked', false);
        }
    });
    $("body").delegate(".all-edit", "change", function() {
        if (this.checked) {
            $(".edit").prop('checked', true);
        } else {
            $(".edit").prop('checked', false);
        }
    });
    $("body").delegate(".all-delete", "change", function() {
        if (this.checked) {
            $(".delete").prop('checked', true);
        } else {
            $(".delete").prop('checked', false);
        }
    });
    $("body").delegate(".all", "change", function() {
        var self=$(this);
        if (this.checked) {
            self.closest('tr').find("input[type='checkbox']").prop('checked', true);
        } else {
            self.closest('tr').find("input[type='checkbox']").prop('checked', false);
        }
    });
    $("body").delegate(".all-all", "change", function() {
        if (this.checked) {
            $(".view").prop('checked', true);
            $(".add").prop('checked', true);
            $(".edit").prop('checked', true);
            $(".delete").prop('checked', true);
            $(".all").prop('checked', true);
        } else {
            $(".view").prop('checked', false);
            $(".add").prop('checked', false);
            $(".edit").prop('checked', false);
            $(".delete").prop('checked', false);
            $(".all").prop('checked', false);
        }
    });
    
    
    
    $("#module-access-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.module-submit-btn'));
            l.start();
        var form = $(this),
                url = form.attr('action'),
                data = form.serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
             l.stop(); 
             if (data.res == 'error') {
                $.each(data.message, function(index, value) {
                    Command: toastr["error"](value);
                });
            }else if(data.res == 'save_err'){
              Command: toastr["error"](data.message);    
            }else{
              Command: toastr["success"](data.message);  
            }
            }
        });

    });
</script>