var ajaxUrl = "/res/lib/Ajax.php";
var handler;

$(function(){

  customSelect('#dropdown-register');
  customSelect('#todo-type-create');
  customSelect('#todo-type-edit');
  customSelect('#project');

  getUserdata();

  $('#update-userdata').on("click", function () {
    changeUserdata();
  });

  $('#login-button').on("click", function () {
    auth_user();
  });

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
    success: function (res) {
      handler = new ResponseHandler(res);
      handler.showUserdata();
    },
    error: function (e) {
      console.log(e);
    }
  })
}

function changeUserdata() {
  var firstname = $('#input-userdata-firstname').val();
  var surname = $('#input-userdata-surname').val();
  var username = $('#input-userdata-username').val();

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {jsonData: JSON.stringify({
      trigger: 'updateUserdata',
      firstname: firstname,
      surname: surname,
      username: username
    })},
    success: function (res) {
      handler = new ResponseHandler();
      if (res === true) {
        timeout(50);
        toastr.success("Die Benutzerdaten wurden aktualisiert");
      }else{
        for (var i = 0; i < res.length; i++) {
          toastr.error(res[i]);
        }
      }
    },

  })
}

function auth_user() {
  var username = $('#fname').val();
  var pass = $('#pname').val();
  pass = sha256(pass);

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {jsonData: JSON.stringify({
      trigger: 'authUser',
      username: username,
      password: pass,
    })},
    success: function (res) {
      if (res === true) {
        window.location.replace("/?pages=todo-overview");
      }else{
        toastr.error(res);
      }
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

function timeout($timeout) {
  setTimeout(function () {
    getUserdata();
  }, $timeout);
}