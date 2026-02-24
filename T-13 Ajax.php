<?php
include 'db.php';

if (isset($_POST['country_id'])) {
    $country_id = $_POST['country_id'];
    $query = mysqli_query($conn, "SELECT * FROM state WHERE country_id='$country_id'");

    $row = mysqli_fetch_all($query, MYSQLI_ASSOC);
    echo json_encode($row);
    exit;
}

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $query = mysqli_query($conn, "SELECT * FROM city WHERE state_id='$state_id'");

    $row = mysqli_fetch_all($query, MYSQLI_ASSOC);
    echo json_encode($row);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div style="place-items:center;margin-top:200px">
        <div>
            <select id="country" class="form-select" style="width: 200px;border : 1px solid">
                <option value="">Select Country</option>
                <?php
                $country = mysqli_query($conn, "SELECT * FROM country");
                while ($row = mysqli_fetch_assoc($country)) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }   ?>
            </select>
        </div>

        <div style="margin-top: 20px;width: 200px;">
            <select id="state" class="form-select" style="border : 1px solid">
                <option value="">Select State</option>
            </select>
        </div>

        <div style="margin-top: 20px; width: 200px;">
            <select id="city" class="form-select" style="border : 1px solid">
                <option value="">Select City</option>
            </select>
        </div>

    </div>
    <script>
        $('#country').change(function() {
            let country_id = $(this).val();
            $.ajax({
                url: "index.php",
                method: "POST",
                data: {
                    country_id: country_id
                },
                dataType: "json",
                success: function(data) {
                    let option = '<option value="">Select State</option>';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }

                    $("#state").html(option);
                    $("#city").html('<option value="">Select City</option>');
                },
            });
        });

        $("#state").change(function() {
            let state_id = $(this).val();
            $.ajax({
                url: "index.php",
                method: "POST",
                data: {
                    state_id: state_id
                },
                dataType: "json",
                success: function(data) {
                    let option = '<option value="">Select City</option>';
                    for (let i = 0; i < data.length; i++) {
                        option += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }

                    $("#city").html(option);
                }
            });
        });
    </script>
</body>

</html>