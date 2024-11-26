set search_path = webacl,public;
create unique index if not exists dbparamname_idx on dbparam (dbparam_name);
alter table dbparam add column dbparam_description varchar;
alter table dbparam add column dbparam_description_en varchar;
insert into dbparam (dbparam_name, dbparam_value, dbparam_description, dbparam_description_en)
values (
'APPLI_code', 
'POMET',
'Code de l''instance, pour les exportations',
'Code of the instance, to export data'
) 
on conflict do nothing;
alter table acllogin add column totp_key varchar;
alter table gacl.acllogin add column email varchar;
alter table logingestion add column actif smallint DEFAULT 1;
alter table logingestion add column	is_clientws boolean DEFAULT false;
alter table gacl.logingestion add column if not exists is_expired boolean;
alter table gacl.logingestion add column if not exists nbattempts integer;
alter table gacl.logingestion add column if not exists lastattempt timestamp;
alter table logingestion add column	locale varchar;

COMMENT ON COLUMN gacl.acllogin.totp_key IS E'TOTP secret key for the user';
COMMENT ON COLUMN gacl.logingestion.is_clientws IS E'True if the login is used by a third-party application to call a web-service';
COMMENT ON COLUMN gacl.logingestion.tokenws IS E'Identification token used for the third-parties applications';
COMMENT ON COLUMN gacl.logingestion.is_expired IS E'If true, the account is expired (password older)';
COMMENT ON COLUMN gacl.logingestion.nbattempts IS E'Number of connection attempts';
COMMENT ON COLUMN gacl.logingestion.lastattempt IS E'last attempt of connection';
COMMENT ON COLUMN gacl.logingestion.locale IS E'Preferred locale for the user';

alter table log add column ipaddress varchar;

insert into dbversion (dbversion_date, dbversion_number) values ('2024-11-30', '24.1');

