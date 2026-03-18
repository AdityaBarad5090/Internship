<?php
$conn = new mysqli("localhost", "root", "", "demo");

if (isset($_POST['order'])) {

    foreach ($_POST['order'] as $pos => $id) {
        $pos = $pos + 1;
        $conn->query("UPDATE categories SET sortorder='$pos' WHERE id='$id'");
    }
    exit;
}
$result = $conn->query("SELECT * FROM categories ORDER BY sortorder");

if (isset($_POST['id'])) {

    $id   = $_POST['id'];
    $from = $_POST['from'];
    $to   = $_POST['to'];

    $result = $conn->query("SELECT * FROM $from WHERE id='$id'");

    if ($result && $result->num_rows > 0) {

        $row  = $result->fetch_assoc();
        $name = $row['name'];

        $conn->query("INSERT INTO $to(name) VALUES('$name')");
        $conn->query("DELETE FROM $from WHERE id='$id'");
    }
    exit;
}

$table1 = $conn->query("SELECT * FROM table1");
$table2 = $conn->query("SELECT * FROM table2");
?>

<!DOCTYPE html>
<html>

<head>

    <title>Documentation</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <style>
        .container {
            display: flex;
            gap: 40px;
          
            height: 350px;
            margin-top: 60px;
        }

        ul {
            width: 200px;
            min-height: 200px;
            padding: 10px;
            list-style: none;
        }

        li {
            padding: 8px;
            margin: 8px;
            background: #f2f2f2;
            border: 1px solid #999;
        }

        #sortable {
            width: 200px;
        }

        #sortable li {
            border: 1px solid #999;
            margin: 8px;
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <div style="display: flex;margin-left: 450px;gap: 25px;margin-top: 50px;">

        <div style="border-right: 1px solid gray;padding-right: 30px;">
            <ul id="sortable">
                <?php while ($row = $result->fetch_assoc()) { ?>

                    <li data-id="<?php echo $row['id']; ?>">
                        ⭥ <?php echo $row['name']; ?>
                    </li>

                <?php } ?>
            </ul>
        </div>

        <div class="container"> 

            <ul id="table1" class="connected">

                <?php while ($row = $table1->fetch_assoc()) { ?>
                    <li data-id="<?= $row['id'] ?>">
                        <?= $row['name'] ?>
                    </li>
                <?php } ?>

            </ul>
            <ul id="table2" class="connected">

                <?php while ($row = $table2->fetch_assoc()) { ?>
                    <li data-id="<?= $row['id'] ?>">
                        <?= $row['name'] ?>
                    </li>
                <?php } ?>

            </ul>
        </div>

    </div>
    <script>
        $(".connected").sortable({

            connectWith: ".connected",

            receive: function(event, ui) {
            var id = ui.item.data("id");
            var from = ui.sender.attr("id");
            var to = $(this).attr("id");

                $.ajax("", {
                    type: "POST",
                    url: "",
                    data: {
                        id: id,
                        from: from,
                        to: to
                    }
                });
            }
        });

        $("#sortable").sortable({
            update: function() {
                var order = [];

                $("#sortable li").each(function() {
                    order.push($(this).data("id"));
                });

                $.ajax({
                    type: "POST",
                    url: "",
                    data: {
                        order: order
                    }
                });
            }
        });
    </script>
</body>

</html>