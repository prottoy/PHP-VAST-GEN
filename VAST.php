<?php
/**
 * Description of VAST
 *
 * @author prottoy
 */
class VAST {
    public function generateVAST(){
                $doc  = new DOMDocument('1.0', 'utf-8');
		
                header("Content-type: text/xml");
		$doc->formatOutput = true;

		//<VAST version="3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vast.xsd">
		$root = $doc->createElement("VAST");
		$doc->appendChild($root);

		// create attribute version
		$version = $doc->createAttribute("version");
		$version->value='3.0';
		$root->appendChild($version);

		// create attribute version
		$xsi = $doc->createAttribute("xmlns:xsi");
		$xsi->value='http://www.w3.org/2001/XMLSchema-instance';
		$root->appendChild($xsi);

		// create attribute version
		$xsi_no = $doc->createAttribute("xsi:noNamespaceSchemaLocation");
		$xsi_no->value='vast.xsd';
		$root->appendChild($xsi_no);

		// <Ad id="preroll-1" sequence="1">
		$ad = $doc->createElement("Ad");
                // create attribute ad id
		$ad_id = $doc->createAttribute("id");
		$ad_id->value='preroll-1';
		$ad->appendChild($ad_id);
                
                // create attribute ad sequence
		$ad_sequence = $doc->createAttribute("sequence");
		$ad_sequence->value='1';
		$ad->appendChild($ad_sequence);
                
                $inLine = $doc->createElement("InLine");
                $adSystem = $doc->createElement("AdSystem");
                // create attribute ad sequence
		$adSystem_ver = $doc->createAttribute("version");
		$adSystem_ver->value='2.0';
		$adSystem->appendChild($adSystem_ver);
                
                
                $adTitle = $doc->createElement("AdTitle");
                $description = $doc->createElement("Description");
                $survey = $doc->createElement("Survey");
                $impression = $doc->createElement("Impression");
                
                // create attribute ad sequence
		$impression_id = $doc->createAttribute("id");
		$impression_id->value='DART';
		$impression->appendChild($impression_id);
                
                $inLine->appendChild($adSystem);
                $inLine->appendChild($adTitle);
                $inLine->appendChild($description);
                $inLine->appendChild($survey);
                $inLine->appendChild($impression);
                
                $ad->appendChild($inLine);
		$root->appendChild($ad);

		echo $doc->saveXML(), "\n";
	}    
}
