<?php
/**
 * Created by PhpStorm.
 * User: MarcoPolo
 * Date: 07.04.2017
 * Time: 20:40
 * @param $page
 */

// redirect user to page
function redirect($page)
{
  header("Location: " . $page);
}


function errorMessage($message)
{
  if (is_array($message)) {
    $messages = $message;
    echo '<div class="failbox">';
      foreach ($messages as $message) {
        echo '<p>'.$message.'</p>';
      }
    echo '</div>
    <div class="space"></div>';
  }else{
    echo
    '<div class="failbox">
      <p>'.$message.'</p>
    </div>
    <div class="space"></div>';
  }
}


function successMessage($message)
{
  if (is_array($message)) {
    $messages = $message;
    echo '<div class="success-box">';
    foreach ($messages as $message) {
      echo '<p>'.$message.'</p>';
    }
    echo '</div>
    <div class="space"></div>';
  }else{
    echo
      '<div class="success-box">
      <p>'.$message.'</p>
    </div>';
  }
}


function infoMessage($message, $colSize)
{
  if (is_array($message)) {
    $messages = $message;
    echo '<div class="aside col-'.$colSize.'">';
    foreach ($messages as $message) {
      echo '<p>'.$message.'</p>';
    }
    echo '</div>';
  }else{
    echo
    '<div class="aside col-'.$colSize.'">
      <p>'.$message.'</p>
    </div>';
  }
}


function sqlErrors($mysqli_errno) {
  switch ($mysqli_errno){
    case '1062':
      $mysqli_custom_error = "Diesen Benutzernamen gibt es bereits";
      break;
  }

  return $mysqli_custom_error;
}


//insert registration in DB
/**
 * @param $name
 * @param $surname
 * @param $conn
 * @param $username_reg
 * @param $password_reg
 * @return bool
 */
function insertRegister($name, $surname, $team, $conn, $username_reg, $password_reg)
{

  $sql = "
  INSERT INTO `user` 
    (`firstname`, `surname`, `password`, `username` , `fk_group`) 
  VALUES  (
    '" . $name . "',
    '" . $surname . "',
    '" . hash("sha256", $password_reg) . "',
    '" . $username_reg . "',
    '" . $team . "'
  )";

  $registerResult = mysqli_query($conn, $sql) or die(mysqli_errno($conn));

  if ($registerResult) {
    return true;
  } else {
    return $registerResult;
  }
}


//insert user edit in database
/**
 * @param $conn
 * @param $uid
 * @param $newPassword
 * @return bool
 */
function updateUserCredentials($conn, $uid, $newPassword)
{
  $sql = "UPDATE `user` SET `password` = '" . hash("sha256", $newPassword) . "' WHERE id = '" . $uid . "'";

  $updateResult = mysqli_query($conn, $sql);

  if ($updateResult) {
    return true;
  } else {
    return false;
  }
}


function getAllGroups($conn) {
  $sql = "
    SELECT
      id,
      group_name,
      group_short
    FROM `group`
  ";

  $values = [];

  $mysqli_result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

  while ($result = $mysqli_result->fetch_assoc()) {
    $values[] = $result;
  }

  if ($mysqli_result){
    return $values;
  }else{
    return $mysqli_result;
  }
}


function getAllProjects($conn) {
  $sql = "
    SELECT 
      id,
      project_name,
      short_name
    FROM project
  ";

  $values = [];

  $mysqli_result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  while($result = mysqli_fetch_assoc($mysqli_result)){
    $values[] = $result;
  }

  if ($mysqli_result){
    return $values;
  }else{
    return $mysqli_result;
  }
}

//insert user edit in database
/**
 * @param $conn
 * @param $uid
 * @param $values
 * @return bool
 */
function updateUserdata($conn, $uid, $values)
{
  $sql = "UPDATE `user` SET `name` = '" . $values['name'] . "', `surname` = '" . $values['surname'] . "', `username` = '" . $values['username'] . "' WHERE id = '" . $uid . "'";

  $updateResult = mysqli_query($conn, $sql);


  if ($updateResult) {
    return true;
  } else {
    return false;
  }
}

//get User ID
/**
 * @param $conn
 * @param $username
 * @return array|null|string
 */
function getUid($conn, $username)
{

  $sql = "
    SELECT 
      u.id,
      firstname,
      surname,
      username, 
      g.id AS 'group_id',
      g.group_name,
      g.group_short
    FROM `user` u
      INNER JOIN user_group ug ON ug.fk_user =  u.id
      INNER JOIN `group` g on ug.fk_group = g.id
    WHERE 
      username = '" . $username . "'
    AND g.group_short != 'self-todo'";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC);

  if ($sqlResult) {
    return $result;
  }else{
    return $sqlResult;
  }
}


