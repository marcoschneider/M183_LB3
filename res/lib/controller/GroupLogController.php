<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 30.06.18
 * Time: 13:39
 */

class GroupLogController {

  private $groupLogModel;

  public function __construct(GroupLogModel $groupLogModel) {
    $this->groupLogModel = $groupLogModel;

  }


}