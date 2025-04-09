--
-- db:seed initial values
--

INSERT INTO "permissions" (id, name, guard_name, created_at, updated_at)
VALUES
('018c8690-c9db-713e-9c46-aa153c59ddd9', 'organisation.viewAny', 'web', null, null ),
('018c8690-c9dc-73c3-95d7-ce58bdc43fff', 'organisation.view', 'web', null, null ),
('018c8690-c9dc-73c3-95d7-ce58be1c2109', 'organisation.create', 'web', null, null ),
('018c8690-c9dc-73c3-95d7-ce58becd6873', 'organisation.update', 'web', null, null ),
('018c8690-c9dc-73c3-95d7-ce58bedbe221', 'organisation.delete', 'web', null, null ),
('018c8690-c9dc-73c3-95d7-ce58bf720171', 'organisation.restore', 'web', null, null ),
('018c8690-c9dc-73c3-95d7-ce58c06efc4c', 'organisation.forcedelete', 'web', null, null );

INSERT INTO "roles" (id, name, guard_name, created_at, updated_at)
VALUES
('018c8690-c9e1-704a-801f-82449176c6a4', 'functional-manager', 'web', now(), now() );
