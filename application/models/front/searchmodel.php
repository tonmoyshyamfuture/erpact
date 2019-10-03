<?php
class searchmodel extends CI_Model
{
    public function __construct()
    {

            parent::__construct();
            $this->load->database();
    }
    
    public function sitesearchdata($term)
    {
        $arr=array();
        $counter=0;
        $sql="select id,name,short_description,sku from ".tablename('products')." where status='1' and archive_status='0' and (name LIKE '%".$term."%' or short_description LIKE '%".$term."%')";
        $query=$this->db->query($sql);
        $result=$query->result();
        
        if(!empty($result))
        {
            foreach($result as $val)
            {
                $url=str_replace(' ', '-', strtolower($val->sku));
                $arr[$counter]['type']="Product";
                $arr[$counter]['name']=$val->name;
                $arr[$counter]['description']=$val->short_description;
                $arr[$counter]['url']=site_url('product-details')."/".$url;
                $arr[$counter]['id']=$val->id;
                $counter++;
            }
        }
        
        $sql1="select id,title,description from ".tablename('blog_post')." where status='1' and archive_status='0' and (title LIKE '%".$term."%' or description LIKE '%".$term."%')";
        $query1=$this->db->query($sql1);
        $result1=$query1->result();
        if(!empty($result1))
        {
            foreach($result1 as $val1)
            {
                $url= str_replace(" ", "-", strtolower($val1->title));
                $arr[$counter]['type']="Blog";
                $arr[$counter]['name']=$val1->title;
                $arr[$counter]['description']=$val1->description;
                $arr[$counter]['url']=site_url('blog')."/".$url;
                $arr[$counter]['id']=$val1->id;
                $counter++;
            }
        }
        
        $sql2="select id,title,content,alias from ".tablename('cms')." where status='1' and on_off='1' and (title LIKE '%".$term."%' or content LIKE '%".$term."%')";
        $query2=$this->db->query($sql2);
        $result2=$query2->result();
        if(!empty($result2))
        {
            foreach($result2 as $val2)
            {
                $url= str_replace(" ", "-", strtolower($val2->title));
                $arr[$counter]['type']="CMS";
                $arr[$counter]['name']=$val2->title;
                $arr[$counter]['description']=$val2->content;
                $arr[$counter]['url']=site_url($val2->alias);
                $arr[$counter]['id']=$val2->id;
                $counter++;
            }
        }
        
        $sql3="select id,name,description,alias from ".tablename('blog_category')." where status='1' and archive_status='0' and (name LIKE '%".$term."%' or description LIKE '%".$term."%')";
        $query3=$this->db->query($sql3);
        $result3=$query3->result();
        if(!empty($result3))
        {
            foreach($result3 as $val3)
            {
                $url= str_replace(" ", "-", strtolower($val3->name));
                $arr[$counter]['type']="Blog category";
                $arr[$counter]['name']=$val3->name;
                $arr[$counter]['description']=$val3->description;
                $arr[$counter]['url']=site_url('category')."/".$val3->alias;
                $arr[$counter]['id']=$val3->id;
                $counter++;
            }
        }
        
        $sql4="select id,name,alias from ".tablename('blog_tags')." where status='1' and archive_status='0' and name LIKE '%".$term."%'";
        $query4=$this->db->query($sql4);
        $result4=$query4->result();
        if(!empty($result4))
        {
            foreach($result4 as $val4)
            {
                $url= str_replace(" ", "-", strtolower($val4->name));
                $arr[$counter]['type']="Blog tag";
                $arr[$counter]['name']=$val4->name;
                $arr[$counter]['description']=$val4->name;
                $arr[$counter]['url']=site_url('tags')."/".$val4->alias;
                $arr[$counter]['id']=$val4->id;
                $counter++;
            }
        }
        
        /*echo '<pre>';
        print_r($arr);
        exit();*/
        
        if(!empty($arr))
        {
            return $arr;
        }
        else 
        {
            return "";
        }
    }
    
    
 
   
    
    
}