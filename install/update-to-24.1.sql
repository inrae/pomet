set search_path = gacl,public;
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
alter table gacl.acllogin add column email varchar;
alter table gacl.logingestion add column if not exists is_expired boolean;
alter table gacl.logingestion add column if not exists nbattempts integer;
alter table gacl.logingestion add column if not exists lastattempt timestamp;

update gacl.aclgroup set groupe = 'manage' where groupe = 'gestion';
update gacl.aclaco set aco = 'manage' where aco = 'gestion';
