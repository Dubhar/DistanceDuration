<?php
require_once 'Helpers.php';
require_once 'Config.php';

class Location
{
  protected string $name;
  protected float $latitude;
  protected float $longitude;

  /**
   * @throws Exception
   */
  public function __construct(string $name, string $latitude = "", string $longitude = "")
  {
    $this->name = $name;
    if ($this->validCoordinates($latitude, $longitude)) {
      $this->latitude = floatval($latitude);
      $this->longitude = floatval($longitude);
    } else {
      $latLong = getCoordinates($name);
      $this->latitude = $latLong[0];
      $this->longitude = $latLong[1];
    }
  }

  public function getLongLat(): array
  {
    return array($this->longitude, $this->latitude);
  }

  public function __toString(): string
  {
    $del = $GLOBALS['config']['delimiter'] . " ";
    return $this->name . $del . $this->latitude . $del . $this->longitude;
  }

  public function getName(): string
  {
    return $this->name;
  }

  private function validCoordinates(string $latitude, string $longitude): bool
  {
    return is_numeric($latitude)
      && is_numeric($longitude)
      && $latitude > -90
      && $latitude < 90
      && $longitude > -180
      && $longitude < 180;
  }
}
