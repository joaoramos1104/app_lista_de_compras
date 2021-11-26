<?php

    namespace App\Models;
    use MF\Model\Model;

    class Home extends Model{
 
        //pedido
        private $data_criacao;
        private $valor;
        


        public function __get( $atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }


    public function searchHome()
    {

       $query = "   SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_data_filtro,
                    IFNULL(TRUNCATE(AVG(p.valor),2),0) AS media_data_filtro,
                    COUNT(p.numero_pedido) AS qt_pedido_data_filtro,
                    p.data_criacao,
                    l.local
                    FROM tb_pedido AS p
                    INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                    WHERE id_status = 2 AND p.data_criacao BETWEEN :data_inicial AND :data_final AND p.id_loja = :id_loja;

                ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':data_inicial', $this->__get('data_inicial'));
        $stmt->bindValue(':data_final', $this->__get('data_final'));
        $stmt->bindValue(':id_loja', $this->__get('id_loja'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function listarLoja()
    {

        $query ="SELECT id, local FROM tb_loja WHERE id != 1;";

        $stmt = $this->db->query($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
//-----------------------------------------------------------dia
        public function valorDia()
        {
             $query = " SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_dia,
                        IFNULL(TRUNCATE(AVG(p.valor),2),0) AS media_dia,
                        COUNT(p.numero_pedido) AS qt_pedido_dia,
                        (SELECT count(DISTINCT id_cliente) AS qt_cliente_mes_atual FROM tb_pedido WHERE id_status = 2 AND data_criacao = CURDATE()) as qt_cliente
                        FROM tb_pedido AS p
                        INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                        WHERE id_status = 2 AND data_criacao = CURDATE();
                    ";

            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

//-----------------------------------------------------------mes
        public function valorMes()
        {
            $query = "  SELECT IFNULL(TRUNCATE(SUM(valor),2),0) AS valor_mes,
                        IFNULL(TRUNCATE(AVG(valor),2),0) AS media_mes,
                        COUNT(numero_pedido) AS qt_pedido_mes,
                        (SELECT count(DISTINCT id_cliente) AS qt_cliente_mes FROM tb_pedido WHERE id_status = 2 AND DATE_FORMAT(data_criacao, '%m') = DATE_FORMAT(CURDATE(), '%m')) as qt_cliente_mes,
                        DATE_FORMAT(data_criacao, '%m/%Y') AS mes
                        FROM tb_pedido
                        WHERE id_status = 2 AND DATE_FORMAT(data_criacao, '%m') = DATE_FORMAT(CURDATE(), '%m');";
        
            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

 
    public function valoresLojas()
    {
        $query = " SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_dia_atual,
                    IFNULL(TRUNCATE(AVG(p.valor),2),0) AS media_dia_atual,
                    COUNT(p.numero_pedido) AS qt_pedido_dia_atual,
                    count(DISTINCT p.id_cliente) AS qt_cliente_dia_atual,
                    date_format(p.data_criacao ,'%d/%m/%Y') AS dia_atual,
                    l.local
					FROM tb_pedido AS p
                    INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                    WHERE id_status = 2 AND DATE_FORMAT(p.data_criacao, '%d/%m/%Y') = DATE_FORMAT(CURDATE(), '%d/%m/%Y')
                    group by id_loja;
                ";
        $stmt = $this->db->query($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

//Search_home_full

    public function searchHomeFull()
    {

        $query = "  SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_data_filtro,
                    IFNULL(TRUNCATE(AVG(p.valor),2),0) AS media_data_filtro,
                    COUNT(p.numero_pedido) AS qt_pedido_data_filtro,
                    l.local,
                    count(DISTINCT p.id_cliente) AS qt_cliente_data_filtro
                    FROM tb_pedido AS p
                    INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                    WHERE id_status = 2 AND p.data_criacao BETWEEN :data_inicial AND :data_final
                    GROUP BY id_loja;
                ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':data_inicial', $this->__get('data_inicial'));
        $stmt->bindValue(':data_final', $this->__get('data_final'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function valoresLojasTotal()
    {
        $query = "  SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_data_filtro,
                    IFNULL(TRUNCATE(AVG(p.valor),2),0) AS media_data_filtro,
                    COUNT(p.numero_pedido) AS qt_pedido_data_filtro,
                    count(DISTINCT p.id_cliente) AS qt_cliente_data_filtro
                    FROM tb_pedido AS p
                    INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                    WHERE id_status = 2 AND p.data_criacao BETWEEN :data_inicial AND :data_final;
                    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':data_inicial', $this->__get('data_inicial'));
        $stmt->bindValue(':data_final', $this->__get('data_final'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
   

    public function valorFiltro()
    {
        $query = "  SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_mes_atual,
                    IFNULL(TRUNCATE(AVG(p.valor),2),'0') AS media_mes_atual,
                    COUNT(p.numero_pedido) AS qt_pedido_mes_atual,
                    count(DISTINCT p.id_cliente) AS cliente_data_filtro,
                    p.data_criacao
                    FROM tb_pedido AS p
                    INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                    WHERE p.id_status = 2 AND p.data_criacao BETWEEN :data_inicial AND :data_final;";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':data_inicial', $this->__get('data_inicial'));
        $stmt->bindValue(':data_final', $this->__get('data_final'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function valorAcumuladoAno()
    {
        $query = "  SELECT IFNULL(TRUNCATE(SUM(p.valor),2),0) AS valor_ano_atual,
                    IFNULL(TRUNCATE(AVG(p.valor),2),0) AS media_ano_atual,
                    COUNT(p.numero_pedido) AS qt_pedido_ano_atual,
                    date_format(p.data_criacao ,'%Y') AS data_criacao,
                    count(DISTINCT p.id_cliente) AS cliente_ano
                    FROM tb_pedido AS p
                    INNER JOIN tb_loja AS l ON (l.id = p.id_loja)
                    WHERE id_status = 2 AND DATE_FORMAT(p.data_criacao, '%y') = DATE_FORMAT(:data_inicial, '%y');
                ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':data_inicial', $this->__get('data_inicial'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
