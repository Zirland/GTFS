SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `agency` ( 
	`agency_id` TINYINT(3) UNSIGNED NOT NULL,
	`agency_name` VARCHAR(100) NOT NULL,
	`agency_url` VARCHAR(255) NOT NULL,
	`agency_timezone` VARCHAR(50) NOT NULL,
	`agency_lang` VARCHAR(2),
	`agency_phone` VARCHAR(30),
	`agency_fare_url` VARCHAR(255),
	`agency_email` VARCHAR(100),
	PRIMARY KEY (`agency_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `calendar` ( 
	`service_id` TINYINT(3) UNSIGNED NOT NULL,
	`monday` TINYINT(1) UNSIGNED NOT NULL,
	`tuesday` TINYINT(1) UNSIGNED NOT NULL,
	`wednesday` TINYINT(1) UNSIGNED NOT NULL,
	`thursday` TINYINT(1) UNSIGNED NOT NULL,
	`friday` TINYINT(1) UNSIGNED NOT NULL,
	`saturday` TINYINT(1) UNSIGNED NOT NULL,
	`sunday` TINYINT(1) UNSIGNED NOT NULL,
	`start_date` INT(8) UNSIGNED NOT NULL,
	`end_date` INT(8) UNSIGNED NOT NULL,
	PRIMARY KEY (`service_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `route` ( 
	`route_id` VARCHAR(10) NOT NULL,
	`agency_id` TINYINT(3) UNSIGNED,
	`route_short_name` VARCHAR(30) NOT NULL,
	`route_long_name` VARCHAR(255) NOT NULL,
	`route_desc` VARCHAR(600),
	`route_type` SMALLINT(5) UNSIGNED NOT NULL,
	`route_url` VARCHAR(255),
	`route_color` VARCHAR(6),
	`route_text_color` VARCHAR(6),
	PRIMARY KEY (`route_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `stop` ( 
	`stop_id` VARCHAR(10) NOT NULL,
	`stop_code` VARCHAR(3),
	`stop_name` VARCHAR(50) NOT NULL,
	`stop_desc` VARCHAR(600),
	`stop_lat` VARCHAR(10) NOT NULL,
	`stop_lon` VARCHAR(10) NOT NULL,
	`zone_id` VARCHAR(5),
	`stop_url` VARCHAR(255),
	`location_type` TINYINT(1) UNSIGNED,
	`parent_station` VARCHAR(10),
	`stop_timezone` VARCHAR(50),
	`wheelchair_boarding` TINYINT(1) UNSIGNED,
	PRIMARY KEY (`stop_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `stoptime` ( 
	`trip_id` INT(10) UNSIGNED NOT NULL,
	`arrival_time` VARCHAR(8) NOT NULL,
	`departure_time` VARCHAR(8) NOT NULL,
	`stop_id` VARCHAR(10) NOT NULL,
	`stop_sequence` TINYINT(3) UNSIGNED NOT NULL,
	`stop_headsign` VARCHAR(50),
	`pickup_type` TINYINT(1) UNSIGNED,
	`drop_off_type` TINYINT(1) UNSIGNED,
	`shape_dist_traveled` DECIMAL(10,3) UNSIGNED,
	`timepoint` TINYINT(1) UNSIGNED,
	PRIMARY KEY (`trip_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `trip` ( 
	`route_id` VARCHAR(10) NOT NULL,
	`service_id` TINYINT(3) UNSIGNED NOT NULL,
	`trip_id` INT(10) UNSIGNED NOT NULL,
	`trip_headsign` VARCHAR(50),
	`trip_short_name` VARCHAR(50),
	`direction_id` TINYINT(1) UNSIGNED,
	`block_id` VARCHAR(5),
	`shape_id` INT(10) UNSIGNED,
	`wheelchair_accessible` TINYINT(1) UNSIGNED,
	`bikes_allowed` TINYINT(1) UNSIGNED,
	PRIMARY KEY (`trip_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `calendar_dates` ( 
	`service_id` TINYINT(3) UNSIGNED NOT NULL,
	`date` INT(8) UNSIGNED NOT NULL,
	`exception_type` TINYINT(1) UNSIGNED,
	PRIMARY KEY (`service_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `fare_attribute` ( 
	`fare_id` VARCHAR(5) NOT NULL,
	`price` DECIMAL(6,2) UNSIGNED NOT NULL,
	`currency_type` VARCHAR(3) NOT NULL,
	`payment_method` TINYINT(1) NOT NULL,
	`transfers` VARCHAR(1) NOT NULL,
	`transfer_duration` INT(10) UNSIGNED,
	PRIMARY KEY (`fare_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `fare_rule` ( 
	`fare_id` VARCHAR(5) NOT NULL,
	`route_id` VARCHAR(10) NOT NULL,
	`origin_id` VARCHAR(5),
	`destination_id` VARCHAR(5),
	`contains_id` VARCHAR(5),
	PRIMARY KEY (`fare_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `feed_info` ( 
	`fare_id` VARCHAR(5) NOT NULL,
	`route_id` VARCHAR(10) NOT NULL,
	`origin_id` VARCHAR(5),
	`destination_id` VARCHAR(5),
	`contains_id` VARCHAR(5),
	PRIMARY KEY (`fare_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `frequency` ( 
	`trip_id` INT(10) UNSIGNED NOT NULL,
	`start_time` VARCHAR(8) NOT NULL,
	`end_time` VARCHAR(8) NOT NULL,
	`headway_secs` INT(10) UNSIGNED NOT NULL,
	`exact_times` TINYINT(1) UNSIGNED,
	PRIMARY KEY (`trip_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `shape` ( 
	`shape_id` INT(10) UNSIGNED NOT NULL,
	`shape_pt_lat` VARCHAR(10) NOT NULL,
	`shape_pt_lon` VARCHAR(10) NOT NULL,
	`shape_pt_sequence` INT(10) UNSIGNED NOT NULL,
	`shape_dist_traveled` DECIMAL(10,3) UNSIGNED,
	PRIMARY KEY (`shape_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE `transfer` ( 
	`from_stop_id` VARCHAR(10) NOT NULL,
	`to_stop_id` VARCHAR(10) NOT NULL,
	`transfer_type` TINYINT(1) UNSIGNED NOT NULL,
	`min_transfer_time` INT(10) UNSIGNED,
	PRIMARY KEY (`from_stop_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
