CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `login` varchar(30) NOT NULL,
    `email` varchar(30) NOT NULL,
    `password` varchar(64) NOT NULL,
    `group` enum('admin', 'manager'),
    PRIMARY KEY (`id`),
    UNIQUE KEY `login` (`login`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `category` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `site` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255),
    `last_sync_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_detail` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `site_id` int(11) NOT NULL,
    `price_selling` decimal(12,2) NOT NULL,
    `product_id` int(11) NOT NULL,
    `inner_product_id` int(11) NOT NULL,
    `income_clear` decimal(12,2) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
    FOREIGN KEY (`inner_product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_code` varchar(32) DEFAULT NULL,
    `product_name` varchar(255) NOT NULL,
    `category_id` int(11) NOT NULL,
    `price_purchase` decimal(12,2) NOT NULL DEFAULT '0.00',
    `amount_supplied` int(8) NOT NULL DEFAULT '0',
    `amount_in_stock` int(8) NOT NULL,
    `comment` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_sold` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `sale_date` DATE NOT NULL,
    `product_id` int(11) NOT NULL,
    `site_id` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
    FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `outlay` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(255) NOT NULL,
    `amount` decimal(12,2) NOT NULL,
    `outlay_date` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `income` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(255) NOT NULL,
    `amount` decimal(12,2) NOT NULL,
    `income_date` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pickup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `amount` decimal(12,2) NOT NULL,
    `pickup_date` DATE NOT NULL,
    `pickup_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ad_public` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ad_group` varchar(255) NOT NULL,
    `ad_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ad_id`) REFERENCES `ad` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ad` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `creator` varchar(255) NOT NULL,
    `price` decimal(12,2) NOT NULL,
    `ad_type` enum('C', 'P') NOT NULL,
    `amount` int(11) NOT NULL,
	`paid_date` date NOT NULL,
	`next_pay_date` date NOT NULL,
	`period` date NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_price` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `old_price` decimal(12,2) DEFAULT NULL,
    `new_price` decimal(12,2) DEFAULT NULL,
    `old_price_purchase` decimal(12,2) NOT NULL,
    `new_price_purchase` decimal(12,2) NOT NULL,
    `amount` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product_render` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    `render_price` decimal(12,2) NOT NULL,
    `render_date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sync_setting` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `site_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
    FOREIGN KEY (`site_id`) REFERENCES `site` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ad_category` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ad_other` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
	`ad_category_id` int(11) NOT NULL,
	`name` varchar(100) NOT NULL,
    `price` decimal(12, 2) NOT NULL,
	`paid_date` date NOT NULL,
	`next_pay_date` date NOT NULL,
	`period` date NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ad_category_id`) REFERENCES `ad_category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;