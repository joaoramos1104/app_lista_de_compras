<?php

    namespace App\Models;
    use MF\Model\Model;

    class Pedido extends Model{

        private $id;
        private $id_cliente;
        private $id_loja;
        private $id_status;
        private $valor;
        private $detalhe_pedido;
        private $cpf;
        private $nome;
        private $codigo_produto;
        private $descricao_produto;
        private $valor_un;
        private $qtd_produto;
        private $valor_total;
        private $numero_pedido;

        private $forma_pagamento;
        private $descricao_forma_pagamento;
        private $num_pdv;
        private $num_cupom;
        private $data_criacao;
      
        public function __get( $atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }

        

        //recuperar
        public function listar_pedidos()
        {
            $query = "  SELECT p.numero_pedido, c.nome, c.cpf, l.local, s.descricao, cast(p.valor as DECIMAL(10,2)) AS valor,  DATE_FORMAT(p.data_criacao, '%d/%m/%Y') AS data_criacao 
                        FROM tb_pedido AS p
                        INNER JOIN tb_cliente AS c ON (p.id_cliente = c.id)
                        INNER JOIN tb_loja AS l ON (p.id_loja = l.id)
                        INNER JOIN tb_status AS s ON (p.id_status = s.id)
                    ";
            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }
        
        //listar
        public function listarDetalhe()
        {
            $query = "  SELECT p.numero_pedido, cast(p.valor as DECIMAL(10,2)) AS valor, DATE_FORMAT(p.data_criacao, '%d/%m/%Y') AS data_criacao, p.forma_pagamento, f.descricao_forma_pagamento,
                        d.detalhe_pedido,
                        c.id, c.nome, c.cpf, c.email,
                        t.telefone, t.tipo,
                        e.rua, e.numero, e.bairro, e.cidade, e.uf, e.referencia,
                        l.nome_loja, l.local, l.cnpj,
                        s.descricao
                        FROM tb_pedido AS p
                        LEFT JOIN tb_detalhe_pedido AS d ON (d.numero_pedido = p.numero_pedido)
                        INNER JOIN tb_cliente AS c ON (c.id = p.id_cliente)
                        INNER JOIN tb_telefone AS t ON (t.id_cliente = c.id)
                        INNER JOIN tb_endereco AS e ON (e.id_cliente = c.id)
                        INNER JOIN tb_loja AS l ON (p.id_loja = l.id)
                        INNER JOIN tb_status AS s ON (s.id = p.id_status)
                        INNER JOIN tb_forma_pagamento AS f ON (f.cod_forma_pagamento = p.forma_pagamento)
                        WHERE p.numero_pedido = :numero_pedido 

                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido',$this->__get('numero_pedido'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function busca_cliente_cpf()
        {
            $query = "  SELECT c.id AS id_cliente , c.nome, c.cpf, l.id AS id_loja, l.local, e.cep, e.rua, e.numero, e.referencia, e.bairro, e.cidade, e.uf 
                        FROM tb_cliente AS c
                        INNER JOIN tb_loja AS l ON (l.id = c.id_loja)
                        INNER JOIN tb_endereco AS e ON (e.id_cliente = c.id)
                        WHERE c.cpf = :cpf";
             
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':cpf',$this->__get('cpf'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        
        public function listarLoja()
        {
        $query = "  SELECT id AS id_loja, nome_loja, local 
                    FROM tb_loja WHERE id != 1;";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        }

        //Pesquisar Cliente
        public function pesquisarClientePedido()
        {
            $query = "  SELECT c.nome AS nome_cliente, c.cpf AS cpf_cliente, l.local AS local_cliente
                        FROM tb_cliente AS c
                        INNER JOIN tb_loja AS l ON (c.id_loja = l.id);
                        ";
            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //Pesquisar Produto
        public function pesquisarProduto()
        {
            $query = "  SELECT codigo_produto, descricao_produto, valor_produto 
                        FROM tb_produto;
                    ";

            //mysql externo
            // $query = "SELECT codigo_ean AS codigo_produto, descricao AS descricao_produto, preco AS valor_produto FROM concentrador.cons_verificador_preco_gertec;";

            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function buscarProduto()
        {
            $query = "  SELECT codigo_produto, descricao_produto, valor_produto 
                        FROM tb_produto
                        WHERE codigo_produto = :codigo";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':codigo', $this->__get('codigo'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);

            //mysql externo 
            // $query = "SELECT codigo_ean AS codigo_produto, descricao AS descricao_produto, preco AS valor_produto FROM concentrador.cons_verificador_preco_gertec
            //             WHERE codigo_ean = :codigo";

            // $stmt = $this->db->prepare($query);
            // $stmt->bindValue(':codigo', $this->__get('codigo'));
            // $stmt->execute();
            // return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

         public function inserir_produto()
        {
            $query = "  INSERT INTO tb_produto_pedido 
                        (numero_pedido, codigo_produto, descricao_produto, valor_un, qtd_produto, valor_total)
                        VALUES
                        (:numero_pedido, :codigo_produto, :descricao_produto, :valor_un, :qtd_produto, :valor_total);

                        SELECT id, numero_pedido, codigo_produto, descricao_produto, valor_un, qtd_produto, valor_total 
                        FROM tb_produto_pedido 
                        WHERE numero_pedido = :numero_pedido;
                    ";
                                            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':codigo_produto', $this->__get('codigo_produto'));
            $stmt->bindValue(':descricao_produto', $this->__get('descricao_produto'));
            $stmt->bindValue(':valor_un', $this->__get('valor_un'));
            $stmt->bindValue(':qtd_produto', $this->__get('qtd_produto'));
            $stmt->bindValue(':valor_total', $this->__get('valor_total'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function cadastro_pedido()
        {
            $query = "  SELECT AUTO_INCREMENT AS numero_pedido
                        FROM   information_schema.tables
                        WHERE  table_name = 'tb_pedido'
                        AND    table_schema = 'db_delivery';
                                
                        INSERT INTO tb_pedido
                        ( id_cliente, id_loja, data_criacao)
                        VALUES
                        (:id_cliente, :id_loja, CURRENT_DATE());
                    ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_cliente', $this->__get('id_cliente'));
            $stmt->bindValue(':id_loja', $this->__get('id_loja'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        
        public function listarProdutos()
        {
            $query = "  SELECT numero_pedido, codigo_produto, descricao_produto, cast(valor_un AS DECIMAL(10,2)) AS valor_un, qtd_produto, cast(valor_total AS DECIMAL(10,2)) AS valor_total
                        FROM tb_produto_pedido
                        WHERE numero_pedido = :numero_pedido;";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

            
            public function updateStatus()
            {
                
            $query = "   UPDATE tb_pedido
                            SET id_status = :status
                            WHERE numero_pedido = :numero_pedido; 
                        ";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':status', $this->__get('status'));
                $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
                $stmt->execute();
                return $this;

            }

            public function excluirPedido()
            {
                $query = "  DELETE FROM tb_pedido 
                            WHERE numero_pedido = :numero_pedido;
                        ";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
                $stmt->execute();
                return $this;

            }


        public function atualizaValorPedido()
        {
            $query = "  UPDATE tb_pedido SET valor = :valor 
                        WHERE numero_pedido = :numero_pedido;
                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':valor', $this->__get('valor'));
            $stmt->execute();
            return $this;
        }

        public function alterarData()
        {
            $query = "  UPDATE tb_pedido SET data_criacao = :data 
                            WHERE numero_pedido = :numero_pedido;
                        ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':data', $this->__get('data'));
            $stmt->execute();
            return $this;
        }
    
        public function listarFormaPagamento()
        {
            $query = "  SELECT cod_forma_pagamento, descricao_forma_pagamento
                        FROM tb_forma_pagamento;
                    ";
            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        //Atualizar Loja pedido
        public function atualizaLojaPedido()
        {
            $query = "  UPDATE tb_pedido SET id_loja = :id_loja 
                        WHERE numero_pedido = :numero_pedido;
                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':id_loja', $this->__get('id_loja'));
            $stmt->execute();
            return $this;
        }

        //Inserir Forma de Pagamento
        public function inserirFormaPagamento()
        {
            $query = "  UPDATE tb_pedido SET forma_pagamento = :forma_pagamento 
                        WHERE numero_pedido = :numero_pedido;
                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':forma_pagamento', $this->__get('forma_pagamento'));
            $stmt->execute();
            return $this;
        }

        //salvar
        public function finalizar_pedido_detalhe()
        {
            $query = "  INSERT INTO tb_detalhe_pedido
                        (numero_pedido, detalhe_pedido)
                        VALUES
                        (:numero_pedido, :detalhe_pedido);
                        ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':detalhe_pedido', $this->__get('detalhe_pedido'));
            $stmt->execute();
            return $this;
            
        }
        public function finalizar_pedido_status()
        {
            $query = "  UPDATE tb_pedido SET id_status = 1, valor = (SELECT SUM(valor_total) AS vtotal FROM tb_produto_pedido WHERE numero_pedido = :numero_pedido) 
                        WHERE numero_pedido = :numero_pedido;
                    ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->bindValue(':detalhe_pedido', $this->__get('detalhe_pedido'));
            $stmt->execute();
            return $this;
        }


        public function listar_produto_pedido()
        {
            $query = "  SELECT id, numero_pedido, codigo_produto, descricao_produto, cast(valor_un AS DECIMAL(10,2)) AS  valor_un, qtd_produto, cast(valor_total AS DECIMAL(10,2)) AS valor_total 
                        FROM tb_produto_pedido 
                        WHERE numero_pedido = :numero_pedido;
                    ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numero_pedido', $this->__get('numero_pedido'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }


}

        
