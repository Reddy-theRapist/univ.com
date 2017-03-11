CREATE TABLE `demo_db`.`demo_table`
(
  `id` INT NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(50) NOT NULL ,
  `author` VARCHAR(100) NOT NULL ,
  `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  `publication_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `demo_table` (`id`, `subject`, `author`, `content`, `publication_date`) VALUES
  (NULL, 'Сабджект', 'Ауфор', 'контент', CURRENT_TIMESTAMP),
  (NULL, 'аж 2 сразу', 'афтор жжот ', 'я пацталом', CURRENT_TIMESTAMP)