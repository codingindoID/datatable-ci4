<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_permohonan'; // sesuaikan sesuai table
    protected $primaryKey       = 'id_permohonan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];
    protected $column_order      = ['id_permohonan', 'id_pemohon', 'perijinan']; // sesuaikan kolom table
    protected $column_search     = ['id_permohonan', 'id_pemohon', 'perijinan']; // sesuaikan kolom table
    protected $order             = ['id_permohonan' => 'desc'];
    protected $builder;
    protected $request;

    public function __construct()
    {
        $this->request = \Config\Services::request();
    }

    function _get_datatables_query()
    {
        $request = $this->request;
        $post = isset($request) ? $request->getPost('search')['value'] : '';
        $builder =  $this->table($this->table);
        $builder = $builder->select('id_permohonan, id_pemohon,perijinan');

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($post) {
                if ($i === 0) {
                    $builder = $builder->groupStart();
                    $builder = $builder->like($item, $post);
                } else {
                    $builder = $builder->orLike($item, $post);
                }

                if (count($this->column_search) - 1 == $i)
                    $builder = $builder->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $builder = $builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder = $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    function getDataTable()
    {
        $builder = $this->_get_datatables_query();
        if ($this->request->getPost('length') != -1)
            $builder  = $builder->limit($this->request->getPost('length'), $this->request->getPost('start'));

        $builder = $builder->get()->getResult();
        return $builder;
    }

    function drawTable()
    {
        $request = $this->request;
        $draw = isset($request) ? $request->getPost('draw') : 1;
        $list = $this->getDataTable();
        $data = array();
        foreach ($list as $o) {
            $row = array();
            $row[] = $o->id_permohonan;
            $row[] = $o->id_pemohon;
            $row[] = $o->perijinan;
            $data[] = $row;
        }

        $output = [
            "draw"                  => $draw,
            "recordsTotal"          => $this->count_all(),
            "recordsFiltered"       => $this->count_filtered(),
            "data"                  => $data,
        ];
        return $output;
    }

    function count_filtered()
    {
        $builder = $this->_get_datatables_query();
        return $builder->countAllResults();
    }

    public function count_all()
    {
        $builder =  $this->table($this->table);
        return $builder->countAll();
    }
}
