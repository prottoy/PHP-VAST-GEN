<?php

/**
 * Description of VAST
 * 
 * @author prottoy
 */
class VAST {

    public $gnrAdId = "preroll-1";
    public $gnrAdSystem = "1.0";
    public $gnrAdTitle;
    public $gnrImpresssionURL;
    public $gnrClickThroughURL;
    public $gnrFileUrl;
    public $reportError;
    private $doc;

    public function generateVAST() {
//        $this->showErrors();
        $this->doc = new DOMDocument('1.0', 'utf-8');

        header("Content-type: text/xml");
        $this->doc->formatOutput = true;

        $root = $this->doc->createElement("VAST");
        $this->doc->appendChild($root);
        $vastAttributes = array('version' => '3.0', 'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance', 'xsi:noNamespaceSchemaLocation' => 'vast.xsd');
        $this->addAttributes($root, $vastAttributes);

        $this->createAd($root, $this->gnrAdId, '1');
        echo $this->doc->saveXML(), "\n";
    }

    private function createAd($root, $id, $sequence) {
        //ad header
        $ad = $this->doc->createElement("Ad");
        $adAttributes = array('id' => $id, 'sequence' => $sequence);
        $this->addAttributes($ad, $adAttributes);


        $inLine = $this->doc->createElement("InLine");
        $adSystem = $this->doc->createElement("AdSystem");
        $adsystemAttributes = array('version' => $this->gnrAdSystem);
        $this->addAttributes($adSystem, $adsystemAttributes);
        $this->addValue($adSystem, 'GNRVAST');

        $adTitle = $this->doc->createElement("AdTitle");
        $description = $this->doc->createElement("Description");
        $survey = $this->doc->createElement("Survey");

        $impression = $this->doc->createElement("Impression");
        $impressionAttributes = array('id' => 'GNR');
        $this->addAttributes($impression, $impressionAttributes);
        $this->addCDATAValue($impression, $this->gnrImpresssionURL);

        $creatives = $this->doc->createElement("Creatives");

        $this->generateCreatives($creatives);

        $childs = array($adSystem, $adTitle, $description, $survey, $impression, $creatives);
        $this->appendChilds($inLine, $childs);

        $ad->appendChild($inLine);
        $root->appendChild($ad);
    }

    private function generateCreatives($creatives) {
        $creative = $this->doc->createElement("Creative");
        $creative_attributes = array('sequence' => '1', 'AdID' => '');
        $this->addAttributes($creative, $creative_attributes);

        $linear = $this->doc->createElement("Linear");
        $linear_attributes = array('skipoffset' => '20%');
        $this->addAttributes($linear, $linear_attributes);

        $duration = $this->doc->createElement("Duration");
        $linear->appendChild($duration);

        //TrackingEvents
        $trackingEvents = $this->doc->createElement("TrackingEvents");
        $linear->appendChild($trackingEvents);

//        $events = array('start', 'midpoint', 'complete', 'mute', 'pause', 'fullscreen');
//        $this->generateTrackingEvent($trackingEvents, $events);
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

        $file1 = $this->gnrFileUrl;
        $attributes1 = array(
            'id' => '1',
            'delivery' => 'progressive',
            'type' => 'video/mp4',
            'bitrate' => '1500',
            'width' => '720',
            'height' => '480');


        $medias = array($file1 => $attributes1);

        $this->mediaFiles($mediaFiles, $medias);

        $creative->appendChild($linear);
        $creatives->appendChild($creative);
    }

    private function generateTrackingEvent($trackingEvents, $events) {
        foreach ($events as $event) {
            $tracking = $this->doc->createElement("Tracking");
            $tracking_event = $this->doc->createAttribute("event");
            $tracking_event->value = $event;
            $tracking->appendChild($tracking_event);
            $trackingEvents->appendChild($tracking);
            $this->addCDATAValue($trackingEvents, 'http://www.green-red.com');
        }
    }

    private function videoClicks($videoClicks) {
        //ClickThrough
        $clickThrough = $this->doc->createElement("ClickThrough");
        $videoClicks->appendChild($clickThrough);
        $this->addCDATAValue($clickThrough, $this->gnrClickThroughURL);

        //ClickTracking
        $clickTracking = $this->doc->createElement("ClickTracking");
        $videoClicks->appendChild($clickTracking);

        $clickTracking_id = $this->doc->createAttribute("id");
        $clickTracking_id->value = 'GNR';
        $clickTracking->appendChild($clickTracking_id);
    }

    private function mediaFiles($mediaFiles, $medias) {
        foreach ($medias as $file => $attributes) {
            $mediaFile = $this->doc->createElement("MediaFile");
            $filename = $this->doc->createCDATASection($file);
            $mediaFile->appendChild($filename);

            $this->addAttributes($mediaFile, $attributes);
            $mediaFiles->appendChild($mediaFile);
        }
    }

    private function addAttributes($parent, $attributes) {
        foreach ($attributes as $key => $value) {
            $attr = $this->doc->createAttribute($key);
            $attr->value = $value;
            $parent->appendChild($attr);
        }
    }

    private function appendChilds($parent, $childs) {
        foreach ($childs as $child) {
            $parent->appendChild($child);
        }
    }

    private function addValue($parent, $text) {
        // create another text node
        $value = $this->doc->createTextNode($text);
        $parent->appendChild($value);
    }

    private function addCDATAValue($parent, $text) {
        // create another text node
        $value = $this->doc->createCDATASection($text);
        $parent->appendChild($value);
    }

    public function showErrors() {
        if (strcmp($this->reportError, 'yes')) {
            echo "Error reporting on";
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(-1);
        }
    }

}
