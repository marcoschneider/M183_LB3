<?php



if(isset($_POST['submit'])){
  if(count($errors) != 0){
    echo '<div class="col-5 col-s12">';
      errorMessage($errors);
    echo '</div>';
  }
}
?>
