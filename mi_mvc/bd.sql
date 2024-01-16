drop database  if exists mvc_pdo;

CREATE database mvc_pdo;
commit;

USE  mvc_pdo;

CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFiscal` varchar(9) NOT NULL COMMENT 'CIF DNI ESPANYOLES',
  `contact_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone_number` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_phone_number` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_contact_email_unique` (`contact_email`),
  UNIQUE KEY `users_cif_dni` (`idFiscal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- mvc_pdo.users definition

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usuario` varchar(100) NOT NULL COMMENT 'Para el login equivalente a nick',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_UN` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- mvc_pdo.projects definition

CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Abierto',
  `user_id` bigint(20) unsigned DEFAULT NULL COMMENT 'JEFE DE PROYECTO',
  `client_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_client_id_foreign` (`client_id`) USING BTREE,
  KEY `projects_user_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `projects_FK` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `projects_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- mvc_pdo.tasks definition

CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `task_status` varchar(255) NOT NULL DEFAULT 'Abierto',
  `user_id` bigint(20) unsigned DEFAULT NULL COMMENT 'RESPONSABLE DE TAREA',
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `project_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_client_id_foreign` (`client_id`) USING BTREE,
  KEY `tasks_project_id_foreign` (`project_id`) USING BTREE,
  KEY `tasks_user_id_foreign` (`user_id`) USING BTREE,
  CONSTRAINT `tasks_FK` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tasks_FK_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tasks_FK_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


insert into users (usuario,password,email,name) value ('ana','ana','ana@gmail.com','Ana Lopez');
insert into users (usuario,password,email,name) value ('luis','luis','luis@gmail.com','Luis Perez');
insert into users (usuario,password,email,name) value ('admin','admin','admin@gmail.com','Admin Mola');
insert into users (usuario,password,email,name) value ('maria','maria','maria@gmail.com','Maria Lopez');

commit; 


insert into projects (name,description,deadline,status,user_id) value ('Proyecto Prueba', 'Muchas cosas','23/11/11','Abierto',1);
insert into projects (name,description,deadline,status,user_id) value ('Proyecto Prueba2 ', 'Demasiado trabajo','24/01/11','Abierto',1);
commit;

