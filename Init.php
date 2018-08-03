<?php
/**
 * @author Pavel Tsydzik <xagero@gmail.com>
 * @date 03.08.2018 12:26
 */

final class Init
{
    /** @var string */
    private $dsn;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var PDO */
    private $connection = null;

    /**
     * Init constructor
     * @param $dsn
     * @param $username
     * @param $password
     */
    public function __construct($dsn, $username, $password)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $username;

        $this->create();
        $this->fill();
    }

    /**
     * Create db
     */
    private function create()
    {
        $sql = "CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `script_name` varchar(25) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `result` enum('normal','illegal','failed','success') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        try {

            $this->connection = new PDO($this->dsn, $this->username, $this->password, [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $this->connection->exec($sql);

        } catch (Throwable $e) {
            print "Error: " . $e->getMessage();
            //die(1);

        }
    }

    /**
     * Fill data
     */
    private function fill()
    {
        $enum = ['normal', 'illegal', 'failed', 'success'];


        $sql = 'INSERT INTO `test` (`script_name`, `start_time`, `end_time`, `result`)';
        $sql.= 'VALUES (:script_name, :start_time, :end_time, :result)';

        for ($i= 0; $i< 10; $i++) {

            $values = [
                'script_name' => substr(md5(rand()), 0, 7),
                'start_time' => rand(0, 9999),
                'end_time' => rand(0, 9999),
                'result' => $enum[rand(0, sizeof($enum) - 1)]
            ];

            try {

                $stmt = $this->connection->prepare($sql);

                $stmt->bindParam(':script_name', $values['script_name']);
                $stmt->bindParam(':start_time', $values['start_time']);
                $stmt->bindParam(':end_time', $values['end_time']);
                $stmt->bindParam(':result', $values['result']);

                $stmt->execute();

            } catch (Throwable $e) {
                print "Error: " . $e->getMessage();
                //die(1);
            }
        }

    }

    /**
     * @param int $limit
     */
    public function get($limit = 10)
    {
        $sql = "SELECT * FROM test where result IN ('normal', 'success') LIMIT $limit";

        $stmt = $this->connection->prepare($sql);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print_r($row);
            }
        }
    }
}