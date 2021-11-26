<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;




class IndexController extends Action {

	
	public function index(){
        $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
        $this->render('index', 'layout');

    }

    public function cadastro_user(){
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
        $this->view->usuario = array(
            'nome' => '',
            'login' => '',
            'email' => '',
            'senha' => '',
            'id_tipo' => '',
            'id_loja' => ''
            
        );

        $this->view->erroCadastro = false;
            $usuario = Container::getModel('Usuario');
            $this->view->tipo_user = $usuario->tipo_usuario();
            $this->view->lojas = $usuario->listarLojas();

        $this->render('cadastro_user', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }
    public function registrar()
    {
        session_start();
        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $usuario = Container::getModel('Usuario');

            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('login', $_POST['login']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha'])); //md5 senha criptografada
            $usuario->__set('id_tipo', $_POST['id_tipo']);
            $usuario->__set('id_loja', $_POST['id_loja']);
           
            if ($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {
                $usuario->salvar();
                header('Location: /usuarios', 'layout1');
                
            }else {
                
                $this->view->usuario = array(
                    'nome' => $_POST['nome'],
                    'login' => $_POST['login'],
                    'email' => $_POST['email'],
                    'senha' => $_POST['senha'],
                    
                );

                $this->view->erroCadastro = true;
                
                $this->render('cadastro_user', 'layout1');
            }
        } else {
            header('Location: /?login=erro');
        }
        
    }

    public function usuarios()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $usuarios = Container::getModel('Usuario');
            $usuario = $usuarios->listar_usuarios();
            $this->view->usuarios = $usuario;
            $this->render('/usuarios', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function perfil_usuario()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $perfil = Container::getModel('Usuario');
            $perfil->__set('id', $_GET['id']);
            $usuario = $perfil->perfil_usuario();
            $this->view->perfil = $usuario;
            $this->view->tipo_user = $perfil->tipo_usuario();
            $this->view->lojas = $perfil->listarLojas();
            
            $this->render("/perfil_usuario", "layout1");
        } else {
            header('Location: /?login=erro');
        }
    }

    public function atualizar_perfil()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $perfil = Container::getModel('Usuario');
            $perfil->__set('id', $_POST['id']);
            $perfil->__set('nome', $_POST['nome']);
            $perfil->__set('login', $_POST['login']);
            $perfil->__set('id_tipo', $_POST['id_tipo']);
            $perfil->__set('email', $_POST['email']);
            $perfil->atualizar_perfil();
           

             header("Location: /perfil_usuario?id={$_POST['id']}", "layout1");
        } else {
            header('Location: /?login=erro');
        }
    }

    public function atualizar_senha()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $perfil = Container::getModel('Usuario');
            $perfil->__set('id', $_POST['id']);
            $perfil->__set('senha', md5($_POST['senha']));
            $perfil->atualizar_senha();


            header("Location: /perfil_usuario?id={$_POST['id']}", "layout1");
        } else {
            header('Location: /?login=erro');
        }
    }

    public function atualizar_loja_usuario()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $perfil = Container::getModel('Usuario');
            $perfil->__set('id', $_POST['id']);
            $perfil->__set('id_loja',$_POST['id_loja']);
            $perfil->atualizar_loja_usuario();


            header("Location: /perfil_usuario?id={$_POST['id']}", "layout1");
        } else {
            header('Location: /?login=erro');
        }
    }
    
}




?>