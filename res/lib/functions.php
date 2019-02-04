<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 07.04.2017
 * Time: 20:40
 * @param $page
 */

/**
 * Redirects user to page.
 *
 * @author vsefa
 */
function redirect($page)
{
  header("Location: " . $page);
}

function stripScriptTag($html) {
  return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
}

/**
 * Outputs error messages to user.
 *
 * @author vsefa
 */
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

function showToastMessage($type, $message) {
  if ($type === 'success') {
    return '<script>toastr.success(' . $message . ');</script>';
  }
  return '<script>toastr.error(' . $message . ');</script>';
}

/**
 * Outputs success messages to user.
 *
 * @author vsefa
 */
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

/**
 * Outputs info messages to user.
 *
 * @author vsefa
 */
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

/**
 * Updates User Credentials.
 *
 * @author vsefa
 *
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

/**
 * Gets all groups.
 *
 * @author vsefa
 *
 * @param $conn
 * @return array|bool|mysqli_result
 */
function getAllGroups($conn) {
  $sql = "
    SELECT
      id,
      group_name,
      group_short
    FROM `group`
    WHERE group_short != 'self-todo'
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

/**
 * Gets all projects.
 *
 * @author vsefa
 *
 * @param $conn
 * @return array|bool|mysqli_result
 */
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

function getAllPriorities($conn) {
  $sql = "
    SELECT 
      id,
      niveau
    FROM priority
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

/**
 * Inserts Todo into DB.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $values
 * @param $uid
 * @return bool
 * @internal param $notes
 * @internal param $date
 * @internal param $id
 */
function addTodo($conn, $values, $uid)
{

  $values['fk_priority'] = (int)$values['niveau'];
  $values['todo-type'] = (int)$values['todo-type'];
  $values['project'] = (int)$values['project'];

  $timestamp = time();

  $sql = " INSERT INTO `todo` (
              `problem`,
              `fixed_date`,
              `title`,
              `creation_date`,
              `website_url`,
              `todo_status`,
              `fk_user`,
              `fk_priority`,
              `fk_project`,
              `fk_group`
            ) VALUES (
              '" . $values['problem'] . "',
              " . $values['fixed_date'] . ",
              '" . $values['title'] . "',
              " . $timestamp . ",
              '" . $values['url'] . "',
              1,
              " . $uid . ",
              " . $values['niveau'] . ",
              " . $values['project'] . ",
              " . $values['todo-type'] . "
            )";

  $taskResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($taskResult) {
    return true;
  } else {
    return $taskResult;
  }
}

/**
 * Deletes eigentodo.
 *
 * @author vsefa
 *
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
 * Updates Edit in Database.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $values
 * @param $getId
 * @param $uid
 * @return bool
 */
function saveEdit($conn, $values, $getId, $uid)
{

  $editTimestamp = time();

  $values['project'] = (int)$values['project'];
  $values['todo-type'] = (int)$values['todo-type'];

  $sql = "
    UPDATE todo
    SET
      `title` = '" . $values['title'] . "',
      `problem` = '" . $values['problem'] . "',
      `fixed_date` = " . $values['fixed_date'] . ",
      `last_edit` = " . $editTimestamp . ",
      `website_url` = '" . $values['url'] . "',
      `fk_priority` = '" . $values['niveau'] . "',
      `fk_project` = '" . $values['project'] . "',
      `fk_group` = '" . $values['todo-type'] . "'
    WHERE
      `id` = '" . $getId . "' AND `fk_user` = '" . $uid . "'";

  mysqli_begin_transaction($conn);
  $updateTodo = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($updateTodo) {
    mysqli_commit($conn);
    return true;
  } else {
    mysqli_rollback($conn);
    return $updateTodo;
  }
}

function sec_session_start()
{
  $session_name = 'm183_sec_session';   // Set a custom session name
  $secure = TRUE;
  // This stops JavaScript being able to access the session id.
  $httponly = true;

  // Forces sessions to only use cookies.
  if (ini_set('session.use_only_cookies', 1) === FALSE) {
    header("Location: /public/view/login.php?login-error");
    exit();
  }
  // Gets current cookies params.
  $cookieParams = session_get_cookie_params();
  session_set_cookie_params
  (
    $cookieParams["lifetime"],
    $cookieParams["path"],
    $cookieParams["domain"],
    $secure,
    $httponly
  );

  // Sets the session name to the one set above.
  session_name($session_name);
  session_start();            // Start the PHP session
  session_regenerate_id(true);    // regenerated the session, delete the old one.
}

/**
 * Gets all tododetails.
 *
 * @author maschneider
 *
 * @param $conn
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
     todo.last_edit,
     todo.fk_priority,
     todo.website_url,
     u.id AS 'uid',
     p.niveau,
     pr.project_name,
     pr.id AS 'project_id',
     g.id AS 'group_id',
     g.group_name
    FROM todo
     INNER JOIN user u ON(u.id = todo.fk_user)
     INNER JOIN priority p ON (todo.fk_priority = p.id)
     INNER JOIN project pr ON (todo.fk_project = pr.id)
     INNER JOIN `group` g on (todo.fk_group) = g.id
    WHERE todo.id = '" . $getId . "'
    ORDER BY todo.id DESC
  ";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC);

  $result['fixed_date_edit'] = $result['fixed_date'] != 0 ? date("d.m.Y", $result['fixed_date']) : $result['fixed_date']  ;
  $result['fixed_date'] = $result['fixed_date'] != 0 ? date("d.m.Y", $result['fixed_date']) : $result['fixed_date'];
  $result['last_edit'] = $result['last_edit'] != null ? date("d.m.Y \\u\\m H:i", $result['last_edit']) : $result['last_edit'];
  $result['creation_date'] = date("d.m.Y \\u\\m h:i:s", $result['creation_date']);


  if ($sqlResult) {
    return $result;
  } else {
    return $sqlResult;
  }
}

/**
 * @author maschneider
 *
 * Gets all group todos.
 *
 * @param $conn
 * @param $groupname
 * @return array|bool|mysqli_result
 */
function getGroupTodos($conn, $groupname) {
  $sql = "SELECT
            todo.id,
            todo.title,
            todo.problem,
            todo.creation_date,
            p.niveau,
            todo.website_url,
            u.id AS 'uid',
            u.username,
            u.firstname,
            u.surname,
            todo.todo_status,
            g.group_name,
            gl.fk_group_log_state
          FROM todo
            INNER JOIN user u ON(u.id = todo.fk_user)
            INNER JOIN priority p ON (todo.fk_priority = p.id)
            INNER JOIN `group` g on (todo.fk_group = g.id)
            LEFT JOIN group_log gl on todo.id = gl.fk_todo
          WHERE g.group_name = '" . $groupname . "'
          ORDER BY p.id ASC";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = [];

  while ($arrayOutput = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC)) {
    if (strlen($arrayOutput['problem']) >= 200) {
      $pos = strpos($arrayOutput['problem'], ' ', 125);
      $arrayOutput['problem'] = substr($arrayOutput['problem'], 0, $pos);
    }

    $arrayOutput['creation_date'] = date("d.m.Y \\u\\m H:i", $arrayOutput['creation_date']);

    array_push($result, $arrayOutput);
  }

  if ($sqlResult) {
    return $result;
  } else {
    return $sqlResult;
  }
}

