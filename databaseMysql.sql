CREATE DATABASE IF NOT EXISTS SubredSur;
USE SubredSur;

CREATE TABLE dbo.Country(
	id 						int NOT NULL,
	code					int NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_country PRIMARY KEY(id)
);

CREATE TABLE dbo.Department(
	id 						int NOT NULL,
	country_id				int NOT NULL,
	code					int NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_department PRIMARY KEY(id),
	CONSTRAINT fk_country FOREIGN KEY(country_id) REFERENCES country(id)
);

CREATE TABLE dbo.Municipality(
	id 						int NOT NULL,
	department_id			int NOT NULL,
	name 					varchar(255) NOT NULL,
	CONSTRAINT pk_municipality PRIMARY KEY(id),
	CONSTRAINT fk_department FOREIGN KEY(department_id) REFERENCES department(id)
);

CREATE TABLE dbo.Means(
	id 						int NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_means PRIMARY KEY(id)
);

CREATE TABLE dbo.Dependence(
	id 						int NOT NULL,
	code					int NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_dependence PRIMARY KEY(id)
);

CREATE TABLE dbo.Document_type(
	id 						int NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_document_type PRIMARY KEY(id)
);

CREATE TABLE dbo.Documents(
	id 						int NOT NULL,
	name					varchar(255) NOT NULL,
	document_name			varchar(255) NOT NULL,
	created_at				varchar(255),
	CONSTRAINT pk_documents PRIMARY KEY(id)
);

CREATE TABLE dbo.User_clasification(
	id 						int NOT NULL,
	name					varchar(255) NOT NULL,
	CONSTRAINT pk_user_clasification PRIMARY KEY(id)
);

CREATE TABLE dbo.App_users(
	id 						int NOT NULL,
	user_clasification_id	int NOT NULL,
	name 					varchar(255) NOT NULL,
	surname					varchar(255) NOT NULL,
	second_surname			varchar(255),
	phone					varchar(12),
	address					varchar(255),
	mail 					varchar(255)
	CONSTRAINT pk_app_users PRIMARY KEY(id),
	CONSTRAINT fk_user_clasification FOREIGN KEY(user_clasification_id) REFERENCES user_clasification(id)
);

CREATE TABLE dbo.Filed_in(
	id 						int NOT NULL,
	app_users_id			int NOT NULL,
	country_id				int NOT NULL,
	department_id			int NOT NULL,
	municipality_id			int NOT NULL,
	means_id				int NOT NULL,
	dependence_id			int NOT NULL,
	document_type_id		int NOT NULL,
	documents_id			int NOT NULL,
	subject					text NOT NULL,
	CONSTRAINT pk_field_in PRIMARY KEY(id),
	CONSTRAINT fk_app_users FOREIGN KEY(app_users_id) REFERENCES app_users(id),
	CONSTRAINT fk_country_filed FOREIGN KEY(country_id) REFERENCES country(id),
	CONSTRAINT fk_department_filed FOREIGN KEY(department_id) REFERENCES department(id),
	CONSTRAINT fk_municipality_filed FOREIGN KEY(municipality_id) REFERENCES municipality(id),
	CONSTRAINT fk_means_filed FOREIGN KEY(means_id) REFERENCES means(id),
	CONSTRAINT fk_dependence_filed FOREIGN KEY(dependence_id) REFERENCES dependence(id),
	CONSTRAINT fk_document_type_filed FOREIGN KEY(document_type_id) REFERENCES document_type(id),
	CONSTRAINT fk_documents_filed FOREIGN KEY(documents_id) REFERENCES documents(id)
);
GO