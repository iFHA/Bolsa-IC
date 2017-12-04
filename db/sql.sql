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
<TABLE NAME="fp_group_session" COMMENT="">
  <FIELDS>
    <FIELD NAME="id" TYPE="int" LENGTH="10" NOTNULL="true" SEQUENCE="true"/>
    <FIELD NAME="timestart" TYPE="int" LENGTH="10" NOTNULL="true" SEQUENCE="false"/>
    <FIELD NAME="timeend" TYPE="int" LENGTH="10" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
    <FIELD NAME="finished" TYPE="int" LENGTH="1" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
    <FIELD NAME="last" TYPE="int" LENGTH="1" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
    <FIELD NAME="report" TYPE="text" NOTNULL="false" SEQUENCE="false"/>
    <FIELD NAME="leader" TYPE="int" LENGTH="10" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
    <FIELD NAME="reporter" TYPE="int" LENGTH="10" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
    <FIELD NAME="invertclass_group" TYPE="int" LENGTH="10" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
    <FIELD NAME="leader_evaluation" TYPE="text" NOTNULL="false" SEQUENCE="false"/>
    <FIELD NAME="reporter_evaluation" TYPE="text" NOTNULL="false" SEQUENCE="false"/>
    <FIELD NAME="group_evaluation" TYPE="text" NOTNULL="false" SEQUENCE="false"/>
    <FIELD NAME="eventid" TYPE="int" LENGTH="10" NOTNULL="true" DEFAULT="0" SEQUENCE="false"/>
  </FIELDS>
  <KEYS>
    <KEY NAME="primary" TYPE="primary" FIELDS="id"/>
    <KEY NAME="id_groupfk" TYPE="foreign" FIELDS="invertclass_group" REFTABLE="fpgroups" REFFIELDS="id" COMMENT="Foreign key to wiki table"/>
  </KEYS>
</TABLE>

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
delete from mdl_fptasks;
delete from mdl_fpgroups;


create table mdl_invertclass(
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
  
        id int bigint auto_increment,
        course int bigint NOTNULL=true SEQUENCE=false/>
        forum int bigint NOTNULL=true SEQUENCE=false/>
        chat int bigint NOTNULL=true SEQUENCE=false/>
        name text NOTNULL=true SEQUENCE=false/>
        intro text NOTNULL=false SEQUENCE=false/>
        introformat int LENGTH=4 NOTNULL=true DEFAULT=0 SEQUENCE=false/>
        knowledge_area text NOTNULL=false SEQUENCE=false/>
        product_format text NOTNULL=false SEQUENCE=false/>
        not_related_words text NOTNULL=false SEQUENCE=false/>
        timecreated int bigint NOTNULL=true SEQUENCE=false/>
        timemodified int bigint NOTNULL=true DEFAULT=0 SEQUENCE=false/>
        arquivo text LENGTH=80 NOTNULL=false SEQUENCE=false COMMENT=Wiki activity/>
        data_inicio text bigint NOTNULL=false SEQUENCE=false COMMENT=Wiki activity/>
        data_fim text bigint NOTNULL=false SEQUENCE=false COMMENT=Wiki activity/>
      </FIELDS>
*/