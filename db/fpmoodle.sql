/*
create table mdl_user(
	id bigint auto_increment,
	firstname varchar(80) not null,
	primary key(id)
	);

create table mdl_course(
	id bigint auto_increment,
	name varchar(80) not null,
	primary key(id));
*/

create table mdl_fpgroups(
	id bigint auto_increment,
	nome varchar(80) not null,
	moduleid bigint not null,
	anexoid bigint,
	primary key(id),
	foreign key (moduleid) references mdl_course_modules(id),
	foreign key (anexoid) references mdl_fpanexos(id)
	);
	
create table mdl_fpmembers(
	id bigint auto_increment,
	id_user bigint not null,
	id_group bigint not null,
	moderador bigint,
	primary key(id),
	foreign key(id_user) references mdl_user(id),
	foreign key(id_group) references mdl_fpgroups(id)
	);

create table mdl_fpref(
	id bigint auto_increment,
	descricao varchar(512) not null,
	moduleid bigint not null,
	arquivo varchar(80),
	primary key(id),
	foreign key(moduleid) references mdl_course_modules(id)
	);
	
create table mdl_fpgain(
	id bigint auto_increment,
	id_user bigint not null,
	aproveitamento bigint,
	primary key(id),
	foreign key(id_user) references mdl_user(id)
	);
	

create table mdl_fpfeedback(
	id bigint auto_increment,
	id_user bigint not null,
	moduleid bigint not null,
	comentario varchar(512) not null,
	primary key(id),
	foreign key(id_user) references mdl_user(id),
	foreign key(moduleid) references mdl_course_modules(id)
	);
	
	
create table mdl_fpavaliar(
	id bigint auto_increment,
	id_group bigint not null,
	nota float not null,
	situacao bigint,
	feedback varchar(512) not null,
	moduleid bigint not null,
	primary key(id),
	foreign key(id_group) references mdl_fpgroups(id),
	foreign key(moduleid) references mdl_course_modules(id)
	);

create table mdl_fpanexos (
	id bigint auto_increment primary key,
	nome_original varchar(80) not null,
	nome_final varchar(80) not null
);

create table mdl_fp_features(
	id bigint auto_increment,
	descricao varchar(512) not null,
	categoria bigint not null default 0,
	primary key(id)
);

create table mdl_fp_requirements(
	id bigint auto_increment,
	value char not null,
	importance float not null default 0,
	feataureid bigint not null default 0,
	invertclassid bigint not null default 0,
	primary key(id),
	foreign key (feataureid) references mdl_fp_features(id),
	foreign key (invertclassid) references mdl_invertclass(id)
);
    
create table mdl_fp_goals(
	id bigint auto_increment,
	feataureid bigint not null default 0,
	invertclassid bigint not null default 0,
	primary key(id),
	foreign key (feataureid) references mdl_fp_features(id),
	foreign key (invertclassid) references mdl_invertclass(id)
);

create table mdl_fp_user_prefered_times(
	id bigint auto_increment,
	sunday varchar(3) not null,
	monday varchar(3) not null,
	tuesday varchar(3) not null,
	wednesday varchar(3) not null,
	thursday varchar(3) not null,
	friday varchar(3) not null,
	saturday varchar(3) not null,
	userid bigint not null default 0,
	primary key(id),
	foreign key (userid) references mdl_user(id)
);

create table mdl_fp_user_features(
	id bigint auto_increment,
  value bigint not null default 0,
	feataureid bigint not null default 0,
	userid bigint not null default 0,
	primary key(id),
	foreign key (feataureid) references mdl_fp_features(id),
	foreign key (userid) references mdl_user(id)
);

create table mdl_fp_unknown_words(
	id bigint auto_increment,
  unknown_words varchar(256),
	fp_group bigint not null default 0,
	userid bigint not null default 0,
	primary key(id),
	foreign key (fp_group) references mdl_fpgroups(id),
	foreign key (userid) references mdl_user(id)
);

/*

create table mdl_invertclass(
	id bigint auto_increment,
	nome varchar(80) not null,
	intro varchar(120),
	introformat smallint(4) not null default 0,
	knowledge_area varchar(200),
	product_format varchar(200),
	not_related_words varchar(200),
	timecreated bigint,
	timemodified bigint,
	descricao varchar(512),
	arquivo varchar(80),
	data_inicio varchar(10),
	data_fim varchar(10),
	course bigint not null,
	forum bigint not null,
	chat bigint not null,
	ultima tinyint(1),
	primary key(id),
	foreign key(course) references mdl_course(id)
	);

create table mdl_fp_group_session(
	id bigint auto_increment,
	timestart bigint not null default 0,
	timeend bigint not null default 0,
	finished tinyint(1) not null default 0,
	last tinyint(1) not null default 0,
	report varchar(200),
	leader bigint,
	reporter bigint,
	invertclass_group bigint,
	leader_evaluation varchar(300),
	reporter_evaluation varchar(300),
	group_evaluation varchar(300),
	eventid bigint,
	primary key(id),
	foreign key(invertclass_group) references mdl_fpgroups(id)
	);

delete from mdl_fp_unknown_words;
delete from mdl_fp_group_session;
delete from mdl_fp_user_features;
delete from mdl_fp_user_prefered_times;
delete from mdl_fp_goals;
delete from mdl_fp_requirements;
delete from mdl_fp_features;
delete from mdl_fpanexos;
delete from mdl_fpavaliar;
delete from mdl_fpfeedback;
delete from mdl_fpgain;
delete from mdl_fpref;
delete from mdl_fpmembers;
delete from mdl_fpgroups;

drop table mdl_fp_unknown_words;
drop table mdl_fp_group_session;
drop table mdl_fp_user_features;
drop table mdl_fp_user_prefered_times;
drop table mdl_fp_goals;
drop table mdl_fp_requirements;
drop table mdl_fp_features;
drop table mdl_fpanexos;
drop table mdl_fpavaliar;
drop table mdl_fpfeedback;
drop table mdl_fpgain;
drop table mdl_fpref;
drop table mdl_fpmembers;
drop table mdl_fpgroups;

*/