/**
 * Deletes Group todo.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $todoID
 * @param $uid
 * @return bool|mysqli_result
 */
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
 * Gets all todos.
 *
 * @author maschneider
 *
 * @param $conn
 * @param $uid
 *
 * @param $request_uri
 *
 * @return array|bool
 * @internal param $id
 */
function getTodos($conn, $uid, $request_uri)
{
  if ($request_uri === '/todo-overview' || $request_uri === '/done-overview'){
    $group_id = 1;
  }

  switch ($request_uri) {
    case '/todo-overview':
      $todo_status = '1';
      break;
    case '/done-overview':
      $todo_status = '0';
  }

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
            FROM todo
             INNER JOIN user u ON(u.id = todo.fk_user)
             INNER JOIN priority p ON (todo.fk_priority = p.id)
             INNER JOIN project pr on todo.fk_project = pr.id
             INNER JOIN `group` g on todo.fk_group = g.id
            WHERE u.id ='" . $uid . "'
            AND g.id ='" . $group_id . "'
            AND todo.todo_status ='" . $todo_status . "'
            ORDER BY todo.fk_priority DESC, todo.creation_date DESC";

  $sqlResult = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $result = array();

  if (mysqli_num_rows($sqlResult) > 0) {
    while ($arrayOutput = mysqli_fetch_array($sqlResult, MYSQLI_ASSOC)) {
      if(strlen($arrayOutput['problem']) >= 200 ) {
        $pos = strpos($arrayOutput['problem'], ' ', 125);
        $arrayOutput['problem'] = substr($arrayOutput['problem'], 0, $pos);
      }

      $arrayOutput['creation_date'] = date("d.m.Y \\u\\m H:i", $arrayOutput['creation_date']);

      array_push($result, $arrayOutput);
    }
  }

  if ($result) {
    return $result;
  } else {
    return $sqlResult;
  }
}

