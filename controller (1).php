<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DocumentUpload extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!isdepartmentLogin()) {
            redirect();
        }
    }
    
    public function index() {
        $data['content'] = "document_upload_view";
        $this->load->view('department_theme/template', $data);
    }
    
    public function fileupload() {
        $result = array();
        $key = $this->input->post("name");
        $prpDocDate = new_get_upload_path($_FILES[$key]['name'], "".$_POST['folder']);            
        $dirDocPath = "uploads/".$_POST['folder']."/$prpDocDate/";
        $namePrefix = $this->input->post("name");
        $namePrefix .= substr(time(), 5);
        $destination = "uploads/".$_POST['folder'];
        $fileinfo = pathinfo($_FILES[$key]['name']);
        $extension = $fileinfo['extension'];
        $_POST['filename1'] = substr($namePrefix, 0, 25).time().".".$extension;
        $file = $_FILES[$key]['tmp_name'];
        $size = $_FILES[$key]['size'];            
        $valid_formats = array("jpg", "jpeg", "png", "JPEG", "JPG", "PNG", "pdf", "PDF");
        $result = fileupload($_POST['filename1'], $extension, $size, $destination, $prpDocDate, $dirDocPath, $file, $valid_formats, 1048576);
        echo json_encode(array('result' => $result));
    }
    
    public function removeFile() {
        $folder = $this->input->post('name');
        $path = $this->input->post('path');
        
        if(file_exists($path)) {
            unlink($path);
            echo "success";
        } else {
            echo "file not found";
        }
    }
}
