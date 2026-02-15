<?php

class AdminModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getByUsername($username)
    {
        $this->query("SELECT * FROM admin WHERE username = ?");
        $this->bind("s", $username);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }
}
