<?php
$conn = new mysqli("localhost", "root", "", "demo");

if (!$conn) {
    die("connection failed");
}

$result = $conn->query("SELECT * FROM employee");

$editdata = "";
if (isset($_GET['edit'])) {
    $editid = $_GET['edit'];
    $editqry = mysqli_query($conn, "SELECT * FROM employee WHERE id=$editid");
    $editdata = mysqli_fetch_assoc($editqry);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $office = $_POST['office'];
    $salary = $_POST['salary'];

    mysqli_query($conn, "UPDATE employee SET name='$name', position='$position', office='$office', salary='$salary' WHERE id=$id ");
    header("Location: Datatable.php");
    exit();
}

if (isset($_GET['delete'])) {
    $delid = $_GET['delete'];
    $delqry = mysqli_query($conn, "DELETE FROM employee WHERE id=$delid");
    header("Location: Datatable.php");
    exit();
}

if (isset($_POST['insert'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $office = $_POST['office'];
    $salary = $_POST['salary'];

    mysqli_query($conn, "INSERT INTO employee (name, position, office, salary) VALUES ('$name', '$position', '$office', '$salary')");
    header("Location: Datatable.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Documentation</title>
    <style>
        .dataTables_wrapper .row {
            justify-content: center;
            gap: 11px;
            margin-top: 10px;
        }

        .dataTables_filter {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }


        .dataTables_length {
            width: 100%;
            text-align: center;
        }

        .dataTables_info {
            width: 100%;
            text-align: center;
        }

        .dataTables_paginate {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .table.dataTable {
            margin-top: -15px !important;
            margin-bottom: -27px !important;
        }

        .dataTables_filter {
            width: 70%;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .addbtn {
            position: absolute;
            left: 245px;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</head>

<body>
    <!-- <div style="justify-content: center; align-items: center;display: flex;">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">Add User</button>
    </div> -->

    <table id="datatable" class="table table-striped table-bordered p-3 m-auto" style="width: 70%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Salary</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['office']; ?></td>
                    <td><?php echo $row['salary']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure delete this users?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="modal fade" id="editmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <a class="btn-close" data-bs-dismiss="modal"></a>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" value="<?php echo $editdata['id'] ?? ''; ?>">
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $editdata['name'] ?? ''; ?>" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Position</label>
                            <input type="text" name="position" value="<?php echo $editdata['position'] ?? ''; ?>" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Office</label>
                            <input type="text" name="office" value="<?php echo $editdata['office'] ?? ''; ?>" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Salary</label>
                            <input type="text" name="salary" value="<?php echo $editdata['salary'] ?? ''; ?>" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <a class="btn-close" data-bs-dismiss="modal"></a>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id">
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" id="addname" name="name" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Position</label>
                            <input type="text" name="position" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Office</label>
                            <input type="text" name="office" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="insert" class="btn btn-primary">Add</button>
                    </div>

                </form>
            </div>
        </div>
    </div> 

    <script>
        $(function() {
            $('#datatable').DataTable();

            $('.dataTables_filter').prepend(
                '<button class="btn btn-primary addbtn" data-bs-toggle="modal" data-bs-target="#addmodal">Add User</button>'
            );
        });

        <?php if ($editdata) { ?>
            var modal = document.getElementById('editmodal');
            var myModal = new bootstrap.Modal(modal);

            modal.addEventListener('shown.bs.modal', function() {
                document.getElementById('name').focus();
            });

            myModal.show();
        <?php } ?>

        var addmodal = document.getElementById('addmodal');
        addmodal.addEventListener('shown.bs.modal', function() {
            document.getElementById('addname').focus();
        });
    </script>

</body>

</html>