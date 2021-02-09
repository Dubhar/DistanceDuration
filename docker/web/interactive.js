// helper functions ///////////////////////////////////////////////////////////

function updateVisibility() {
  if($('#dropdown').val() == "duration") {
        $('.duration').show();
        $('.distance').hide();
    } else if($('#dropdown').val() == "distance") {
        $('.duration').hide();
        $('.distance').show();
    }
}

function updateColors() {
  var dropdown = ".".concat($('#dropdown').val());
  var aboveColor = getComputedStyle(document.documentElement,null).getPropertyValue('--aboveLimitColor');
  var belowColor = getComputedStyle(document.documentElement,null).getPropertyValue('--belowLimitColor');
  var thresholdColor = getComputedStyle(document.documentElement,null).getPropertyValue('--borderlineColor');

  $('#distDur').find('td').each(function() {
    var cellValue = $(this).children(dropdown).html();
    var threshold = $('#threshold').val();
    var divergence = $('#divergence').val();
    
    if(parseFloat(cellValue) <= (parseFloat(threshold))) {
      $(this).children(dropdown).css('background-color', belowColor);
    }
    else if(parseFloat(cellValue) > (parseFloat(threshold))
            && parseFloat(cellValue) < (parseFloat(threshold)+parseFloat(divergence))) {
      $(this).children(dropdown).css('background-color', thresholdColor);
    }
    else {
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
