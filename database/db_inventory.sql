CREATE DATABASE db_inventory
USE db_inventory

CREATE TABLE ADMIN(
id_admin VARCHAR(50) NOT NULL,
nama VARCHAR(100)NOT NULL,
kontak VARCHAR(20) NOT NULL,
email VARCHAR(100)NOT NULL
);

CREATE TABLE inventory(
id_barang INT(11) NOT NULL,
nama_barang VARCHAR(100)NOT NULL,
jenis_barang VARCHAR(50)NOT NULL,
kualitas_stok INT(11)NOT NULL,
harga DECIMAL(12,2)NOT NULL,
serial_number VARCHAR(100)NOT NULL,
id_gudang INT(11)DEFAULT NULL,
id_vendor INT(11)DEFAULT NULL
);

CREATE TABLE storage_unit(
id_gudang INT(11) NOT NULL,
nama_gudang VARCHAR(100) NOT NULL,
lokasi TEXT NOT NULL
);

CREATE TABLE vendor_supplier(
id_vendor INT(11)NOT NULL,
nama VARCHAR(100)NOT NULL,
kontak VARCHAR (20) NOT NULL
);

ALTER TABLE ADMIN
ADD PRIMARY KEY (id_admin);

ALTER TABLE inventory
ADD PRIMARY KEY(id_barang),
ADD UNIQUE KEY(serial_number),
ADD KEY id_gudang(id_gudang),
ADD KEY id_vendor(id_vendor);

ALTER TABLE vendor_supplier
ADD PRIMARY KEY(id_vendor);