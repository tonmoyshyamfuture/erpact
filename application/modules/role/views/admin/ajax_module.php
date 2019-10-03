<?php
$parent = "";
$sub_cat = "";
foreach ($menu as $i => $row) {
    
    ?>
    <?php if ($parent != $row->parent && $sub_cat == $row->sub_cat): ?>
        <tr>
            <td colspan="100%"><h4><?php echo $row->parent ?></h4></td>
        </tr>
    <?php //endif ?>
    <?php elseif ($parent == $row->parent && $sub_cat != $row->sub_cat): ?>
        <tr>
            <td colspan="100%"><h4><?php echo $row->parent ?><?php echo (isset($row->sub_cat)) ? " > " . $row->sub_cat : ""; ?></h4></td>
        </tr>
    <?php endif ?>
    <?php $parent = $row->parent; ?>
    <?php $sub_cat = $row->sub_cat; ?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td><?php echo $row->name; ?> <input type="hidden" name="module_id[]" value="<?php echo $row->id; ?>"></td>
        <td><input type="checkbox" class="view" name="view[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->v) && $row->v==1)? 'checked':'';?>></td>
        <td><input type="checkbox" class="add" name="add[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->a) && $row->a==1)? 'checked':'';?>></td>
        <td><input type="checkbox" class="edit" name="edit[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->e) && $row->e==1)? 'checked':'';?>></td>
        <td><input type="checkbox" class="delete" name="delete[<?php echo $row->id; ?>]" value="1" <?php echo (isset($row->d) && $row->d==1)? 'checked':'';?>></td>
        <td><input type="checkbox" class="all"></td>
    </tr>
    <?php
}
?> 

