<?php


/**
 * Class PDOMySQL driver
 *
 * PDOMySQL::setOption(
 *       array(
 *           "host" => 'localhost',
 *           "user" => 'root',
 *           "pass" => '1234',
 *           "database" => 'cyc',
 *           "charset" => "utf8"
 *       )
 *   );
 */
class PDOMySQL
{


    /**
     * The connection options array
     * @var array
     */
    public static $connectionOptions = array(
        'host' => 'localhost',
        'port' => 3306,
        'user' => null,
        'pass' => null,
        'database' => null,
        'charset' => "utf8"
    );

    /**
     * The DB Instance
     * @var object
     */
    private static $instance;

    /**
     *
     * @var object pdo
     */
    private $pdo = null;

    /**
     * Query params
     * @var array
     */
    private $params;

    /**
     * query string
     * @var string
     */
    private $sql;

    /**
     *
     * @var object STMT
     */
    private $stmt;

    /** Define Class name */
    const CLASSNAME = 'PDOMySQL';
    const MYSQLDEBUG = 'PDOMySQLDEBUG';

    /**
     * Get the static instance, if it is not set
     * @return object
     */
    public static function getInstance()
    {
        return isset(static::$instance) ? static::$instance : static::$instance = new static();
    }

    /**
     * set conection params
     *
     *
     * @param array $options
     * @return boolean return true if successfully set options
     */
    public static function setOption(array $options)
    {
        if (self::$instance !== null) {
            echo("Not allowed in this time, because instance is already created");
            return false;
        }

        /** Host */
        if (isset($options['host'])) {
            self::$connectionOptions['host'] = $options['host'];
        }

        /** User */
        if (isset($options['user'])) {
            self::$connectionOptions['user'] = $options['user'];
        }

        /** Password */
        if (isset($options['pass'])) {
            self::$connectionOptions['pass'] = $options['pass'];
        }
        /** Database */
        if (isset($options['database'])) {
            self::$connectionOptions['database'] = $options['database'];
        }
        /** port */
        if (isset($options['port'])) {
            self::$connectionOptions['port'] = $options['port'];
        }
        /** Charset */
        if (isset($options['charset'])) {
            self::$connectionOptions['charset'] = $options['charset'];
        }

        return true;
    }

    /**
     * The factory object
     * @param array $databaseOptions The options
     * @return object The static object
     */
    public static function Factory(array $databaseOptions)
    {
        return new static($databaseOptions);
    }

    /**
     * Constructor
     * @param array $connectionOptions The connection options
     */
    private function __construct(array $connectionOptions = array())
    {

        $this->prepereConnectionOptions($connectionOptions);
        $this->db_connect();
    }

    /**
     * Prepares the connection options in order to be executed
     * @param  array $connectionOptions The connection options
     *
     * @throws Exception If any of the required options is not set, throw
     *                 exception
     *
     * @return array                    Sets the options as a property
     */
    private function prepereConnectionOptions($connectionOptions)
    {
        if ($connectionOptions) {
            //host
            if (!isset($connectionOptions['host'])) {
                throw new \Exception("Host not set");
            } else {
                self::$connectionOptions['host'] = $connectionOptions['host'];
            }
            //user
            if (!isset($connectionOptions['user'])) {
                throw new \Exception("User not set");
            } else {
                self::$connectionOptions['user'] = $connectionOptions['user'];
            }
            //pass
            if (!isset($connectionOptions['pass'])) {
                throw new \Exception("Password not set");
            } else {
                self::$connectionOptions['pass'] = $connectionOptions['pass'];
            }
            //database
            if (!isset($connectionOptions['database'])) {
                throw new \Exception("Database not set");
            } else {
                self::$connectionOptions['database'] = $connectionOptions['database'];
            }
            //charset
            if (!isset($connectionOptions['port'])) {
                self::$connectionOptions['port'] = '3306';
            } else {
                self::$connectionOptions['port'] = $connectionOptions['port'];
            }
            //charset
            if (!isset($connectionOptions['charset'])) {
                self::$connectionOptions['charset'] = 'utf8';
            } else {
                self::$connectionOptions['charset'] = $connectionOptions['charset'];
            }
        }
    }

