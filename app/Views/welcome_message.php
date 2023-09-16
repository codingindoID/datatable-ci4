<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ?>jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        table,
        table thead tr,
        table thead tr th,
        table tbody tr td,
        table tbody tr {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div class="container">
        <h5>Contoh Datatable Serverside</h5>
        <hr>
        <table class="table" width="100%" id="example">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID 2</th>
                    <th>ID 3</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
    <script src="<?= base_url('assets/') ?>jquery-3.7.0.js"></script>
    <script src="<?= base_url('assets/') ?>jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "serverSide": true,
                "pageLength": 10,
                "ajax": {
                    "url": "<?= base_url('datatable-data') ?>",
                    "type": "POST"
                },
            });
        });
    </script>
</body>

</html>