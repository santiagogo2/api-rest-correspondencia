CREATE DATABASE IF NOT EXISTS SubredSur;
USE SubredSur;

CREATE TABLE country(
	id 						int(100) auto_increment NOT NULL,
	code					int(100) NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_country PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE department(
	id 						int(100) auto_increment NOT NULL,
	country_id				int(100) NOT NULL,
	code					int(100) NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_department PRIMARY KEY(id),
	CONSTRAINT fk_country FOREIGN KEY(country_id) REFERENCES country(id)
) ENGINE=InnoDb;

CREATE TABLE municipality(
	id 						int(100) auto_increment NOT NULL,
	department_id			int(100) NOT NULL,
	name 					varchar(255) NOT NULL,
	CONSTRAINT pk_municipality PRIMARY KEY(id),
	CONSTRAINT fk_department FOREIGN KEY(department_id) REFERENCES department(id)
) ENGINE=InnoDb;

CREATE TABLE means(
	id 						int(100) auto_increment NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_means PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE dependence(
	id 						int(100) auto_increment NOT NULL,
	code					int(100) NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_dependence PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE document_type(
	id 						int(100) auto_increment NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_document_type PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE documents(
	id 						int(100) auto_increment NOT NULL,
	name					varchar(255) NOT NULL,
	document_name			varchar(255) NOT NULL,
	created_at				varchar(255),
	CONSTRAINT pk_documents PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE user_clasification(
	id 						int(100) auto_increment NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_user_clasification PRIMARY KEY(id)
) ENGINE=InnoDb;

CREATE TABLE app_users(
	id 						int(100) auto_increment NOT NULL,
	user_clasification_id	int(100) NOT NULL,
	country_id				int(100) NOT NULL,
	department_id			int(100) NOT NULL,
	municipality_id			int(100) NOT NULL,
	name 					varchar(255) NOT NULL,
	surname					varchar(255) NOT NULL,
	second_surname			varchar(255),
	phone					varchar(12),
	address					varchar(255),
	mail 					varchar(255)
	CONSTRAINT pk_app_users PRIMARY KEY(id),
	CONSTRAINT fk_user_clasification FOREIGN KEY(user_clasification_id) REFERENCES user_clasification(id),
	CONSTRAINT fk_country FOREIGN KEY(country_id) REFERENCES country(id),
	CONSTRAINT fk_department FOREIGN KEY(department_id) REFERENCES department(id),
	CONSTRAINT fk_municipality FOREIGN KEY(municipality_id) REFERENCES municipality(id),
) ENGINE=InnoDb;

CREATE TABLE filed_in(
	id 						int(100) auto_increment NOT NULL,
	app_users_id			int(100) NOT NULL,
	means_id				int(100) NOT NULL,
	dependence_id			int(100) NOT NULL,
	document_type_id		int(100) NOT NULL,
	documents_id			int(100) NOT NULL,
	subject					text NOT NULL,
	CONSTRAINT pk_field_in PRIMARY KEY(id),
	CONSTRAINT fk_app_users FOREIGN KEY(app_users_id) REFERENCES app_users(id),
	CONSTRAINT fk_means_filed FOREIGN KEY(means_id) REFERENCES means(id),
	CONSTRAINT fk_dependence_filed FOREIGN KEY(dependence_id) REFERENCES dependence(id),
	CONSTRAINT fk_document_type_filed FOREIGN KEY(document_type_id) REFERENCES document_type(id),
	CONSTRAINT fk_documents_filed FOREIGN KEY(documents_id) REFERENCES documents(id)
) ENGINE=InnoDb;

CREATE TABLE Memorandums(
	id 						int(100) auto_increment NOT NULL,
	app_users_id			int(100) NOT NULL,
	means_id				int(100) NOT NULL,
	dependence_id			int(100) NOT NULL,
	document_type_id		int(100) NOT NULL,
	documents_id			int(100) NOT NULL,
	dignitary				varchar(255),
	affair					text NOT NULL,
	annexes_description		text,
	number_folios			int NOT NULL,
	number_annexes			int NOT NULL,
	created_at				varchar(255) NOT NULL,
	updated_at				varchar(255) NOT NULL,
	CONSTRAINT pk_field_in PRIMARY KEY(id),
	CONSTRAINT fk_app_users_memorandum FOREIGN KEY(app_users_id) REFERENCES app_users(id),
	CONSTRAINT fk_means_memorandum FOREIGN KEY(means_id) REFERENCES means(id),
	CONSTRAINT fk_dependence_memorandum FOREIGN KEY(dependence_id) REFERENCES dependence(id),
	CONSTRAINT fk_document_type_memorandum FOREIGN KEY(document_type_id) REFERENCES document_type(id),
	CONSTRAINT fk_documents_memorandum FOREIGN KEY(documents_id) REFERENCES documents(id)
) ENGINE=InnoDb;