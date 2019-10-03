<?php
$amount_field_count = 0;
?>
<thead>
    <tr>
        <th>Date</th>
        <th>Particulars</th>
        <th>Voucher Type</th>
        <th>Voucher No</th>
        <th>Amount</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $result) : ?>

            <tr>
                <td>
                    <?= date($current_date_format, strtotime($result['entry_date'])); ?>
                </td>
                <td>
                    <a href="<?php echo base_url('transaction/invoice') . '.aspx/' . $result['entry_id'] . '/' . $result['entry_type_id']; ?>">
                        <?php
                        $led = array();
                        $devit = json_decode($result['ledger_ids_by_accounts']);

                        echo "<strong>Dr </strong>";
                        if (isset($devit->Dr)) {
                            for ($i = 0; $i < count($devit->Dr); $i++) {
                                echo $devit->Dr[$i];
                                if (isset($devit->Dr) && count($devit->Dr) > 1) {
                                    echo ' + ';
                                }
                                break;
                            }
                        }
                        ?>
                        /
                        <?php
                        echo "<strong>Cr </strong>";
                        for ($i = 0; $i < count($devit->Cr); $i++) {
                            echo $devit->Cr[$i];
                            if (count($devit->Cr) > 1) {
                                echo ' + ';
                            }
                            break;
                        }
                        ?>
                    </a>
                </td>
                <td>
                    <?= $result['voucher_type']; ?>
                </td>
                <td>
                    <?= $result['voucher_no']; ?>
                </td>
                <td>
                    <?php echo '<span class="balence_show_' . ++$amount_field_count . '">' . number_format($result['cr_amount'], 2, '.', ',') . '</span>'; ?>
                    <input type="hidden" class="balence_hidden" data-id="<?php echo $amount_field_count; ?>" value="<?= $result['cr_amount']; ?>">
                </td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="100%">No result found</td>
        </tr>
    <?php endif; ?>
</tbody>