/**
 * Deletes Link with id.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $uid
 * @param $link_id
 * @return bool|mysqli_result
 */
function deleteLink($conn, $uid, $link_id){

  $uid = (int)$uid;

  $sql = "DELETE FROM
            link
          WHERE 
            fk_user = '" . $uid . "'
          AND
            id = '" . $link_id . "'";

  mysqli_begin_transaction($conn);
  $deleteLink = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if($deleteLink){
    mysqli_commit($conn);
    return true;
  }else{
    mysqli_rollback($conn);
    return $deleteLink;
  }

}

/**
 * Updates Link with id and values.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $values
 * @param $uid
 * @return bool|mysqli_result
 */
function updateLink($conn, $values, $uid) {
  $uid = (int)$uid;

  $values['link'] = htmlspecialchars($values['link']);
  $values['link_name'] = htmlspecialchars($values['link_name']);
  $values['link_id'] = htmlspecialchars($values['link_id']);

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
 * Adds link.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $values
 * @param $uid
 * @return bool
 */
function addLink($conn, $values, $uid)
{

  $uid = (int)$uid;

  $values['link'] = htmlspecialchars($values['link']);
  $values['link_name'] = htmlspecialchars($values['link_name']);

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
 * Gets all links.
 *
 * @author vsefa
 *
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
 * Changes status of todo.
 *
 * @author vsefa
 *
 * @param $conn
 * @param $todoStatus
 * @param $uid
 * @param $getId
 *
 * @return bool
 */
function doneTodo($conn, $todoStatus, $uid, $getId)
{

  $sql = "UPDATE
            `todo` 
         SET
            `todo_status` = " . $todoStatus . "
         WHERE `todo`.`id` = '" . $getId . "' AND fk_user = '" . $uid . "'";

  $updateTodoStatus = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($updateTodoStatus) {
    return true;
  } else {
    return $updateTodoStatus;
  }
}

/**
 * @author maschneider
 *
 * @param $conn
 * @param $uid
 * @return array|bool
 */
function countTodoStatus($conn, $uid)
{
  $countedTodos = "SELECT count(*) AS 'countedStatus' FROM todo WHERE todo_status = 1 AND fk_user = '" . $uid . "'";
  $countedDone = "SELECT count(*) AS 'countedStatus' FROM todo WHERE todo_status = 0 AND fk_user = '" . $uid . "'";

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

/**
 * Creates Menu.
 *
 * @author vsefa
 *
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

function createSupportLinksMenu($links) {
  $ul = '<ul>';
  foreach ($links as $link) {
    $ul .= '<li><a target="_blank" href="' . $link['link_url'] . '">' . $link['link_name'] . '<span class="link-id">' . $link['id'] . '</span></a></li>';
  }
  $ul .= '</ul>';
  echo $ul;
}

/**
 * @author maschneider
 *
 * @param $formValues
 * @param $conn
 *
 * @return array
 */
function validateTodoForm($formValues, $conn)
{
$errors = array();
$values = array();

  //Überprüft ob das Feld ausgefüllt ist.
  if(isset($formValues['project']) && $formValues['project'] != '' && $formValues['project'] != '--Bitte wählen--'){
    $values['project'] = htmlspecialchars($formValues['project']);
  }else{
    $errors['project'] = "Bitte wählen Sie ein Projekt aus";
  }

  if (isset($formValues['title']) && $formValues['title'] != '') {
    $values['title'] = htmlspecialchars($formValues['title']);
  }else{
    $errors['title'] = "Bitte einen Titel eingeben";
  }

  if(isset($formValues['problem']) && $formValues['problem'] != ''){
    $values['problem'] = htmlspecialchars(stripScriptTag($formValues['problem']));
  }else{
    $errors['problem'] = "Feld Beschreibung darf nicht leer sein";
  }

  if (isset($formValues['niveau'])){
    $values['niveau'] = (int)$formValues['niveau'];
  } else {
    $errors['niveau'] = "Bitte geben Sie ein Niveau der Aufgabe an.";
  }

  if(isset($formValues['todo-type']) && $formValues['todo-type'] != '' && $formValues['todo-type'] != '--Bitte wählen--'){
    $values['todo-type'] = $formValues['todo-type'];
  }else{
    $errors['todo-type'] = "Bitte wählen Sie die Todo-Zuteilung aus";
  }

  if (isset($formValues['fixed_date']) && $formValues['fixed_date'] != ''){
    $todoDate = strtotime($formValues['fixed_date']);
    if($todoDate > time() - (24 * 60 * 60)){
      $values['fixed_date'] = $todoDate;
    }else{
      $errors['fixed_date'] = "In der Vergangenheit kann keine Aufgabe erledigt werden ;)";
    }
  }else{
    $values['fixed_date'] = 0;
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

function validateAddLinkForm($formValues, $conn, $uid) {
  if (isset($formValues['addlink']) || isset($formValues['update-link'])){
    if (isset($formValues['link_name']) && $formValues['link_name'] != '') {
      $link_name = htmlspecialchars(trim($formValues['link_name']));
      $values['link_name'] = $link_name;
    }else{
      $errors['links_name'] = 'Bitte geben Sie dem Link einen Namen';
    }

    if (isset($formValues['link_id'])) {
      $values['link_id'] = htmlspecialchars($formValues['link_id']);
    }

    if (isset($formValues['link']) && $formValues['link'] != '') {
      $link = htmlspecialchars(trim($formValues['link']));
      $values['link'] = $link;
    }else{
      $errors['link'] = 'Bitte eine URL hinzufügen';
    }

    if (isset($formValues['addlink'])){
      if (empty($errors)){
        $insertResult = addLink($conn, $formValues, $uid);
        if($insertResult === true) {
          redirect('/support-links');
        }else{
          $errors['message'] = $insertResult;
        }
      }else{
        errorMessage($errors);
      }
    }elseif (isset($formValues['update-link'])) {
      if (empty($errors)) {
        $updateResult = updateLink($conn, $values, $uid);
        if ($updateResult === true) {
          redirect('/support-links');
        }else{
          $errors['message'] = $updateResult;
        }
      }else{
        $errors['has_error'] = TRUE;
      }
    }
  }
}
