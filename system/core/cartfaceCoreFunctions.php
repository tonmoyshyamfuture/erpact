<?php
function foldersize($path) {
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }   
    }

    return $total_size;
}


function format_size($size) {
   $units = explode(' ', 'B KB MB GB TB PB');

    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".")+3;

    return substr( $size, 0, $endIndex).' '.$units[$i];
}

function newFomat($size)
{
    $siz_in_gb = $size/1000;
    $size_in_mb = $size%1000;
    return $siz_in_gb . "." .$size_in_mb . " GB";
}

function deleteDirectory($dir) 
{
    if (!file_exists($dir)) 
    {
        return true;
    }
    if (!is_dir($dir)) 
    {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) 
    {
        if ($item == '.' || $item == '..') 
        {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) 
        {
            return false;
        }
    }
    return rmdir($dir);
}

?>