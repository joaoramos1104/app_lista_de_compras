<?php

    namespace App\Models;
    use MF\Model\Model;

    class Loja extends Model{

        private $id;
        private $nome_loja;
        private $local;
        private $cnpj;
        private $sequencia;
        private $cep;
        private $rua;
        private $numero;
        private $referencia;
        private $cidade;
        private $bairro;
        private $uf;
        private $id_loja;


       


        public function __get( $atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }

        //litar
        public function recuperar()
        {
           $query = " SELECT id, nome_loja, cnpj, local, sequencia FROM tb_loja;
                    ";
            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function get_loja()
        {
            $query = " SELECT l.id, l.nome_loja, l.cnpj, l.local, l.sequencia, e.cep, e.rua, e.numero, e.referencia, e.cidade, e.bairro, e.uf
                        FROM tb_loja AS l
                        INNER JOIN tb_endereco_loja AS e ON (l.id = e.id_loja)
                        WHERE l.id = :id;
                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function salvar_loja()
        {
            $query = " UPDATE tb_loja AS l 
                        INNER JOIN tb_endereco_loja AS e ON (l.id = e.id_loja)
                        SET l.cnpj = :cnpj, l.sequencia = :sequencia, l.nome_loja = :nome_loja, l.local = :local,
                            e.cep = :cep, e.rua = :rua, e.numero = :numero, e.referencia = :referencia, e.bairro = :bairro, e.cidade = :cidade, e.uf = :uf
                        WHERE l.id = :id
                    ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':nome_loja', $this->__get('nome_loja'));
            $stmt->bindValue(':local', $this->__get('local'));
            $stmt->bindValue(':cnpj', $this->__get('cnpj'));
            $stmt->bindValue(':sequencia', $this->__get('sequencia'));
            $stmt->bindValue(':cep', $this->__get('cep'));
            $stmt->bindValue(':rua', $this->__get('rua'));
            $stmt->bindValue(':numero', $this->__get('numero'));
            $stmt->bindValue(':referencia', $this->__get('referencia'));
            $stmt->bindValue(':cidade', $this->__get('cidade'));
            $stmt->bindValue(':bairro', $this->__get('bairro'));
            $stmt->bindValue(':uf', $this->__get('uf'));
            $stmt->execute();
            return $this;

        }


    public function cad_loja()
    {
        $query = " INSERT INTO tb_loja 
                    (nome_loja, local, cnpj, sequencia)
                    VALUES 
                    (:nome_loja, :local, :cnpj, :sequencia);

                    SELECT @id := LAST_INSERT_ID();

                        
                    INSERT INTO tb_endereco_loja
                    (cep, rua, numero, referencia, bairro, cidade, uf, id_loja)
                    VALUES
                    (:cep, :rua, :numero, :referencia, :bairro, :cidade, :uf, @id);
                ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_loja', $this->__get('nome_loja'));
        $stmt->bindValue(':local', $this->__get('local'));
        $stmt->bindValue(':cnpj', $this->__get('cnpj'));
        $stmt->bindValue(':sequencia', $this->__get('sequencia'));
        //endereco
        $stmt->bindValue(':cep', $this->__get('cep'));
        $stmt->bindValue(':rua', $this->__get('rua'));
        $stmt->bindValue(':numero', $this->__get('numero'));
        $stmt->bindValue(':referencia', $this->__get('referencia'));
        $stmt->bindValue(':bairro', $this->__get('bairro'));
        $stmt->bindValue(':cidade', $this->__get('cidade'));
        $stmt->bindValue(':uf', $this->__get('uf'));
        $stmt->execute();
        return $this;
    }
        

    }

?>