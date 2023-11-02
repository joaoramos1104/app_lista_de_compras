//jquery

    (function($) {
    "use strict";

    // Add active state to sidbar nav links
    var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
        $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function() {
            if (this.href === path) {
                $(this).addClass("active");
            }
        });

    // Toggle the side navigation
    $("#sidebarToggle").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });
})(jQuery);

//Gravar Produto Pedido via ajax
$("#form_prod").submit(function (event) {
    event.preventDefault();
    var $form = $(this),
        codigo_prod = $form.find("input[name='codigo_prod']").val(),
        descricao_prod = $form.find("input[name='descricao_prod']").val(),
        valor_prod = $form.find("input[name='valor_prod']").val(),
        qtd_prod = $form.find("input[name='qtd_prod']").val(),
        valor_total_prod = $form.find("input[name='valor_total_prod']").val(),
        numero_pedido = $form.find("input[name='numero_pedido']").val()

    url = $form.attr("action");

    // Send the data using post
    var posting = $.post(url, {
        codigo_prod: codigo_prod,
        descricao_prod: descricao_prod,
        valor_prod: valor_prod,
        qtd_prod: qtd_prod,
        valor_total_prod: valor_total_prod,
        numero_pedido: numero_pedido
    });

    // Put the results in a div
    posting.done(function (data) {
      //console.log(data);
       dados = $.parseJSON(data);
       var dataSet = [];
        $.each(dados, function (key, value) {
            dataSet.push([
                //value.id,
                value.codigo_produto,
                value.descricao_produto,
                value.valor_un.replace(".", ","), 
                value.qtd_produto.replace(".", ","),
                value.valor_total.replace(".", ",")
            ]);    
        })
        
        var table = $("#list_prod").DataTable({
            
            retrieve: true,
            paging: false,
            
            data: dataSet,
            columns: [
                   //{ title: 'Id' },
                   { title: 'Codigo' },
                   { title: 'Descricao' },
                   { title: 'Valor' },
                   { title: 'Qunatidade' },
                   { title: 'Total' },
            ]
      });
      table.destroy();
      //Limpar input
        $("#button_inserir_produto").remove();
        $("#form_prod input[data-name='entrada']").val("");

    });
    
});
//Fim Gravar Produto Pedido via ajax


//Gravar - Cancelar Pedido
$('#salvar').click(function () {
    $('#salv_canc').attr('action', 'finalizar_pedido');
});

$('#cancelar').click(function () {
    $('#salv_canc').attr('action', 'excluir_pedido');
});
//Fim Gravar - Cancelar Pedido



// Counter Number Animation
$('.count').each(function () {
    $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
    }, {
        duration: 2000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});
// Fim Counter Number Animation


//Pesquisar Cliente por nomo para incluir no pedido
$(document).ready(function () {
    $.ajaxSetup({
        cache: false
    });
    $('#buscar_cliente').keyup(function () {
        $('#resultado').html('');
        $('#state').val('');
        var searchField = $('#buscar_cliente').val();
        var expression = new RegExp(searchField, "i");
        $.getJSON('pesquisarClientePedido', function (data) {
            $.each(data, function (key, value) {
                if (value.nome_cliente.search(expression) != -1 || value.cpf_cliente.search(expression) != -1) {
                    $('#resultado').append(
                        '<table class="table table-sm table-responsive-sm"><tr class="rounded-pill shadow-sm"><td class="text-info border-left-01 rounded-pill shadaw-sm" id="busc_cpf">' + value.cpf_cliente + '</td><td>' + value.nome_cliente + '</td> <td class="text-right">' + value.local_cliente +'</td></tr></table>'
                        );
                }
            });
        });
    });

    $('#resultado').on('click', '#busc_cpf', function () {
        var click_text = $(this).text().split('|');
        $('#cpf').val($.trim(click_text[0]));
        $('#buscar_cliente').val($.trim(click_text[0]));
        $("#resultado").html('');
        
    });
});


//Pesquisar Produto para incluir no pedido
$(document).ready(function () {
    $.ajaxSetup({
        cache: false
    });
    $('#buscar_produto_desc').keyup(function () {
        $('#resultado_prod').html('');
        $('#state').val('');
        var searchField = $('#buscar_produto_desc').val();
        var expression = new RegExp(searchField, "i");
        $.getJSON('pesquisarProduto', function (data) {
            $.each(data, function (key, value) {
                if (value.codigo_produto.search(expression) != -1 || value.descricao_produto.search(expression) != -1) {
                    
                    $('#resultado_prod').append(
                        '<table class="table table-sm table-responsive-sm"><tr class="rounded-pill shadow-sm"><td class="text-info border-left-01 rounded-pill shadaw-sm" id="busc_cod">' + value.codigo_produto + '</td><td>' + value.descricao_produto + '</td> <td class="text-right">R$ ' + value.valor_produto.replace(".", ",") + '</td></tr></table>'
                    );
                }
            });
        });
    });

    $('#resultado_prod').on('click', '#busc_cod', function () {
        var click_text = $(this).text().split('|');
        $('#codigo').val($.trim(click_text[0]));
        $('#buscar_produto_desc').val($.trim(click_text[0]));
        $("#resultado_prod").html('');

    });
});

//Fim Pesquisar Produto para incluir no pedido


