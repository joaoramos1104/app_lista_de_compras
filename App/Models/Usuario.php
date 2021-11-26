<?php

    namespace App\Models;
    use MF\Model\Model;

    class Usuario extends Model{

        private $id;
        private $nome;
        private $login;
        private $email;
        private $senha;
        private $id_tipo;
        private $id_loja;
       


        public function __get( $atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }

        //salvar
        public function salvar()
        {
            $query = "insert into tb_usuario (nome, login, email, senha, id_tipo, id_loja)
            values(:nome, :login, :email, :senha, :id_tipo, :id_loja)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':login', $this->__get('login'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));// md5() -> hash 32 caracter
            $stmt->bindValue(':id_tipo', $this->__get('id_tipo'));
            $stmt->bindValue(':id_loja', $this->__get('id_loja'));
            $stmt->execute();
            return $this;

        }
        //validar cadastro sem valor vazio
        public function validarCadastro()
        {
            $validar = true;

            if (strlen($this->__get('login'))< 3) {
                $validar = false;
            }
            if (strlen($this->__get('nome'))< 3) {
                $validar = false;
            }
            if (strlen($this->__get('senha'))< 3) {
                $validar = false;
            }
            if (strlen($this->__get('email'))< 3) {
                $validar = false;
            }
            return $validar;
        }

        //Validae se um cadastro pode ser feito
        public function getUsuarioPorEmail()
        {
            $query = " select nome, email from tb_usuario where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function autenticar()
        {
            $query = "select id, nome, login, id_tipo from tb_usuario where login = :login and senha = :senha";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':login', $this->__get('login'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($usuario['id'] != '' && $usuario['nome'] != '' && $usuario['id_tipo'] != '') {
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
                $this->__set('id_tipo', $usuario['id_tipo']);

            }
            return $this;


        }

        public function listar_usuarios()
       {
           $query = "SELECT u.id, u.nome, u.login, u.email,u.id_tipo, t.descricao , l.local AS loja
                        FROM tb_usuario AS u
                        INNER JOIN tb_tipo_usuario AS t ON (t.id = u.id_tipo)
                        INNER JOIN tb_loja AS l ON (u.id_loja = l.id)";

            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
       }

        public function perfil_usuario()
        {
            $query = "SELECT u.id, u.nome, u.login, u.email, u.img, t.descricao, l.local AS loja
                            FROM tb_usuario AS u
                            INNER JOIN tb_tipo_usuario AS t ON (t.id = u.id_tipo)
                            INNER JOIN tb_loja AS l ON (u.id_loja = l.id)
                            WHERE u.id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function tipo_usuario()
        {
            $query = "SELECT id, tipo, descricao 
                            FROM tb_tipo_usuario";

            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }




        public function atualizar_perfil()
        {
            $query = "  UPDATE tb_usuario
                        SET nome = :nome, login = :login, id_tipo = :id_tipo, email = :email
                        WHERE id = :id";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':login', $this->__get('login'));
            $stmt->bindValue(':id_tipo', $this->__get('id_tipo'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();
            return $this;
        }

    public function atualizar_senha()
    {
        $query = "  UPDATE tb_usuario
                        SET senha = :senha
                        WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
        return $this;
    }

    public function atualizar_loja_usuario()
    {
        $query = "  UPDATE tb_usuario
                        SET id_loja = :id_loja
                        WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':id_loja', $this->__get('id_loja'));
        $stmt->execute();
        return $this;
    }

    public function listarLojas()
    {
        $query = "   SELECT id AS id_loja, nome_loja, local FROM tb_loja;
                ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    }

?>