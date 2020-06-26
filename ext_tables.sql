CREATE TABLE tx_moceansmslogin_domain_model_user (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	frontend_user int (11) DEFAULT '0' NOT NULL,
	username varchar(255) DEFAULT '' NOT NULL,
	telephone varchar(30) NOT NULL,
	enabled smallint(5) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
