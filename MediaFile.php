<?php
/**
 * Description of GNRMediaFile
 *
 * @author prottoy
 */
class MediaFile {
    public $fileID;
    public $fileURL;
    public $delivery='progressive';
    public $type;
    public $bitrate;
    public $width;
    public $height;
    
    public function setFileId($__id){
        $this->fileID= $__id;
    }
    public function setFileURL($__url){
        $this->fileURL= $__url;
    }
    public function setDelivery($__delivery){
        $this->delivery= $__delivery;
    }
    public function setType($__type){
        $this->type= $__type;
    }
    public function setBitrate($__bitrate){
        $this->bitrate= $__bitrate;
    }
    public function setWidth($__width){
        $this->width= $__width;
    }
    public function setHeight($__height){
        $this->height= $__height;
    }
}
