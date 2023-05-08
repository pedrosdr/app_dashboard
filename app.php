<?php
    final class Dashboard
    {
        // fields
        private $dataInicio;
        private $dataFim;
        private $numeroVendas;
        private $totalVendas;

        // constructor
        public function __construct()
        {
        }

        // properties
        public function DataInicio($set=null)
        {
            if($set != null)
                $this->dataInicio = $set;
            return $this->dataInicio;
        }

        public function DataFim($set=null)
        {
            if($set != null)
                $this->dataFim = $set;
            return $this->dataFim;
        }

        public function NumeroVendas($set=null)
        {
            if($set != null)
                $this->numeroVendas = $set;
            return $this->numeroVendas;
        }

        public function TotalVendas($set=null)
        {
            if($set != null)
                $this->totalVendas = $set;
            return $this->totalVendas;
        }
    }
?>

<?php
    final class Conn
    {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $password = '';

        public function connect()
        {
            try
            {
                $conn = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    $this->user, 
                    $this->password
                );

                $conn->exec('set charset utf8');

                return $conn;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
    }
?>

<?php
    final class BD 
    {
        // fields
        private PDO $connection;
        private Dashboard $dashboard;

        // constructor
        public function __construct(Conn $connection, Dashboard $dashboard)
        {
            $this->connection = $connection->connect();
            $this->dashboard = $dashboard;
        }

        // properties
        public function Dashboard()
        {
            return $this->dashboard;
        }

        // methods
        public function NumeroVendas() 
        {
            $query = "SELECT COUNT(*) AS numero_vendas FROM tb_vendas 
            WHERE data_venda BETWEEN :data_inicio AND :data_fim";

            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->DataInicio());
            $stmt->bindValue(':data_fim', $this->dashboard->DataFim());
            $stmt->execute();

            $numeroVendas = $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
            $this->dashboard->NumeroVendas($numeroVendas);
            return $numeroVendas;
        }

        public function TotalVendas()
        {
            $query = "SELECT SUM(total) as total_vendas
            FROM tb_vendas
            WHERE data_venda BETWEEN :data_inicio AND :data_fim";

            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->DataInicio());
            $stmt->bindValue(':data_fim', $this->dashboard->DataFim());
            $stmt->execute();

            $totalVendas = $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
            $this->dashboard->TotalVendas($totalVendas);
            return $totalVendas;
        }
    }
?>

<?php
    $dashboard = new Dashboard();
    $dashboard->DataInicio('2018-10-01');
    $dashboard->DataFim('2018-10-31');

    $bd = new BD(new Conn(), $dashboard);

    echo '<pre>';
    print_r($bd->NumeroVendas());
    print_r($bd->TotalVendas());
    print_r($bd->Dashboard());
    echo '</pre>';
?>