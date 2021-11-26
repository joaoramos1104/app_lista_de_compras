<?php
namespace App\Controllers;
use MF\Controller\Action;
use MF\Model\Container;

include('../phpqrcode/qrlib.php');

class AppController extends Action
{
    
    //-------------------------------------pedido
    public function listar_pedidos()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $dados = Container::getModel('Pedido');
            $pedido = $dados->listar_pedidos();
            $this->view->dados = $pedido;
            $this->render('/pedidos', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function cadastro_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $inserir_pedido = Container::getModel('Pedido');
            $inserir_pedido->__set('id_cliente', $_GET['id_cliente']);
            $inserir_pedido->__set('id_loja', $_GET['id_loja']);
            $dados = $inserir_pedido->cadastro_pedido();
            $this->view->dados_ped = $dados;
            echo json_encode($this->view->dados_ped);
  
        } else {
            header('Location: /?login=erro');
        }
    }
    public function finalizar_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            print_r($_POST);
            $finalizar_pedido = Container::getModel('Pedido');
            $finalizar_pedido->__set('numero_pedido', $_POST['numero_pedido']);
            $finalizar_pedido->__set('detalhe_pedido', $_POST['detalhe_pedido']);
            $finalizar_pedido->finalizar_pedido_detalhe();
            $finalizar_pedido->finalizar_pedido_status();
            header("Location: detalhe_pedido?numero_pedido={$_POST['numero_pedido']}", "laout1");
        } else {
            header('Location: /?login=erro');
        }
    }
  
    public function inserir_produto()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
                
            $produto = Container::getModel('Pedido');
            $produto->__set('numero_pedido', $_POST['numero_pedido']);
            $produto->__set('codigo_produto', $_POST['codigo_prod']);
            $produto->__set('descricao_produto', $_POST['descricao_prod']);
            $produto->__set('valor_un', (str_replace(",", ".", $_POST['valor_prod'])));
            $produto->__set('qtd_produto', (str_replace(",", ".", $_POST['qtd_prod'])));
            $produto->__set('valor_total', (str_replace(",", ".", $_POST['valor_total_prod'])));
            $produto->inserir_produto();
            $this->view->produtos = $produto->listar_produto_pedido();
            $data = json_encode($this->view->produtos);
            echo $data;
        } else {
            header('Location: /?login=erro');
        }
    }

    public function busca_produto()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != ''
        ) {
            $produtos = Container::getModel('Pedido');
            $produtos->__set('codigo', $_GET['codigo']);
            $produto = $produtos->buscarProduto();
            $this->view->produtos = $produto;
            $lista_produtos = json_encode($this->view->produtos);
            echo $lista_produtos;
        } else {
            header('Location:/?login=erro ');
        }
    }
    public function listar_produto_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != ''
        ) {
            $lista_produtos = Container::getModel('Pedido');
            $lista_produtos->__set('id_prod', $_GET['id_prod']);
            $lista_produtos->__set('numero_pedido', $_GET['numero_pedido']);
            $produto = $lista_produtos->listar_produto_pedido();
            $this->view->lista_produtos = $produto;
            $lista_produtos_pedido = json_encode($this->view->lista_produtos);
            echo $lista_produtos_pedido;
        } else {
            header('Location:/?login=erro ');
        }
    }


    public function detalhe_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $detalhePedido = Container::getModel('Pedido');
            $detalhePedido->__set('numero_pedido', $_GET['numero_pedido']);
            $detalhe = $detalhePedido->listarDetalhe();
            $this->view->detalhePedido = $detalhe;
            $this->view->lojas = $detalhePedido->listarLoja();
            $this->view->produtos_pedido = $detalhePedido->listarProdutos();
            $this->view->forma_pagamento = $detalhePedido->listarFormaPagamento();

    //gerar arquido .PED
            // $fw = fopen("pedidos/" . $_GET['numero_pedido'] . ".PED", "w+", 0);
            // $ped = "PED" . "#" . str_pad($_GET['numero_pedido'], 8, '0', STR_PAD_LEFT) . '#' . ' ' . '#' . '000000' . '#' .  '000000000000' . '#' .  '000000000000' . '#' . '000000000000' . '#' . '000000000000' . '#' . '000000000000' . '#' . '00000000000000000000';
            // $linha = $ped . "\r\n";
            // fwrite($fw, $linha, strlen($linha));
            // fclose($fw);

        foreach ($this->view->produtos_pedido as $key => $value) {    
            $codigo_produto = str_replace(".", "", $value['codigo_produto']);
            $qtd_produto = str_replace(".", "", $value['qtd_produto']);
            $valor_un = str_replace(".", "", $value['valor_un']);
            $valor_total = str_replace(".", "", $value['valor_total']);
            $descricao_pedido = $value['descricao_produto'];

            $f = fopen("pedidos/" . $value['numero_pedido'] . ".PED", "a+", 0);
            $ite = "ITE"."#". str_pad($codigo_produto, 13, '0', STR_PAD_LEFT) . '#' . str_pad($descricao_pedido, 45, ' ') . '#' . str_pad($qtd_produto, 6, '0', STR_PAD_LEFT) . '#' .  str_pad($valor_un, 12, '0', STR_PAD_LEFT). '#' .  str_pad($valor_total, 11, '0', STR_PAD_LEFT) . '#' . '000000000000';
            $linha = $ite. "\r\n";
            fwrite($f, $linha, strlen($linha));
            fclose($f);
        }
            //fim gerar arquido .PED

            //inicio do QRcode
            // function ler()
            // {
            //     //Variável arquivo armazena o nome e extensão do arquivo.
            //     $arquivo = "pedidos/" . $_GET['numero_pedido'] . ".PED";
            //     //Variável $fp armazena a conexão com o arquivo e o tipo de ação.
            //     $fp = fopen($arquivo, "r");
            //     //Lê o conteúdo do arquivo aberto.
            //     $conteudo = fread($fp, filesize($arquivo));
            //     //Fecha o arquivo.
            //     fclose($fp);
            //     //retorna o conteúdo.
            //     return str_replace("#", "", $conteudo);
            // }
         
            // $arquivo = $_GET['numero_pedido'];

            // \QRcode::png($arquivo, "pedidos/qrcode/".$_GET['numero_pedido'].".png", QR_ECLEVEL_L);
            // \QRcode::png(ler(), "pedidos/qrcode/" . $_GET['numero_pedido'] . ".png", QR_ECLEVEL_L);

        //Fim do QRcode

            $this->render('detalhe_pedido', 'layout1');
        } else {
            header('Location:/?login=erro ');
        }
    }

    public function cad_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            Container::getModel('Pedido');
            $this->render('/cad_pedido', 'layout1');
        } else {
            header('Location:/?login=erro ');
        }
    }

    public function busca_cliente_cpf()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $busca_cliente_cpf = Container::getModel('Pedido');
            $busca_cliente_cpf->__set('cpf', $_GET['cpf']);
            $busca_cliente = $busca_cliente_cpf->busca_cliente_cpf();
            $this->view->busca_cliente_cpf = $busca_cliente;
            $lista_cliente = json_encode($this->view->busca_cliente_cpf);
            echo $lista_cliente;
        } else {
            header('Location:/?login=erro ');
        }
    }


    public function status()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $status = Container::getModel('Pedido');
            $status->__set('numero_pedido', $_GET['numero_pedido']);
            $status->__set('status', $_GET['status']);
            $status->updateStatus();
            header("Location: detalhe_pedido?numero_pedido={$_GET['numero_pedido']}", "laout1");
        } else {
            header('Location:/?login=erro ');
        }
    }

    public function excluir_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $status = Container::getModel('Pedido');
            $status->__set('numero_pedido', $_POST['numero_pedido']);
            $status->excluirPedido();
            $arquivo = "pedidos/" . $_GET['numero_pedido'] . ".PED";
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
            // $arquivo = "pedidos/" . $_GET['numero_pedido'] . ".PED";
            // unlink("pedidos/qrcode/" . $_GET['numero_pedido'] . ".png");
        
            header("Location: /listar_pedidos", "laout1");
        } else {
            header('Location:/?login=erro ');
        }
    }

    public function atualiza_valor_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $status = Container::getModel('Pedido');
            $status->__set('numero_pedido', $_GET['numero_pedido']);
            $status->__set('valor', (str_replace(",", ".",$_GET['valor'])));
            $status->atualizaValorPedido();
            header("Location: detalhe_pedido?numero_pedido={$_GET['numero_pedido']}", "laout1");
        } else {
            header('Location:/?login=erro ');
        }
    }
    //Atualizar Loja Pedido
    public function atualiza_loja_pedido()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $status = Container::getModel('Pedido');
            $status->__set('numero_pedido', $_GET['numero_pedido']);
            $status->__set('id_loja', $_GET['id_loja']);
            $status->atualizaLojaPedido();

            header("Location: detalhe_pedido?numero_pedido={$_GET['numero_pedido']}", "laout1");
        } else {
            header('Location:/?login=erro ');
        }
    }

    //Alterar Data Pedido
    public function alterar_data()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $data = Container::getModel('Pedido');
            $data->__set('numero_pedido', $_GET['numero_pedido']);
            $data->__set('data', $_GET['data']);
            $data->alterarData();

            header("Location: detalhe_pedido?numero_pedido={$_GET['numero_pedido']}", "laout1");
        } else {
            header('Location:/?login=erro ');
        }
    }

    //Adicionar Forma de Pagamento
    public function inserir_forma_pagamnto()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $form_pagamneto = Container::getModel('Pedido');
            $form_pagamneto->__set('numero_pedido', $_GET['numero_pedido']);
            $form_pagamneto->__set('forma_pagamento', $_GET['forma_pagamento']);
            $form_pagamneto->inserirFormaPagamento();

            header("Location: detalhe_pedido?numero_pedido={$_GET['numero_pedido']}", "laout1");
        } else {
            header('Location:/?login=erro ');
        }
    }

    //Pesquisar Cliente
    public function pesquisarClientePedido()
    {
        session_start();

        if (
            $_SESSION['id'] != '' && $_SESSION['nome'] != ''
        ) {
            $dados = Container::getModel('Pedido');
            $cliente = $dados->pesquisarClientePedido();
            $clientes = json_encode($cliente);
            echo $clientes;

        } else {
            header('Location: /?login=erro');
        }
    }

    //Pesquisar Produto
    public function pesquisarProduto()
    {
        session_start();

        if (
            $_SESSION['id'] != '' && $_SESSION['nome'] != ''
        ) {
            $dados = Container::getModel('Pedido');
            $produto = $dados->pesquisarProduto();
            $produtos = json_encode($produto);
            echo $produtos;
        } else {
            header('Location: /?login=erro');
        }
    }

    //-------------------------------------cliente

    public function cliente()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $dados = Container::getModel('Cliente');
            $cliente = $dados->listarCliente();
            $this->view->dados = $cliente;
            $this->render('cliente', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }
    

    public function cadastro_cliente()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $this->view->cadCliente = array(
                'nome' => '',
                'email' => '',
                'cpf' => '',
                'id_loja' => '',
                'cep' => '',
                'rua' => '',
                'numero' => '',
                'referencia' => '',
                'cidade' => '',
                'bairro' => '',
                'uf' => '',
                'telefone' => '',
                'tipo' => ''

            );
            $this->view->erroCadastro = false;
            $lojas = Container::getModel('Cliente');
            $loja = $lojas->listarLojas();
            $this->view->lojas = $loja;
            $this->render('cadastro_cliente', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function cadastroCliente()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $cadCliente = Container::getModel('Cliente');
            $cadCliente->__set('nome', $_POST['nome']);
            $cadCliente->__set('cpf', $_POST['cpf']);
            $cadCliente->__set('email', $_POST['email']);
            $cadCliente->__set('id_loja', $_POST['id_loja']);
            $cadCliente->__set('cep', $_POST['cep']);
            $cadCliente->__set('rua', $_POST['rua']);
            $cadCliente->__set('numero', $_POST['numero']);
            $cadCliente->__set('referencia', $_POST['referencia']);
            $cadCliente->__set('cidade', $_POST['cidade']);
            $cadCliente->__set('bairro', $_POST['bairro']);
            $cadCliente->__set('uf', $_POST['uf']);
            $cadCliente->__set('telefone', $_POST['telefone']);
            $cadCliente->__set('tipo', $_POST['tipo']);
            if ($cadCliente->validarCadastro() && count($cadCliente->getClientePorCpf()) == 0) {
                $cadCliente->salvar();
                header('Location: cliente', 'layout1');
            } else {
                $this->view->cadCliente = array(
                    'nome' => $_POST['nome'],
                    'cpf' => $_POST['cpf'],
                    'email' => $_POST['email'],
                    'id_loja' => $_POST['id_loja'],
                    'cep' => $_POST['cep'],
                    'rua' => $_POST['rua'],
                    'numero' => $_POST['numero'],
                    'referencia' => $_POST['referencia'],
                    'cidade' => $_POST['cidade'],
                    'bairro' => $_POST['bairro'],
                    'uf' => $_POST['uf'],
                    'telefone' => $_POST['telefone'],
                    'tipo' => $_POST['tipo']

                );
                $this->view->erroCadastro = true;
                $this->render('/cadastro_cliente', 'layout1');
            }
           
        } else {
            header('Location: /?login=erro');
        }
    }


    public function detalhe_cliente()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $cadCliente = Container::getModel('Cliente');
            $detalhe_valor = Container::getModel('Cliente');
            $cadCliente->__set('id', $_GET['id']);
            $detalhe_valor->__set('id', $_GET['id']);

            $detalhe = $cadCliente->listarDetalhe();
            $this->view->cadCliente = $detalhe;
            $this->view->lojas = $cadCliente->listarLojas();
            

            $detalhe = $detalhe_valor->listarValorCliente();
            $this->view->detalhe_valor = $detalhe;
            $this->render('detalhe_cliente', 'layout1');
        } else {
            header('Location:/?login=erro ');
        }
    }

    
    public function atualizar_cliente()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $atualiza_cli = Container::getModel('Cliente');
            $atualiza_cli->__set('id', $_POST['id']);
            $atualiza_cli->__set('nome', $_POST['nome']);
            $atualiza_cli->__set('cpf', $_POST['cpf']);
            $atualiza_cli->__set('email', $_POST['email']);
            $atualiza_cli->__set('id_loja', $_POST['id_loja']);
            $atualiza_cli->__set('cep', $_POST['cep']);
            $atualiza_cli->__set('rua', $_POST['rua']);
            $atualiza_cli->__set('numero', $_POST['numero']);
            $atualiza_cli->__set('referencia', $_POST['referencia']);
            $atualiza_cli->__set('cidade', $_POST['cidade']);
            $atualiza_cli->__set('bairro', $_POST['bairro']);
            $atualiza_cli->__set('uf', $_POST['uf']);
            $atualiza_cli->__set('telefone', $_POST['telefone']);
            $atualiza_cli->__set('tipo', $_POST['tipo']);
            $atualiza_cli->atualizaCliente();
            header("Location: /detalhe_cliente?id={$_POST['id']}", 'layout1');
        } else {
            header('Location:/?login=erro ');
        }
    }


    //-------------------------------------Home

    public function home()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != ''
        ) {
            $lista_loja = Container::getModel('Home');
            $dados = $lista_loja->listarLoja();
            $this->view->lista_loja = $dados;
            $this->view->dados_dia = $lista_loja->valorDia();
            $this->view->valor_mes = $lista_loja->valorMes();
            $this->view->valor_dia_atual_lojas = $lista_loja->valoresLojas();
            $this->render('home', 'layout1');
        } else {
            header('Location:/?login=erro ');
        }
    }
 

    public function search_home()
    {

        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
           
                $search_home = Container::getModel('Home');
                $search_home->__set('id_loja', $_GET['id_loja']);
                $search_home->__set('data_inicial', $_GET['data_inicial']);
                $search_home->__set('data_final', $_GET['data_final']);
                $search = $search_home->searchHome();
                $this->view->search_home = $search;
                $this->view->lista_loja = $search_home->listarLoja();
                $this->render('/search_home', 'layout1');

        } else {
            header('Location:/?login=erro ');
        }

    }

    public function search_home_full()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $search_home_full = Container::getModel('Home');
            $search_home_full->__set('data_inicial', $_GET['data_inicial']);
            $search_home_full->__set('data_final', $_GET['data_final']);
            $search = $search_home_full->searchHomeFull();
            $this->view->search_home_full = $search;
            $this->view->lista_loja = $search_home_full->listarLoja();
            $this->view->valor_total_lojas = $search_home_full->valoresLojasTotal();
            $this->view->dados_filtro = $search_home_full->valorFiltro();
            $this->view->valor_ano_filtro = $search_home_full->valorAcumuladoAno();
            

            $this->render('/search_home_full', 'layout1');
        } else {
            header('Location:/?login=erro ');
        }
    }


