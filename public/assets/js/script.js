var ajaxUrl = "/res/lib/Ajax.php";
var handler;

$(function(){

  customSelect('#dropdown-register');
  customSelect('#todo-type-create');
  customSelect('#todo-type-edit');
  customSelect('#project');

  if (document.URL === 'http://m133.test/?pages=userdata') {
    getUserdata();
  }
  if (document.URL === 'http://m133.test/?pages=group-log'){
    showInfoGroupLogs();
    showPendingGroupLogs();
  }

  var todoLogState = $('.todo-log-status');
  todoLogState.each(function (i) {
    switch (todoLogState[i].value) {
      case '2':
        todoLogState[i].parentNode.style = "background: #f39c12";
        console.log(todoLogState[i]);
        todoLogState[i].previousElementSibling.previousElementSibling.remove();
        break;
    }
  });

  //Event listeners
  $('#update-userdata').on("click", function () {
    changeUserdata();
  });

  $('#login-button').on("click", function () {
    auth_user();
  });

  $('#register-button').on("click", function () {
    register_user();
  });

  $(document).on("click", '#update-link-submit', function () {
    editLink();
  });

  $(document).on("click", '#group-log-trigger', function () {
    saveInfoGroupLogs();
  });

  $('[data-trigger]').on("click", function (event) {
    insertLogAfterDelete(event);
  });

  $(document).on("click", "#confirm-delete-group-todo", function () {
    confirmDeleteGroupTodo();
  });

});

function editLink() {
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
}

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

function saveInfoGroupLogs() {

  var todoID = $('#todo-id').val();
  var uid = $('#user-id').val();

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'insertLogAfterEdit',
        message: 'Dein Todo wurde bearbeitet',
        todoID: todoID,
        uid: uid
      })
    },
    success: function (res) {
      console.log(res);
    }
  });
}

function insertLogAfterDelete(event) {

  var todoID = event.target.parentNode.getAttribute('id');
  var uid = event.target.parentNode.getAttribute('data-uid');

  console.log(todoID, uid);

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'insertLogAfterDelete',
        message: 'Dein Todo wurde zur Löschung angefordert',
        todoID: todoID,
        uid: uid
      })
    },
    success: function (res) {
      console.log(res);
      if (res === true) {
        toastr.success("Das Todo wurde zur Löschung beantragt");
        setTimeout(function () {
          window.location.replace("/../?pages=group-overview");
        }, 1000);
      }else if(res === 'deletedTodo'){
        window.location.replace("/../?pages=group-overview");
      }
    }
  });
}

function showInfoGroupLogs() {
  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'getInfoGroupLog'
    })},
    success: function (res) {
      handler = new ResponseHandler(res);
      handler.showInfoGroupLogs();
    }
  })
}

function showPendingGroupLogs() {
  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'getPendingGroupLogs'
      })},
    success: function (res) {
      handler = new ResponseHandler(res);
      handler.showPendingGroupLogs();
    }
  })
}

function confirmDeleteGroupTodo() {

  var todoID = $('#todo-id').val();

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'deleteGroupTodo',
        idOfTodo: todoID
      })
    },
    success: function (res) {
      console.log(res);
      if (res === 'deletedTodo') {
        showPendingLogsTimeout(50);
      }
    }
  });
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
      if (res === true) {
        getUserdataTimeout(50);
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
        window.location.replace("/../?pages=todo-overview");
      }else{
        toastr.error(res);
      }
    }
  });
}

function register_user() {
  var firstname = $('#firstname').val();
  var surname = $('#surname').val();
  var username = $('#username').val();
  var pass = $('#password').val();
  var fk_group = $('#group-in-register').val();

  pass = sha256(pass);

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {jsonData: JSON.stringify({
      trigger: 'registerUser',
      firstname: firstname,
      surname: surname,
      username: username,
      password: pass,
      fk_group: fk_group
    })},
    success: function (res) {
      handler = new ResponseHandler(res);
      if (res === true) {
        toastr.success("Benutzer wurde registriert");
      }else{
        handler.error();
      }
    }
  });
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

    liData = liText + "<input id=\"group-in-register\" type=\"hidden\" name='" + name + "' value='" + liData + "'/>";
    $(elementID+' p').html(liData);
  });
}

function getUserdataTimeout($timeout) {
  setTimeout(function () {
    getUserdata();
  }, $timeout);
}

function showPendingLogsTimeout($timeout) {
  setTimeout(function () {
    showPendingGroupLogs();
  }, $timeout);
}