-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- CREATE TABLE "game" -----------------------------------------
CREATE TABLE `game`( 
	`id` Int( 0 ) AUTO_INCREMENT NOT NULL,
	`winner_id` Int( 0 ) NULL,
	`datetime` DateTime NULL,
	`is_finished` TinyInt( 1 ) NOT NULL,
	`is_ia` TinyInt( 1 ) NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE "player" ---------------------------------------
CREATE TABLE `player`( 
	`id` Int( 0 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 50 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `UNIQ_98197A655E237E06` UNIQUE( `name` ) )
CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "turn" -----------------------------------------
CREATE TABLE `turn`( 
	`id` Int( 0 ) AUTO_INCREMENT NOT NULL,
	`player_id` Int( 0 ) NULL,
	`game_id` Int( 0 ) NULL,
	`datetime` DateTime NULL,
	`column_position` Int( 0 ) NOT NULL,
	`row_position` Int( 0 ) NOT NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- Dump data of "game" -------------------------------------
-- ---------------------------------------------------------


-- Dump data of "player" -----------------------------------
-- ---------------------------------------------------------


-- Dump data of "turn" -------------------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "IDX_232B318C5DFCD4B8" -------------------------
CREATE INDEX `IDX_232B318C5DFCD4B8` USING BTREE ON `game`( `winner_id` );
-- -------------------------------------------------------------


-- CREATE INDEX "IDX_2020154799E6F5DF" -------------------------
CREATE INDEX `IDX_2020154799E6F5DF` USING BTREE ON `turn`( `player_id` );
-- -------------------------------------------------------------


-- CREATE INDEX "IDX_20201547E48FD905" -------------------------
CREATE INDEX `IDX_20201547E48FD905` USING BTREE ON `turn`( `game_id` );
-- -------------------------------------------------------------


-- CREATE LINK "FK_2020154799E6F5DF" ---------------------------
ALTER TABLE `turn`
	ADD CONSTRAINT `FK_2020154799E6F5DF` FOREIGN KEY ( `player_id` )
	REFERENCES `player`( `id` )
	ON DELETE No Action
	ON UPDATE No Action;
-- -------------------------------------------------------------


-- CREATE LINK "FK_20201547E48FD905" ---------------------------
ALTER TABLE `turn`
	ADD CONSTRAINT `FK_20201547E48FD905` FOREIGN KEY ( `game_id` )
	REFERENCES `game`( `id` )
	ON DELETE No Action
	ON UPDATE No Action;
-- -------------------------------------------------------------


-- CREATE LINK "FK_232B318C5DFCD4B8" ---------------------------
ALTER TABLE `game`
	ADD CONSTRAINT `FK_232B318C5DFCD4B8` FOREIGN KEY ( `winner_id` )
	REFERENCES `player`( `id` )
	ON DELETE No Action
	ON UPDATE No Action;
-- -------------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


