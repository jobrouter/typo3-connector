CREATE TABLE tx_jobrouterconnector_domain_model_connection (
	name varchar(255)  DEFAULT '' NOT NULL,
	base_url varchar(255) DEFAULT '' NOT NULL,
	username varchar(50)  DEFAULT '' NOT NULL,
	password varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE tx_jobrouterconnector_log (
    uid int(11) unsigned NOT NULL AUTO_INCREMENT,
    request_id varchar(13) DEFAULT '' NOT NULL,
    time_micro double(16,4) NOT NULL default '0.0000',
    component varchar(255) DEFAULT '' NOT NULL,
    level tinyint(1) unsigned DEFAULT '0' NOT NULL,
    message text,
    data text,

    PRIMARY KEY (uid),
    KEY request (request_id)
);
