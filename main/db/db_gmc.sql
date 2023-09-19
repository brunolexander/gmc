SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `db_gmc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_gmc`;

CREATE TABLE IF NOT EXISTS `log` (
  `log` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `log`;
INSERT INTO `log` VALUES('Administrador <b>GMC</b> adicionou a pedra <b>Teste #4</b>.', '2017-11-17 16:27:59');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> adicionou a pedra <b>Teste #5</b>.', '2017-11-17 16:28:21');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> adicionou a pedra <b>Teste #6</b>', '2017-11-18 16:27:59');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> removeu a pedra <b>Teste </b>.', '2017-11-17 16:35:04');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> removeu a pedra <b>Teste #4</b>.', '2017-11-17 16:35:30');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> removeu a pedra <b>Teste #5</b>.', '2017-11-17 16:35:44');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> removeu a pedra <b>Teste #3</b>.', '2017-11-17 16:36:03');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> adicionou a pedra <b>teste #1</b>.', '2017-11-17 16:41:56');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> inseriu o usuário <b>Admin</b>.', '2017-11-17 16:45:10');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> atualizou o usuário <b>Admin</b>.', '2017-11-17 16:52:03');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> atualizou o usuário <b>Admin1</b>.', '2017-11-17 16:52:20');
INSERT INTO `log` VALUES('Administrador <b>GMC</b> removeu o usuário <b>Admin1</b>.', '2017-11-17 16:52:51');
INSERT INTO `log` VALUES('Administrador <b>Root</b> atualizou a pedra <b>Balrog-1 Blue Explosion</b>.', '2017-11-18 10:15:44');
INSERT INTO `log` VALUES('Administrador <b>Root</b> inseriu o slide <b>Lorem</b>.', '2017-11-18 10:27:47');
INSERT INTO `log` VALUES('Administrador <b>Root</b> removeu o slide <b>Lorem</b>.', '2017-11-18 10:28:21');
INSERT INTO `log` VALUES('Administrador <b>Root</b> adicionou <b>Lorem</b> no portfolio.', '2017-11-18 10:29:04');
INSERT INTO `log` VALUES('Administrador <b>Root</b> removeu <b>Lorem</b> do portfolio.', '2017-11-18 10:29:11');
INSERT INTO `log` VALUES('Administrador <b>Root</b> inseriu o usuário <b>Teste</b>.', '2017-11-18 10:38:28');
INSERT INTO `log` VALUES('Administrador <b>Root</b> deletou os usuários: Teste.', '2017-11-18 10:39:37');
INSERT INTO `log` VALUES('Administrador <b>Root</b> atualizou o(s) usuário(s): <b>GMC</b>.', '2017-11-18 10:50:14');
INSERT INTO `log` VALUES('Administrador <b>Root</b> atualizou o(s) usuário(s): <b>GMC</b>, <b>Root</b>.', '2017-11-18 10:50:34');
INSERT INTO `log` VALUES('Administrador <b>Root</b> removeu a pedra <b>Balrog-1 Blue Explosion</b>.', '2017-11-23 13:06:19');

CREATE TABLE IF NOT EXISTS `pedras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(35) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

TRUNCATE TABLE `pedras`;
INSERT INTO `pedras` VALUES(5, 'Amarelo Florença', '/img/amarelo_florenca.jpg');
INSERT INTO `pedras` VALUES(6, 'Amarelo Icaraí', '/img/Icarai.jpg');
INSERT INTO `pedras` VALUES(7, 'Amarelo Maracujá / Vitória', '/img/Maracuja.jpg');
INSERT INTO `pedras` VALUES(8, 'Amarelo Ornamental', '/img/Ornamental.jpg');
INSERT INTO `pedras` VALUES(9, 'Améndoa Rio (Dourado Carioca)', '/img/Amendoa.jpg');
INSERT INTO `pedras` VALUES(10, 'Branco Ceará', '/img/Ceara.jpg');
INSERT INTO `pedras` VALUES(11, 'Branco Dallas', '/img/Dallas.jpg');
INSERT INTO `pedras` VALUES(12, 'Branco Fortaleza / Cachimir', '/img/Fortaleza.jpg');
INSERT INTO `pedras` VALUES(13, 'Branco Itaúnas', '/img/Itaunas.jpg');
INSERT INTO `pedras` VALUES(14, 'Branco Marfim', '/img/Marfim.jpg');
INSERT INTO `pedras` VALUES(15, 'Branco Siena A', '/img/Siena_A.jpg');
INSERT INTO `pedras` VALUES(16, 'Branco Siena B', '/img/Siena_B.jpg');
INSERT INTO `pedras` VALUES(17, 'Café Imperial', '/img/Imperial.jpg');
INSERT INTO `pedras` VALUES(18, 'Cinza Bressan', '/img/Bressan.jpg');
INSERT INTO `pedras` VALUES(19, 'Cinza Castelo / Mundo Novo', '/img/Castelo.jpg');
INSERT INTO `pedras` VALUES(20, 'Cinza Corumbá', '/img/Corumba.jpg');
INSERT INTO `pedras` VALUES(21, 'Cinza Mauá', '/img/Maua.jpg');
INSERT INTO `pedras` VALUES(22, 'Coral', '/img/Coral.jpg');
INSERT INTO `pedras` VALUES(23, 'Jacarandá Vinho', '/img/jacaranda_vinho.jpg');
INSERT INTO `pedras` VALUES(24, 'Kinawa / Raissa', '/img/Kinawa.jpg');
INSERT INTO `pedras` VALUES(25, 'Marrom Absoluto', '/img/marrom_absoluto.jpg');
INSERT INTO `pedras` VALUES(26, 'Marrom Madeira A', '/img/marrom_madeira_a.jpg');
INSERT INTO `pedras` VALUES(27, 'Marrom Madeira B', '/img/marrom_madeira_b.jpg');
INSERT INTO `pedras` VALUES(28, 'Marrom São Paulo A', '/img/marrom_sao_paulo_a.jpg');
INSERT INTO `pedras` VALUES(29, 'Marrom São Paulo B', '/img/marrom_sao_paulo_b.jpg');
INSERT INTO `pedras` VALUES(30, 'Ocre Itabira', '/img/Ocre.jpg');
INSERT INTO `pedras` VALUES(31, 'Preto Absoluto Granito', '/img/pretoabsoluto.jpg');
INSERT INTO `pedras` VALUES(32, 'Preto Indiano', '/img/pretoindiano.jpg');
INSERT INTO `pedras` VALUES(33, 'Preto Minas (Diamante Negro)', '/img/pretominas.jpg');
INSERT INTO `pedras` VALUES(34, 'Preto Black Blue (P.A)', '/img/pretoblackblue.jpg');
INSERT INTO `pedras` VALUES(35, 'Preto São Gabriel', '/img/pretosaogabriel.jpg');
INSERT INTO `pedras` VALUES(36, 'Preto Via Lactea', '/img/pretovialactea.jpg');
INSERT INTO `pedras` VALUES(37, 'Samoa Light / Arabesco', '/img/Samoa.jpg');
INSERT INTO `pedras` VALUES(38, 'Santa Cecília', '/img/santacecilia.jpg');
INSERT INTO `pedras` VALUES(39, 'Spetacollo Brown', '/img/spetacollobrown.jpg');
INSERT INTO `pedras` VALUES(40, 'Verde Labrador / Verde Perola', '/img/verdelabrador.jpg');
INSERT INTO `pedras` VALUES(41, 'Giallo Califórnia', '/img/giallocalifornia.jpg');
INSERT INTO `pedras` VALUES(42, 'Verde Aquários / Verde Candeias', '/img/verdeaquarios.jpg');
INSERT INTO `pedras` VALUES(43, 'Vermelho Bragança', '/img/vermelhobraganca.jpg');
INSERT INTO `pedras` VALUES(44, 'Vermelho Brasília', '/img/vermelho_brasilia.jpg');
INSERT INTO `pedras` VALUES(45, 'White Piracema', '/img/whitepiracema.jpg');
INSERT INTO `pedras` VALUES(46, 'Ardósia (Lustrada e Calibrada)', '/img/ardosia.jpg');
INSERT INTO `pedras` VALUES(47, 'Ardósia Bruta', '/img/ardosia.jpg');
INSERT INTO `pedras` VALUES(48, 'Ardósia Lustrada 2 lados', '/img/ardosia.jpg');
INSERT INTO `pedras` VALUES(49, 'Travertino', '/img/travertino.jpg');
INSERT INTO `pedras` VALUES(50, 'Teste', '/img/pretoabsoluto.jpg');
INSERT INTO `pedras` VALUES(51, 'Teste 2', '/img/Amendoa.jpg');
INSERT INTO `pedras` VALUES(65, 'teste #1', '/img/sem_img.jpg');

CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(35) NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

TRUNCATE TABLE `portfolio`;
INSERT INTO `portfolio` VALUES(1, '123 #1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitatio, teste.', '/img/Amendoa.jpg');
INSERT INTO `portfolio` VALUES(2, 'Teste #2', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitatio.', '/img/Amendoa.jpg');
INSERT INTO `portfolio` VALUES(3, 'Teste #3', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitatio.', '/img/Amendoa.jpg');

CREATE TABLE IF NOT EXISTS `slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) NOT NULL,
  `descricao` varchar(150) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

