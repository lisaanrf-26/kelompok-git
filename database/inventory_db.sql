

-- Database: `db_inventory`
--
CREATE DATABASE IF NOT EXISTS `db_inventory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_inventory`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` VARCHAR(50) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `kontak` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `kontak`, `email`, `password`) VALUES
('ADM001', 'Vina Tri Astutik', '081234567890', 'vina@email.com', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id_barang` INT(11) NOT NULL,
  `nama_barang` VARCHAR(100) NOT NULL,
  `jenis_barang` VARCHAR(50) NOT NULL,
  `kuantitas_stok` INT(11) NOT NULL,
  `harga` DECIMAL(12,2) NOT NULL,
  `serial_number` VARCHAR(100) NOT NULL,
  `id_gudang` INT(11) DEFAULT NULL,
  `id_vendor` INT(11) DEFAULT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `storage_unit`
--

CREATE TABLE `storage_unit` (
  `id_gudang` INT(11) NOT NULL,
  `nama_gudang` VARCHAR(100) NOT NULL,
  `lokasi` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storage_unit`
--

INSERT INTO `storage_unit` (`id_gudang`, `nama_gudang`, `lokasi`) VALUES
(1, 'pelangi', 'jl.mawar');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_supplier`
--

CREATE TABLE `vendor_supplier` (
  `id_vendor` INT(11) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `kontak` VARCHAR(20) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_supplier`
--

INSERT INTO `vendor_supplier` (`id_vendor`, `nama`, `kontak`) VALUES
(1, 'jingga', '08654321');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `id_gudang` (`id_gudang`),
  ADD KEY `id_vendor` (`id_vendor`);

--
-- Indexes for table `storage_unit`
--
ALTER TABLE `storage_unit`
  ADD PRIMARY KEY (`id_gudang`);

--
-- Indexes for table `vendor_supplier`
--
ALTER TABLE `vendor_supplier`
  ADD PRIMARY KEY (`id_vendor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id_barang` INT(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `storage_unit`
--
ALTER TABLE `storage_unit`
  MODIFY `id_gudang` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_supplier`
--
ALTER TABLE `vendor_supplier`
  MODIFY `id_vendor` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`id_gudang`) REFERENCES `storage_unit` (`id_gudang`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`id_vendor`) REFERENCES `vendor_supplier` (`id_vendor`) ON DELETE SET NULL;
--
-- Database: `inventory_db`
--
CREATE DATABASE IF NOT EXISTS `inventory_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `inventory_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `contact` VARCHAR(30) DEFAULT NULL,
  `email` VARCHAR(120) NOT NULL,
  `password` VARCHAR(255) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `contact`, `email`, `password`) VALUES
(1, 'Administrator', '081234567890', 'admin@inventory.local', '$2y$12$bqEBizmb2UoyrKcu9gERbuPss.KgRp3k/2cu9MjasJ65u1HvTVHsi');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` INT(11) NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `item_type` VARCHAR(100) NOT NULL,
  `stock` INT(11) NOT NULL DEFAULT 0,
  `storage_id` INT(11) NOT NULL,
  `serial_number` VARCHAR(100) NOT NULL,
  `price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `vendor_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `item_type`, `stock`, `storage_id`, `serial_number`, `price`, `vendor_id`, `created_at`, `updated_at`) VALUES
(1, 'Laptop', 'Elektronik', 10, 1, 'SN-LAP-001', 8500000.00, 1, '2026-08-28 05:00:47', '2026-08-28 05:00:47'),
(2, 'Printer', 'Elektronik', 0, 2, 'SN-PRN-001', 2500000.00, 2, '2026-08-28 05:00:47', '2026-08-28 05:00:47');

-- --------------------------------------------------------

--
-- Table structure for table `storage_unit`
--

CREATE TABLE `storage_unit` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `location` VARCHAR(255) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storage_unit`
--

INSERT INTO `storage_unit` (`id`, `name`, `location`) VALUES
(1, 'Gudang Pusat', 'Jakarta'),
(2, 'Gudang Cabang', 'Bandung');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_supplier`
--

CREATE TABLE `vendor_supplier` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `contact` VARCHAR(30) DEFAULT NULL,
  `item_name` VARCHAR(150) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_supplier`
--

