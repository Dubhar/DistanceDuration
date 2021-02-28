<?php
require_once 'TravelMatrix.php';

class HtmlDocument
{
  protected string $title;
  protected array $css;
  protected array $js;
  protected TravelMatrix $matrix;
  protected string $error;

  public function __construct(string $title = "NoTitle")
  {
    $this->title = $title;
    $this->css = array();
    $this->js = array();
    $this->error = '';
  }

  public function addCss(string $cssPath)
  {
    $this->css[] = $cssPath;
  }

  public function addJs(string $jsPath)
  {
    $this->js[] = $jsPath;
  }

  public function addTravelMatrix(TravelMatrix $matrix)
  {
    $this->matrix = $matrix;
  }

  public function addError(string $error)
  {
    $this->error .= $error . '<br/>';
  }

  private function matrixAsTable(): string
  {
    $ret = '<table id="distDur"><tr><th></th>';
    foreach ($this->matrix->getDestinations() as $dest) {
      $ret .= '<th>' . $dest->getName() . "</th>";
    }
    $ret .= '</tr>';

    for ($s = 0; $s < count($this->matrix->getSources()); ++$s) {
      $ret .= '<tr><th>' . $this->matrix->getSources()[$s]->getName() . '</th>';
      for ($d = 0; $d < count($this->matrix->getDestinations()); ++$d) {
        $km = round(floatval($this->matrix->getDistances()[$s][$d]) / 1000, 2);
        $min = round(floatval($this->matrix->getDurations()[$s][$d]) / 60);
        $ret .= '<td><div class="distance">' . $km . '</div><div class="duration">' . $min . '</div></td>';
      }
      $ret .= '</tr>';
    }
    $ret .= '</table>';
    return $ret;
  }

  public function __toString(): string
  {
    $html = '<!DOCTYPE html><html lang="en"><head><title>' . $this->title . '</title>';
    foreach ($this->css as $cssFile) {
      $html .= '<link rel="stylesheet" href="' . $cssFile . '">';
    }
    $html .= '</head><body>';
    if (!empty($this->error)) {
      $html .= '<div id="error">Error: ' . $this->error . '</div>';
    }
    if (isset($this->matrix)) {
      $html .= $this->matrixAsTable();
      $html .= '<br/><div id="control">';
      $html .= '<select id="dropdown"><option value="distance">Distance[km]</option><option value="duration" selected>Duration[min]</option></select>';
      $html .= '<label for="threshold">Limit:</label>';
      $html .= '<input type="number" id="threshold" min="0" max="999" value="45">';
      $html .= '<label for="divergence">Variance:</label>';
      $html .= '<input type="number" id="divergence" min="0" max="99" value="15">';
      $html .= '</div>';
      foreach ($this->js as $jsFile) {
        $html .= '<script src="' . $jsFile . '"></script>';
      }
    }
    $html .= '</body></html>';

    return $html;
  }
}
