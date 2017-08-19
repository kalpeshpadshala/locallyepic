<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//echo "<pre>";print_r($_SERVER);
ini_set('display_errors', 'off');
error_reporting(0);
define('MP_DB_DEBUG', FALSE);

class Image extends CI_Controller {

    function Image() {

        parent::__construct();
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $this->basic_path = $_SERVER['DOCUMENT_ROOT'] . "/";
        } else {
            $this->basic_path = $_SERVER['DOCUMENT_ROOT'] . "/";
        }
    }

    function index() {
        $path=$_GET['path'];
        $reso_p=$_GET['width'];
        $image_path=$this->basic_path.$path;
       // echo "<pre>";print_r($_GET);
       
      
        $orig_path = $image_path;
        $t = $this->get_image_wh($reso_p, $image_path);
        //echo "<pre>";print_r($t);exit;
        if ($t['is_set_ori'] == 1) {
     
            $this->display_image($image_path);
        }

        $width = $t['width'];
        $height = $t['height'];
        $path_arr= explode("/", $path);
        $filename = end($path_arr);
        $fol_path = reset($path_arr);
        $fn_arr= explode(".", $filename);
        $ext = end($fn_arr);
        $fn = reset($fn_arr);
        $ori_ext = $ext;
       
        if ($ext == "png" || $ext == "PNG"){
            $ext = "jpg";
            $fn.="_pngjpg";
        }
       $image_path1 = $this->basic_path . $fol_path . "/crop/" . $fn . "-" . $width . "x" . $height . "." . $ext;
        if (!file_exists($image_path1)){

            if (!file_exists($this->basic_path . $fol_path . "/crop")) {
                mkdir($this->basic_path . $fol_path . "/crop");
            }

            if ($ori_ext == "png" || $ori_ext == "PNG") {
                $this->convert_png_to_jpg($image_path, $image_path1);
                $image_path = $image_path1;
            }

            $config['image_library'] = 'GD2';
            $config['source_image'] = $image_path;
            // $config['create_thumb'] = TRUE;
            //$config['maintain_ratio'] = FALSE;
            $config['width'] = $width;
            $config['height'] = $height;
            $config['quality'] = "80%";
            $config['new_image'] = $image_path1;

            //$config['dynamic_output'] = TRUE;
            $this->load->library('image_lib', $config);
            if (!$this->image_lib->resize()) {
                $image_path1 = $orig_path;
            }
            $this->image_lib->clear();
        }
        $this->display_image($image_path1);
        // echo "<pre>";print_r($size);exit;
    }

    function display_image($p) {
        $size = GetImageSize($p);
        $mime = $size['mime'];
        header("Content-type: $mime");
        readfile($p);
        exit;
    }
    function crop(){
       //echo phpinfo();exit;
        //http://localhost/dapi/v1/image/crop/300/wp-content/uploads/2014/05/How-to-Raise-Happy-Kids.png
      
//        $spath = substr($uri, strpos($uri, "crop") + 6);
//        $spath = substr($spath, strpos($spath, "/") + 1);
//        $fol_path = substr($spath, 0, strrpos($spath, "/"));
//        $filename = substr(strrchr($spath, "/"), 1);
        
        $reso_p = $_GET['width'];
        $spath = $_GET['path'];
       
        $fol_path=substr($spath,0,strrpos($spath, "/"));
        $filename = substr(strrchr($spath, "/"), 1);
        
        
        
        $image_path = $this->basic_path . $spath;        
        $orig_path = $image_path;
        $t = $this->get_image_wh($reso_p, $image_path);
        $width = $t['width'];
        $height = $t['height'];
        $t_a = explode(".", $filename);
        $ext = end($t_a);
        
        $fn = reset($t_a)."_info";

        $image_path1 = $this->basic_path . $fol_path . "/crop/" . $fn . "-" . $width . "x" . $height . "." . $ext;     
        //echo "<pre>";print_r($t);exit;

        if (!file_exists($image_path1)) {

            if (!file_exists($this->basic_path . $fol_path . "/crop")){
                mkdir($this->basic_path . $fol_path . "/crop");
            }
            $config['image_library'] = 'GD2';
            //$config['library_path'] = 'C:/Program Files/ImageMagick-6.8.9-Q16/';
            $config['source_image'] = $image_path;
            // $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = $t['original_width'];
            $yh=450;
            if($t['original_height'] < 450){
               $yh = $t['original_height'];
            }
            $config['height'] =$yh;
            $config['quality'] = "80%";
            $config['new_image'] = $image_path1;
            $config['x_axis'] ="0";
            $config['y_axis'] = round(($t['original_height']/2)-222);
            $config['master_dim'] = "auto";

            $this->load->library('image_lib', $config);
            //$this->image_lib->clear();
            if (!$this->image_lib->crop()){
                
                  $image_path1 = $orig_path;            
            }
        }
        $this->display_image($image_path1);
    }
    
    function resizecrop(){
//        $reso_p = $this->uri->segment(4);
//        $uri = $this->uri->uri_string();
//        $spath = substr($uri, strpos($uri, "resizecrop") + 11);
//        $spath = substr($spath, strpos($spath, "/") + 1);
//        $fol_path = substr($spath, 0, strrpos($spath, "/"));
//        $filename = substr(strrchr($spath, "/"), 1);
        
        $reso_p = $_GET['width'];
        $spath = $_GET['path'];
       
        $fol_path=substr($spath,0,strrpos($spath, "/"));
        $filename = substr(strrchr($spath, "/"), 1);
        
        $image_path = $this->basic_path . $spath;        
        $orig_path = $image_path;
        $t = $this->get_image_wh($reso_p, $image_path);
        $width = $t['width'];
        $height = $t['height'];
        $t_a = explode(".", $filename);
        $ext = end($t_a);
        $ori_ext = $ext;
        $fn = reset($t_a);
        if ($ext == "png" || $ext == "PNG") {
            $ext = "jpg";
            $fn.="_pngjpg";
        }      
        
        $image_path1 = $this->basic_path . $fol_path . "/crop/" . $fn . "-" . $width . "x" . $height . "." . $ext;
        $this->load->library('image_lib');
        if (!file_exists($image_path1)) {
            if (!file_exists($this->basic_path . $fol_path . "/crop")) {
                mkdir($this->basic_path . $fol_path . "/crop");
            }
            if ($ori_ext == "png" || $ori_ext == "PNG") {
                $this->convert_png_to_jpg($image_path, $image_path1);
                $image_path = $image_path1;
            }
            $config['image_library'] = 'GD2';
            //$config['library_path'] = 'C:/Program Files/ImageMagick-6.8.9-Q16/';
            $config['source_image'] = $image_path;
            // $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = $t['original_width'];
            $yh=450;
            if($t['original_height'] < 450){
               $yh = $t['original_height'];
            }
            $config['height'] =$yh;
            $config['quality'] = "80%";
            $config['new_image'] = $image_path1;
            $config['x_axis'] ="0";
            $config['y_axis'] = round(($t['original_height']/2)-222);
            $config['master_dim'] = "auto";

            $this->image_lib->initialize($config);            
            if (!$this->image_lib->crop()){
                  $image_path1 = $orig_path;            
            }
            $this->image_lib->clear();
            if (file_exists($image_path1)) 
            {
                list($ip1_width, $ip1_height) = getimagesize($image_path1);                
                $image_path2 = $this->basic_path . $fol_path . "/crop/" . $fn . "-crop-" . round($ip1_width/8). "x" . round($ip1_height/8) . "." . $ext;
                if (!file_exists($image_path2)) {
                    $config['image_library'] = 'GD2';
                    $config['source_image'] = $image_path1;
                    // $config['create_thumb'] = TRUE;
                    //$config['maintain_ratio'] = FALSE;
                    $config['width'] = round($ip1_width/8);
                    $config['height'] = round($ip1_height/8);
                    $config['quality'] = "70%";
                    $config['new_image'] = $image_path2;

                    //$config['dynamic_output'] = TRUE;           
                    $this->image_lib->initialize($config);
                    if (!$this->image_lib->resize()) {
                        $image_path2 = $orig_path;
                    }            
                    $this->image_lib->clear();                  
                }
                $this->display_image($image_path2);
            }
        }else{
            list($ip2_width, $ip2_height) = getimagesize($image_path1);
            $image_path2 = $this->basic_path . $fol_path . "/crop/" . $fn . "-crop-" . round($ip2_width/8) . "x" . round($ip2_height/8) . "." . $ext;
            if (!file_exists($image_path2)) {                
                $config['image_library'] = 'GD2';
                $config['source_image'] = $image_path1;
                $config['width'] = round($ip2_width/8);
                $config['height'] = round($ip2_height/8);
                $config['quality'] = "70%";
                $config['new_image'] = $image_path2;
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    $image_path2 = $image_path1;
                }            
                $this->image_lib->clear();                  
            }
            $this->display_image($image_path2);
        }        
    }
    
    function test_resize() {
        $path = "c:/wamp/www/dashburst/wp-content/uploads/2014/05/How-to-Raise-Happy-Kids.png";
        $path1 = "c:/wamp/www/dashburst/wp-content/uploads/2014/05/crop/How-to-Raise-Happy-Kids.png";
        $filename = $path;
//the resize will be a percent of the original size
        $percent = 0.5;

// Content type
        header('Content-Type: image/png');

// Get new sizes
        list($width, $height) = getimagesize($filename);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;

// Load
        $thumb = imagecreatetruecolor(700, 8445);
        $source = @imagecreatefrompng($filename);

// Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output and free memory
//the resized image will be 400x300
//imagejpeg($thumb);
        imagepng($thumb);
        imagedestroy($thumb);
    }

    function test_resize1() {
        $input_file = "c:/wamp/www/dashburst/wp-content/uploads/2014/05/How-to-Raise-Happy-Kids.png";
        $path1 = "c:/wamp/www/dashburst/wp-content/uploads/2014/05/crop/How-to-Raise-Happy-Kids.jpg";
        $filename = $path;
        $input = imagecreatefrompng($input_file);
        list($width, $height) = getimagesize($input_file);
        $output = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($output, 255, 255, 255);
        imagefilledrectangle($output, 0, 0, $width, $height, $white);
        imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
        imagejpeg($output, $path1);
    }

    function convert_png_to_jpg($input_file, $dpath) {

        $input = imagecreatefrompng($input_file);
        list($width, $height) = getimagesize($input_file);
        $output = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($output, 255, 255, 255);
        imagefilledrectangle($output, 0, 0, $width, $height, $white);
        imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
        imagejpeg($output, $dpath);
    }

    function get_image_wh($width, $path) {
        $is_set_ori = 0;
        $param = $width;
        if ($width <= 100) {
            $width = 100;
        } else if ($width > 100 && $width < 300) {
            $width = 300;                    
        } else if ($width >= 300 && $width < 450) {
            $width = 450;
        } else if ($width >= 450 && $width < 600) {
            $width = 600;
        } else if ($width >= 600 && $width < 750) {
            $width = 750;
        } else if ($width >= 750 && $width < 900) {
            $width = 900;
        } else {
            $width = 1280;
        }
        list($ori_width, $ori_height) = getimagesize($path);

        $height = round(($ori_height * $width) / $ori_width);
        if ($ori_width <= $width) {
            $is_set_ori = 1;
            $width = $ori_width;
            $height = $ori_height;
        }

        return array("width" => $width, "height" => $height, "is_set_ori" => $is_set_ori,"original_height"=>$ori_height,"original_width"=>$ori_width);
    }

    public function profile_pic_crop(){
        
        $fn=$_GET['fn'];
        $hp=$_GET['hp'];
        $w1=$_GET['w1'];
        $h1=$_GET['h1'];
        //$kw=$_GET['kw'];
        //$kh=$_GET['kh'];
        $x1=$_GET['x1'];
        $y1=$_GET['y1'];
        
    
        
        $image_path = $hp.$fn;
        $orig_path = $image_path;
     
        $t_a = explode(".",$fn);
        $ext = end($t_a);
        $ofn=  reset($t_a);
        $image_path1 = $hp. $ofn . "-" . $w1 . "x" . $h1 . "." . $ext;
        //echo "<pre>";print_r($t);exit;

        if (!file_exists($image_path1)) {

            $config['image_library'] = 'GD2';
        
            $config['source_image'] = $image_path;
            // $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = $w1;
          
            $config['height'] =$h1;
            $config['quality'] = "90%";
            $config['new_image'] = $image_path1;
            $config['x_axis'] =$x1;
            $config['y_axis'] = $y1;
            //$config['dynamic_output'] = TRUE;

            $this->load->library('image_lib', $config);
         
            if (!$this->image_lib->crop()){
                  $image_path1 = $orig_path;
                  //echo $this->image_lib->display_errors();exit;
            }
        }
        $this->display_image($image_path1);
    }
    
    
}
