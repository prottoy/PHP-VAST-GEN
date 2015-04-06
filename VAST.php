<?php

/**
 * Description of VAST
 * 
 * @author prottoy
 */
class VAST {
    public $doc;

    public function generateVAST() {
        $this->doc = new DOMDocument('1.0', 'utf-8');

        header("Content-type: text/xml");
        $this->doc->formatOutput = true;

        $root = $this->doc->createElement("VAST");
        $this->doc->appendChild($root);
        $vastAttributes=array('version'=>'3.0', 'xmlns:xsi'=>'http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation'=> 'vast.xsd'); 
        $this->addAttributes( $root, $vastAttributes);

        $this->createAd($root);
        echo $this->doc->saveXML(), "\n";
    }
    
    public function createAd($root){
        //ad header
        $ad = $this->doc->createElement("Ad");
        $adAttributes=array('id'=>'preroll-1','sequence'=>'1');
        $this->addAttributes( $ad, $adAttributes);
        
       
        $inLine = $this->doc->createElement("InLine");
        $adSystem = $this->doc->createElement("AdSystem");
        $adsystemAttributes= array('version'=>'2.0');
        $this->addAttributes( $adSystem, $adsystemAttributes);


        $adTitle = $this->doc->createElement("AdTitle");
        $description = $this->doc->createElement("Description");
        $survey = $this->doc->createElement("Survey");
        
        $impression = $this->doc->createElement("Impression");
        $impressionAttributes= array('id'=>'DART');
        $this->addAttributes($impression, $impressionAttributes);
        
        $creatives = $this->doc->createElement("Creatives");

        $this->generateCreatives($creatives);
        
        $childs= array($adSystem, $adTitle, $description, $survey, $impression, $creatives);        
        $this->appendChilds($inLine, $childs);

        $ad->appendChild($inLine);
        $root->appendChild($ad);
    }

    public function generateCreatives($creatives) {
        $creative=$this->doc->createElement("Creative");
        $creative_attributes= array('sequence'=>'1','AdID'=>'');
        $this->addAttributes($creative, $creative_attributes);
        
        $linear = $this->doc->createElement("Linear");
        $linear_attributes= array('skipoffset'=>'20%');
        $this->addAttributes($linear, $linear_attributes);

        $duration = $this->doc->createElement("Duration");
        $linear->appendChild($duration);

        //TrackingEvents
        $trackingEvents = $this->doc->createElement("TrackingEvents");
        $linear->appendChild($trackingEvents);

        $events = array('start', 'midpoint', 'complete', 'mute', 'pause', 'fullscreen');
        $this->generateTrackingEvent($trackingEvents,$events);
        
        //AdParameters
        $adParameters = $this->doc->createElement("AdParameters");
        $linear->appendChild($adParameters);
        
        //VideoClicks
        $videoClicks = $this->doc->createElement("VideoClicks");
        $linear->appendChild($videoClicks);
        
        $this->videoClicks($videoClicks);
        
        //VideoClicks
        $mediaFiles = $this->doc->createElement("MediaFiles");
        $linear->appendChild($mediaFiles);
        
        $file1='http://cdnapi.kaltura.com/p/777122/sp/77712200/playManifest/entryId/0_vriq23ct/flavorId/0_g0vnoj5i/format/url/protocol/http/a.mp4';
        $file2='http://cdnapi.kaltura.com/p/777122/sp/77712200/playManifest/entryId/0_vriq23ct/flavorId/0_ve7wy5mt/format/url/protocol/http/a.mp4';
        
        $attributes1=array(
                'id'=>'1', 
                'delivery'=>'progressive', 
                'type'=>'video/mp4',
                'bitrate'=>'1500',
                'width'=>'720',
                'height'=>'480');
        
        $attributes2=array(
                'id'=>'2', 
                'delivery'=>'progressive', 
                'type'=>'video/mp4',
                'bitrate'=>'1500',
                'width'=>'720',
                'height'=>'480');
        
        
        $medias= array($file1=>$attributes1, $file2=>$attributes2);
        
        $this->mediaFiles($mediaFiles,$medias);
        
        $creative->appendChild($linear);
        $creatives->appendChild($creative);
    }

    public function generateTrackingEvent($trackingEvents, $events) {
        foreach ($events as $event) {
            $tracking = $this->doc->createElement("Tracking");
            $tracking_event = $this->doc->createAttribute("event");
            $tracking_event->value = $event;
            $tracking->appendChild($tracking_event);
            $trackingEvents->appendChild($tracking);
        }
    }
    
    public function videoClicks($videoClicks){
        //ClickThrough
        $clickThrough = $this->doc->createElement("ClickThrough");
        $videoClicks->appendChild($clickThrough);
        
        //ClickTracking
        $clickTracking = $this->doc->createElement("ClickTracking");
        $videoClicks->appendChild($clickTracking);
        
        $clickTracking_id = $this->doc->createAttribute("id");
        $clickTracking_id->value = 'DART';
        $clickTracking->appendChild($clickTracking_id);
    }
    
    public function mediaFiles( $mediaFiles,$medias){
        foreach ($medias as $file => $attributes) {
            $mediaFile = $this->doc->createElement("MediaFile");
            $filename = $this->doc->createCDATASection($file);
            $mediaFile->appendChild($filename);
            
            $this->addAttributes($mediaFile, $attributes);
            $mediaFiles->appendChild($mediaFile);
        }
    }
    
    public function addAttributes($parent, $attributes){
        foreach ($attributes as $key => $value) {
            $attr = $this->doc->createAttribute($key);
            $attr->value = $value;
            $parent->appendChild($attr);
        }
            
    }
    
    public function appendChilds($parent,$childs){
        foreach ($childs as $child) {
            $parent->appendChild($child);
        }
    }

}