// authenticate user
/**
 * @param $conn
 * @param $username
 * @param $password
 * @return bool
 */
function auth_user($conn, $username, $password)
{

  $escUser = mysqli_real_escape_string($conn, $username);
  $escPass = mysqli_real_escape_string($conn, $password);

  //Checks if username and password matches post
  $sql = "SELECT id FROM user WHERE `username`='" . $escUser . "' AND `password`='" . $escPass . "'";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return mysqli_error($conn);
  }
}


/**
 * @param $conn
 * @param $values
 * @param $uid
 * @return bool
 * @internal param $notes
 * @internal param $date
 * @internal param $id
 */

//insert To-do into DB
function addTodo($conn, $values, $uid)
{

  $values['fk_priority'] = (int)$values['niveau'];
  $values['date'] = ($values['date'] == '0') ? 0 : $values['date'];

  $sql = " INSERT INTO `todo` (
              `problem`,
              `fixed_date`,
              `title`,
              `fk_user`,
              `fk_priority`,
              `fk_project`,
              `creation_date`,
              `website_url`,
              `todo_status`,
              fk_group
            ) VALUES (
              '" . $values['problem'] . "',
              " . $values['date'] . ",
              '" . $values['title'] . "',
              '" . $uid . "',
              '" . $values['niveau'] . "',
              '" . $values['project'] . "',
              CURRENT_TIMESTAMP,
              '" . $values['url'] . "',
              '1',
              '" . $values['todo-type'] . "'
            )";

  $taskResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($taskResult) {
    return true;
  } else {
    return $taskResult;
  }
}

/**
 * @param $conn
 * @param $uid
 * @param $getId
 * @return bool
 */
function deleteTodo($conn, $uid, $getId)
{

  $sql = "DELETE FROM `todo` WHERE `id` = '" . $getId . "' AND `fk_user` = '" . $uid . "'";
  $deleteTodo = mysqli_query($conn, $sql);

  if ($deleteTodo) {
    return true;
  } else {
    return false;
  }
}

/**
 * @param $conn
 * @param $values
 * @param $getId
 * @param $uid
 * @return bool
 */
function updateTodo($conn, $values, $getId, $uid)
{

  $editTimestamp = time();

  $sql = "
    UPDATE todo
    SET
      `fk_projekte` = '" . $values['project'] . "',
      `title` = '" . $values['title'] . "',
      `problem` = '" . $values['problem'] . "',
      `fk_priority` = '" . $values['niveau'] . "',
      `edit_date` = " . $editTimestamp . ",
      `datum` = " . $values['date'] . ",
      `url` = '" . $values['url'] . "',
      `groupname` = '" . $values['todo-type'] . "'
    WHERE
      `id` = '" . $getId . "' AND `fk_user` = '" . $uid . "'";

  $updateTodo = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($updateTodo) {
    return true;
  } else {
    return $updateTodo;
  }
}


/**
 * @param $conn
 * @param $uid
 * @param $getId
 * @return array|bool
 */
function getTodoDetails($conn, $getId)
{

  $sql = "
    SELECT
     todo.id,
     todo.problem,
     todo.title,
     todo.creation_date,
     todo.fixed_date,
     p.niveau,
     pr.project_name,
     pr.id AS 'project_id',
     todo.fk_priority,
     todo.website_url,
     g.id AS 'group_id',
     g.group_name
    FROM m133_todo_app_beta.todo
     INNER JOIN user u ON(u.id = todo.fk_user)
     INNER JOIN priority p ON (todo.fk_priority = p.id)
     INNER JOIN project pr ON (todo.fk_project = pr.id)
     INNER JOIN `group` g on (todo.fk_group) = g.id
    WHERE todo.id = '" . $getId . "'
    ORDER BY todo.id DESC
  ";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC);

  var_dump($result['fixed_date']);

  $result['fixed_date'] = ($result['fixed_date'] == '0000-00-00 00:00:00')
    ? $result['fixed_date'] = ''
    : $result['fixed_date'] = date("Y-m-d", (int)$result['fixed_date']);

  $date = date_create($result['creation_date']);
  $result['creation_date'] = date_format($date, "d.m.Y \\u\\m H:i");



  if ($sqlResult) {
    return $result;
  } else {
    return $sqlResult;
  }
}

