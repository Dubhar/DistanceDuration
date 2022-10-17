// helper functions ///////////////////////////////////////////////////////////

function updateVisibility() {
  const currentDropdownSelection = $('#dropdown').val();
  if (currentDropdownSelection === "duration") {
    $('.duration').show();
    $('.distance').hide();
  } else if (currentDropdownSelection === "distance") {
    $('.duration').hide();
    $('.distance').show();
  }
}

function updateColors() {
  const dropdown = ".".concat($('#dropdown').val());
  const aboveColor = getComputedStyle(document.documentElement, null).getPropertyValue('--aboveLimitColor');
  const belowColor = getComputedStyle(document.documentElement, null).getPropertyValue('--belowLimitColor');
  const thresholdColor = getComputedStyle(document.documentElement, null).getPropertyValue('--borderlineColor');

  $('#distDur').find('td').each(function () {
    const cellValue = $(this).children(dropdown).html();
    const threshold = $('#threshold').val();
    const divergence = $('#divergence').val();

    if (parseFloat(cellValue) <= (parseFloat(threshold))) {
      $(this).children(dropdown).css('background-color', belowColor);
    } else if (parseFloat(cellValue) > (parseFloat(threshold))
      && parseFloat(cellValue) < (parseFloat(threshold) + parseFloat(divergence))) {
      $(this).children(dropdown).css('background-color', thresholdColor);
    } else {
      $(this).children(dropdown).css('background-color', aboveColor);
    }
  });
}

function update() {
  updateVisibility();
  updateColors();
}

// actions ////////////////////////////////////////////////////////////////////

$('#dropdown').change(update);
$('#threshold').change(updateColors);
$('#divergence').change(updateColors);
$(document).ready(update);
