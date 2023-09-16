<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_datatable extends CI_Model
{
    var $table             = 'tb_permohonan';
    var $column_order      = ['id_permohonan', 'tgl_pengajuan', 'nama', 'nama_perijinan', '', 'tgl_penetapan'];
    var $column_search     = ['id_permohonan', 'tgl_pengajuan', 'nama', 'nama_perijinan', 'nik', 'tgl_penetapan', 'nama_peruntukan', 'jenis_perijinan'];
    var $order             = ['tgl_penetapan' => 'desc'];

    /*serverside penduduk*/
    private function _get_datatables_query($params = null)
    {
        switch ($params) {
            case 'selesai':
                $where = [SELESAI];
                break;
            case 'proses':
                $where = [0, 8, 15, 16, REVISI, DICABUT, DITOLAK];
                break;
            case 'dicabut':
                $where = [DICABUT];
                break;
            case 'batal':
                $where = [DITOLAK];
                break;
            case 'revisi':
                $where = [REVISI, REVISI_MANDIRI];
                break;
            default:
                $where = [];
                break;
        }

        // $where = [SELESAI, DITOLAK];
        $this->db->select('*');
        $this->db->from('tb_permohonan');
        $this->db->join('tb_pemohon', 'tb_pemohon.id_pemohon = tb_permohonan.id_pemohon');
        $this->db->join('tb_perijinan', 'tb_perijinan.id_perijinan = tb_permohonan.perijinan');
        $this->db->join('tb_peruntukan', 'tb_permohonan.peruntukan = tb_peruntukan.id_peruntukan');
        if ($params == 'proses') {
            $this->db->where_not_in('tb_permohonan.status', $where);
        } else {
            $this->db->where_in('tb_permohonan.status', $where);
        }
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($params)
    {
        $this->_get_datatables_query($params);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtered($params)
    {
        $this->_get_datatables_query($params);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}

/* End of file M_datatables.php */
