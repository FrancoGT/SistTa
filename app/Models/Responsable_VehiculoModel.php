<?php

namespace App\Models;

use CodeIgniter\Model;

class Responsable_VehiculoModel extends Model
{
    public function guardar($data)
    {
        $this->db->table('tbl_responsable_vehiculo')->insert($data);
    }

    public function getIdResponsable($id_person, $id_vehiculo)
    {
        $id_responsable = '0';
        $query          = $this->db->query('SELECT id_responsable FROM `tbl_responsable_vehiculo` WHERE id_person_responsable=? AND id_vehi=?', [$id_person, $id_vehiculo]);

        foreach ($query->getResult() as $row) {
            $id_responsable = $row->id_responsable;
        }

        return $id_responsable;
    }

    public function al_menos_un_propietario($id_vehiculo)
    {
        $query = $this->db->query('SELECT * FROM `tbl_responsable_vehiculo` WHERE id_vehi=? AND es_propietario=1', [$id_vehiculo]);

        return $query->getNumRows() === 1;
    }

    public function existe_responsable($id_person, $id_vehiculo)
    {
        $id_responsable = '';
        $query          = $this->db->query('SELECT id_responsable FROM `tbl_responsable_vehiculo` WHERE id_person_responsable=? AND id_vehi=?', [$id_person, $id_vehiculo]);

        foreach ($query->getResult() as $row) {
            $id_responsable = $row->id_responsable;
        }

        return $id_responsable != '';
    }

    public function quitar_propietario($anterior_id_person, $id_vehiculo)
    {
        $id_responsable = $this->getIdResponsable($anterior_id_person, $id_vehiculo);
        $this->db->table('tbl_responsable_vehiculo')->where('id_responsable', $id_responsable)->update(['es_propietario' => '0']);
    }

    public function quitar_conductor($anterior_id_person, $id_vehiculo)
    {
        $id_responsable = $this->getIdResponsable($anterior_id_person, $id_vehiculo);
        $this->db->table('tbl_responsable_vehiculo')->where('id_responsable', $id_responsable)->update(['conduce_vehiculo' => '0']);
    }

    public function reasignar_propietario($id_person, $id_vehiculo)
    {
        $id_responsable = $this->getIdResponsable($id_person, $id_vehiculo);
        $this->db->table('tbl_responsable_vehiculo')->where('id_responsable', $id_responsable)->update(['es_propietario' => '1']);
    }

    public function reasignar_conductor($id_person, $id_vehiculo)
    {
        $id_responsable = $this->getIdResponsable($id_person, $id_vehiculo);
        $this->db->table('tbl_responsable_vehiculo')->where('id_responsable', $id_responsable)->update(['conduce_vehiculo' => '1']);
    }

    // validar vehiculos ya asignados
    public function ya_es_propietario($id_person, $id_vehiculo)
    {
        $es_propietario = '0';
        $id_responsable = $this->getIdResponsable($id_person, $id_vehiculo);
        $query          = $this->db->query('SELECT es_propietario FROM `tbl_responsable_vehiculo` WHERE id_responsable=?', [$id_responsable]);

        foreach ($query->getResult() as $row) {
            $es_propietario = $row->es_propietario;
        }

        return $es_propietario == '1';
    }

    public function ya_es_conductor($id_person, $id_vehiculo)
    {
        $conduce_vehiculo = '0';
        $id_responsable   = $this->getIdResponsable($id_person, $id_vehiculo);
        $query            = $this->db->query('SELECT conduce_vehiculo FROM `tbl_responsable_vehiculo` WHERE id_responsable=?', [$id_responsable]);

        foreach ($query->getResult() as $row) {
            $conduce_vehiculo = $row->conduce_vehiculo;
        }

        return $conduce_vehiculo == '1';
    }
}
