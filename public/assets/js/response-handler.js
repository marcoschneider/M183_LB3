class ResponseHandler{

  constructor(response) {
    this._response = response;
  }

  showUserdata(){
    var firstnameField = document.getElementById('input-userdata-firstname');
    var surnameField = document.getElementById('input-userdata-surname');
    var usernameField = document.getElementById('input-userdata-username');

    firstnameField.value = this._response.firstname;
    surnameField.value = this._response.surname;
    usernameField.value = this._response.username;
  }

}