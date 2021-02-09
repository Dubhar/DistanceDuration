<?php  
  class TravelMatrix {
    protected array $sources;
    protected array $destinations;
    protected array $distances;
    protected array $durations;
    
    public function __construct(array $sources, array $destinations, array $distances, array $durations) {
      $this->sources = $sources;
      $this->destinations = $destinations;
      $this->distances = $distances;
      $this->durations = $durations;
    }

    public function __toString() {
      $ret = '';
      for($s=0; $s<count($this->sources); ++$s) {
        for($d=0; $d<count($this->destinations); ++$d) {
          $from = $this->sources[$s];
          $to = $this->destinations[$d];
          $km = round(floatval($this->distances[$s][$d])/1000, 2);
          $min = round(floatval($this->durations[$s][$d])/60, 2);
          $ret .= $from->getName()." -> ".$to->getName().": ".$km."km ".$min."min\n";
        }
      }
      return $ret;
    }
    
    public function getSources() {
      return $this->sources;
    }

    public function getDestinations() {
      return $this->destinations;
    }

    public function getDistances() {
      return $this->distances;
    }

    public function getDurations() {
      return $this->durations;
    }
  }
?>
