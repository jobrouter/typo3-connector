CREATE TABLE tx_jobrouterconnector_domain_model_connection (
	name varchar(255)  DEFAULT '' NOT NULL,
	handle varchar(30) DEFAULT '' NOT NULL,
	base_url varchar(255) DEFAULT '' NOT NULL,
	username varchar(50)  DEFAULT '' NOT NULL,
	password varchar(255) DEFAULT '' NOT NULL,
	jobrouter_version varchar(10) DEFAULT '' NOT NULL,
	description text,

	UNIQUE KEY handle (handle)
);
