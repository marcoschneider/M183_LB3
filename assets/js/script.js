$(function(){

  customSelect('#dropdown-register');
  customSelect('#todo-type');
  customSelect('#project');

});

function customSelect(elementID) {

  $(elementID).on("click", function () {
    $(".dropdown-list").slideToggle(250, function () {
      $(this).toggleClass("block");
    });
  });

  var dropdownLists = elementID+' .dropdown-list';

  $(dropdownLists+' li').on("click", function () {
    var liText = $(this).text();
    var liData = $(this).data("list-value");
    var name = $(dropdownLists).data('name');

    liData = liText + "<input type=\"hidden\" name='" + name + "' value='" + liData + "'/>";
    $(elementID+' p').html(liData);
  });
}