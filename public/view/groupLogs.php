<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 29.06.18
 * Time: 19:19
 */

?>

<div class="container">
  <div class="row">
    <h1 class="page-title col-12">Deine Gruppen Logs</h1>
  </div>
  <div class="row">
    <div class="col-6">
      <h3>Info Logs</h3>
      <div class="space"></div>
      <table id="group-log-table">
        <thead>
        <tr>
          <td>Status Nachricht</td>
          <td>Todo Titel</td>
          <td>Bearbeitet von</td>
        </tr>
        </thead>
        <tbody id="group-log-table-body">

        </tbody>
      </table>
    </div>
    <div class="col-6">
      <h3>Anfrage für Löschung</h3>
      <div class="space"></div>
      <table id="group-log-pending-table">
        <thead>
        <tr>
          <td>Todo ID</td>
          <td>Status Nachricht</td>
          <td>Todo Titel</td>
          <td>Angefordert von</td>
          <td>Aktion</td>
        </tr>
        </thead>
        <tbody id="group-log-pending-table-body">

        </tbody>
      </table>
    </div>
  </div>
</div>
