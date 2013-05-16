<?php
/**
 * Description of DB
 *
 * @author cavard
 */
require_once '../configuration.php';

class DB {

  private $blnConnected = FALSE;
  private $mysqli = null;
  private $dbdata = null;

  public function __destruct () {
    if ($this->blnConnected) {
      $this->Disconnect($param);
    }
  }

  public function __construct () {

    if (defined('DB_CONNECTION')) {
      $this->dbdata = unserialize(DB_CONNECTION);

      if (is_array($this->dbdata)) {
        if (!array_key_exists('server', $this->dbdata)) {
          throw new Exception('server not defined.');
          exit();
        }
        if (!array_key_exists('username', $this->dbdata)) {
          throw new Exception('username not defined.');
          exit();
        }
        if (!array_key_exists('password', $this->dbdata)) {
          throw new Exception('password not defined.');
          exit();
        }
        if (!array_key_exists('database', $this->dbdata)) {
          throw new Exception('database not defined.');
          exit();
        }
        if (!array_key_exists('encoding', $this->dbdata)) {
          throw new Exception('encoding not defined.');
          exit();
        }
        return;
      }
      else {
        throw new Exception('DB_CONECTION is invalid.');
        exit();
      }
    }
    else {
      throw new Exception('DB_CONECTION not defined.');
      exit();
    }
  }

  public function Connect () {
    // Connect to the Database Server
    $this->mysqli = new mysqli($this->dbdata['server'],
        $this->dbdata['username'],
        $this->dbdata['password'],
        $this->dbdata['database']
    );

    if (!$this->mysqli)
      throw new Exception("Unable to connect to Database");

    if ($this->mysqli->error)
      throw new Exception($this->mysqli->error, $this->mysqli->errno, null);

    // Update "Connected" Flag
    $this->blnConnected = true;

    // Set to AutoCommit
    $this->NonQuery('SET AUTOCOMMIT=1;');

    // Set NAMES (if applicable)
    $this->NonQuery('SET NAMES ' . $this->dbdata['encoding'] . ';');
  }

  public function Disconnect () {

    if ($this->blnConnected) {
      $this->mysqli->close();
      $this->blnConnected = false;
    }
  }

  public function Query ($strQuery) {
    // Connect if Applicable
    if (!$this->blnConnected)
      $this->Connect();

    // Perform the Query
    $result = $this->mysqli->query($strQuery);
    if ($this->mysqli->error)
      throw new Exception($this->mysqli->error, $this->mysqli->errno);

    $response = array();
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $response[] = $row;
    }

    return $response;
  }

  public function BeginTransaction () {
    if ($this->blnConnected) {
      // Set to AutoCommit to FALSE
      $this->NonQuery('SET AUTOCOMMIT=0;');
    }
  }

  public function Commit () {
    if ($this->blnConnected) {
      $this->mysqli->commit();
      $this->NonQuery('SET AUTOCOMMIT=0;');
      return;
    }
    throw new Exception("Can't commit if not connected.");
  }

  public function Rollback () {
    if ($this->blnConnected) {
      $this->mysqli->rollback();
      $this->NonQuery('SET AUTOCOMMIT=0;');
      return;
    }
    throw new Exception("Can't rollback if not connected.");
  }

  public function NonQuery ($strNonQuery) {
    // Connect if Applicable
    if (!$this->blnConnected)
      $this->Connect();

    // Perform the Query
    $this->mysqli->query($strNonQuery);

    if ($this->mysqli->error){
      throw new Exception($this->objMySqli->error, $this->objMySqli->errno);
      exit();
    }
    return $this->mysqli->insert_id;
  }
}

?>
