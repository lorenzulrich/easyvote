#
# Table structure for table 'tx_easyvote_domain_model_metavotingproposal'
#
CREATE TABLE tx_easyvote_domain_model_metavotingproposal (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	type int(11) DEFAULT '0' NOT NULL,
	scope int(11) DEFAULT '0' NOT NULL,
	private_title varchar(255) DEFAULT '' NOT NULL,
	voting_day int(11) unsigned DEFAULT '0' NOT NULL,
	main_proposal_approval double(11,2) DEFAULT '0.00' NOT NULL,
	voting_proposals int(11) unsigned DEFAULT '0' NOT NULL,
	kanton int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_easyvote_domain_model_votingproposal'
#
CREATE TABLE tx_easyvote_domain_model_votingproposal (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	short_title varchar(255) DEFAULT '' NOT NULL,
	official_title varchar(255) DEFAULT '' NOT NULL,
	youtube_url varchar(255) DEFAULT '' NOT NULL,
	image varchar(255) DEFAULT '' NOT NULL,
	goal text NOT NULL,
	initial_status text NOT NULL,
	consequence text NOT NULL,
	pro_arguments text NOT NULL,
	contra_arguments text NOT NULL,
	government_opinion text NOT NULL,
	links text NOT NULL,
	proposal_approval varchar(255) DEFAULT '' NOT NULL,
	kanton_majority varchar(255) DEFAULT '' NOT NULL,
	additional_information_header varchar(255) DEFAULT '' NOT NULL,
	additional_information_content text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_easyvote_domain_model_kanton'
#
CREATE TABLE tx_easyvote_domain_model_kanton (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	abbreviation varchar(255) DEFAULT '' NOT NULL,
	cities int(11) unsigned DEFAULT '0' NOT NULL,
	languages int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_easyvote_domain_model_votingday'
#
CREATE TABLE tx_easyvote_domain_model_votingday (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	voting_date int(11) DEFAULT '0' NOT NULL,
	archived tinyint(1) unsigned DEFAULT '0' NOT NULL,
	upload_allowed tinyint(1) unsigned DEFAULT '0' NOT NULL,
	meta_voting_proposals int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_easyvote_domain_model_poll'
#
CREATE TABLE tx_easyvote_domain_model_poll (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	voting_proposal int(11) DEFAULT '0' NOT NULL,
	community_user int(11) DEFAULT '0' NOT NULL,
	value tinyint(1) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_easyvote_domain_model_city'
#
CREATE TABLE tx_easyvote_domain_model_city (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	zip int(11) DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_easyvote_domain_model_language'
#
CREATE TABLE tx_easyvote_domain_model_language (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

  language_uid int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_easyvote_metavotingproposal_votingproposal_mm'
#
CREATE TABLE tx_easyvote_metavotingproposal_votingproposal_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_easyvote_kanton_city_mm'
#
CREATE TABLE tx_easyvote_kanton_city_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_easyvote_kanton_language_mm'
#
CREATE TABLE tx_easyvote_kanton_language_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_easyvote_votingday_metavotingproposal_mm'
#
CREATE TABLE tx_easyvote_votingday_metavotingproposal_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_easyvote_domain_model_messagingjob'
#
CREATE TABLE tx_easyvote_domain_model_messagingjob (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	type int(11) DEFAULT '0' NOT NULL,
	community_user int(11) unsigned DEFAULT '0' NOT NULL,
	recipient_name varchar(255) DEFAULT '' NOT NULL,
	recipient_email varchar(255) DEFAULT '' NOT NULL,
	sender_name varchar(255) DEFAULT '' NOT NULL,
	sender_email varchar(255) DEFAULT '' NOT NULL,
	return_path varchar(255) DEFAULT '' NOT NULL,
	reply_to varchar(255) DEFAULT '' NOT NULL,
	subject varchar(255) DEFAULT '' NOT NULL,
	content text NOT NULL,
	distribution_time int(11) unsigned DEFAULT '0' NOT NULL,
	time_distributed int(11) unsigned DEFAULT '0' NOT NULL,
	time_error int(11) unsigned DEFAULT '0' NOT NULL,
	error_code int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	t3_origuid int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
 KEY language (l10n_parent,sys_language_uid)

);


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
  tx_easyvote_contentclass tinytext
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
  tx_easyvote_pageclass tinytext
);


#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (

	gender int(1) unsigned DEFAULT '0' NOT NULL,
	kanton int(11) unsigned DEFAULT '0' NOT NULL,
	user_language int(11) unsigned DEFAULT '0' NOT NULL,
	birthdate int(11) unsigned DEFAULT '0' NOT NULL,
	notification_mail_active int(1) unsigned DEFAULT '0' NOT NULL,
	notification_sms_active int(1) unsigned DEFAULT '0' NOT NULL,
  notification_related_users int(11) unsigned DEFAULT '0' NOT NULL,
  community_user int(11) unsigned DEFAULT '0' NOT NULL,
	tx_extbase_type varchar(255) DEFAULT '' NOT NULL,

);