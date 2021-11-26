<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

	protected function initRoutes()
	{

		$routes['index'] = array(
			'route' => '/',
			'controller' => 'IndexController',
			'action' => 'index'
		);

		$routes['cadastro_user'] = array(
			'route' => '/cadastro_user',
			'controller' => 'IndexController',
			'action' => 'cadastro_user'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'registrar'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);

		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'AppController',
			'action' => 'home'
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);

		$routes['listar_pedidos'] = array(
			'route' => '/listar_pedidos',
			'controller' => 'AppController',
			'action' => 'listar_pedidos'
		);
		
		$routes['busca_produto'] = array(
			'route' => '/busca_produto',
			'controller' => 'AppController',
			'action' => 'busca_produto'
		);

		//Pesquisar Cliente

		$routes['pesquisarClientePedido'] = array(
			'route' => '/pesquisarClientePedido',
			'controller' => 'AppController',
			'action' => 'pesquisarClientePedido'
		);

		//Pesquisar Produto
		$routes['pesquisarProduto'] = array(
			'route' => '/pesquisarProduto',
			'controller' => 'AppController',
			'action' => 'pesquisarProduto'
		);

		

		$routes['listar_produto_pedido'] = array(
			'route' => '/listar_produto_pedido',
			'controller' => 'AppController',
			'action' => 'listar_produto_pedido'
		);

		$routes['finalizar_pedido'] = array(
			'route' => '/finalizar_pedido',
			'controller' => 'AppController',
			'action' => 'finalizar_pedido'
		);

		$routes['cadastro_pedido'] = array(
			'route' => '/cadastro_pedido',
			'controller' => 'AppController',
			'action' => 'cadastro_pedido'
		);

		$routes['inserir_produto'] = array(
			'route' => '/inserir_produto',
			'controller' => 'AppController',
			'action' => 'inserir_produto'
		);

		$routes['detalhe_pedido'] = array(
			'route' => '/detalhe_pedido',
			'controller' => 'AppController',
			'action' => 'detalhe_pedido'
		);

		$routes['busca_cliente_cpf'] = array(
			'route' => '/busca_cliente_cpf',
			'controller' => 'AppController',
			'action' => 'busca_cliente_cpf'
		);

		$routes['cad_pedido'] = array(
			'route' => '/cad_pedido',
			'controller' => 'AppController',
			'action' => 'cad_pedido'
		);

		$routes['status'] = array(
			'route' => '/status',
			'controller' => 'AppController',
			'action' => 'status'
		);

		//Excluir Pedido
		$routes['excluir_pedido'] = array(
			'route' => '/excluir_pedido',
			'controller' => 'AppController',
			'action' => 'excluir_pedido'
		);

		//Atualizar Valor Pedido
		$routes['atualiza_valor_pedido'] = array(
			'route' => '/atualiza_valor_pedido',
			'controller' => 'AppController',
			'action' => 'atualiza_valor_pedido'
		);

		//Atualizar Loja Pedido
		$routes['atualiza_loja_pedido'] = array(
			'route' => '/atualiza_loja_pedido',
			'controller' => 'AppController',
			'action' => 'atualiza_loja_pedido'
		);

		//Forma de Pagamento Pedido
		$routes['inserir_forma_pagamnto'] = array(
			'route' => '/inserir_forma_pagamnto',
			'controller' => 'AppController',
			'action' => 'inserir_forma_pagamnto'
		);

		//Alterar data pedido
		$routes['alterar_data'] = array(
			'route' => '/alterar_data',
			'controller' => 'AppController',
			'action' => 'alterar_data'
		);
		
		$routes['cliente'] = array(
			'route' => '/cliente',
			'controller' => 'AppController',
			'action' => 'cliente'
		);

		$routes['cadastro_cliente'] = array(
			'route' => '/cadastro_cliente',
			'controller' => 'AppController',
			'action' => 'cadastro_cliente'
		);

		$routes['cadastroCliente'] = array(
			'route' => '/cadastroCliente',
			'controller' => 'AppController',
			'action' => 'cadastroCliente'
		);

		$routes['detalhe_cliente'] = array(
			'route' => '/detalhe_cliente',
			'controller' => 'AppController',
			'action' => 'detalhe_cliente'
		);

		$routes['atualizar_cliente'] = array(
			'route' => '/atualizar_cliente',
			'controller' => 'AppController',
			'action' => 'atualizar_cliente'
		);

		

		$routes['search_home'] = array(
			'route' => '/search_home',
			'controller' => 'AppController',
			'action' => 'search_home'
		);

		$routes['search_home_full'] = array(
			'route' => '/search_home_full',
			'controller' => 'AppController',
			'action' => 'search_home_full'
		);

		$routes['lojas'] = array(
			'route' => '/lojas',
			'controller' => 'AppController',
			'action' => 'lojas'
		);

		$routes['atualiza_loja'] = array(
			'route' => '/atualiza_loja',
			'controller' => 'AppController',
			'action' => 'atualiza_loja'
		);

		$routes['salvar_loja'] = array(
			'route' => '/salvar_loja',
			'controller' => 'AppController',
			'action' => 'salvar_loja'
		);

		$routes['cad_loja'] = array(
			'route' => '/cad_loja',
			'controller' => 'AppController',
			'action' => 'cad_loja'
		);

		$routes['cadastro_loja'] = array(
			'route' => '/cadastro_loja',
			'controller' => 'AppController',
			'action' => 'cadastro_loja'
		);

		$routes['usuarios'] = array(
			'route' => '/usuarios',
			'controller' => 'IndexController',
			'action' => 'usuarios'
		);

		$routes['perfil_usuario'] = array(
			'route' => '/perfil_usuario',
			'controller' => 'IndexController',
			'action' => 'perfil_usuario'
		);

		$routes['atualizar_perfil'] = array(
			'route' => '/atualizar_perfil',
			'controller' => 'IndexController',
			'action' => 'atualizar_perfil'
		);

		$routes['atualizar_senha'] = array(
			'route' => '/atualizar_senha',
			'controller' => 'IndexController',
			'action' => 'atualizar_senha'
		);

		$routes['atualizar_loja_usuario'] = array(
			'route' => '/atualizar_loja_usuario',
			'controller' => 'IndexController',
			'action' => 'atualizar_loja_usuario'
		);
		


		$this->setRoutes($routes);
	}
}

?>