//-----------------------------------------------------------Lojas

    public function lojas()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $loja = Container::getModel('Loja');
            $lojas = $loja->recuperar();
            $this->view->loja = $lojas;
            $this->render('lojas', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function atualiza_loja()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $get_loja = Container::getModel('Loja');
            $get_loja->__set('id', $_GET['id']);
            $lojas = $get_loja->get_loja();
            $this->view->get_loja = $lojas;
            $this->render('atualiza_loja', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }


    public function salvar_loja()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $get_loja = Container::getModel('Loja');
            $get_loja->__set('id', $_GET['id']);
            $get_loja->__set('nome_loja', $_GET['nome_loja']);
            $get_loja->__set('local', $_GET['local']);
            $get_loja->__set('cnpj', $_GET['cnpj']);
            $get_loja->__set('sequencia', $_GET['sequencia']);
            $get_loja->__set('cep', $_GET['cep']);
            $get_loja->__set('rua', $_GET['rua']);
            $get_loja->__set('numero', $_GET['numero']);
            $get_loja->__set('referencia', $_GET['referencia']);
            $get_loja->__set('cidade', $_GET['cidade']);
            $get_loja->__set('bairro', $_GET['bairro']);
            $get_loja->__set('uf', $_GET['uf']);
            $get_loja->salvar_loja();
            header('Location: /lojas', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function cad_loja()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $cad_loja = Container::getModel('Loja');
            $cad_loja->__set('nome_loja', $_POST['nome_loja']);
            $cad_loja->__set('local', $_POST['local']);
            $cad_loja->__set('cnpj', $_POST['cnpj']);
            $cad_loja->__set('sequencia', $_POST['sequencia']);
            $cad_loja->__set('cep', $_POST['cep']);
            $cad_loja->__set('rua', $_POST['rua']);
            $cad_loja->__set('numero', $_POST['numero']);
            $cad_loja->__set('referencia', $_POST['referencia']);
            $cad_loja->__set('cidade', $_POST['cidade']);
            $cad_loja->__set('bairro', $_POST['bairro']);
            $cad_loja->__set('uf', $_POST['uf']);
            $cad_loja->cad_loja();

            header('Location: /lojas', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function cadastro_loja()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {
            $this->render('/cadastro_loja', 'layout1');
        } else {
            header('Location: /?login=erro');
        }
    }

}
?>

