<?php


class Db
{

    private static $instances = [];

    private $db_host;
    private $db_port;
    private $db_name;
    private $db_user;
    private $db_password;
    private $dsn;
    private $opt;


    /**
     * Db constructor.
     */
    protected function __construct()
    {
        $this->db_host = 'localhost';
        $this->db_port = '3306';
        $this->db_name = 'netpeak_test';
        $this->db_user = 'root';
        $this->db_password = 'root';
        $this->dsn = "mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8";
        $this->opt = array (
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

    }

    /**
     *
     */
    protected function __clone()
    {

    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * @return mixed|static
     */
    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {

            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }


    /**
     * @return PDO
     */
    private function pdo()
    {
        try {
            $pdo = new PDO($this->dsn, $this->db_user, $this->db_password, $this->opt);
            return $pdo;
        } catch(PDOException $e) {
            echo "The connection to the database $this->db_name was broken: " . $e->getMessage() . "\n";
            exit;
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneProduct($id)
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute(array($id));
        return $stmt->fetch();
    }


    /**
     * @return array
     */
    public function getAllProducts()
    {
        $stmt = $this->pdo()->query('SELECT * FROM products');
        return $stmt->fetchAll();
    }

    /**
     * @param $name
     * @param $img_path
     * @param $add_name
     * @param $price
     */
    public function insertProduct($name, $img_path, $add_name, $price)
    {
        $stmt = $this->pdo()->prepare('INSERT INTO products SET  name = ?, img_path = ?, add_name = ?, price = ?');
        $stmt->execute(array($name, $img_path, $add_name, $price));
    }

    /**
     * @param $product_id
     * @param $rating
     * @param $add_name
     * @param $text
     */
    public function insertComment($product_id, $rating, $add_name, $text)
    {
        $stmt = $this->pdo()->prepare('INSERT INTO comments SET  product_id = ?, rating = ?, add_name = ?, text = ?');
        $stmt->execute(array($product_id, $rating, $add_name, $text));
    }

    /**
     * @param $product_id
     * @return int
     */
    public function numberOfComments ($product_id)
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM comments WHERE product_id = ?');
        $stmt->execute(array($product_id));
        $count = 0;
        foreach ($stmt as $key => $value) {
            $count++;
        }
        return $count;
    }

    /**
     * @param $product_id
     * @return array
     */
    public function getComments($product_id)
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM comments WHERE product_id = ?');
        $stmt->execute(array($product_id));
        $comments = [];
        foreach ($stmt as $key => $value) {
            $comments[] = $value;
        }
        return $comments;
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function averageRating($product_id)
    {
        $stmt = $this->pdo()->prepare('SELECT AVG(rating) as avgRating FROM comments WHERE product_id = ?');
        $stmt->execute(array($product_id));
        foreach ($stmt->fetch() as $value) {
            return $value;
        }
    }






}