TRUNCATE TABLE `slide`;
INSERT INTO `slide` VALUES(1, 'Teste #1', 'Lorem Ipsum Dolor', '/img/ardosia.jpg');
INSERT INTO `slide` VALUES(2, 'Teste #2', 'Lorem Ipsum Dolor', '/img/pretoabsoluto.jpg');
INSERT INTO `slide` VALUES(3, 'Teste #3', 'Lorem Ipsum Dolor', '/img/verdelabrador.jpg');
INSERT INTO `slide` VALUES(4, 'Teste #4', 'Lorem Ipsum Dolor', '/img/Coral.jpg');
INSERT INTO `slide` VALUES(5, 'Teste #5', 'Lorem Ipsum Dolor', '/img/Imperial.jpg');
INSERT INTO `slide` VALUES(6, 'Teste #6', 'Lorem Ipsum Dolor', '/img/verdecandeias.jpg');

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

TRUNCATE TABLE `tag`;
INSERT INTO `tag` VALUES(18, 'mármore');
INSERT INTO `tag` VALUES(19, 'granito');

CREATE TABLE IF NOT EXISTS `tag_relac` (
  `id_tag` int(11) NOT NULL,
  `id_pedra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE TABLE `tag_relac`;
INSERT INTO `tag_relac` VALUES(19, 5);
INSERT INTO `tag_relac` VALUES(19, 6);
INSERT INTO `tag_relac` VALUES(19, 7);
INSERT INTO `tag_relac` VALUES(19, 8);
INSERT INTO `tag_relac` VALUES(19, 9);
INSERT INTO `tag_relac` VALUES(19, 10);
INSERT INTO `tag_relac` VALUES(19, 11);
INSERT INTO `tag_relac` VALUES(19, 12);
INSERT INTO `tag_relac` VALUES(19, 13);
INSERT INTO `tag_relac` VALUES(19, 14);
INSERT INTO `tag_relac` VALUES(19, 15);
INSERT INTO `tag_relac` VALUES(19, 16);
INSERT INTO `tag_relac` VALUES(19, 17);
INSERT INTO `tag_relac` VALUES(19, 18);
INSERT INTO `tag_relac` VALUES(19, 19);
INSERT INTO `tag_relac` VALUES(19, 20);
INSERT INTO `tag_relac` VALUES(19, 21);
INSERT INTO `tag_relac` VALUES(19, 22);
INSERT INTO `tag_relac` VALUES(19, 23);
INSERT INTO `tag_relac` VALUES(19, 24);
INSERT INTO `tag_relac` VALUES(19, 25);
INSERT INTO `tag_relac` VALUES(19, 26);
INSERT INTO `tag_relac` VALUES(19, 27);
INSERT INTO `tag_relac` VALUES(19, 28);
INSERT INTO `tag_relac` VALUES(19, 29);
INSERT INTO `tag_relac` VALUES(19, 30);
INSERT INTO `tag_relac` VALUES(19, 31);
INSERT INTO `tag_relac` VALUES(19, 32);
INSERT INTO `tag_relac` VALUES(19, 33);
INSERT INTO `tag_relac` VALUES(19, 34);
INSERT INTO `tag_relac` VALUES(19, 35);
INSERT INTO `tag_relac` VALUES(19, 36);
INSERT INTO `tag_relac` VALUES(19, 37);
INSERT INTO `tag_relac` VALUES(19, 38);
INSERT INTO `tag_relac` VALUES(19, 39);
INSERT INTO `tag_relac` VALUES(19, 40);
INSERT INTO `tag_relac` VALUES(19, 41);
INSERT INTO `tag_relac` VALUES(19, 42);
INSERT INTO `tag_relac` VALUES(19, 43);
INSERT INTO `tag_relac` VALUES(19, 44);
INSERT INTO `tag_relac` VALUES(19, 45);
INSERT INTO `tag_relac` VALUES(19, 46);
INSERT INTO `tag_relac` VALUES(19, 47);
INSERT INTO `tag_relac` VALUES(19, 48);
INSERT INTO `tag_relac` VALUES(18, 49);
INSERT INTO `tag_relac` VALUES(19, 50);
INSERT INTO `tag_relac` VALUES(19, 51);
INSERT INTO `tag_relac` VALUES(19, 65);

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(75) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `acesso` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

TRUNCATE TABLE `usuarios`;
INSERT INTO `usuarios` VALUES(8, 'GMC', 'gmc', 'gmc@gmc.com', '19b6b9192d17760f89de4817bfae42bd', 'Administrador');
INSERT INTO `usuarios` VALUES(11, 'Root', 'root', 'root@root.com', '63a9f0ea7bb98050796b649e85481845', 'Administrador');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
