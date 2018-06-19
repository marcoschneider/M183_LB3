USE m133_todo_app_beta;

INSERT INTO `group`
  (group_name, group_short)
VALUES
  ('Drupal','drupal'),
  ('Kundenservice','support'),
  ('Typo3','typo'),
  ('Agenturleitung','agl');

INSERT INTO user
  (firstname, surname, username, password, fk_group)
VALUES
  ('Marco','Schneider','maschneider','d3751d33f9cd5049c4af2b462735457e4d3baf130bcbb87f389e349fbaeb20b9', 3);

INSERT INTO link
  (link_url, link_name, fk_user)
VALUES
  ('https://www.google.ch', 'Google', 1);

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
  ('Test Title', 'Problem', 0, CURRENT_TIMESTAMP,
   CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'https://www.google.ch',
  1, 2, 1, 1);