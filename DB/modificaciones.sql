create database proyecto_sca;
use proyecto_sca;

ALTER TABLE productos_maquina ADD COLUMN slot_numero INT NULL AFTER cantidad;