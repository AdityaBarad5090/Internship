<?php
$conn = new mysqli("localhost", "root", "", "demo");

if (!$conn) {
    die("Connection failed");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dcomentation</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div>
        <div class="d-flex flex-column align-items-center" style="margin-top: 100px;">
            <select id="singledrop" style="width:500px" class="form-control">

                <?php
                $query = "SELECT * FROM selects";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {  ?>

                    <option value="<?php echo $row['value']; ?>">
                        <?php echo $row['name']; ?>
                    </option>

                <?php } ?>
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
                <?php
                $query = "SELECT * FROM selects"; 
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) { ?>

                    <option value="<?php echo $row['value']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
     
                <?php } ?>
            </select>
        </div>

        <div class="d-flex justify-content-center mt-3 gap-2">
            <button id="multiselect" class="btn btn-secondary">Set California & Alabama</button>
            <button id="clear" class="btn btn-secondary">Clear</button>
        </div>

    </div>
    <script>
        $(document).ready(function() {

            $('#singledrop').select2({
                placeholder: "Select State",
                allowClear: true
            });

            $('#multidrop').select2({
                placeholder: "Select State",
                allowClear: true
            });

            $('#open').click(function() {
                $('#singledrop').select2('open');
            });

            $('#close').click(function() {
                $('#singledrop').select2('close');
            });

            $('#setvalue').click(function() {
                $('#singledrop').val('CA').trigger('change');
            });

            $('#destroy').click(function() {
                $('#singledrop').select2('destroy');
            });

            $('#init').click(function() {
                $('#singledrop').select2({
                    placeholder: "Select City",
                    allowClear: true
                });
            });

            $('#multiselect').click(function() {
                $('#multidrop').val(['CA', 'AL']).trigger('change');
            });
 
            $('#clear').click(function() {
                $('#multidrop').val(null).trigger('change');
            });
  
        });
    </script>
</body>
</html> 