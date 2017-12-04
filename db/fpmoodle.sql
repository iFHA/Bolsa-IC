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
	id_curso bigint not null,
	primary key(id),
	foreign key (id_curso) references mdl_course(id)
	);
	
	
create table mdl_fptasks(
	id bigint auto_increment,
	nome varchar(80) not null,
	descricao varchar(128) not null,
	arquivo varchar(80) not null,
	data_inicio varchar(10) not null,
	data_fim varchar(10) not null,
	id_curso bigint not null,
	ultima bool,
	primary key(id),
	foreign key(id_curso) references mdl_course(id)
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
	id_task bigint not null,
	arquivo varchar(80),
	id_course bigint not null,
	primary key(id),
	foreign key(id_task) references mdl_fptasks(id),
	foreign key(id_course) references mdl_course(id)
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
	id_task bigint not null,
	comentario varchar(512) not null,
	id_course bigint not null,
	primary key(id),
	foreign key(id_user) references mdl_user(id),
	foreign key(id_task) references mdl_fptasks(id),
	foreign key(id_course) references mdl_course(id)
	);
	
	
create table mdl_fpavaliar(
	id bigint auto_increment,
	id_group bigint not null,
	nota float not null,
	situacao bigint,
	feedback varchar(512) not null,
	id_task bigint not null,
	id_course bigint not null,
	primary key(id),
	foreign key(id_group) references mdl_fpgroups(id),
	foreign key(id_task) references mdl_fptasks(id),
	foreign key(id_course) references mdl_course(id)
	);
create table mdl_fpanexos ( 
	nome_anexo varchar(80) not null primary key, 
	grupo_id bigint not null, 
	tarefa_id bigint not null,
	foreign key(grupo_id) references mdl_fpgroups(id) on delete restrict,
	foreign key(tarefa_id) references mdl_fptasks(id) on delete restrict
);