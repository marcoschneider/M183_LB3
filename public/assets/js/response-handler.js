class ResponseHandler{

  constructor(response) {
    this.res = response;
  }

  showUserdata(){
    var firstnameField = document.getElementById('input-userdata-firstname');
    var surnameField = document.getElementById('input-userdata-surname');
    var usernameField = document.getElementById('input-userdata-username');

    firstnameField.value = this.res.firstname;
    surnameField.value = this.res.surname;
    usernameField.value = this.res.username;
  }

  error() {
    if (this.res instanceof Array) {
      for (var i = 0; i < this.res.length; i++) {
        toastr.error(this.res[i]);
      }
    }else{
      toastr.error(this.res);
    }
  }

  showInfoGroupLogs() {
    var table = document.getElementById('group-log-table');
    var tableBody = document.getElementById('group-log-table-body');
    var output = '';
    if (this.res.length !== 0) {
      for (var i = 0; i < this.res.length; i++) {
        output += '<tr>';
        output += `<td>${this.res[i].message}</td>`;
        output += `<td>${this.res[i].title}</td>`;
        output += `<td>${this.res[i].username}</td>`;
        output += '</tr>';
      }
      tableBody.innerHTML = output;
    }else{
      table.innerText = 'Du hast noch keine Info Logs';
    }
  }

  showPendingGroupLogs() {
    var table = document.getElementById('group-log-pending-table');
    var tableBody = document.getElementById('group-log-pending-table-body');
    var output = '';
    if (this.res.length !== 0) {
      for (var i = 0; i < this.res.length; i++) {
        output += '<tr>';
        output += `<td>${this.res[i].message}</td>`;
        output += `<td>${this.res[i].title}</td>`;
        output += `<td>${this.res[i].username}</td>`;
        output += `<td>
                    <button id="confirm-delete-group-todo" class="button-default">Bestätigen</button>
                    <input type="hidden" id="todo-id" value="${this.res[i].todoID}"/>
                    </td>`;
        output += '</tr>';
      }
      tableBody.innerHTML = output;
    }else{
      table.innerText = 'Du hast noch keine pending Logs';
    }
  }

}