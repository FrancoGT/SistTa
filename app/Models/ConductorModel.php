<?php

namespace App\Models;

use CodeIgniter\Model;

class ConductorModel extends Model
{
    public function guardar($data)
    {
        $this->db->table('tbl_conductor')->insert($data);
    }

    public function actualizar($data, $id_conductor)
    {
        $this->db->table('tbl_conductor')->where('id_conductor', $id_conductor)->update($data);
    }

    public function get_id_conductor($criterio, $dato)
    {
        $id_conductor = '';
        // (en el original los parámetros están invertidos: $dato es la columna)
        $query = $this->db->query('SELECT id_conductor FROM `tbl_conductor` WHERE ' . sql_ident($dato) . '=?', [$criterio]);

        foreach ($query->getResult() as $row) {
            $id_conductor = $row->id_conductor;
        }

        return $id_conductor;
    }

    public function get_id_person_conductor($criterio, $dato)
    {
        $id_person = '';
        $query     = $this->db->query('SELECT id_persona FROM `tbl_persona` WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        foreach ($query->getResult() as $row) {
            $id_person = $row->id_persona;
        }

        return $id_person;
    }

    public function get_id_person($id_conductor)
    {
        $id_person_conductor = '';
        $query               = $this->db->query('SELECT id_person_conductor FROM `tbl_conductor` WHERE id_conductor=?', [$id_conductor]);

        foreach ($query->getResult() as $row) {
            $id_person_conductor = $row->id_person_conductor;
        }

        return $id_person_conductor;
    }

    public function get_id_person_actual($id_conductor)
    {
        $id_person = '';
        $query     = $this->db->query('SELECT id_person_conductor FROM `tbl_conductor` WHERE id_conductor=?', [$id_conductor]);

        foreach ($query->getResult() as $row) {
            $id_person = $row->id_person_conductor;
        }

        return $id_person;
    }

    public function get_documento($id_conductor)
    {
        $id_person = $this->get_id_person($id_conductor);
        $query     = $this->db->query('SELECT documento FROM `tbl_persona` WHERE id_persona=?', [$id_person]);
        $documento = '';

        foreach ($query->getResult() as $row) {
            $documento = $row->documento;
        }

        return $documento;
    }

    public function get_foto_conductor($id_conductor)
    {
        $file_full_path = '';
        $query          = $this->db->query('SELECT foto FROM `tbl_conductor` WHERE id_conductor=?', [$id_conductor]);

        foreach ($query->getResult() as $row) {
            $file_full_path = $row->foto;
        }

        return $file_full_path;
    }

    public function num_rows_registrar_conductor($criterio, $dato)
    {
        $query = $this->db->query('SELECT * FROM tbl_conductor WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        return $query->getNumRows();
    }

    public function num_rows_actualizacion_conductor($criterio, $dato, $id_conductor)
    {
        $query = $this->db->query('SELECT * FROM tbl_conductor WHERE ' . sql_ident($criterio) . '=? AND id_conductor<>?', [$dato, $id_conductor]);

        return $query->getNumRows();
    }

    public function num_rows_actualizacion_persona($criterio, $dato, $id_conductor)
    {
        $id_person     = $this->get_id_person_actual($id_conductor);
        $id_encontrado = $this->get_id_person_conductor($criterio, $dato); // buscamos el id del registro actual
        // primero validamos si existe otro conductor con el mismo dato que queremos asignar para actualizar
        $query = $this->db->query('SELECT * FROM tbl_persona as p LEFT JOIN tbl_conductor as a ON p.id_persona=a.id_person_conductor WHERE p.' . sql_ident($criterio) . '=? AND a.id_conductor<>?', [$dato, $id_conductor]);

        if ($query->getNumRows() === 0 || $id_encontrado === '') {
            // segundo verificamos si existe otra persona que no es afiliado si tienen ese mismo dato
            if ($id_encontrado === '') {
                return 0;
            }
            if ($id_person == $id_encontrado) {
                return 0;
            }

            return 1;
        }

        return $query->getNumRows();
    }

    public function get_id_person_by_documento($documento)
    {
        $id_person = '';
        $query     = $this->db->query('SELECT id_persona FROM `tbl_persona` WHERE documento=?', [$documento]);

        foreach ($query->getResult() as $row) {
            $id_person = $row->id_persona;
        }

        return $id_person;
    }

    public function existe_conductor($id_person)
    {
        $query = $this->db->query('SELECT * FROM `tbl_conductor` WHERE id_person_conductor=?', [$id_person]);

        return $query->getNumRows();
    }

    public function existe_afiliado($id_person)
    {
        $query = $this->db->query('SELECT * FROM `tbl_afiliado` WHERE id_person_afiliado=?', [$id_person]);

        return $query->getNumRows();
    }

    public function ya_es_afiliado($documento)
    {
        $id_person = $this->get_id_person_by_documento($documento);

        if ($id_person === '') {
            return 0;
        }
        if ($this->existe_conductor($id_person)) {
            return -1;
        }
        if ($this->existe_afiliado($id_person)) {
            return 1;
        }

        return null;
    }
}
