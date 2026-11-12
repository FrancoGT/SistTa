CREATE DATABASE sist_taxis;
USE sist_taxis;
CREATE TABLE `tbl_persona`
(
  `id_persona` INT(150) NOT NULL AUTO_INCREMENT,
  `documento` VARCHAR(100) NOT NULL,
  `nombre_persona` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `direccion_persona` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `telefono_persona` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  PRIMARY KEY (`id_persona`),
  UNIQUE(`documento`)
);
CREATE TABLE `tbl_vehiculo`
(
  `id_vehiculo` INT(150) NOT NULL AUTO_INCREMENT,
  `cod_vehiculo` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `placa` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `estado_vehiculo` BOOLEAN NOT NULL,
  PRIMARY KEY (`id_vehiculo`)
);
CREATE TABLE `tbl_servicio`
(
  `id_servicio` INT(150) NOT NULL AUTO_INCREMENT,
  `nombre_servicio` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `estado_servicio` BOOLEAN NOT NULL,
  `pago_diario` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `aplica` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id_servicio`)
);
CREATE TABLE `tbl_session`
(
  `id` VARCHAR(40) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `timestamp` INT(10) NOT NULL,
  `data` BLOB NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `tbl_users`
(
  `id_user` int(150) NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` tinyint(1) NOT NULL,
  `email` varchar(150) NOT NULL,
  `estado_usuario` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
CREATE TABLE `tbl_operacion`
(
  `id_operacion` INT(150) NOT NULL AUTO_INCREMENT,
  `monto` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `fecha` DATE,
  `tipo_operacion` TINYINT(1) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  PRIMARY KEY (`id_operacion`)
);
CREATE TABLE `tbl_configuracion`
(
  `id_configuracion` int(150) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(150) NOT NULL,
  `RUC` varchar(150) NOT NULL,
  `direccion_empresa` varchar(150) NOT NULL,
  `cod_configuracion` varchar(150) NOT NULL,
  PRIMARY KEY (`id_configuracion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
CREATE TABLE `tbl_afiliado`
(
  `id_afiliado` INT(150) NOT NULL AUTO_INCREMENT,
  `id_person_afiliado` INT(150) NOT NULL,
  `cod_afiliado` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `estado_afiliado` BOOLEAN NOT NULL,
  PRIMARY KEY (`id_afiliado`),
  FOREIGN KEY (`id_person_afiliado`) REFERENCES tbl_persona(`id_persona`)
);
CREATE TABLE `tbl_conductor`
(
  `id_conductor` INT(150) NOT NULL AUTO_INCREMENT,
  `cod_conductor` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `brevete` VARCHAR(100) COLLATE utf16_spanish_ci NOT NULL,
  `estado_conductor` BOOLEAN NOT NULL,
  `foto` VARCHAR(200) COLLATE utf16_spanish_ci NOT NULL,
  `id_person_conductor` INT(150) NOT NULL,
  PRIMARY KEY (`id_conductor`),
  FOREIGN KEY (`id_person_conductor`) REFERENCES tbl_persona(`id_persona`)
);

CREATE TABLE `tbl_responsable_vehiculo`
(
  `id_responsable` INT(150) NOT NULL AUTO_INCREMENT,
  `id_person_responsable` INT(150) NOT NULL,
  `id_vehi` INT(150) NOT NULL,
  `es_propietario` BOOLEAN NOT NULL,
  `conduce_vehiculo` BOOLEAN NOT NULL,
  `estado_responsable_vehiculo` BOOLEAN NOT NULL,
  PRIMARY KEY (`id_responsable`),
  FOREIGN KEY (`id_vehi`) REFERENCES tbl_vehiculo(`id_vehiculo`),
  FOREIGN KEY (`id_person_responsable`) REFERENCES tbl_persona(`id_persona`)
);
CREATE TABLE `tbl_periodo_pago`
(
  `id_periodo` INT(150) NOT NULL AUTO_INCREMENT,
  `inicio` DATE NOT NULL,
  `estado_periodo` BOOLEAN NOT NULL,
  `estado_pago` BOOLEAN NOT NULL,
  `id_serv` INT(150) NOT NULL,
  `monto_provicional` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_periodo`),
  FOREIGN KEY (`id_serv`) REFERENCES tbl_servicio(`id_servicio`)
);
CREATE TABLE `tbl_realiza_pago`
(
  `id_pago` INT(150) NOT NULL AUTO_INCREMENT,
  `id_person` INT(150) NOT NULL,
  `id_perio` INT(150) NOT NULL,
  PRIMARY KEY (`id_pago`),
  FOREIGN KEY (`id_person`) REFERENCES tbl_persona(`id_persona`),
  FOREIGN KEY (`id_perio`) REFERENCES tbl_periodo_pago(`id_periodo`)
);
CREATE TABLE `tbl_realiza_pago_vehiculo`
(
  `id_pago` INT(150) NOT NULL AUTO_INCREMENT,
  `id_vehi` INT(150) NOT NULL,
  `id_perio` INT(150) NOT NULL,
  PRIMARY KEY (`id_pago`),
  FOREIGN KEY (`id_vehi`) REFERENCES tbl_vehiculo(`id_vehiculo`),
  FOREIGN KEY (`id_perio`) REFERENCES tbl_periodo_pago(`id_periodo`)
);
CREATE TABLE `tbl_pago_operacion`
(
  `id_pago_operacion` INT(150) NOT NULL AUTO_INCREMENT,
  `id_ope` INT(150) NOT NULL,
  `id_perio` INT(150) NOT NULL,
  PRIMARY KEY (`id_pago_operacion`),
  FOREIGN KEY (`id_ope`) REFERENCES tbl_operacion(`id_operacion`),
  FOREIGN KEY (`id_perio`) REFERENCES tbl_periodo_pago(`id_periodo`)
);
CREATE TABLE `tbl_realizado`
(
  `id_realizado` INT(150) NOT NULL AUTO_INCREMENT,
  `id_us` INT(150) NOT NULL,
  `id_ope` INT(150) NOT NULL,
  PRIMARY KEY (`id_realizado`),
  FOREIGN KEY (`id_us`) REFERENCES tbl_users(`id_user`),
  FOREIGN KEY (`id_ope`) REFERENCES tbl_operacion(`id_operacion`)
);
CREATE TABLE `tbl_comprobante`
(
  `id_comprobante` INT(150) NOT NULL AUTO_INCREMENT,
  `doc_comprobante` VARCHAR(100) NULL,
  `proveedor` VARCHAR(100) NULL,
  `id_ope` INT(150) NOT NULL,
  `tipo_comprobante` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id_comprobante`),
  FOREIGN KEY (`id_ope`) REFERENCES tbl_operacion(`id_operacion`)
);


INSERT INTO `tbl_users` (`id_user`, `nama_user`, `username`, `password`, `tipo`, `email`, `estado_usuario`) VALUES
(1, 'Cesar Gomez', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'cgomez@taxiplanet.com', 1);


INSERT INTO `tbl_session` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('gefurdfdju2ehd6affn33bfpik2ghi6m', '::1', 1490264823, 0x5f5f63695f6c6173745f726567656e65726174657c693a313439303236343532333b);


INSERT INTO `tbl_configuracion` (`id_configuracion`, `empresa`, `RUC`, `direccion_empresa`, `cod_configuracion`) VALUES
(4, 'Taxi Planet S.A.C.', '20492259030', 'Cal. las Encinas Nro. 487', '66692259030');
