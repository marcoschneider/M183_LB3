var ajaxUrl = "/res/lib/Ajax.php";
var handler;

$(function(){

  customSelect('#dropdown-register');
  customSelect('#todo-type-create');
  customSelect('#todo-type-edit');
  customSelect('#project');

  getUserdata();

  $('#update-link-submit').on("click", function () {
    var supportLinkID = $('.link-id');
    var linkToUpdate = $('#link-to-update').val();
    var updateInputSubmit = '<input type="submit" name="update-link" class="button-default" value="Link aktualisieren">';
    supportLinkID.each(function (i) {
      if (supportLinkID[i].innerHTML === linkToUpdate) {

        var hiddenLinkIdInput = '<input type="hidden" name="link_id" value="'+linkToUpdate+'">';

        let nameOfLink = $(supportLinkID[i]).parent().text();
        let refOfLink = $(supportLinkID[i]).parent().attr("href");

        nameOfLink = nameOfLink.replace(linkToUpdate, '');

        $('#name-link').val(nameOfLink);
        $('#link-url').val(refOfLink);

        $('#add-link-title').text("Link bearbeiten");

        $('#update-link-trigger').replaceWith(updateInputSubmit);
        $('#add-link').append(hiddenLinkIdInput);
      }else{
        var error = "<div class='failbox'>Dieser Link existiert nicht</div>";
        $('#update-edit-link-form').append(error);
      }
    });
  });
});


function getUserdata() {
  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {jsonData: JSON.stringify({
        trigger: 'getUserdata'
    })},
    success: function (response) {
      handler = new ResponseHandler(response);
      handler.showUserdata();
    },
    error: function (e) {
      console.log(e);
    }
  })
}

function customSelect(elementID) {

  $(elementID).on("click", function () {
    $(elementID+" .dropdown-list").slideToggle(250, function () {
      $(this).toggleClass("block");
    });
    return false;
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