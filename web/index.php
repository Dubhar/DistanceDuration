<?php
require_once 'Config.php';
require_once 'Helpers.php';
require_once 'HtmlDocument.php';

// prepare html
$html = new HtmlDocument("DistanceDuration");
$html->addCss("./style.css");
$html->addJs("./jquery-3.6.1.min.js");
$html->addJs("./interactive.js");

try {
  // load locations from text files and enhance with coordinates if they were missing
  $locations = loadAndEnhanceLocations($config['locations']);
  $destinations = loadAndEnhanceLocations($config['destinations']);

  // save enhanced locations so we dont have to resolve "name -> coordinates" again
  save($locations, $config['locations']);
  save($destinations, $config['destinations']);

  // calc distance and duration matrix
  $travelMatrix = getDistanceAndDuration($locations, $destinations);
  $html->addTravelMatrix($travelMatrix);
} catch (Exception $exception) {
  $html->addError($exception->getMessage());
}

// print the document
echo $html;
