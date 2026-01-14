<?php

trait Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    protected $conn;
    protected $stmt;
    protected $error;

    protected function connect()
    {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            if ($this->conn->connect_error) {
                $this->error = $this->conn->connect_error;
                echo "Database connection failed: " . $this->error;
                return false;
            }
            return true;
        } catch (mysqli_sql_exception $e) {
            $this->error = $e->getMessage();
            echo "Database connection failed: " . $this->error;
            return false;
        }
    }

    public function query($sql)
    {
        if (!isset($this->conn)) {
            if (!$this->connect()) {
                echo "Unable to establish database connection: " . $this->error;
                die();
            }
        }
        $this->stmt = $this->conn->prepare($sql);

        if (!$this->stmt) {
            echo "Prepare failed: " . $this->conn->error;
            die();
        }
    }

    public function bind($types, ...$params)
    {
        if (!$this->stmt) {
            echo "No statement prepared.";
            die();
        }
        $this->stmt->bind_param($types, ...$params);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultSet()
    {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function single()
    {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_assoc();
    }

    public function rowCount()
    {
        return $this->stmt->affected_rows;
    }

    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }

    public function getError()
    {
        return $this->error;
    }

    public function deleteById($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = ?";
        $this->query($sql);
        $this->bind("i", $id);
        return $this->execute();
    }

    public function findById($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = ?";
        $this->query($sql);
        $this->bind("i", $id);
        return $this->single();
    }

    public function findByColumn($table, $column, $value, $type = "s")
    {
        $sql = "SELECT * FROM $table WHERE $column = ?";
        $this->query($sql);
        $this->bind($type, $value);
        return $this->single();
    }

    public function findAllByColumn($table, $column, $value, $type = "s")
    {
        $sql = "SELECT * FROM $table WHERE $column = ?";
        $this->query($sql);
        $this->bind($type, $value);
        return $this->resultSet();
    }

    /**
     * Unconcat GROUP_CONCAT results into arrays
     * @param array $data - The data array containing concatenated strings
     * @param array $fields - Array of field names to unconcat (default: null)
     * @return array - Data with unconcatenated arrays
     */
    public function unconcatGroupFields($data, $fields = null)
    {
        if (!is_array($data)) {
            return $data;
        }
        if ($fields === null) {
            $fields = [];
            foreach ($data as $key => $value) {
                if (is_string($value) && strpos($value, ',') !== false) {
                    $fields[] = $key;
                }
            }
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                if ($data[$field] === null || $data[$field] === '') {
                    $data[$field] = [];
                } else {
                    $data[$field] = array_filter(explode(',', $data[$field]));
                }
            }
        }

        return $data;
    }

    public function close()
    {
        if ($this->stmt) {
            $this->stmt->close();
        }
        if ($this->conn) {
            $this->conn->close();
        }
    }

   
}
