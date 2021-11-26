<?php

    namespace App\Models;
    use MF\Model\Model;

    class Cliente extends Model{
        //cliente
        private $id;
        private $nome;
        private $email;
        private $cpf;
        private $id_loja;
        //endereco
        private $cep;
        private $rua;
        private $numero;
        private $referencia;
        private $bairro;
        private $cidade;
        private $uf;
        //telefone
        private $telefone;
        private $tipo;
      

        public function __get( $atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }

    public function listarLojas()
    {
        $query ="   SELECT id AS id_loja, nome_loja, local FROM tb_loja;
                ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

//listar
        public function listarCliente(){
            $query = "   SELECT c.id, c.nome, c.cpf, IFNULL(c.email,'Não possui E-mail') AS email, l.local, t.telefone, DATE_FORMAT(c.data_cadastro, '%d/%m/%Y') AS data_cadastro
                        FROM tb_cliente AS c
                        INNER JOIN tb_loja AS l ON (c.id_loja = l.id)
                        INNER JOIN tb_telefone AS t ON (t.id_cliente = c.id)
                    "; 
            $stmt= $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        

//salvar
        public function salvar()
        {
            $query = "  INSERT INTO tb_cliente 
                        (nome, email, cpf, id_loja )
                        VALUES
                        (:nome, :email, :cpf, :id_loja);

                        SELECT @id := LAST_INSERT_ID();

                        INSERT INTO tb_endereco
                        (cep, rua, numero, referencia, bairro, cidade, uf, id_cliente)
                        VALUES
                        (:cep, :rua, :numero, :referencia, :bairro, :cidade, :uf, @id);
                        INSERT INTO tb_telefone
                        (telefone, tipo, id_cliente)
                        VALUES
                        (:telefone, :tipo,  @id)
                    ";


            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':cpf', $this->__get('cpf'));
            $stmt->bindValue(':id_loja', $this->__get('id_loja'));
            //endereco
            $stmt->bindValue(':cep', $this->__get('cep'));
            $stmt->bindValue(':rua', $this->__get('rua'));
            $stmt->bindValue(':numero', $this->__get('numero'));
            $stmt->bindValue(':referencia', $this->__get('referencia'));
            $stmt->bindValue(':cidade', $this->__get('cidade'));
            $stmt->bindValue(':bairro', $this->__get('bairro'));
            $stmt->bindValue(':uf', $this->__get('uf'));
            //telefone
            $stmt->bindValue(':telefone', $this->__get('telefone'));
            $stmt->bindValue(':tipo', $this->__get('tipo'));
            $stmt->execute();
            return $this;

        }
//validar cadastro sem valor vazio
        
        public function validarCadastro()
        {
            $validar = true;

            if (strlen($this->__get('nome'))< 3) {
                $validar = false;
            }
            if (strlen($this->__get('cpf'))< 3) {
                $validar = false;
            }
            return $validar;
        }

//Validae se um cadastro pode ser feito
        public function getClientePorCpf()
        {
            $query = " SELECT nome, cpf FROM tb_cliente where cpf = :cpf";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':cpf', $this->__get('cpf'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

//listar
        public function listarDetalhe()
        {
            $query = "  SELECT c.id, c.nome, c.cpf, IFNULL(c.email,'Não possui E-mail') AS email, c.id_loja, l.nome_loja, l.local, e.cep, e.rua, e.numero, e.referencia, e.cidade, e.bairro, e.uf, t.telefone, t.tipo, DATE_FORMAT(c.data_cadastro, '%d/%m/%Y') as data_cadastro
                        FROM tb_cliente as c 
                        INNER JOIN tb_loja as l ON (c.id_loja = l.id)
                        INNER JOIN tb_endereco as e ON (c.id = e.id_cliente) 
                        INNER JOIN tb_telefone as t ON (c.id = t.id_cliente)
                        WHERE c.id = :id;
                    "; 
            $stmt= $this->db->prepare($query);
            $stmt->bindValue(':id',$this->__get('id'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

    public function listarValorCliente()
    {
        $query = "  SELECT count(c.numero_pedido) AS qt_total_pedido, sum(c.valor) AS valor_total, l.local FROM tb_pedido AS c
                    INNER JOIN tb_loja AS l ON (c.id_loja = l.id)
                    WHERE ID_CLIENTE = :id
                    GROUP BY l.local 
                    ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



//atualizar
        public function atualizaCliente()
        {
            $query = "  UPDATE tb_cliente AS c
                        INNER JOIN tb_endereco AS e ON (c.id = e.id_cliente)
                        INNER JOIN tb_telefone AS t ON (c.id = t.id_cliente)
                        SET c.nome = :nome , c.cpf = :cpf, c.email = :email, c.id_loja = :id_loja,
                        cep = :cep, e.rua = :rua, e.numero = :numero, e.referencia = :referencia, e.cidade = :cidade, e.bairro = :bairro, e.uf = :uf,
                        t.telefone = :telefone, t.tipo = :tipo
                        WHERE c.id = :id;
                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':cpf', $this->__get('cpf'));
            $stmt->bindValue(':id_loja', $this->__get('id_loja'));

            $stmt->bindValue(':cep', $this->__get('cep'));
            $stmt->bindValue(':rua', $this->__get('rua'));
            $stmt->bindValue(':numero', $this->__get('numero'));
            $stmt->bindValue(':referencia', $this->__get('referencia'));
            $stmt->bindValue(':cidade', $this->__get('cidade'));
            $stmt->bindValue(':bairro', $this->__get('bairro'));
            $stmt->bindValue(':uf', $this->__get('uf'));

            $stmt->bindValue(':telefone', $this->__get('telefone'));
            $stmt->bindValue(':tipo', $this->__get('tipo'));
            $stmt->execute();
            return $this;
        }


    }


    
