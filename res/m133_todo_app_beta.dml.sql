USE m133_todo_app_beta;

INSERT INTO `group`
  (group_name, group_short)
VALUES
  ('self-todo', 'self'),
  ('Drupal','drupal'),
  ('Kundenservice','support'),
  ('Typo3','typo'),
  ('Agenturleitung','agl');

INSERT INTO user
  (firstname, surname, username, password)
VALUES
  ('Marco','Schneider','maschneider','d3751d33f9cd5049c4af2b462735457e4d3baf130bcbb87f389e349fbaeb20b9');

INSERT INTO user_group
  (fk_user, fk_group)
VALUES
  (1, 1),
  (1,2);

INSERT INTO link
  (link_url, link_name, fk_user)
VALUES
  ('https://so-web.atlassian.net/issues/?filter=-1','Meine Tickets',1),
  ('http://sov5.test/','Suedostschweiz Lokal',1),
  ('https://www.suedostschweiz.ch/','Südostschweiz Live',1),
  ('https://sv0006.suedostschweiz.ch:24781/','Crypto',1),
  ('https://so-web.atlassian.net/wiki/spaces/AFADEE/pages/2425738/05.02+-+Technische+Dokumentationen','Südostschweiz Technische Doku',1);


INSERT INTO priority
  (niveau)
VALUES
  ('Low'),
  ('Normal'),
  ('High');

INSERT INTO project
  (project_name, short_name)
VALUES
  ('Südostschweiz V5', 'sov5'),
  ('Züriost V2', 'zolv2');

INSERT INTO m133_todo_app_beta.todo
  (title, problem, todo_status, fixed_date,
   last_edit, creation_date, website_url,
   fk_project, fk_priority, fk_group, fk_user)
VALUES
  ('Test Title', 'Problem', 0, NOW(),
   NOW(), NOW(), 'https://www.google.ch',
  1, 2, 1, 1);