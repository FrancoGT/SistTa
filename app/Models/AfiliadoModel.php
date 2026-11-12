<?php

namespace App\Models;

use CodeIgniter\Model;

class AfiliadoModel extends Model
{
    public function guardar($data)
    {
        $this->db->table('tbl_afiliado')->insert($data);
    }

    public function get_id_person($id_afiliado)
    {
        $id_person = '';
        $query     = $this->db->query('SELECT id_person_afiliado FROM `tbl_afiliado` WHERE id_afiliado=?', [$id_afiliado]);

        foreach ($query->getResult() as $row) {
            $id_person = $row->id_person_afiliado;
        }

        return $id_person;
    }

    public function get_id_person_afiliado($criterio, $dato)
    {
        $id_person = '';
        $query     = $this->db->query('SELECT id_persona FROM `tbl_persona` WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        foreach ($query->getResult() as $row) {
            $id_person = $row->id_persona;
        }

        return $id_person;
    }

    public function get_id_person_actual($id_afiliado)
    {
        $id_person = '';
        $query     = $this->db->query('SELECT id_person_afiliado FROM `tbl_afiliado` WHERE id_afiliado=?', [$id_afiliado]);

        foreach ($query->getResult() as $row) {
            $id_person = $row->id_person_afiliado;
        }

        return $id_person;
    }

    public function actualizar($data, $id_afiliado)
    {
        $this->db->table('tbl_afiliado')->where('id_afiliado', $id_afiliado)->update($data);
    }

    public function num_rows_registrar_afiliado($criterio, $dato)
    {
        $query = $this->db->query('SELECT * FROM tbl_afiliado WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        return $query->getNumRows();
    }

    public function num_rows_actualizacion_afiliado($criterio, $dato, $id_afiliado)
    {
        $query = $this->db->query('SELECT * FROM tbl_afiliado WHERE ' . sql_ident($criterio) . '=? AND id_afiliado<>?', [$dato, $id_afiliado]);

        return $query->getNumRows();
    }

    public function num_rows_actualizacion_persona($criterio, $dato, $id_afiliado)
    {
        $id_person     = $this->get_id_person_actual($id_afiliado);
        $id_encontrado = $this->get_id_person_afiliado($criterio, $dato); // buscamos el id del registro actual
        // primero validamos si existe otro afiliado con el mismo dato que queremos asignar para actualizar
        $query = $this->db->query('SELECT * FROM tbl_persona as p LEFT JOIN tbl_afiliado as a ON p.id_persona=a.id_person_afiliado WHERE p.' . sql_ident($criterio) . '=? AND a.id_afiliado<>?', [$dato, $id_afiliado]);

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
}
