<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    public function guardar($data)
    {
        $this->db->table('tbl_persona')->insert($data);
    }

    public function actualizar($data, $id_persona)
    {
        $this->db->table('tbl_persona')->where('id_persona', $id_persona)->update($data);
    }

    public function get_id_person($criterio, $dato)
    {
        $query = $this->db->query('SELECT id_persona FROM `tbl_persona` WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        return $query->getNumRows();
    }

    public function num_rows($criterio, $dato)
    {
        $query = $this->db->query('SELECT * FROM `tbl_persona` WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        return $query->getNumRows();
    }

    public function num_rows_afiliado($criterio, $dato)
    {
        $id_persona = $this->get_id_person($criterio, $dato);
        $num_rows   = $this->num_rows($criterio, $dato);

        if ($id_persona == '') {
            return 0;
        }
        if ($num_rows == 0) {
            $query = $this->db->query('SELECT * FROM `tbl_afiliado` WHERE id_person_afiliado=?', [$id_persona]);

            return $query->getNumRows();
        }

        return $num_rows;
    }

    public function num_rows_conductor($criterio, $dato)
    {
        $id_persona = $this->get_id_person($criterio, $dato);
        $num_rows   = $this->num_rows($criterio, $dato);

        if ($id_persona == '') {
            return 0;
        }
        if ($num_rows == 0) {
            $query = $this->db->query('SELECT * FROM `tbl_conductor` WHERE id_person_conductor=?', [$id_persona]);

            return $query->getNumRows();
        }

        return $num_rows;
    }
}
