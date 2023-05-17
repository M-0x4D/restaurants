-- ALTER TABLE `users` CHANGE `name` `name` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL;
-- ALTER TABLE `users` CHANGE `email` `email` VARCHAR(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL;
-- ALTER TABLE `users` CHANGE `telephone` `telephone` VARCHAR(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL;
-- ALTER TABLE `users` CHANGE `otp` `otp` CHAR(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL;
-- ALTER TABLE `users` CHANGE `phonecode` `phonecode` VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL;
-- ALTER TABLE `users` CHANGE `telephone` `phone` VARCHAR(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL;
-- ALTER TABLE `users` CHANGE `phonecode` `country_code` VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL;
-- ALTER TABLE `profiles` CHANGE `telephone` `phone` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL;
-- ALTER TABLE `addresses` CHANGE `telephone` `phone` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL;


-- ALTER TABLE `users` ADD `terms` BOOLEAN NOT NULL DEFAULT TRUE AFTER `is_active`;


ALTER TABLE `meals` ADD `is_offer` BOOLEAN NOT NULL DEFAULT FALSE AFTER `tag_id`;
