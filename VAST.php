<?php

/**
 * Description of VAST
 * 
 * @author prottoy
 */
class VAST {
    public static $doc;
    public function generateVAST() {
        $doc = new DOMDocument('1.0', 'utf-8');

        header("Content-type: text/xml");
        $doc->formatOutput = true;

        //<VAST version="3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vast.xsd">
        $root = $doc->createElement("VAST");
        $doc->appendChild($root);

        // create attribute version
        $version = $doc->createAttribute("version");
        $version->value = '3.0';
        $root->appendChild($version);

        // create attribute version
        $xsi = $doc->createAttribute("xmlns:xsi");
        $xsi->value = 'http://www.w3.org/2001/XMLSchema-instance';
        $root->appendChild($xsi);

        // create attribute noNamespaceSchemaLocation
        $xsi_no = $doc->createAttribute("xsi:noNamespaceSchemaLocation");
        $xsi_no->value = 'vast.xsd';
        $root->appendChild($xsi_no);

        // <Ad id="preroll-1" sequence="1">
        $ad = $doc->createElement("Ad");
        // create attribute ad id
        $ad_id = $doc->createAttribute("id");
        $ad_id->value = 'preroll-1';
        $ad->appendChild($ad_id);

        // create attribute ad sequence
        $ad_sequence = $doc->createAttribute("sequence");
        $ad_sequence->value = '1';
        $ad->appendChild($ad_sequence);

        $inLine = $doc->createElement("InLine");
        $adSystem = $doc->createElement("AdSystem");

        // create attribute ad sequence
        $adSystem_ver = $doc->createAttribute("version");
        $adSystem_ver->value = '2.0';
        $adSystem->appendChild($adSystem_ver);


        $adTitle = $doc->createElement("AdTitle");
        $description = $doc->createElement("Description");
        $survey = $doc->createElement("Survey");
        $impression = $doc->createElement("Impression");
        $creatives = $doc->createElement("Creatives");

        $this->generateCreatives($doc, $creatives);
        // create attribute ad sequence
        $impression_id = $doc->createAttribute("id");
        $impression_id->value = 'DART';
        $impression->appendChild($impression_id);

        $inLine->appendChild($adSystem);
        $inLine->appendChild($adTitle);
        $inLine->appendChild($description);
        $inLine->appendChild($survey);
        $inLine->appendChild($impression);
        $inLine->appendChild($creatives);

        $ad->appendChild($inLine);
        $root->appendChild($ad);

        echo $doc->saveXML(), "\n";
    }

    public function generateCreatives($doc, $creatives) {
        $linear = $doc->createElement("Linear");
        $linear_skipoffset = $doc->createAttribute("skipoffset");
        $linear_skipoffset->value = '20%';
        $linear->appendChild($linear_skipoffset);

        $duration = $doc->createElement("Duration");
        $linear->appendChild($duration);

        //TrackingEvents

        $trackingEvents = $doc->createElement("TrackingEvents");
        $linear->appendChild($trackingEvents);

        $events = array('start', 'midpoint', 'complete', 'mute', 'pause', 'fullscreen');
        $this->generateTrackingEvent($doc, $trackingEvents,$events);
        
        //AdParameters
        $adParameters = $doc->createElement("AdParameters");
        $linear->appendChild($adParameters);
        
        //VideoClicks
        $videoClicks = $doc->createElement("VideoClicks");
        $linear->appendChild($videoClicks);
        
        $this->videoClicks($doc, $videoClicks);
        
        //VideoClicks
        $mediaFiles = $doc->createElement("MediaFiles");
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
        
        $this->mediaFiles($doc, $mediaFiles,$medias);
        
        $creatives->appendChild($linear);
    }

    public function generateTrackingEvent($doc, $trackingEvents, $events) {
        foreach ($events as $event) {
            $tracking = $doc->createElement("Tracking");
            $tracking_event = $doc->createAttribute("event");
            $tracking_event->value = $event;
            $tracking->appendChild($tracking_event);
            $trackingEvents->appendChild($tracking);
        }
    }
    
    public function videoClicks($doc, $videoClicks){
        //ClickThrough
        $clickThrough = $doc->createElement("ClickThrough");
        $videoClicks->appendChild($clickThrough);
        
        //ClickTracking
        $clickTracking = $doc->createElement("ClickTracking");
        $videoClicks->appendChild($clickTracking);
        
        $clickTracking_id = $doc->createAttribute("id");
        $clickTracking_id->value = 'DART';
        $clickTracking->appendChild($clickTracking_id);
    }
    
    public function mediaFiles($doc, $mediaFiles,$medias){
        foreach ($medias as $file => $attributes) {
            $mediaFile = $doc->createElement("MediaFile");
            $filename = $doc->createCDATASection($file);
            $mediaFile->appendChild($filename);
            
            $this->addAttributes($doc, $mediaFile, $attributes);
            $mediaFiles->appendChild($mediaFile);
        }
    }
    
    public function addAttributes($doc,$parent, $attributes){
        foreach ($attributes as $key => $value) {
            $attr = $doc->createAttribute($key);
            $attr->value = $value;
            $parent->appendChild($attr);
        }
            
    }

}
