var urlPrefix = '';
var ajaxUrl = urlPrefix+"/res/lib/Ajax.php";
var handler;
var body = $('body');

$(function(){

  if (document.URL === 'http://' + window.location.hostname + urlPrefix + '/user') {
    getUserdata();
  }
  if (document.URL === 'http://' + window.location.hostname + urlPrefix + '/group-log'){
    showInfoGroupLogs();
    showPendingGroupLogs();
  }

  let todoLogState = $('.todo-log-status');
  todoLogState.each(function (i) {
    switch (todoLogState[i].value) {
      case '2':
        todoLogState[i].parentNode.style = "background: #f39c12";
        todoLogState[i].previousElementSibling.previousElementSibling.remove();
        break;
    }
  });

  $('#label-2fa-code').hide();
  $('#login-button-after-2fa').hide();

  //Event listeners
  $('#update-userdata').on("click", function () {
    changeUserdata();
  });

  $('#login-button').on("click", function () {
    // $('#label-2fa-code').show();
    // $('#fields-user-password').hide();
    // $('#login-button').hide();
    // $('#login-button-after-2fa').show();
    // $('#login-button-after-2fa').on("click", function () {
    // })
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

  $('#changePassword').on("click", function () {
    checkPassword();
  });

  $('#create-todo').on("click", function () {
    createTodo();
  });

  $('.todo-wrapper').on("click", function (event) {
    if (event.target.className === 'fas fa-trash') {
      insertLogAfterDelete(event);
    }
  });

  $(document).on("click", '.confirm-delete-group-todo', function (event) {
    if (event.target.className === 'button-default confirm-delete-group-todo') {
      confirmDeleteGroupTodo(event);
    }
  });

  $(document).on("click", '.decline-delete-group-todo', function (event) {
    if (event.target.className === 'button-default do-not-delete decline-delete-group-todo') {
      declineDeleteGroupTodo(event);
    }
  });

});

function editLink() {
  let supportLinkID = $('.link-id');
  let linkToUpdate = $('#link-to-update').val();
  let updateInputSubmit = '<input type="submit" name="update-link" class="button-default" value="Link aktualisieren">';
  supportLinkID.each(function (i) {
    if (supportLinkID[i].innerHTML === linkToUpdate) {

      let hiddenLinkIdInput = '<input type="hidden" name="link_id" value="'+linkToUpdate+'">';

      let nameOfLink = $(supportLinkID[i]).parent().text();
      let refOfLink = $(supportLinkID[i]).parent().attr("href");

      nameOfLink = nameOfLink.replace(linkToUpdate, '');

      $('#name-link').val(nameOfLink);
      $('#link-url').val(refOfLink);

      $('#add-link-title').text("Link bearbeiten");

      $('#update-link-trigger').replaceWith(updateInputSubmit);
      $('#add-link').append(hiddenLinkIdInput);
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

  let todoID = $('#todo-id').val();
  let uid = $('#user-id').val();

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

  let todoID = event.target.parentNode.getAttribute('id');
  let uid = event.target.parentNode.getAttribute('data-uid');

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
      if (res === true) {
        toastr.success("Das Todo wurde zur Löschung beantragt");
        setTimeout(function () {
          window.location.replace(urlPrefix+"/group-overview");
        }, 1000);
      }else if(res === 'deletedTodo'){
        window.location.replace(urlPrefix+"/group-overview");
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

function confirmDeleteGroupTodo(event) {

  let todoID = event.target.parentNode.lastElementChild.value;

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
      if (res === 'deletedTodo') {
        showPendingLogsTimeout(50);
      }
    }
  });
}

function declineDeleteGroupTodo(event) {
  let todoID = event.target.parentNode.lastElementChild.value;

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'declineDeleteTodo',
        idOfTodo: todoID
      })
    },
    success: function (res) {
      if (res === true) {
        showPendingLogsTimeout(50);
      }
    }
  });
}

function checkPassword() {

  let currentPassword = $('#currentPassword').val();
  let newPassword = $('#newPassword').val();
  let repeatPassword = $('#repeatPassword').val();

  currentPassword = sha256(currentPassword);
  newPassword = sha256(newPassword);
  repeatPassword = sha256(repeatPassword);

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {
      jsonData: JSON.stringify({
        trigger: 'checkPassword',
        currentPassword: currentPassword,
        newPassword: newPassword,
        repeatPassword: repeatPassword
      })},
    success: function (res) {
      if (res === true) {
        toastr.success("Die Benutzerdaten wurden aktualisiert!");
        setTimeout(function () {
          sessionStorage.clear();
          window.location.replace(urlPrefix+"/public/view/login.php");
        }, 2000);
      }else{
        for (let i = 0; i < res.length; i++) {
          toastr.error(res[i]);
        }
      }
    }
  })
}

function changeUserdata() {
  let firstname = $('#input-userdata-firstname').val();
  let surname = $('#input-userdata-surname').val();
  let username = $('#input-userdata-username').val();

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
        for (let i = 0; i < res.length; i++) {
          toastr.error(res[i]);
        }
      }
    },

  })
}

function auth_user() {
  let username = $('#fname').val();
  let pass = $('#pname').val();
  let code = $('#2fa-code').val();
  pass = sha256(pass);

  $.ajax({
    url: ajaxUrl,
    type: 'POST',
    data: {jsonData: JSON.stringify({
      trigger: 'authUser',
      username: username,
      password: pass,
      code: code,
    })},
    success: function (res) {
      if (res === true) {
        window.location.replace(urlPrefix+"/todo-overview");
      }else{
        toastr.error(res);
      }
    },
    error: function (res) {
      console.log(body.append(res.responseText));
    }
  });
}

function register_user() {
  let firstname = $('#firstname').val();
  let surname = $('#surname').val();
  let username = $('#username').val();
  let pass = $('#password').val();
  let fk_group = $('#group-in-register').val();

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
    },
    error: function (res) {
      console.log(body.append(res.responseText));
    }
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

function guid() {
  return "ss-s-s-s-sss".replace(/s/g, s4);
}

function s4() {
  return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
}
