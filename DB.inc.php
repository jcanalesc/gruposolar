<?

class DB {

    private $_queue = array();
    private $_queue_lock = false;
    private $_queue_iterator = 0;
    private $_host = "";
    private $_user = "";
    private $_pass = "";
    private $_db = "";
    private $_connection = null;
    private $_last_queryexecresult = null;
    private $_last_queryfields = array();

    private function init() { // say something
        $this->_connection = mysql_connect($this->_host, $this->_user, $this->_pass);
        mysql_select_db($this->_db, $this->_connection);
    }

    private function error($query) {
        $err = mysql_errno($this->_connection).": MySQL Error: " . mysql_error($this->_connection) . ") on query: $query\n";
        throw new Exception($err);
    }

    public function query($query, $lock = false) { // awesome
        /*
          if ($lock === true)
          {
          $locked_tables = array();
          rematelog("Expresiones: ".implode("//", $expr_array));
          // mysql_query("LOCK TABLES ".$locked_tables_string);
          }
         */
        $res = mysql_query($query, $this->_connection);
        if ($res === false) {
            $this->error($query);
            return false;
        }
        $rows = array();
        if (substr(strtolower($query), 0, strlen("select")) != "select")
            return true;
        while (($row = mysql_fetch_assoc($res)) !== false)
            $rows[] = $row;
        mysql_free_result($res);
        /*
          if ($lock === true)
          mysql_query("UNLOCK TABLES");
         */
        return $rows;
    }

    public function queue_add($fields, $tables, $conditions = null, $modifiers = null) {
        $fields_arr = array();
        foreach ($fields as &$field) {
            $pair = explode(" as ", $field);
            if (count($pair) == 1)
                $fields_arr[] = trim($field);
            else {
                $fields_arr[] = trim($pair[1]);
                $field = trim($pair[0]);
            }
        }
        $this->_queue[] = array('fields' => $fields, 'tables' => $tables, 'conditions' => $conditions, 'modifiers' => $modifiers);
        $this->_last_queryfields[] = $fields_arr;
    }

    public function queue_clear() {
        $this->_queue = array();
        $this->_last_queryfields = array();
        $this->_queue_iterator = 0;
        $this->_last_queryexecresult = null;
    }

    public function queue_exec($lock = false) {
        $max_fields_size = 0;
        foreach ($this->_queue as $row) {
            $cnt = count($row['fields']);
            if ($cnt > $max_fields_size) {
                $max_fields_size = $cnt;
            }
        }
        $normalized_queries = array();
        $query_number = 0;
        foreach ($this->_queue as $row) {
            $norm_row = array('fields' => array("$query_number as queryn"), 'tables' => null, 'conditions' => null, 'modifiers' => null);
            $added_rows = $max_fields_size - count($row['fields']);
            for ($i = 0; $i < $added_rows; $i++)
                $row['fields'][] = "''";
            for ($i = 1; $i <= $max_fields_size; $i++)
                $norm_row['fields'][$i] = $row['fields'][$i - 1] . " as data$i";
            $norm_row['tables'] = $row['tables'];
            $norm_row['conditions'] = $row['conditions'];
            $norm_row['modifiers'] = $row['modifiers'];
            $normalized_queries[] = $norm_row;
            $query_number++;
        }

        $constructed_queries = array();
        foreach ($normalized_queries as $q) {
            $conds = "";
            $modifiers = $q['modifiers'];
            if ($q['conditions'] != null && count($q['conditions']) > 0)
                $conds = implode(" and ", $q['conditions']);
            if (strlen($conds) > 0)
                $conds = " where " . $conds;
            $constructed_queries[] = "select " . implode(",", $q['fields']) . " from " . implode(",", $q['tables']) . $conds . " $modifiers";
        }

        $final_query = implode(" union all ", $constructed_queries);
        $this->_last_queryexecresult = $this->query($final_query, $lock);

        // print_r($this->_last_queryexecresult);
        // echo "fin de" . __FUNCTION__;
    }

    public function queue_fetch() {
        $recover = array();
        if ($this->_queue_iterator == count($this->_queue))
            return null;
        $queryn = $this->_queue_iterator;
        $delete_rows = 0;
        foreach ($this->_last_queryexecresult as $row) {
            if ($queryn == $row['queryn']) {
                $restored_row = array();
                for ($k = 0; $k < count($this->_last_queryfields[$this->_queue_iterator]); $k++) {
                    $restored_row[$this->_last_queryfields[$this->_queue_iterator][$k]] = $row['data' . ($k + 1)];
                }
                $recover[] = $restored_row;
                $delete_rows++;
            }
        }
        $this->_queue_iterator++;
        return $recover;
    }

    public function __destruct() {
        mysql_close($this->_connection);
    }

    public function __construct($host, $user, $passwd, $db) {
        $this->_host = $host;
        $this->_user = $user;
        $this->_pass = $passwd;
        $this->_db = $db;
        $this->init();
    }

}

global $db;
$db = new DB("localhost", "gruposolaruser", "p0r74lr3m4T","gruposolar");
?>
