SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_delivery`
--
CREATE DATABASE IF NOT EXISTS `db_delivery` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `db_delivery`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_cliente`
--

DROP TABLE IF EXISTS `tb_cliente`;
CREATE TABLE IF NOT EXISTS `tb_cliente` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `CPF` char(11) COLLATE utf8_unicode_ci NOT NULL,
  `EMAIL` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ID_LOJA` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `CPF` (`CPF`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_detalhe_pedido`
--

DROP TABLE IF EXISTS `tb_detalhe_pedido`;
CREATE TABLE IF NOT EXISTS `tb_detalhe_pedido` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DETALHE_PEDIDO` longtext COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
  `NUMERO_PEDIDO` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_DETALHE_PEDIDO` (`NUMERO_PEDIDO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_endereco`
--

DROP TABLE IF EXISTS `tb_endereco`;
CREATE TABLE IF NOT EXISTS `tb_endereco` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CEP` char(8) COLLATE utf8_unicode_ci NOT NULL,
  `RUA` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `NUMERO` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `REFERENCIA` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BAIRRO` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `CIDADE` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `UF` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `ID_CLIENTE` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ENDERECO` (`ID_CLIENTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_endereco_loja`
--

DROP TABLE IF EXISTS `tb_endereco_loja`;
CREATE TABLE IF NOT EXISTS `tb_endereco_loja` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CEP` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `RUA` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `NUMERO` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `REFERENCIA` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BAIRRO` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `CIDADE` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `UF` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `ID_LOJA` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ENDERECO_LOJA` (`ID_LOJA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_loja`
--

DROP TABLE IF EXISTS `tb_loja`;
CREATE TABLE IF NOT EXISTS `tb_loja` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CNPJ` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `SEQUENCIA` int(11) NOT NULL,
  `NOME_LOJA` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `LOCAL` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `tb_loja` (`ID`, `CNPJ`, `SEQUENCIA`, `NOME_LOJA`, `LOCAL`) VALUES
(1, '000.000.000/0000-00', 0, 'TODAS', 'TODAS');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pedido`
--

DROP TABLE IF EXISTS `tb_pedido`;
CREATE TABLE IF NOT EXISTS `tb_pedido` (
  `NUMERO_PEDIDO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CLIENTE` int(11) NOT NULL,
  `ID_LOJA` int(11) NOT NULL,
  `ID_STATUS` int(11) NOT NULL DEFAULT 4,
  `VALOR` float(10,2) NOT NULL DEFAULT 0.00,
  `NUM_PDV` int(11) NOT NULL DEFAULT 0,
  `NUM_CUPOM` int(11) NOT NULL DEFAULT 0,
  `FORMA_PAGAMENTO` int(11) NOT NULL,
  `DATA_CRIACAO` date NOT NULL,
  PRIMARY KEY (`NUMERO_PEDIDO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_forma_pagamento`
--

DROP TABLE IF EXISTS `tb_forma_pagamento`;
CREATE TABLE IF NOT EXISTS `tb_forma_pagamento` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `COD_FORMA_PAGAMENTO` int(11) NOT NULL,
  `DESCRICAO_FORMA_PAGAMENTO` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tb_forma_pagamento` (`ID`,`COD_FORMA_PAGAMENTO`, `DESCRICAO_FORMA_PAGAMENTO`) VALUES
(1, 0, 'Não Definido'),(2, 1, 'C. Debito'),(3, 2, 'C. Credito'),(4, 3, 'Dinheiro'),(5, 4, 'Pic Pay'),(6, 5, 'Pix');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produto`
--

DROP TABLE IF EXISTS `tb_produto`;
CREATE TABLE IF NOT EXISTS `tb_produto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_PRODUTO` varchar(13) NOT NULL,
  `DESCRICAO_PRODUTO` text COLLATE utf8_unicode_ci NOT NULL,
  `VALOR_PRODUTO` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_produto_pedido`
--

DROP TABLE IF EXISTS `tb_produto_pedido`;
CREATE TABLE IF NOT EXISTS `tb_produto_pedido` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NUMERO_PEDIDO` int(11) NOT NULL,
  `CODIGO_PRODUTO` varchar(13) NOT NULL,
  `DESCRICAO_PRODUTO` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `VALOR_UN` float(10,2) NOT NULL,
  `QTD_PRODUTO` float(10,3) NOT NULL,
  `VALOR_TOTAL` float(10,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PRODUTO_PEDIDO` (`NUMERO_PEDIDO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_status`
--

DROP TABLE IF EXISTS `tb_status`;
CREATE TABLE IF NOT EXISTS `tb_status` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIPO` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `DESCRICAO` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tb_status` (`ID`, `TIPO`, `DESCRICAO`) VALUES
(1, '1', 'NOVO'),
(2, '2', 'REALIZADO'),
(3, '3', 'EM PROCESSAMENTO'),
(4, '4', 'NÃO FINALIZADO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_telefone`
--

DROP TABLE IF EXISTS `tb_telefone`;
CREATE TABLE IF NOT EXISTS `tb_telefone` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TELEFONE` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `TIPO` enum('CEL','RES','COM') COLLATE utf8_unicode_ci NOT NULL,
  `ID_CLIENTE` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_TELEFONE` (`ID_CLIENTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipo_usuario`
--

DROP TABLE IF EXISTS `tb_tipo_usuario`;
CREATE TABLE IF NOT EXISTS `tb_tipo_usuario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIPO` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `DESCRICAO` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tb_tipo_usuario` (`ID`, `TIPO`, `DESCRICAO`) VALUES
(1, '1', 'Admin'),
(2, '2', 'Padrão');
-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `LOGIN` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `EMAIL` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `SENHA` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ID_TIPO` int(11) NOT NULL,
  `ID_lOJA` int(11) NOT NULL DEFAULT 0,
  `IMG` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`NOME`, `LOGIN`, `EMAIL`, `SENHA`, `ID_TIPO`, `ID_LOJA`, `IMG`) VALUES
('Administrador', 'admin', 'admin@admin.com.br', '21232f297a57a5a743894a0e4a801fc3', 1, 1, NULL);


--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_detalhe_pedido`
--
ALTER TABLE `tb_detalhe_pedido`
  ADD CONSTRAINT `FK_DETALHE_PEDIDO` FOREIGN KEY (`NUMERO_PEDIDO`) REFERENCES `tb_pedido` (`NUMERO_PEDIDO`) ON DELETE CASCADE;
  

--
-- Limitadores para a tabela `tb_detalhe_pedido`
--
ALTER TABLE `tb_produto_pedido`
  ADD CONSTRAINT `FK_PRODUTO_PEDIDO` FOREIGN KEY (`NUMERO_PEDIDO`) REFERENCES `tb_pedido` (`NUMERO_PEDIDO`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tb_endereco`
--
ALTER TABLE `tb_endereco`
  ADD CONSTRAINT `FK_ENDERECO` FOREIGN KEY (`ID_CLIENTE`) REFERENCES `tb_cliente` (`ID`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tb_endereco_loja`
--
ALTER TABLE `tb_endereco_loja`
  ADD CONSTRAINT `FK_ENDERECO_LOJA` FOREIGN KEY (`id_loja`) REFERENCES `tb_loja` (`ID`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tb_telefone`
--
ALTER TABLE `tb_telefone`
  ADD CONSTRAINT `FK_TELEFONE` FOREIGN KEY (`ID_CLIENTE`) REFERENCES `tb_cliente` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
