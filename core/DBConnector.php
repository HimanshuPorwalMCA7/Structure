<?php
// Define a class named DBController for database operations
class DBController {
    // Properties for database connection
    private $host = "localhost"; // Database host (e.g., localhost)
    private $user = "root"; // Database user (e.g., root)
    private $password = ""; // Password for the database user
    private $database = "nexgi-intern-cms-blog"; // Name of the database to connect to
    protected $conn; // Property to store the database connection

    // Constructor method
    public function __construct() {
        // Initialize the database connection when the object is created
        $this->conn = $this->connectDB();
    }

    // Private method to establish a database connection
    private function connectDB() {
        try {
            // Create a new PDO instance for MySQL database connection
            $conn = new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
            // Set the PDO error mode to exception for better error handling
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Return the connection object
            return $conn;
        } catch(PDOException $e) {
            // Handle any connection errors
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Public method to execute a query (e.g., SELECT) and return the result
    public function runQuery($query,$params) {
        // Prepare the SQL statement for execution
        $stmt = $this->conn->prepare($query);
        // Execute the prepared statement
        $stmt->execute($params);
        // Return the statement object (with results, if any)
        return $stmt;
    }

    // Public method to execute an insert query with parameters
    public function insertQuery($query, $params) {
        // Prepare the SQL statement for execution
        $stmt = $this->conn->prepare($query);
        // Execute the prepared statement with parameters
        $stmt->execute($params);
        // Return the statement object
        return $stmt;
    }

    // Public method to close the database connection
    public function closeConnection() {
        // Set the connection property to null to close the connection
        $this->conn = null;
    }

    /**
     * Fetches all rows from a given PDO statement.
     *
     * @param PDOStatement $stmt The prepared statement from which to fetch the rows.
     * @return array An array of rows fetched from the database.
     */
    public function fetchAllRows($stmt) {
        // Fetch all rows from the prepared statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rowCount($stmt){
        return $stmt->rowCount();
    }
}