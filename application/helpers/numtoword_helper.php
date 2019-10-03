<?php
    function numberTowords($num)
    { 
        $ones = array( 
            1 => "one", 
            2 => "two", 
            3 => "three", 
            4 => "four", 
            5 => "five", 
            6 => "six", 
            7 => "seven", 
            8 => "eight", 
            9 => "nine", 
            10 => "ten", 
            11 => "eleven", 
            12 => "twelve", 
            13 => "thirteen", 
            14 => "fourteen", 
            15 => "fifteen", 
            16 => "sixteen", 
            17 => "seventeen", 
            18 => "eighteen", 
            19 => "nineteen" 
        ); 
        $tens = array( 
             1 => "one", 
            2 => "twenty", 
            3 => "thirty", 
            4 => "forty", 
            5 => "fifty", 
            6 => "sixty", 
            7 => "seventy", 
            8 => "eighty", 
            9 => "ninety" 
        ); 
        $hundreds = array( 
            "hundred", 
            "thousand", 
            "million", 
            "billion", 
            "trillion", 
            "quadrillion" 
        ); //limit t quadrillion 
    $num = number_format($num,2,".",","); 

    $num_arr = explode(".",$num); 
    $wholenum = $num_arr[0]; 
    $decnum = $num_arr[1]; 
    $whole_arr = array_reverse(explode(",",$wholenum)); 
    krsort($whole_arr); 
    $rettxt = "Rupees "; 
    foreach($whole_arr as $key => $i){ 
        //if($i < 20){
        if($i < 20 && $i != 0){ 
            $rettxt .= $ones[intval($i)]; 
        }elseif($i < 100){ 
            // $rettxt .= $tens[substr($i,0,1)]; 
            // $rettxt .= " ".$ones[substr($i,1,1)]; 
            if(substr($i,0,1) !== '0'){
                $rettxt .= $tens[substr($i,0,1)]; 
            }else{
                $rettxt;
            }
            if(substr($i,1,1) !== '0' && substr($i,1,1) != ''){
                $rettxt .= " ".$ones[substr($i,1,1)]; 
            }else{
                $rettxt;
            } 
        }else{ 
            $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
            // $rettxt .= " ".$tens[substr($i,1,1)]; 
            // $rettxt .= " ".$ones[substr($i,2,1)]; 
            if(substr($i,1,1) !== '0'){
                $rettxt .= " ".$tens[substr($i,1,1)]; 
            }
            if(substr($i,2,1) !== '0'){
                $rettxt .= " ".$ones[substr($i,2,1)]; 
            }
        } 
        if($key > 0){ 
            $rettxt .= " ".$hundreds[$key]." "; 
        } 
    } 
    if($decnum > 0){ 
        $rettxt .= " and "; 
        if($decnum < 20){ 
            $rettxt .= $ones[intval($decnum)]; 
        }elseif($decnum < 100){ 
            // $rettxt .= $tens[substr($decnum,0,1)]; 
            // $rettxt .= " ".$ones[substr($decnum,1,1)];
            if(substr($decnum,0,1) !== '0'){
                $rettxt .= " ".$tens[substr($decnum,0,1)]; 
            }
            if(substr($decnum,1,1) !== '0'){
                $rettxt .= " ".$ones[substr($decnum,1,1)]; 
            } 
        } 
        $rettxt.=' paisa';
    } 
    return $rettxt; 
    } 
    
    function number_to_words($number){
        $wtot = '';
//        $no = round($number);
//        $point = round($number - $no, 2) * 100;
//        $number = number_format($number,2,".",","); 
        $num_arr = explode(".",$number);
        $no = $num_arr[0];
        $point = isset($num_arr[1])?$num_arr[1]:'';
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
         '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
         '7' => 'seven', '8' => 'eight', '9' => 'nine',
         '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
         '13' => 'thirteen', '14' => 'fourteen',
         '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
         '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
         '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
         '60' => 'sixty', '70' => 'seventy',
         '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
          $divider = ($i == 2) ? 10 : 100;
          $number = floor($no % $divider);
          $no = floor($no / $divider);
          $i += ($divider == 10) ? 1 : 2;
          if ($number) {
             $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
             $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
             $str [] = ($number < 21) ? $words[$number] .
                 " " . $digits[$counter] . $plural . " " . $hundred
                 :
                 $words[floor($number / 10) * 10]
                 . " " . $words[$number % 10] . " "
                 . $digits[$counter] . $plural . " " . $hundred;
          } else $str[] = null;
       }
       $str = array_reverse($str);
       $result = implode('', $str);
       $points = ($point > 0) ?
               $words[$point / 10] . " " . 
               $words[$point = $point % 10] : '';
//       echo $result . "Rupees  " . $points . " Paise";
       if($result != ''){
           $wtot .= "Rupees ";
           $wtot .= $result;
       }
       if($points != ''){
           $wtot .= " and ";
           $wtot .= $points;
           $wtot .= " Paise";
       }
       
       return $wtot;
    }
