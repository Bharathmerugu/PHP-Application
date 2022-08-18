

CREATE TABLE `cart` (
  `product` varchar(60) NOT NULL,
  `quantities` float NOT NULL,
  `user` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4



CREATE TABLE `comments` (
  `product` varchar(40) NOT NULL,
  `user` varchar(40) NOT NULL,
  `rating` int(10) NOT NULL,
  `image` varchar(60) NOT NULL,
  `text` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


CREATE TABLE `product` (
  `productname` varchar(60) NOT NULL,
  `description` varchar(60) NOT NULL,
  `image` varchar(100) NOT NULL,
  `pricing` float NOT NULL,
  `shippingcost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4


CREATE TABLE `users` (
  `id` int(60) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(30) NOT NULL,
  `role` varchar(30) NOT NULL,
  `shippingaddress` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `EMAIL_INDEX` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4