function getGroupTodos($conn, $groupname){
  $sql = "SELECT
            todo.id,
            todo.title,
            todo.problem,
            todo.creation_date,
            p.niveau,
            todo.website_url,
            u.username,
            u.firstname,
            u.surname,
            todo.todo_status,
            g.group_name
          FROM m133_todo_app_beta.todo
            INNER JOIN user u ON(u.id = todo.fk_user)
            INNER JOIN priority p ON (todo.fk_priority = p.id)
            INNER JOIN `group` g on (todo.fk_group = g.id)
          WHERE g.group_name = '" . $groupname . "'
          ORDER BY todo.fk_priority ASC";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = [];

  while ($arrayOutput = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC)) {
    if(strlen($arrayOutput['problem']) >= 200 ) {
      $pos = strpos($arrayOutput['problem'], ' ', 125);
      $arrayOutput['problem'] = substr($arrayOutput['problem'], 0, $pos);
    }
    $date = date_create($arrayOutput['creation_date']);
    $arrayOutput['creation_date'] = date_format($date, "d.m.Y \\u\\m H:i");
    array_push($result, $arrayOutput);
  }

  if ($sqlResult) {
    return $result;
  } else {
    return $sqlResult;
  }
}

function deleteGroupTodos($conn, $todoID, $uid) {
  $sql = "
    DELETE FROM todo
    WHERE todo.id = ".$todoID." AND todo.fk_user = ".$uid."
  ";

  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($result) {
    return true;
  }else{
    return $result;
  }

}

/**
 * @param $conn
 * @param $uid
 * @return array|bool
 * @internal param $id
 */
function getTodos($conn, $uid)
{
  $sql = "SELECT
             todo.id,
             todo.title,
             todo.problem,
             todo.creation_date,
             todo.website_url,
             todo.todo_status,
             p.niveau,
             pr.project_name,
             g.group_name,
             g.group_short
            FROM m133_todo_app_beta.todo
             INNER JOIN user u ON(u.id = todo.fk_user)
             INNER JOIN priority p ON (todo.fk_priority = p.id)
             INNER JOIN project pr on todo.fk_project = pr.id
             INNER JOIN `group` g on todo.fk_group = g.id
            WHERE u.id ='" . $uid . "'
            ORDER BY todo.fk_priority ASC, todo.creation_date DESC";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = array();

  while ($arrayOutput = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC)) {
    if(strlen($arrayOutput['problem']) >= 200 ) {
      $pos = strpos($arrayOutput['problem'], ' ', 125);
      $arrayOutput['problem'] = substr($arrayOutput['problem'], 0, $pos);
    }
    $arrayOutput['creation_date'] = date_create($arrayOutput['creation_date']);
    $arrayOutput['creation_date'] = date_format($arrayOutput['creation_date'], "d.m.Y \\u\\m H:i");

    array_push($result, $arrayOutput);
  }

  if ($result) {
    return $result;
  } else {
    return $sqlResult;
  }
}

function deleteLink($conn, $uid, $link_id){

  $uid = (int)$uid;

  $sql = "DELETE FROM
            link
          WHERE 
            fk_user = '" . $uid . "'
          AND
            id = '" . $link_id . "'";

  $deleteLink = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if($deleteLink){
    return true;
  }else{
    return $deleteLink;
  }

}

function updateLink($conn, $values, $uid) {
  $uid = (int)$uid;

  $sql = "
    UPDATE link
    SET 
      link_url = '".$values['link']."',
      link_name = '".$values['link_name']."'
    WHERE
      id = '".$values['link_id']."'
      AND
      fk_user = '".$uid."'
  ";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($sqlResult) {
    return true;
  }else{
    return $sqlResult;
  }
}

/**
 * @param $conn
 * @param $values
 * @param $uid
 * @return bool
 */
function addLink($conn, $values, $uid)
{

  $uid = (int)$uid;

  $sql = "INSERT INTO `link`(
                  `link_url`,
                  `link_name`,
                  `fk_user`
                )VALUES(
                  '" . $values['link'] . "',
                  '" . $values['link_name'] . "',
                  " . $uid . "
                )";

  $insertResult = mysqli_query($conn, $sql);

  if ($insertResult) {
    return true;
  } else {
    return false;
  }
}

/**
 * @param $conn
 * @param $uid
 * @return array|bool
 */
function getLinks($conn, $uid)
{
  $sql = "SELECT
              link_url,
              id,
              link_name
            FROM link
            WHERE fk_user ='" . $uid . "'";

  $sqlResult = mysqli_query($conn, $sql);

  $result = [];
  while ($arrayOutput = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC)) {
    array_push($result, $arrayOutput);
  }

  if ($result) {
    return $result;
  } else {
    return false;
  }
}

/**
 * @param $conn
 * @param $uid§
 * @param $getId
 * @return bool
 */
function doneTodo($conn, $uid, $getId)
{

  $sql = "UPDATE
            `todo` 
         SET
            `todo_status` = 1
         WHERE `todo`.`id` = '" . $getId . "' AND fk_user = '" . $uid . "'";

  $updateTodoStatus = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($updateTodoStatus) {
    return true;
  } else {
    return $updateTodoStatus;
  }
}

/**
 * @param $conn
 * @param $uid
 * @param $getId
 * @return bool
 */
