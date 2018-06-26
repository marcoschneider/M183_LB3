var playingField;
var car;
var parkingSpot;
var actualCarPosition

$(function () {
  playingField = $('#game-field');
  car = $('#car');

  $(document).keypress(function (e) {

    switch (e.which){
      case 97:
        driveLeft();
        break;
      case 119:
        driveForward();
        break;
      case 100:
        driveRight();
        break;
      case 115:
        driveBack();
        break;
    }
  });
});

function generateParkingSpot() {
  parkingSpot = $('#parking-spot');
}

function driveLeft() {
  actualCarPosition = getActualPosition();
  var left = actualCarPosition.left;
  car.css({left: left - 10, transform: 'rotate(-90deg)'});
}

function driveForward() {
  actualCarPosition = getActualPosition();
  var top = actualCarPosition.top;
  car.css({top: top - 10});
}

function driveRight() {
  actualCarPosition = getActualPosition();
  var left = actualCarPosition.left;
  car.css({left: left + 10, transform: 'rotate(90deg)'});
}

function driveBack() {
  actualCarPosition = getActualPosition();
  var top = actualCarPosition.top;
  car.css({top: top + 10});
}

function getActualPosition() {
  actualCarPosition = car.position();
  return actualCarPosition;
}