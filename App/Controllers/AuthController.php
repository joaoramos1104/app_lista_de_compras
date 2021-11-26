<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {
    public function autenticar()
    {
        $usuario = Container::getModel('Usuario');

        $usuario->__set('login', $_POST['login']);
        $usuario->__set('senha', md5($_POST['senha']));//md5 senha criptografada

        $usuario->autenticar();

       if ($usuario->__get('id') != '' && $usuario->__get('nome') && $usuario->__get('id_tipo')) {
           session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');
            $_SESSION['id_tipo'] = $usuario->__get('id_tipo');
           
           header('Location: /home');
       } else {
          header('Location: /?login=erro');
       }

    }

    public function sair()
    {
        session_start();
        session_destroy();
        header('Location: /');

    }

}

?>
