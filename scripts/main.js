document.querySelector('html').onclick = function() {
    alert('Click!');
}
function getAlert(ex) {
  if(ex){
  alert('Hello!');
  return "ok";
  }
  else {
  alert('Github only one!');
  return "ok";
  }
}
function multiply(num1,num2) {
  var result = num1 * num2;
  return result;
}