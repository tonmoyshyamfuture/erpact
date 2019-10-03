 
  <?php 
                $opening_balance = 0;
                $closing_balance = 0;
                $total_closing_balance = 0;
                $cr_balance = 0;
                $dr_balance = 0;
                $account_type = '';

  ?>
 <table class="table table-bordered tablarea_new">
        <thead>
            <tr>
                <th>Date</th>
                              
                <th>Voucher No</th>                
                <th style="width: 1000px;">Particulars</th>
                  <th>Voucher Type</th>
                              
                <th>Debit</th>                
                <th>Credit</th>
                <th>Current Balance</th>
            </tr>
            <tr>
                <th colspan="4">Opening Balance</th>

                  <?php 
                    if(isset($opening_bal)){
                        if($opening_bal['account_type'] == 'Dr'){
                            echo ' <th>'.sprintf('%0.2f',$opening_bal['opening_balance']) .'</th> <th></th>';
                            $account_type = 'Dr';
                        }

                        if($opening_bal['account_type'] == 'Cr'){
                            echo '<th></th> <th>'.sprintf('%0.2f',$opening_bal['opening_balance']) .'</th> ';
                            $account_type = 'Cr';
                        }
                         $opening_balance = $opening_bal['opening_balance'];

                    }
                  ?>
                
                <th></th>
                             
            </tr>
        </thead>
        
        <tbody>
          <?php 
            if(isset($ledger_result)){
                foreach ($ledger_result as $row) { 
                      if($row['account'] == 'Dr'){
                          $dr_balance += $row['balance'];
                      }
                      if($row['account'] == 'Cr'){
                          $cr_balance += $row['balance'];
                      }
                  ?>
                  <tr>
                    <!-- <td><?php echo date("d-m-Y", strtotime($row['modified_date'])); ?></td> -->
                    <td><?php echo get_date_format($row['modified_date']); ?></td>
                    <!-- <td>15</td> -->
                    <td><?php echo $row['entry_no']; ?></td>
                    <!-- <td><?php echo $row['ladger_name']; ?></td> -->
                    <td>
                       <?php
                                    $led = array();
                                    $devit = json_decode($row['ledger_ids_by_accounts']);
                                    echo "<strong>Dr </strong>";
                                    for ($i = 0; $i < count($devit->Dr); $i++) {
                                        echo $devit->Dr[$i];
                                        if (count($devit->Dr) > 1) {
                                            echo ' + ';
                                        }
                                        break;
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
                    </td>
                    <td><?php echo $row['type']; ?></td>
                    <!-- <td>sdsd</td> -->
                    <?php 
                      if($row['account'] == 'Dr'){
                          echo '<td>'.$row['balance'].'</td><td></td>';
                      }
                      if($row['account'] == 'Cr'){
                          echo '<td></td><td>'.$row['balance'].'</td>';
                      }
                     ?>
                    <td>
                        <?php 

                             if($row['account'] == 'Dr'){
                                   $diff_drcr = $row['balance'];
                              }
                              if($row['account'] == 'Cr'){
                                  $diff_drcr = -$row['balance'];
                              }

                            if($diff_drcr >= 0){
                                if($account_type == 'Dr'){
                                    $closing_balance = $diff_drcr + $opening_balance;
                                    $opening_balance = $closing_balance;
                                    $account_type = 'Dr';
                                    echo sprintf('%0.2f',$closing_balance) .'(Dr)';
                                }
                                if($account_type == 'Cr'){
                                    $closing_balance =  $diff_drcr - $opening_balance;

                                    if($closing_balance >= 0){
                                      $opening_balance = $closing_balance;
                                      $account_type = 'Dr';
                                      echo sprintf('%0.2f',$closing_balance) .'(Dr)';
                                    }else{
                                        echo str_replace( '-','',sprintf('%0.2f',$closing_balance)) .'(Cr)';
                                        $opening_balance = str_replace( '-','',sprintf('%0.2f',$closing_balance));
                                        $account_type = 'Cr';
                                    }
                                    
                                }
                            }

                            if($diff_drcr < 0){
                                if($account_type == 'Cr'){
                                    $closing_balance = str_replace( '-','',$diff_drcr) + $opening_balance;
                                    $opening_balance = $closing_balance;
                                    $account_type = 'Cr';
                                    echo sprintf('%0.2f',$closing_balance) .'(Cr)';
                                }
                                if($account_type == 'Dr'){
                                    $closing_balance =  str_replace( '-','',$diff_drcr) - $opening_balance;
                                    if($closing_balance >= 0){
                                        echo sprintf('%0.2f',$closing_balance) .'(Cr)';
                                        $opening_balance = $closing_balance;
                                        $account_type = 'Cr';
                                    }else{
                                        echo str_replace( '-','',sprintf('%0.2f',$closing_balance)) .'(Dr)';
                                        $opening_balance = str_replace( '-','',sprintf('%0.2f',$closing_balance));
                                        $account_type = 'Dr';
                                    }
                                    
                                }
                            }

                        ?>
                    </td>
                </tr>
              <?php  }
            }
           ?>
            
           
        
            
              <tr>
                 <td colspan="4">Grand Total</td>
                <td><?php echo sprintf('%0.2f',$dr_balance); ?></td>
                <td><?php echo sprintf('%0.2f',$cr_balance); ?></td>
                <td><?php echo sprintf('%0.2f',$opening_balance) .'('.$account_type.')'; ?></td>
                 
               
            </tr>
        </tbody>
        
    </table>