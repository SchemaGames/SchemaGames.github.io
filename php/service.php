<?php

require_once 'mysqlauth2.php';

abstract class SchemaGamesService
{
    /**
     * Common function for services that run and return some kind of data
     */
    abstract public function run();

    /**
     * Obtain data from the database given certain parameters
     * 
     * @param string     $sql             Raw SQL with '?'s to replace with criteria
     * @param array      $inputTypes      Denotes (in order) PDO types of the input fields
     * @param array      $inputFields     Input criteria for the prepared statement
     * @param array|null $outputMapping   Optional array of ("sql_column_name" => "new_field_name") pairs
     * @return array                      Array containing output row arrays with (column => data) pairs                 
     */
    protected function query($sql, array $inputTypes, array $inputFields,array $outputMapping = NULL)
    {
        // Begin by connecting to the SQL database
        try
        {
            $pdo = new PDO(
                'mysql:host=' . MySQLAuth::$servername . ';dbname=' . MySQLAuth::$dbname,
                MySQLAuth::$username,
                MySQLAuth::$password
                );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        // On failed connection, throw error
        catch (PDOException $e)
        {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        // Bind parameters to the prepared statement (types required)
        try
        {
            $stmt = $pdo->prepare($sql);
            for($i = 0; $i < count($inputFields); $i++)
            {
                $stmt->bindValue($i + 1, $inputFields[$i], $inputTypes[$i]);
            }
            unset($i);
        }
        catch(PDOException $e)
        {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        // Execute the query
        $execSuccess = $stmt->execute();
        if(!$execSuccess)
        {
            trigger_error(
                'Wrong SQL:   Error : ' . $stmt->errorCode(),
                E_USER_ERROR);
        }
        else
        {
            // Bundle up results into a return value
            $output = array();
            $remapOutputs = isset($outputMapping);

            // One row at a time, copy data to the output array
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                // Optional output mapping changes field names
                if($remapOutputs)
                {
                    foreach ($outputMapping as $sql_col => $field_name)
                    {
                        if(array_key_exists($sql_col, $row))
                        {
                            // Replace the field for this row
                            $row[$field_name] = $row[$sql_col];
                            unset($row[$sql_col]);
                        }
                    }
                }
                $output[] = $row;
            }
            unset($stmt);
        }
        // Clean up
        unset($pdo);

        return $output;
    }

    /**
     * Outputs given tabular data as JSON
     * 
     * @var array $execOutput   Output of the execute step (tabular data array)
     */
    protected function render($execOutput)
    {
        header('Content-Type: application/json');
        $num_rows = count($execOutput);
        $dataTree = array(
            "total_rows" => $num_rows,
            "rows" => $execOutput
            );
        echo json_encode($dataTree);
    }
}