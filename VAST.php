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

        
        //Vast header
        $root = $doc->createElement("VAST");
        $doc->appendChild($root);
        $vastAttributes=array('version'=>'3.0', 'xmlns:xsi'=>'http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation'=> 'vast.xsd'); 
        $this->addAttributes($doc, $root, $vastAttributes);


        //ad header
        $ad = $doc->createElement("Ad");        
        $adAttributes=array('id'=>'preroll-1','sequence'=>'1'); 
        $this->addAttributes($doc, $ad, $adAttributes);
        
       
        $inLine = $doc->createElement("InLine");
        $adSystem = $doc->createElement("AdSystem");
        $adsystemAttributes= array('version'=>'2.0');
        $this->addAttributes($doc, $adSystem, $adsystemAttributes);


        $adTitle = $doc->createElement("AdTitle");
        $description = $doc->createElement("Description");
        $survey = $doc->createElement("Survey");
        
        $impression = $doc->createElement("Impression");
        $impressionAttributes= array('id'=>'DART');
        $this->addAttributes($doc, $impression, $impressionAttributes);
        
        $creatives = $doc->createElement("Creatives");

        $this->generateCreatives($doc, $creatives);
        
        $childs= array($adSystem, $adTitle, $description, $survey, $impression, $creatives);        
        $this->appendChilds($inLine, $childs);

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
    
    public function appendChilds($parent,$childs){
        foreach ($childs as $child) {
            $parent->appendChild($child);
        }
    }

}
