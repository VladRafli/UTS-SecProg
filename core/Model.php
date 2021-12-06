<?php

class Model
{
    // Get Environment Variables
    private $host;
    private $db;
    private $user;
    private $pass;
    private $conn;
    /**
     * Establish Connection to Database and Start Session
     * 
     * Config on env.php
     */
    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->db = getenv('DB_NAME');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        // Connect to Database
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        // Check if Connection is ok
        if (mysqli_connect_errno()) {
            printf('Connection to database failed: %s\n', mysqli_connect_error());
            exit(1);
        }
    }
    /**
     * Query using Prepared Statement
     * 
     * Reference: https://www.php.net/manual/en/mysqli.prepare.php#107200
     * @param string $query
     * @param false|string $type
     * @param []|array $params
     * @return array|string
     */
    public function query($query, $type = false, $params = [])
    {
        $bindParams = array();
        // Hold value if Query Result is noy one
        $multiQuery = false;
        // Prepare SQL Query
        if ($stmt = mysqli_prepare($this->conn, $query)) {
            // Check Param that inserted
            if (count($params) == count($params, 1)) {
                $params = array($params);
                $multiQuery = false;
            } else {
                $multiQuery = true;
            }
            // Check the type, not really know about the fancy code below
            if ($type) {
                $bindParamsReferences = array();
                $bindParams = array_pad($bindParams, (count($params, 1) - count($params)) / count($params), "");
                foreach ($bindParams as $key => $value) {
                    $bindParamsReferences[$key] = &$bindParams[$key];
                }
                array_unshift($bindParamsReferences, $type);
                $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
                $bindParamsMethod->invokeArgs($stmt, $bindParamsReferences);
            }

            $result = array();
            foreach ($params as $queryKey => $query) {
                foreach ($bindParams as $paramKey => $value) {
                    $bindParams[$paramKey] = $query[$paramKey];
                }
                $queryResult = array();
                // Execute SQL Statement
                if (mysqli_stmt_execute($stmt)) {
                    $resultMetaData = mysqli_stmt_result_metadata($stmt);
                    if ($resultMetaData) {
                        $stmtRow = array();
                        $rowReferences = array();
                        while ($field = mysqli_fetch_field($resultMetaData)) {
                            $rowReferences[] = &$stmtRow[$field->name];
                        }
                        mysqli_free_result($resultMetaData);
                        $bindResultMethod = new ReflectionMethod('mysqli_stmt', 'bind_result');
                        $bindResultMethod->invokeArgs($stmt, $rowReferences);
                        while (mysqli_stmt_fetch($stmt)) {
                            $row = array();
                            foreach ($stmtRow as $key => $value) {
                                $row[$key] = $value;
                            }
                            $queryResult[] = $row;
                        }
                        mysqli_stmt_free_result($stmt);
                    } else {
                        $queryResult[] = mysqli_stmt_affected_rows($stmt);
                    }
                } else {
                    $queryResult[] = false;
                }
                $result[$queryKey] = $queryResult;
            }
            mysqli_stmt_close($stmt);
        } 
        // If SQL Prepare is Error
        else {
            var_dump(mysqli_error($this->conn));
            $result = false;
        }

        if ($multiQuery) {
            return $result;
        } else {
            if ($result !== false) {
                return $result[0];
            } else {
                return "Error";
            }
        }
    }
}
