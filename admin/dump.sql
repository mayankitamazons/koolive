/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.1.30-MariaDB : Database - escorts
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`escorts` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `escorts`;

/*Table structure for table `adminbanner` */

DROP TABLE IF EXISTS `adminbanner`;

CREATE TABLE `adminbanner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `image_path` varchar(64) NOT NULL,
  `position` int(11) NOT NULL,
  `text` text,
  `active` varchar(64) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `adminbanner` */

insert  into `adminbanner`(`id`,`title`,`image_path`,`position`,`text`,`active`,`created_date`) values 
(3,'banner2','uploads/banner/images (5).jpg',10,'<p>This is banner2</p>','Yes','2018-10-12 11:56:27'),
(4,'banner1','uploads/banner/images (2).jpg',5,'<p>This is banner1</p>','No','2018-10-12 11:57:05'),
(5,'banner3','uploads/banner/nhs111 - banner 2.jpg',2,'<p>This is banner3</p>','Yes','2018-10-12 11:57:27');

/*Table structure for table `agencies` */

DROP TABLE IF EXISTS `agencies`;

CREATE TABLE `agencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `mobile` varchar(64) DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `info` text,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

/*Data for the table `agencies` */

insert  into `agencies`(`id`,`name`,`email`,`mobile`,`website`,`info`,`password`) values 
(111,'aaabbbccc','a@a.com','aaabbbccc','aaabbbccc','<p style=\"text-align: center;\"><em><strong>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbbbbbbbcccccccccccccccccccccccccccccc</strong></em></p>','d1aaf4767a3c10a473407a4e47b02da6'),
(112,'bbb','b@b.com','bbb','bbb','<p>bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb</p>','08f8e0260c64418510cefb2b06eee5cd'),
(113,'ccc','c@c.com','ccc','ccc','<p>cccccccccccccccccccccccccccccccccccccccc</p>','9df62e693988eb4e1e1444ece0578579'),
(114,'ddd','d@d.com','ddd','ddd','<p>dddddddddddddddddddddddddddd</p>','77963b7a931377ad4ab5ad6a9cd718aa');

/*Table structure for table `banners` */

DROP TABLE IF EXISTS `banners`;

CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `banner_path` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Data for the table `banners` */

insert  into `banners`(`id`,`email`,`banner_path`) values 
(19,'a@a.com','uploads/agency/banner/images (4).jpg'),
(20,'b@b.com','uploads/agency/banner/23276090_2018_09_21_09.30.48.png'),
(21,'c@c.com','uploads/agency/banner/choose image1 (3).png'),
(22,'d@d.com','uploads/agency/banner/5bb06c565250aFireShot Capture 3 - Vehicle ');

/*Table structure for table `blogs` */

DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `url` varchar(64) DEFAULT NULL,
  `content` text NOT NULL,
  `blog_image` varchar(64) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `blogs` */

insert  into `blogs`(`id`,`title`,`url`,`content`,`blog_image`,`created_date`) values 
(24,'Tiger','Tiger.com','<p>This is white tiger.</p>','uploads/blog/Beautiful_White_Tiger_Wallpaper.jpg','2018-10-11 14:47:29'),
(28,'lion','lion.com','<p>This is lion</p>','uploads/blog/2256381-white-lion-wallpaper.jpg','2018-10-11 16:34:59');

/*Table structure for table `broadcasts` */

DROP TABLE IF EXISTS `broadcasts`;

CREATE TABLE `broadcasts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `escort_id` int(11) NOT NULL,
  `text` text,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `broadcasts` */

insert  into `broadcasts`(`id`,`title`,`agency_id`,`escort_id`,`text`,`created_date`) values 
(1,'broadcast2',114,29,'<p><em><strong>ddd-sss</strong></em></p>','2018-10-12 14:24:20'),
(2,'broadcast1',111,37,'<p><em><strong>aaa-xx</strong></em></p>','2018-10-12 14:24:49');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL,
  `url` varchar(64) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

insert  into `categories`(`id`,`category`,`url`,`created_date`) values 
(1,'cate2','cate2','2018-10-09 03:26:45'),
(2,'cate1','cate1','2018-10-09 16:11:24'),
(3,'cate3','cate3','2018-10-09 16:11:32'),
(4,'cate4','cate4','2018-10-09 16:11:40');

/*Table structure for table `escorts` */

DROP TABLE IF EXISTS `escorts`;

CREATE TABLE `escorts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `masseuse` varchar(64) NOT NULL,
  `url` varchar(64) NOT NULL,
  `bio` text,
  `full_bio` text NOT NULL,
  `age` int(11) NOT NULL,
  `nationality` varchar(64) NOT NULL,
  `incall_location` varchar(64) NOT NULL,
  `outcall_location` varchar(64) NOT NULL,
  `hair` varchar(64) NOT NULL,
  `eyes` varchar(64) NOT NULL,
  `height` double NOT NULL,
  `weight` double NOT NULL,
  `statistics` varchar(64) NOT NULL,
  `language` varchar(64) NOT NULL,
  `active` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `category` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Data for the table `escorts` */

