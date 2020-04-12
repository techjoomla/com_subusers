ALTER TABLE `#__tjsu_roles` ENGINE = InnoDB;
ALTER TABLE `#__tjsu_actions` ENGINE = InnoDB;
ALTER TABLE `#__tjsu_role_action_map` ENGINE = InnoDB;
ALTER TABLE `#__tjsu_users` ENGINE = InnoDB;

ALTER TABLE `#__tjsu_roles` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__tjsu_actions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__tjsu_role_action_map` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__tjsu_users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
