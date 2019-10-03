
<select id="courier" name="courier" class="form-control">	
    <option>Select</option>
    <?php foreach ($couriors as $key => $courior): ?>
            <option value="<?php echo $courior->id; ?>" ><?php echo $courior->despatch_through; ?></option>
    <?php endforeach ?>
</select>
        