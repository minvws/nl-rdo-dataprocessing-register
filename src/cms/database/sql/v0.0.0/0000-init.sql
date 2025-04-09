--- Create web user
DO
$do$
BEGIN
  IF EXISTS (
      SELECT FROM pg_catalog.pg_roles
        WHERE  rolname = 'dpr') THEN RAISE NOTICE 'Role "dpr" already exists. Skipping.';
  ELSE
    CREATE ROLE dpr;
  END IF;
END
$do$;

ALTER ROLE dpr WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS ;

--- Create DBA role
DO
$do$
BEGIN
  IF EXISTS (
      SELECT FROM pg_catalog.pg_roles
        WHERE  rolname = 'dpr_dba') THEN RAISE NOTICE 'Role "dpr_dba" already exists. Skipping.';
  ELSE
    CREATE ROLE dpr_dba;
  END IF;
END
$do$;
ALTER ROLE dpr_dba WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS ;


CREATE TABLE deploy_releases
(
        version varchar(255),
        deployed_at timestamp default now()
);

ALTER TABLE deploy_releases OWNER TO dpr_dba;

GRANT SELECT ON deploy_releases TO dpr;

INSERT INTO deploy_releases values ('v0.0.0', '2000-01-01 00:00:00' );
