CREATE TABLE `gdvl_prod_reg`(
  `id` int NOT NULL primary key AUTO_INCREMENT,
  `newsLetEmailId` int NOT NULL,
  `zipCode` VARCHAR(255) NOT NULL,
  `state` VARCHAR(255) NOT NULL,
  `country` VARCHAR(255) NOT NULL,
  `styleNumber` VARCHAR(255) NOT NULL,
  `dateOfPurchase` DATE NOT NULL,
  FOREIGN KEY (`newsLetEmailId`) REFERENCES `wp_newsletter` (`id`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;