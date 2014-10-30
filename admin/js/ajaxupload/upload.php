<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){		
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
		
		$image_extensions = array('jpg', 'jpeg', 'gif', 'png');
		if (in_array(strtolower($ext), $image_extensions)) {
			// Image
			// Only keep alphanumerics and dashes...
			$new_filename = date('Ymd') . '-' . preg_replace('/[^\w\.-]/', '', str_replace(' ', '-', $filename));
			
			if (strtolower($ext) == "jpg" || strtolower($ext) == "jpeg" ) {
				$src = imagecreatefromjpeg("php://input");
			} else if(strtolower($ext) == "png") {
				$src = imagecreatefrompng("php://input");
			} else {
				$src = imagecreatefromgif("php://input");
			}
			
			list($width, $height) = getimagesize("php://input");
			
			if (isset($_GET["width"])) {
				// Width set	
				if (isset($_GET["height"])) {
					// Height set too
					$xoord = 0;
					$yoord = 0;
					$ratio = max($_GET["width"]/$width, $_GET["height"]/$height);
					$crop_height = $_GET["height"] / $ratio;
					$x = ($width - $_GET["width"] / $ratio) / 2;
					$y = ($height - $_GET["height"] / $ratio) / 2;
					$crop_width = $_GET["width"] / $ratio;
					$photo = imagecreatetruecolor($_GET["width"], $_GET["height"]);
					imagecopyresampled($photo,$src,0,0,$x,$y,$_GET["width"],$_GET["height"],$crop_width,$crop_height);
					$new_filename_path = '../../../upload/' . $_GET["path"] . '/' . $new_filename . '.' . strtolower($ext);
					imagejpeg($photo,$new_filename_path,100);
					imagedestroy($photo);	
				} else {
					// Resize to set width
					$newwidth = $width;
					$newheight = $height;
					if ($width > $_GET["width"]) {
						$newwidth = $_GET["width"];
						$newheight = round(($height * $newwidth) / $width);
					} 
					if ($newheight > $_GET["width"]) {
						$newheight = $_GET["width"];
						$newwidth = round(($width * $newheight) / $height);
					}
					$photo = imagecreatetruecolor($newwidth, $newheight);
					imagecopyresampled($photo,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
					$new_filename_path = '../../../upload/' . $_GET["path"] . '/' . $new_filename . '.' . strtolower($ext);
					imagejpeg($photo,$new_filename_path,100);
					imagedestroy($photo);
				}
			} else {
				// Resize to 900	
				$newwidth = $width;
				$newheight = $height;
				if ($width > 900) {
					$newwidth = 900;
					$newheight = round(($height * $newwidth) / $width);
				} 
				if ($newheight > 900) {
					$newheight = 900;
					$newwidth = round(($width * $newheight) / $height);
				}
				$photo = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($photo,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				$new_filename_path = '../../../upload/' . $_GET["path"] . '/' . $new_filename . '.' . strtolower($ext);
				imagejpeg($photo,$new_filename_path,100);
				imagedestroy($photo);
			}
			
			if (isset($_GET["thumb"])) {
				// Generate a thumbnail
				$thumb_size_w = $_GET["thumb"];
				$thumb_size_h = $_GET["thumb"];
				$thumb_quality = 100;
				$xoord = 0;
				$yoord = 0;
				$ratio = max($thumb_size_w/$width, $thumb_size_h/$height);
				$thumb_height = $thumb_size_h / $ratio;
				$x = ($width - $thumb_size_w / $ratio) / 2;
				$y = ($height - $thumb_size_h / $ratio) / 2;
				$thumb_width = $thumb_size_w / $ratio;
				$photo = imagecreatetruecolor($thumb_size_w, $thumb_size_h);
				imagecopyresampled($photo,$src,0,0,$x,$y,$thumb_size_w,$thumb_size_h,$thumb_width,$thumb_height);
				$new_thumb_path = '../../../upload/' . $_GET["path"] . '/thumbs/' . $new_filename . '.' . strtolower($ext);
				imagejpeg($photo,$new_thumb_path,$thumb_quality);
				imagedestroy($photo);
			}
			imagedestroy($src);
			
			return array('success'=>true, 'filename'=> $new_filename . '.' . strtolower($ext));
		} else {
			// Not an image
			if(!$replaceOldFile){
				/// don't overwrite previous files that were uploaded
				while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
					$filename .= rand(10, 99);
				}
			}
			
			if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
				return array('success'=>true, 'filename'=>$filename . '.' . $ext);
			} else {
				return array('error'=> 'Could not save uploaded file.' .
					'The upload was cancelled, or server error encountered');
			}
		}
    }    
}

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array('jpeg', 'jpg', 'gif', 'png', 'pdf');
// max file size in bytes
$sizeLimit = 20 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('../../../upload/' . $_GET["path"] . '/');
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
