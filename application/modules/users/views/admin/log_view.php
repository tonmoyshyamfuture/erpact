<table class="table">
    <thead>
        <tr>
            <th colspan="5">
                <div class="row report-header-company-info">
                    <?php echo $this->company_address();?>
                </div>
                <div class="row report-header">
                    <h3 class="text-center">Log</h3>
                </div>
            </th>
        </tr>
        <tr>
            <th>
                <?php if ($this->session->userdata('admin_uid') == 1): ?>                            
                    <input type="checkbox" name="log_check_all" class="log_check_all" value="">
                <?php endif ?>
            </th>
            <th>Action</th>
            <th>Module</th>
            <th>Performed By</th>
            <th>Performed At</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($log_details)): ?>
            <?php foreach ($log_details as $log) : ?>
                <tr>
                    <td>
                        <?php if ($this->session->userdata('admin_uid') == 1): ?>                            
                            <input type="checkbox" name="log_check[]" class="log_check" value="<?php echo $log->id; ?>">
                        <?php endif ?>
                    </td>
                    <td><?php echo $log->action; ?></td>
                    <td><?php echo strtoupper($log->module); ?></td>
                    <td><?php echo $log->username; ?></td>
                    <td><?php echo date('dS M Y h:i:s A', strtotime($log->performed_at)); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td></td>
                <td colspan="100%">No log found</td>
            </tr>
        <?php endif; ?>
    </tbody> 
</table>