insert  into `escorts`(`id`,`agency_id`,`name`,`email`,`masseuse`,`url`,`bio`,`full_bio`,`age`,`nationality`,`incall_location`,`outcall_location`,`hair`,`eyes`,`height`,`weight`,`statistics`,`language`,`active`,`password`,`category`) values 
(29,111,'sss','s@s.com','Yes','sss','<p>ssssssssssssss</p>','<p style=\"text-align: center;\"><em><strong>sssssssssssssssssssssssss</strong></em></p>',111,'sss','sss','sss','sss','sss',111,111,'111','sss','Yes','9f6e6800cfae7749eb6c486619254b9c','[\"3\",\"4\"]'),
(30,111,'ttt','t@t.com','Yes','ttt','<p>ttt</p>','<p>ttttttttttttttttttttttttttttttt</p>',222,'ttt','ttt','ttt','ttt','ttt',222,222,'222','ttt','Yes','9990775155c3518a0d7917f7780b24aa','[\"1\",\"3\"]'),
(34,112,'eee','e@e.com','No','eee','<p>eeeee</p>','<p>eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee</p>',555,'eee','eee','eee','eee','eee',555,555,'555','eee','Yes','d2f2297d6e829cd3493aa7de4416a18f','[\"2\",\"4\"]'),
(35,113,'vvv','v@v.com','No','vvv','<p>vvvvv</p>','<p>vvvvvvvvvvvvvvvvvvvvvvvvvv</p>',121,'vvv','vvv','vvv','vvv','vvv',212,121,'121','vvv','Yes','4786f3282f04de5b5c7317c490c6d922','[\"3\",\"4\"]'),
(36,113,'kkk','k@k.com','No','kkk','<p>kkkk</p>','<p>kkkkkkkkkkkkkkkk</p>',777,'kkk','kkk','kkk','kkk','kkk',777,777,'777','kkk','Yes','cb42e130d1471239a27fca6228094f0e','[\"1\",\"2\"]'),
(37,113,'xxx','x@x.com','No','xxx','<p>xxxxxxxxxxx</p>','<p>xxxxxxxxxxxxxxxxxx</p>',0,'','','','','',0,0,'','','No','f561aaf6ef0bf14d4208bb46a4ccb3ad','[\"1\",\"2\"]');

/*Table structure for table `girls` */

DROP TABLE IF EXISTS `girls`;

CREATE TABLE `girls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `agency_id` int(11) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `nationality` varchar(64) NOT NULL,
  `incall_location` varchar(64) DEFAULT NULL,
  `outcall_location` varchar(64) DEFAULT NULL,
  `hair` varchar(64) DEFAULT NULL,
  `eyes` varchar(64) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `language` varchar(64) DEFAULT NULL,
  `statistics` varchar(64) DEFAULT NULL,
  `masseuse` int(11) DEFAULT NULL,
  `url` varchar(64) DEFAULT NULL,
  `bio` text,
  `full_bio` text,
  `mobile` varchar(64) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `address` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `girls` */

/*Table structure for table `logos` */

DROP TABLE IF EXISTS `logos`;

CREATE TABLE `logos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `logo_path` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

/*Data for the table `logos` */

insert  into `logos`(`id`,`email`,`logo_path`) values 
(113,'a@a.com','uploads/agency/logo/black_and_white_tiger__drawing.jpg'),
(114,'b@b.com','uploads/agency/logo/23276090_2018_09_21_09.30.48.png'),
(115,'c@c.com','uploads/agency/logo/choose image1 (3).png'),
(116,'d@d.com','uploads/agency/logo/5bb06c565250aFireShot Capture 3 - Vehicle - http___vehicle.com_main.php.png'),
(119,'s@s.com','uploads/escort/logo/large_4764ad52b10d0bfa3f62e8ff933f9658.jpg'),
(120,'t@t.com','uploads/escort/logo/lion-drawing-wallpaper-60.jpg'),
(123,'e@e.com','uploads/escort/logo/24acef8b3a6a45d7239480bcc4ff0193.jpg'),
(124,'v@v.com','uploads/escort/logo/lionking.jpg'),
(125,'k@k.com','uploads/escort/logo/9329d0432af08368a94dc689f628660a--cool-live-wallpapers-lion.jpg'),
(126,'x@x.com','uploads/escort/logo/lion-drawing-wallpaper-60.jpg');

