<?php
$conn = new mysqli("localhost", "root", "", "demo");

if (!$conn) {
    die("Connection failed");
}

if (isset($_POST['getdata'])) {
    $search = $_POST['search'] ?? '';

    if ($search != '') {
        $query = "SELECT * FROM selects WHERE name LIKE '%$search%'";
    } else {
        $query = "SELECT * FROM selects";
    }

    $result = mysqli_query($conn, $query);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            "id" => $row['value'],
            "text" => $row['name']
        ];
    }
    echo json_encode([
        'results' => $data
    ]);
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Documentation</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div>

        <div class="d-flex flex-column align-items-center" style="margin-top:100px;">

            <select id="singledrop" style="width:500px" class="form-control">
            </select>

        </div>


        <div class="d-flex justify-content-center mt-3 gap-2">
            <button id="setvalue" class="btn btn-secondary">Set California</button>
            <button id="open" class="btn btn-secondary">Open</button>
            <button id="close" class="btn btn-secondary">Close</button>
            <button id="init" class="btn btn-secondary">Init</button>
            <button id="destroy" class="btn btn-secondary">Destroy</button>
        </div>


        <div class="d-flex flex-column align-items-center mt-5">

            <select id="multidrop" multiple style="width:500px">
            </select>

        </div>


        <div class="d-flex justify-content-center mt-3 gap-2">
            <button id="multisetvalue" class="btn btn-secondary">Set California & Alabama</button>
            <button id="clear" class="btn btn-secondary">Clear</button>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <button id="save" class="btn btn-primary">Save</button>
        </div>

    </div>


    <script>
        $(document).ready(function() {

            $('#singledrop').select2({
                ajax: {
                    url: "",
                    type: "POST",
                    dataType: "json",   
                    delay:700,
                    data: function(params) {
                        return {
                            getdata: 1,
                            search: params.term,
                        };
                    },
                    processResults: function(data) {
                        return data;
                    }
                },
                placeholder: "Select State",
            });

            $('#multidrop').select2({
                ajax: {
                    url: "",
                    type: "POST",
                    dataType: "json",
                    delay:700,
                    data: function(params) {
                        return {
                            getdata: 1,
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return data;
                    }
                },
                placeholder: "Select State",
            });

            $('#open').click(function() {
                $('#singledrop').select2('open');
            });

            $('#close').click(function() {
                $('#singledrop').select2('close');
            });

            $('#setvalue').click(function() {
                var option = new Option("California", "CA", true, true);
                $('#singledrop').append(option).val('CA').trigger('change');
            });

            $('#destroy').click(function() {
                $('#singledrop').select2('destroy');
            });

            $('#init').click(function() {
                $('#singledrop').select2({
                    ajax: {
                        url: "",
                        type: "POST",
                        dataType: "json",
                        data: function(params) {
                            return {
                                getdata: 1,
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            return data;
                        }
                    },
                    placeholder: "Select State",
                });
            });

            $('#multisetvalue').click(function() {
                var option1 = new Option("California", "CA", true, true);
                var option2 = new Option("Alabama", "AL", true, true);
                $('#multidrop').append(option1).append(option2).val(['CA', 'AL']).trigger('change');
            });

            $('#clear').click(function() {
                $('#multidrop').val(null).trigger('change');
            });

            $('#save').click(function() {
                var singleval = $('#singledrop').val();
                var multival = $('#multidrop').val();

                console.log("single value:", singleval);
                console.log("multi value:", multival);
            });
        });
    </script>
</body>

</html>
