<?php
    final class Dashboard
    {
        // fields
        public $dataInicio;
        public $dataFim;
        public $numeroVendas;
        public $totalVendas;

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
    $competencia = explode('-', $_GET['competencia']);
    $year = $competencia[0];
    $month = $competencia[1];
    $last_day = cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);

    $data_inicio = $year . '-' . $month . '-01';
    $data_fim = $year . '-' . $month . '-' . $last_day;

    $dashboard = new Dashboard();
    $dashboard->DataInicio($data_inicio);
    $dashboard->DataFim($data_fim);

    $bd = new BD(new Conn(), $dashboard);
    $bd->NumeroVendas();
    $bd->TotalVendas();
    
    echo json_encode($dashboard);
?>