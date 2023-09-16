<?php

namespace App\Controllers;

use App\Models\HomeModel;

class Home extends BaseController
{
    protected $homeModel;
    public function __construct()
    {
        $this->homeModel = new HomeModel();
    }
    public function index()
    {
        return view('welcome_message');
    }

    function dataTable()
    {
        $data = $this->homeModel->drawTable();
        return $this->response->setJSON($data);
    }

    /* 
    
{
    "draw": 1,
    "recordsTotal": 0,
    "recordsFiltered": 0,
    "tangkap": {
        "draw": "3",
        "columns": [
            {
                "data": "0",
                "name": "",
                "searchable": "true",
                "orderable": "true",
                "search": {
                    "value": "",
                    "regex": "false"
                }
            },
            {
                "data": "1",
                "name": "",
                "searchable": "true",
                "orderable": "true",
                "search": {
                    "value": "",
                    "regex": "false"
                }
            },
            {
                "data": "2",
                "name": "",
                "searchable": "true",
                "orderable": "true",
                "search": {
                    "value": "",
                    "regex": "false"
                }
            }
        ],
        "order": [
            {
                "column": "0",
                "dir": "asc"
            }
        ],
        "start": "0",
        "length": "10",
        "search": {
            "value": "asd",
            "regex": "false"
        }
    },
    "data": []
}

    */
}