/*Table structure for table `rates` */

DROP TABLE IF EXISTS `rates`;

CREATE TABLE `rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `duration` varchar(64) NOT NULL,
  `rate` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Data for the table `rates` */

insert  into `rates`(`id`,`email`,`duration`,`rate`) values 
(8,'v@v.com','morning','3'),
(9,'v@v.com','day','5'),
(10,'v@v.com','midnight','4'),
(11,'v@v.com','evening','2'),
(12,'k@k.com','morning','4'),
(13,'k@k.com','midnight','2'),
(14,'k@k.com','day','5'),
(15,'k@k.com','evening','3'),
(16,'k@k.com','anytime','4'),
(19,'x@x.com','morning','1'),
(20,'x@x.com','day','2'),
(21,'x@x.com','night','3'),
(22,'x@x.com','midnight','4'),
(28,'s@s.com','morning','3'),
(29,'s@s.com','midnight','5'),
(32,'t@t.com','',''),
(33,'t@t.com','','');

/*Table structure for table `thumbnails` */

DROP TABLE IF EXISTS `thumbnails`;

CREATE TABLE `thumbnails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `path` varchar(512) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;

/*Data for the table `thumbnails` */

insert  into `thumbnails`(`id`,`email`,`path`,`created_date`) values 
(83,'e@e.com','uploads/escort/thumbnails/9329d0432af08368a94dc689f628660a--cool-live-wallpapers-lion.jpg','2018-10-11 18:08:57'),
(84,'e@e.com','uploads/escort/thumbnails/ava-600x600.jpg','2018-10-11 18:08:57'),
(85,'e@e.com','uploads/escort/thumbnails/2256381-white-lion-wallpaper.jpg','2018-10-11 18:08:57'),
(88,'v@v.com','uploads/escort/thumbnails/Beautiful_White_Tiger_Wallpaper.jpg','2018-10-12 01:50:48'),
(89,'v@v.com','uploads/escort/thumbnails/black_and_white_tiger__drawing.jpg','2018-10-12 01:50:48'),
(93,'x@x.com','uploads/escort/thumbnails/download.jpg','2018-10-12 12:13:59'),
(94,'x@x.com','uploads/escort/thumbnails/Leon-recortado.jpg','2018-10-12 12:13:59'),
(95,'x@x.com','uploads/escort/thumbnails/lion_mane_predator_125068_300x168.jpg','2018-10-12 12:13:59'),
(103,'s@s.com','uploads/escort/thumbnails/24acef8b3a6a45d7239480bcc4ff0193.jpg','2018-10-12 18:22:08'),
(104,'s@s.com','uploads/escort/thumbnails/2256381-white-lion-wallpaper.jpg','2018-10-12 18:22:08'),
(105,'s@s.com','uploads/escort/thumbnails/Beautiful_White_Tiger_Wallpaper.jpg','2018-10-12 18:22:08'),
(170,'t@t.com','uploads/escort/thumbnails/lionking.jpg','2018-10-12 18:23:06'),
(171,'t@t.com','uploads/escort/thumbnails/images.jpg','2018-10-12 18:23:06'),
(172,'t@t.com','uploads/escort/thumbnails/download.jpg','2018-10-12 18:23:06'),
(173,'t@t.com','uploads/escort/thumbnails/sleeping-lion-background_wallcg.jpg','2018-10-12 18:23:06');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `role` int(11) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`role`,`password`) values 
(1,'admin@admin.com',0,'21232f297a57a5a743894a0e4a801fc3'),
(129,'a@a.com',1,'47bce5c74f589f4867dbd57e9ca9f808'),
(130,'b@b.com',1,'08f8e0260c64418510cefb2b06eee5cd'),
(131,'c@c.com',1,'9df62e693988eb4e1e1444ece0578579'),
(132,'d@d.com',1,'77963b7a931377ad4ab5ad6a9cd718aa'),
(136,'s@s.com',2,'9f6e6800cfae7749eb6c486619254b9c'),
(137,'t@t.com',2,'9990775155c3518a0d7917f7780b24aa'),
(141,'e@e.com',2,'d2f2297d6e829cd3493aa7de4416a18f'),
(142,'v@v.com',2,'4786f3282f04de5b5c7317c490c6d922'),
(143,'k@k.com',2,'cb42e130d1471239a27fca6228094f0e'),
(144,'x@x.com',2,'f561aaf6ef0bf14d4208bb46a4ccb3ad');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
