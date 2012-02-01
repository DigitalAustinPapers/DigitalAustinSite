SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `AustinCollection` ;
CREATE SCHEMA IF NOT EXISTS `AustinCollection` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `AustinCollection` ;

-- -----------------------------------------------------
-- Table `AustinCollection`.`Document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Document` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Document` (
  `idDocument` VARCHAR(11) NOT NULL ,
  `creation` DATE NULL ,
  `citation` VARCHAR(250) NULL ,
  `language` VARCHAR(20) NULL ,
  `status` VARCHAR(10) NULL ,
  `type` VARCHAR(50) NULL ,
  `sender` VARCHAR(50) NULL ,
  `title` VARCHAR(250) NULL ,
  `digital_creation` VARCHAR(45) NULL ,
  `author` VARCHAR(150) NULL ,
  `recipient` VARCHAR(150) NULL ,
  PRIMARY KEY (`idDocument`) )
ENGINE = InnoDB
COMMENT = 'Document metadata' ;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Barker`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Barker` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Barker` (
  `barker_summary` TEXT NULL ,
  `footnotes` TEXT NULL ,
  `barker_citation` VARCHAR(250) NULL ,
  `idDocument` VARCHAR(11) NOT NULL ,
  PRIMARY KEY (`idDocument`) ,
  CONSTRAINT `fk_Barker_Document1`
    FOREIGN KEY (`idDocument` )
    REFERENCES `AustinCollection`.`Document` (`idDocument` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Text`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Text` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Text` (
  `body` TEXT NULL ,
  `summary` TEXT NULL ,
  `idDocument` VARCHAR(11) NOT NULL ,
  PRIMARY KEY (`idDocument`) ,
  FULLTEXT INDEX `bodyFulltext` (`body` ASC, `summary` ASC) ,
  CONSTRAINT `fk_Text_Document1`
    FOREIGN KEY (`idDocument` )
    REFERENCES `AustinCollection`.`Document` (`idDocument` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Place`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Place` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Place` (
  `sent_from` VARCHAR(55) NULL ,
  `sent_to` VARCHAR(90) NULL ,
  `idDocument` VARCHAR(11) NOT NULL ,
  `normalized_from` VARCHAR(90) NULL ,
  `normalized_to` VARCHAR(90) NULL ,
  PRIMARY KEY (`idDocument`) ,
  CONSTRAINT `fk_Place_Document1`
    FOREIGN KEY (`idDocument` )
    REFERENCES `AustinCollection`.`Document` (`idDocument` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`People`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`People` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`People` (
  `person` VARCHAR(250) NOT NULL ,
  `peopleList` TEXT NOT NULL ,
  PRIMARY KEY (`person`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Places`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Places` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Places` (
  `place` VARCHAR(250) NOT NULL ,
  `placesList` TEXT NOT NULL ,
  PRIMARY KEY (`place`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Authors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Authors` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Authors` (
  `author` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`author`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Recipients`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Recipients` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Recipients` (
  `recipient` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`recipient`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Coordinates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Coordinates` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Coordinates` (
  `idCity` VARCHAR(90) NOT NULL ,
  `lat` DOUBLE NULL ,
  `lon` DOUBLE NULL ,
  PRIMARY KEY (`idCity`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AustinCollection`.`Search_index`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AustinCollection`.`Search_index` ;

CREATE  TABLE IF NOT EXISTS `AustinCollection`.`Search_index` (
  `word` VARCHAR(100) NOT NULL ,
  `documentList` TEXT NOT NULL ,
  PRIMARY KEY (`word`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
