-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema dependencia
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema dependencia
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dependencia` DEFAULT CHARACTER SET utf8 ;
USE `dependencia` ;

-- -----------------------------------------------------
-- Table `dependencia`.`empresas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dependencia`.`empresas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_empresa` VARCHAR(100) NOT NULL,
  `nit` VARCHAR(10) NOT NULL,
  `correo` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(10) NOT NULL,
  `direccion` VARCHAR(100) NOT NULL,
  `nombre_representante_legal` VARCHAR(100) NULL,
  `contacto_representante_legal` VARCHAR(10) NULL,
  `correo_representante_legal` VARCHAR(100) NULL,
  `estado` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `nombre_empresa_UNIQUE` (`nombre_empresa` ASC) VISIBLE,
  UNIQUE INDEX `nit_UNIQUE` (`nit` ASC) VISIBLE,
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC) VISIBLE,
  UNIQUE INDEX `telefono_UNIQUE` (`telefono` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dependencia`.`dependencias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dependencia`.`dependencias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cod_dependencia` VARCHAR(50) NOT NULL,
  `nombre_dependencia` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(10) NULL,
  `direccion` VARCHAR(100) NULL,
  `estado` TINYINT NOT NULL DEFAULT 1,
  `empresas_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_dependencias_empresas1_idx` (`empresas_id` ASC) VISIBLE,
  CONSTRAINT `fk_dependencias_empresas1`
    FOREIGN KEY (`empresas_id`)
    REFERENCES `dependencia`.`empresas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dependencia`.`colaboradores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dependencia`.`colaboradores` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombres` VARCHAR(80) NOT NULL,
  `apellidos` VARCHAR(80) NOT NULL,
  `correo` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(10) NULL,
  `estado` TINYINT NOT NULL DEFAULT 1,
  `fecha_creacion` TIMESTAMP NULL DEFAULT now(),
  `fecha_actualizacion` TIMESTAMP NULL DEFAULT now(),
  `empresas_id` INT NOT NULL,
  `dependencias_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC) VISIBLE,
  INDEX `fk_colaboradores_empresas_idx` (`empresas_id` ASC) VISIBLE,
  INDEX `fk_colaboradores_dependencias1_idx` (`dependencias_id` ASC) VISIBLE,
  CONSTRAINT `fk_colaboradores_empresas`
    FOREIGN KEY (`empresas_id`)
    REFERENCES `dependencia`.`empresas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_colaboradores_dependencias1`
    FOREIGN KEY (`dependencias_id`)
    REFERENCES `dependencia`.`dependencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