INSERT INTO `vendor_supplier` (`id`, `name`, `contact`, `item_name`) VALUES
(1, 'PT Sumber Barang', '081111111111', 'Laptop'),
(2, 'CV Maju Jaya', '082222222222', 'Printer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `storage_id` (`storage_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `storage_unit`
--
ALTER TABLE `storage_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_supplier`
--
ALTER TABLE `vendor_supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `storage_unit`
--
ALTER TABLE `storage_unit`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_supplier`
--
ALTER TABLE `vendor_supplier`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`storage_id`) REFERENCES `storage_unit` (`id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendor_supplier` (`id`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` INT(10) UNSIGNED NOT NULL,
  `dbase` VARCHAR(255) NOT NULL DEFAULT '',
  `user` VARCHAR(255) NOT NULL DEFAULT '',
  `label` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` VARCHAR(64) NOT NULL,
  `col_name` VARCHAR(64) NOT NULL,
  `col_type` VARCHAR(64) NOT NULL,
  `col_length` TEXT DEFAULT NULL,
  `col_collation` VARCHAR(64) NOT NULL,
  `col_isNull` TINYINT(1) NOT NULL,
  `col_extra` VARCHAR(255) DEFAULT '',
  `col_default` TEXT DEFAULT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` INT(5) UNSIGNED NOT NULL,
  `db_name` VARCHAR(64) NOT NULL DEFAULT '',
  `table_name` VARCHAR(64) NOT NULL DEFAULT '',
  `column_name` VARCHAR(64) NOT NULL DEFAULT '',
  `comment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` VARCHAR(255) NOT NULL DEFAULT '',
  `transformation_options` VARCHAR(255) NOT NULL DEFAULT '',
  `input_transformation` VARCHAR(255) NOT NULL DEFAULT '',
  `input_transformation_options` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` VARCHAR(64) NOT NULL,
  `settings_data` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` INT(5) UNSIGNED NOT NULL,
  `username` VARCHAR(64) NOT NULL,
  `export_type` VARCHAR(10) NOT NULL,
  `template_name` VARCHAR(64) NOT NULL,
  `template_data` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` VARCHAR(64) NOT NULL,
  `tables` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` BIGINT(20) UNSIGNED NOT NULL,
  `username` VARCHAR(64) NOT NULL DEFAULT '',
  `db` VARCHAR(64) NOT NULL DEFAULT '',
  `table` VARCHAR(64) NOT NULL DEFAULT '',
  `timevalue` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `sqlquery` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` VARCHAR(64) NOT NULL,
  `item_name` VARCHAR(64) NOT NULL,
  `item_type` VARCHAR(64) NOT NULL,
  `db_name` VARCHAR(64) NOT NULL,
  `table_name` VARCHAR(64) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` VARCHAR(64) NOT NULL DEFAULT '',
  `page_nr` INT(10) UNSIGNED NOT NULL,
  `page_descr` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` VARCHAR(64) NOT NULL,
  `tables` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` VARCHAR(64) NOT NULL DEFAULT '',
  `master_table` VARCHAR(64) NOT NULL DEFAULT '',
  `master_field` VARCHAR(64) NOT NULL DEFAULT '',
  `foreign_db` VARCHAR(64) NOT NULL DEFAULT '',
  `foreign_table` VARCHAR(64) NOT NULL DEFAULT '',
  `foreign_field` VARCHAR(64) NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` INT(5) UNSIGNED NOT NULL,
  `username` VARCHAR(64) NOT NULL DEFAULT '',
  `db_name` VARCHAR(64) NOT NULL DEFAULT '',
  `search_name` VARCHAR(64) NOT NULL DEFAULT '',
  `search_data` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` VARCHAR(64) NOT NULL DEFAULT '',
  `table_name` VARCHAR(64) NOT NULL DEFAULT '',
  `pdf_page_number` INT(11) NOT NULL DEFAULT 0,
  `x` FLOAT UNSIGNED NOT NULL DEFAULT 0,
  `y` FLOAT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` VARCHAR(64) NOT NULL DEFAULT '',
  `table_name` VARCHAR(64) NOT NULL DEFAULT '',
  `display_field` VARCHAR(64) NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` VARCHAR(64) NOT NULL,
  `db_name` VARCHAR(64) NOT NULL,
  `table_name` VARCHAR(64) NOT NULL,
  `prefs` TEXT NOT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` VARCHAR(64) NOT NULL,
  `table_name` VARCHAR(64) NOT NULL,
  `version` INT(10) UNSIGNED NOT NULL,
  `date_created` DATETIME NOT NULL,
  `date_updated` DATETIME NOT NULL,
  `schema_snapshot` TEXT NOT NULL,
  `schema_sql` TEXT DEFAULT NULL,
  `data_sql` LONGTEXT DEFAULT NULL,
  `tracking` SET('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` INT(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` VARCHAR(64) NOT NULL,
  `timevalue` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `config_data` TEXT NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-08-03 07:36:27', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` VARCHAR(64) NOT NULL,
  `tab` VARCHAR(64) NOT NULL,
  `allowed` ENUM('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` VARCHAR(64) NOT NULL,
  `usergroup` VARCHAR(64) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
