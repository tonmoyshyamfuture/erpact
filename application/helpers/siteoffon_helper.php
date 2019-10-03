<?php
/**
 *
 * This helper is used 
 * for retrieving the sitename 
 * defined in the sitesettings
 *
 * <p>
 *  @author : Suvra Bhattacharyya
 *  @param : Without paramater for retrieving sitename. and name of the logo can be passed for retrieval of the full logo path
 * </p>
 *
 */

function getsitename($LOGO=NULL)
{
    $CI = & get_instance();
    $CI->load->model('admin/sitesettingsmodel');
    $detail=$CI->sitesettingsmodel->loadsitesettings();
    if(!empty($detail))
    {
        $result=$detail->sitename;
        if($LOGO)
        {
            $result=$detail->sitelogo;
        }
        return $result;
    }
    else
    {
      return "";
    }
    
}


/* Previous version*/
function generate_directory_list($dir) {
    $CI = & get_instance();
    echo '<ul>';
    if(is_dir($dir)) {
        $predir=urlencode(base64_encode($dir));
        
        $parentid=clean($predir);
        
        $dirprm="'".$predir."'";
        $newfilemodal="'newfilemodal'";
        $newimagemodel="'newimagemodal'";
        $basename=basename($dir);
        switch ($basename) {
            case 'images':
                echo '<li id="'.$parentid.'"><a href="javascript:void(0)">'.basename($dir).'</a><a href="javascript:void(0)" onclick="addnewimage('.$newimagemodel.','.$dirprm.',this)" title="New image"><span class="label label-info addnewfile">New</span></a>';
                break;
            case 'thumb':
                echo '<li id="'.$parentid.'"><a href="javascript:void(0)">'.basename($dir).'(200 X 100)</a>';
                break;
            default:
                echo '<li id="'.$parentid.'"><a href="javascript:void(0)">'.basename($dir).'</a><a href="javascript:void(0)" onclick="addnewfile('.$newfilemodal.','.$dirprm.',this)" title="New file"><span class="label label-info addnewfile">New</span></a>';
        }
        //echo '<li><a href="javascript:void(0)">'.basename($dir).'</a><br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="addnewfile('.$newfilemodal.','.$dirprm.')" title="New file"><span class="label label-info">Add new file&nbsp;&nbsp;<i class="fa fa-file-text-o"></i></span></a>';
        foreach(glob("$dir/*") as $path) {
            generate_directory_list($path);
        }
        echo "</li>";
    }
    else {
        $extension = @end(explode('.',$dir));
        //$extension = $extension['extension'];
        $sndparam=urlencode(base64_encode($dir));
        $param="'".$sndparam."'";
        //$filenameenc=urlencode(base64_encode(basename($dir)));
        $filenameenc=basename($dir);
        $filename="'".$filenameenc."'";
        
        if (strtolower(substr($filenameenc, strrpos($filenameenc, '.') + 1)) == 'jpg')
        {
            echo '<li><a onclick="showimage('.$param.','.$filename.')" href="javascript:void(0)">'.basename($dir).'</a></li>';
        }
        else if(strtolower(substr($filenameenc, strrpos($filenameenc, '.') + 1)) == 'jpeg')
        {
            echo '<li><a onclick="showimage('.$param.','.$filename.')" href="javascript:void(0)">'.basename($dir).'</a></li>';
        }
        else if(strtolower(substr($filenameenc, strrpos($filenameenc, '.') + 1)) == 'png')
        {
            echo '<li><a onclick="showimage('.$param.','.$filename.')" href="javascript:void(0)">'.basename($dir).'</a></li>';
        }
        else if(strtolower(substr($filenameenc, strrpos($filenameenc, '.') + 1)) == 'bmp')
        {
            echo '<li><a onclick="showimage('.$param.','.$filename.')" href="javascript:void(0)">'.basename($dir).'</a></li>';
        }
        else
        {
            echo '<li><a onclick="showcontent('.$param.','.$filename.')" href="javascript:void(0)">'.basename($dir).'</a></li>';
        }
    }
  echo '</ul>';
}


function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

/*function generate_directory_list($dirlist,$path)
{

    $showdir = array('application','themes','default','include','css');
    $showfile=array('index.php','index.html','developer.js','developer.css');
      echo '<ul>';
	if(!empty($dirlist))
	{
         $cnt=1;
	 foreach($dirlist as $key => $list)
	 {
             
            if(is_array($list))
            {
               if(in_array($key,$showdir)) 
               {

                   
                   $path=$path.'/'.$key;
                   echo '<li><a href="'.$path.'">'.$key.'</a>';
                   generate_directory_list($list,$path);
                   echo '</li>';
               }
               
            }
            else
            {
              
                if(in_array($list,$showfile))    continue;
                echo '<li><a href="'.basename($list).'">'.$list.'</a></li>';
                   //$dir_arr[$key][] = $list ;
                //echo $path."***";
            }
         }
	}
      echo '</ul>';
      return $dir_arr;
}*/
    