//Endereço
function getDadosEnderecoPorCEP(cep) {
    var url = 'https://viacep.com.br/ws/' + cep + '/json/'

    var xmlHttp = new XMLHttpRequest()
    xmlHttp.open('GET', url)

    xmlHttp.onreadystatechange = () => {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var dadosJSONText = xmlHttp.responseText
            var dadosJSONObj = JSON.parse(dadosJSONText)

            document.getElementById('endereco').value = dadosJSONObj.logradouro
            document.getElementById('bairro').value = dadosJSONObj.bairro
            document.getElementById('cidade').value = dadosJSONObj.localidade
            document.getElementById('uf').value = dadosJSONObj.uf

        }
    }

    xmlHttp.send()
}


//Buscar Cliente CPF
function getCpf() {
    let cpf = document.getElementById("cpf");

    let url = 'busca_cliente_cpf?cpf=' + cpf.value

    let xmlHttp = new XMLHttpRequest()
    xmlHttp.open('GET', url)

    xmlHttp.onreadystatechange = () => {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let dadosJSONText = xmlHttp.responseText
            let dadosJSONObj = JSON.parse(dadosJSONText)

            document.getElementById('id_cliente').value = dadosJSONObj.id_cliente;
            document.getElementById('nome').value = dadosJSONObj.nome;
            document.getElementById('id_loja').value = dadosJSONObj.id_loja;
            document.getElementById('loja').value = dadosJSONObj.local;
            document.getElementById('cep').value = dadosJSONObj.cep;
            document.getElementById('rua').value = dadosJSONObj.rua;
            document.getElementById('numero').value = dadosJSONObj.numero;
            document.getElementById('referencia').value = dadosJSONObj.referencia;
            document.getElementById('bairro').value = dadosJSONObj.bairro;
            document.getElementById('cidade').value = dadosJSONObj.cidade;
            document.getElementById('uf').value = dadosJSONObj.uf;
            if (dadosJSONObj.id_cliente != undefined) {
                $("#button_gerar_pedido").remove();
                $('#button_inserir').append('<input id="button_gerar_pedido" class="btn btn-sm btn-blue shadow rounded-pill" type="button" onclick="getPed()" value="Iniciar Pedido" />');
            } else {
                alert("Cliente não Encontrado, CPF Não cadastrado ou Inválido!")
                $("#form_cliente input[data-name='cliente']").val("");
                $("#button_gerar_pedido").remove();
            }

        }
    }

    xmlHttp.send()
}
//Fim Buscar Cliente CPF


//Gera pedido
function getPed() {

    let id_cliente = document.getElementById("id_cliente");
    let id_loja = document.getElementById("id_loja");

    let url = 'cadastro_pedido?id_cliente=' + id_cliente.value + '&id_loja=' + id_loja.value

    let xmlHttp = new XMLHttpRequest()
    xmlHttp.open('GET', url)

    xmlHttp.onreadystatechange = () => {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let dadosJSONText = xmlHttp.responseText
            let dadosJSONObj = JSON.parse(dadosJSONText)
            console.log(dadosJSONText)
            document.getElementById('numero_ped').value = dadosJSONObj.numero_pedido;
            document.getElementById('num_ped').value = dadosJSONObj.numero_pedido;
            document.getElementById("inserir_produtos").style.display = "block";
            $("#button_gerar_pedido").remove();
            
        }
    }

    xmlHttp.send()
}
//Fim Gera pedido


//Buscar Produto
function getCodigo() {

    var codigo = document.getElementById("codigo");
    var url = 'busca_produto?codigo=' + codigo.value

    var xmlHttp = new XMLHttpRequest()
    xmlHttp.open('GET', url)

    xmlHttp.onreadystatechange = () => {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var dadosJSONText = xmlHttp.responseText
            var dadosJSONObj = JSON.parse(dadosJSONText)

            if (dadosJSONObj.descricao_produto != undefined) {
                let button = document.createElement('button');
                button.id = 'button_inserir_produto';
                button.textContent = 'Inserir';
                button.className = 'btn btn-sm btn-success';
                button.setAttribute = 'submit';
                document.getElementById("inserir_produto").append(button);

                //console.log(dadosJSONText)
                var qtd = document.getElementById("qtd");
                var valor = document.getElementById("valor_produto");

                document.getElementById('codigo_produt').value = dadosJSONObj.codigo_produto;
                document.getElementById('descricao_produto').value = dadosJSONObj.descricao_produto;
                document.getElementById('valor_produto').value = dadosJSONObj.valor_produto.replace(".", ",");
                var total = document.getElementById("valor_total");

                /*Criando eventos para executarem a função calcular sempre que uma tecla for pressionada*/
                qtd.addEventListener("keyup", function () {
                    calcular();
                });

                /*Função calcular; converte os valores dos inputs para float e então calcula (total = custo + frete - desconto)*/
                function calcular() {
                    var valorTotal = Number(qtd.value.replace(",", ".")) * Number(valor.value.replace(",", "."));
                    total.value = parseFloat(valorTotal.toFixed(2)).toLocaleString('pt-BR');

                }
            } else {
                alert("Produto não encontrado!");
            }
        }
    }
    xmlHttp.send()
}
//Fim Buscar Produto

//Listar Produtos Pedido
function ListProduto() {
    let id_cliente = document.getElementById("id_cliente");
    let id_loja = document.getElementById("id_loja");

    let url = 'cadastro_pedido?id_cliente=' + id_cliente.value + '&id_loja=' + id_loja.value

    let xmlHttp = new XMLHttpRequest()
    xmlHttp.open('GET', url)

    xmlHttp.onreadystatechange = () => {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let dadosJSONText = xmlHttp.responseText
            let dadosJSONObj = JSON.parse(dadosJSONText)
            //console.log(dadosJSONText)
            document.getElementById('numero_ped').value = dadosJSONObj.max_id;
        }
    }

    xmlHttp.send()
}
//Fim Listar Produtos Pedido

//js 