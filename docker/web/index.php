<?php
require_once 'Config.php';
require_once 'Helpers.php';
require_once 'HtmlDocument.php';

// load locations from text files and enhance with coordinates if they were missing
$locations = loadAndEnhanceLocations($config['locations']);
$destinations = loadAndEnhanceLocations($config['destinations']);

// save enhanced locations so we dont have to resolve "name -> coordinates" again
save($locations, $config['locations']);
save($destinations, $config['destinations']);

// calc distance and duration matrix
$travelMatrix = getDistanceAndDuration($locations, $destinations);

// create and print html
$html = new HtmlDocument("DistanceDuration");
$html->addCss("./style.css");
$html->addJs("./jquery-3.5.1.min.js");
$html->addJs("./interactive.js");
$html->addTravelMatrix($travelMatrix);
echo $html;
