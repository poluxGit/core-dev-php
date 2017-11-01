<?php

namespace Core\Tasks;

/**
 * Abstract Class ATask
 *
 * Represents mutualized Task Objects behaviour.
 */
abstract class ATask {

  protected $_UID = null;

  protected $_percent = 0.0;

  protected $_status = null;

  abstract public function createTask();

  abstract public function startTask();

  abstract public function finishTask();

}//end class

?>
