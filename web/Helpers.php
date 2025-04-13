<?php
require_once 'Config.php';
require_once 'Location.php';

/**
 * @throws Exception
 */
function loadAndEnhanceLocations(string $filename): array
{
  $locations = array();
  if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, $GLOBALS['config']['delimiter'], '"', "\\")) !== FALSE) {
      if (count($data) != 3) exit - 1;
      $locations[] = new Location($data[0], $data[1], $data[2]);
    }
  }
  if (empty($locations)) {
    throw new Exception('File ' . $filename . ' contains no locations!');
  }
  return $locations;
}

function save(array $locations, string $filename)
{
  $csv = arrayToString($locations);
  file_put_contents($filename, $csv);
}

/**
 * @throws Exception
 */
function getCoordinates(string $name): array
{
  // construct url + params
  $endpoint = 'https://api.openrouteservice.org/geocode/search';
  $params = array('api_key' => $GLOBALS['config']['api_key'],
    'text' => $name);
  $url = $endpoint . '?' . http_build_query($params);

  // exec get request
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $jsonResponse = curl_exec($ch);
  curl_close($ch);

  // parse result
  $response = json_decode($jsonResponse, true);
  if (isset($response['error'])) {
    throw new Exception($response['error']);
  }
  $latitude = $response['features'][0]['geometry']['coordinates'][1];
  $longitude = $response['features'][0]['geometry']['coordinates'][0];

  return array($latitude, $longitude);
}

/**
 * @throws Exception
 */
function getDistanceAndDuration(array $locations, array $destinations): TravelMatrix
{
  // construct params
  $allLocations = array_merge($locations, $destinations);
  $allCoords = array_map(function ($o) {
    return $o->getLongLat();
  }, $allLocations);
  $requestSources = array_map(function ($o, $k) {
    return $k;
  }, $locations, array_keys($locations));
  $requestDestinations = array_map(function ($o, $k) use ($locations) {
    return ($k + count($locations));
  }, $destinations, array_keys($destinations));
  $data = array('locations' => $allCoords,
    'destinations' => $requestDestinations,
    'sources' => $requestSources,
    'metrics' => array('distance', 'duration'),
    'units' => 'm',
    'resolve_locations' => false);
  $data = json_encode($data);
  $headers = ['Authorization: ' . $GLOBALS['config']['api_key'],
    'Content-Type:application/json',
    'Accept: application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8'];

  // exec post request
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.openrouteservice.org/v2/matrix/driving-car");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  curl_close($ch);

  // parse response
  $response = json_decode($response, true);
  if (isset($response['error'])) {
    throw new Exception($response['error']);
  }
  return new TravelMatrix($locations, $destinations, $response['distances'], $response['durations']);
}

function arrayToString(array $arr): string
{
  $result = "";
  foreach ($arr as $element) {
    $result .= $element . PHP_EOL;
  }
  return $result;
}