    /**
     * Connection to the Database
     *
     * @throws Exception If cannot connect, throw Exception
     */
    private function db_connect()
    {
        /** DNS */
        $dsn = 'mysql:host=' . self::$connectionOptions['host'] . ';port=' . self::$connectionOptions['port'] . ';dbname=' . self::$connectionOptions['database'];

        /** Options */
        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names " . self::$connectionOptions['charset'],
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false
        );
        try {
            /** Connect */
            $this->pdo = new \PDO($dsn, self::$connectionOptions['user'], self::$connectionOptions['pass'], $options);
        } catch (PDOException $e) {
            /** Can't Connect */
            echo ("Can't connect to PDOMySQL : {" . self::$connectionOptions['host'] . "}<br />\n" . $e->getMessage());
        }
    }

    /**
     * Prepare statement for the PDO
     * @param  string $sql     The SQL Query
     * @param  array  $params  Params which to prepare
     * @param  array  $options Options for the prepare (if any)
     *
     *
     * @return object          The SQL resource
     */
    public function prepare($sql, array $params = array(), $options = array())
    {
//        /** Log */
//        $log = "Prepere\n"
//                . "Query: " . $sql . "\n"
//                . "Params: " .print_r($params, true);
////        echo $log

        /** Prepare */
        $this->stmt = $this->pdo->prepare($sql);

        /** Parameters */
        $this->params = $params;

        /** SQL */
        $this->sql = $sql;
        return $this;
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from 
     * $params are are in the same order as specified in $query
     *
     * @return string The interpolated query
     */
    private function interpolateQuery()
    {
        $query = $this->sql;
        $params = $this->params;

        $keys = array();

        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }
        }

        $query = preg_replace($keys, $params, $query, 1, $count);

        return $query;
    }

    /**
     * Execute the SQL statement
     * @param  array  $params Parameters
     * @return object The SQL resource
     */
    public function execute($params = null)
    {
        /** Set parameters */
        if ($params) {
            $this->params = $params;
        }

        /** Execute */
        try {
            $this->stmt->execute($this->params);
        } catch (\PDOException $e) {
            echo ('[ERROR]: ' . $e->getMessage() . "\n" . '[SQL]: ' . $this->interpolateQuery());
        }

        return $this;
    }

    /**
     * Fetch the result as an associative array indexed by column name
     * @return array result
     */
    public function fetchAllAssoc()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Fetch the resulted row as an associative array indexed by column name
     * @return array result
     */
    public function fetchRowAssoc()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Fetch the result as an array indexed by column number, returned in your
     * result set, starting at column 0
     * @return array result
     */
    public function fetchAllNum()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_NUM);
    }

    /**
     * Fetch the row as an array indexed by column number, returned in your
     * result set, starting at column 0
     * @return array result
     */
    public function fetchRowNum()
    {
        return $this->stmt->fetch(\PDO::FETCH_NUM);
    }

    /**
     * Returns an anonymous object with property names that correspond to the
     * column names returned in your result set
     * @return object result
     */
    public function fetchAllObj()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Returns an anonymous object with property names that correspond to the
     * column names returned in your result set
     * @return object result
     */
    public function fetchRowObj()
    {
        return $this->stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Returns a single column from the next row of a result set
     * @param  integer $column The column number
     * @return array           result
     */
    public function fetchAllColumn($column)
    {
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN, $column);
    }

    /**
     * Returns a single column from the next row of a result set
     * @param  integer $column The column number
     * @return array           result
     */
    public function fetchRowColumn($column)
    {
        return $this->stmt->fetch(\PDO::FETCH_COLUMN, $column);
    }

    /**
     * Returns a new instance of the $class, mapping the columns of the result
     * set to named properties in the class
     * @param  string $class The class name
     * @return object
     */
    public function fetchAllClass($class)
    {
        return $this->stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    /**
     * Returns a new instance of the $class, mapping the first columns of the
     * result set to named property in the class
     * @param  string $class The class name
     * @return object
     */
    public function fetchRowClass($class)
    {
        return $this->stmt->fetch(\PDO::FETCH_CLASS, $class);
    }

    /**
     * Get the last modified insert ID
     * @return integer The ID
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Returns the number of rows affected by the last SQL statement
     * @return integer
     */
    public function getAffectedRows()
    {
        return $this->stmt->rowCount();
    }

    /**
     * Get the instance of the statement, in order to customize the result, or
     * execute another Query
     * @return object   The statement object
     */
    public function getSTMT()
    {
        return $this->stmt;
    }

}