function updateTodoStatus($conn, $uid, $getId)
{

  $sql = "
  UPDATE
    `todo`
  SET
    `todo_status` = 0
  WHERE `todo`.`id` = '" . $getId . "' AND fk_user = '" . $uid . "'";

  $updateTodoStatus = mysqli_query($conn, $sql);

  if ($updateTodoStatus) {
    return true;
  } else {
    return false;
  }
}

/**
 * @param $conn
 * @param $uid
 * @return array|bool
 */
function countTodoStatus($conn, $uid)
{
  $countedTodos = "SELECT count(*) AS 'countedStatus' FROM m133_todo_app_beta.todo WHERE todo_status = 1 AND fk_user = '" . $uid . "'";
  $countedDone = "SELECT count(*) AS 'countedStatus' FROM m133_todo_app_beta.todo WHERE todo_status = 0 AND fk_user = '" . $uid . "'";

  $countedTask = mysqli_query($conn, $countedTodos);
  $countedDone = mysqli_query($conn, $countedDone);

  $countResultTaskOverview = mysqli_fetch_array($countedTask, MYSQLI_ASSOC);
  $countResultTaskOverview['countedStatusTaskOverview'] = $countResultTaskOverview['countedStatus'];
  unset($countResultTaskOverview['countedStatus']);

  $countResultDoneOverview = mysqli_fetch_array($countedDone, MYSQLI_ASSOC);
  $countResultDoneOverview['countedStatusDoneOverview'] = $countResultDoneOverview['countedStatus'];
  unset($countResultDoneOverview['countedStatus']);

  $arrayStatusCount = array_merge($countResultDoneOverview, $countResultTaskOverview);

  if ($arrayStatusCount) {
    return $arrayStatusCount;
  } else {
    return false;
  }
}

//function for Navigation
/**
 * @param $links
 * @return string
 */
function createMenu($links)
{
  $ul = '<ul>';
  foreach ($links as $link => $path) {
    $ul .= '<li><a href="' . $path . '">' . $link . '</a></li>';
  }
  $ul .= '</ul>';
  return $ul;
}

/**
 * @param $formValues
 * @param $conn
 *
 * @return array
 */
function checkForm($formValues, $conn)
{
$errors = array();
$values = array();

  //Überprüft ob das Feld ausgefüllt ist.
  if(isset($formValues['project']) && $formValues['project'] != '' && $formValues['project'] != '--Bitte wählen--'){
    $values['project'] = $formValues['project'];
  }else{
    $errors['project'] = "Bitte wählen Sie ein Projekt aus";
  }

  if (isset($formValues['title']) && $formValues['title'] != '') {
    $values['title'] = htmlspecialchars($formValues['title']);
  }else{
    $errors['title'] = "Bitte einen Titel eingeben";
  }

  //Überprüft ob das Feld ausgefüllt ist.
  if(isset($formValues['problem']) && $formValues['problem'] != ''){
    $description = mysqli_real_escape_string($conn, $formValues['problem']);
    $values['problem'] = $description;
  }else{
    $errors['problem'] = "Feld Beschreibung darf nicht leer sein";
  }

  //Überprüft ob der Newsletter versendet werden soll oder nicht.
  if (isset($formValues['niveau'])){
    switch ($formValues['niveau']){
      case  '1':
        $niveau = $formValues['niveau'];
        $values['niveau'] = (int)$niveau;
        break;
      case '2':
        $niveau = $formValues['niveau'];
        $values['niveau'] = (int)$niveau;
        break;
      case '3':
        $niveau = $formValues['niveau'];
        $values['niveau'] = (int)$niveau;
        break;
    }
  } else {
    $errors['niveau'] = "Bitte geben Sie ein Niveau der Aufgabe an.";
  }

  if(isset($formValues['todo-type']) && $formValues['todo-type'] != '' && $formValues['todo-type'] != '--Bitte wählen--'){
    $values['todo-type'] = $formValues['todo-type'];
  }else{
    $errors['todo-type'] = "Bitte wählen Sie die Todo-Zuteilung aus";
  }

  if (isset($formValues['date']) && $formValues['date'] != ''){
    $todoDate = strtotime($formValues['date']);
    if($todoDate > time() - (24 * 60 * 60)){
      $values['date'] = $todoDate;
    }else{
      $errors['date'] = "In der Vergangenheit kann keine Aufgabe erledigt werden ;)";
    }
  }else{
    $values['date'] = 0;
  }

  if (isset($formValues['url']) && $formValues['url'] != '') {
    if (filter_var($formValues['url'], FILTER_VALIDATE_URL)){
      $values['url'] = $formValues['url'];
    }else{
      $errors['url'] = "Bitte geben Sie eine Valide URL ein";
    }
  }else{
    $values['url'] = $formValues['url'];
  }

  $values['errors'] = $errors;
  return $values;
}