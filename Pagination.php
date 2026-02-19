<?php
include 'db.php';

$limit =  10;
$page =  1;
$search = '';
$sort  = '';
$order = '';

if (isset($_GET['limit'])) {
    $limit = $_GET['limit'];
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
}
if (isset($_GET['order'])) {
    $order = $_GET['order'];
}

$start = ($page - 1) * $limit;

$query = " SELECT * FROM users WHERE username LIKE '%$search%'
       OR email LIKE '%$search%'
       OR mobile LIKE '%$search%'
       OR city LIKE '%$search%' ";

if ($sort != "" && $order != "") {
    $query .= " ORDER BY $sort $order ";
}

$query .= " LIMIT $start, $limit ";

$result = mysqli_query($conn, $query);


 
$countquery = " SELECT COUNT(*) AS total FROM users 
    WHERE username LIKE '%$search%'
       OR email LIKE '%$search%'
       OR mobile LIKE '%$search%'
       OR city LIKE '%$search%'";

$countresult = mysqli_query($conn, $countquery);
$countrow = mysqli_fetch_assoc($countresult);
$countdata = $countrow['total'];


$totalPages = ceil($countdata / $limit);

$startEntry = $start + 1;

$endEntry = $start + $limit;
if ($endEntry > $countdata) {
    $endEntry = $countdata;
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$delete_id");
    header("Location: index.php");
    exit();
}

$editData = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM users WHERE id=$edit_id");
    $editData = mysqli_fetch_assoc($editQuery);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $city = $_POST['city'];

    mysqli_query($conn, "UPDATE users SET username='$username',email='$email',mobile='$mobile',city='$city' WHERE id=$id ");
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        a {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <form method="GET" class="d-flex mb-3 mt-3 justify-content-center">

        <label style="margin-top: 5px;">Show</label>
        <select name="limit" class="form-select w-auto" style="margin-left: 5px;" onchange="this.form.submit()">
            <option value="5" <?php echo ($limit == 5)  ? 'selected' : '' ?>>5</option>
            <option value="10" <?php echo ($limit == 10) ? 'selected' : '' ?>>10</option>
            <option value="25" <?php echo ($limit == 25) ? 'selected' : '' ?>>25</option>
            <option value="50" <?php echo ($limit == 50) ? 'selected' : '' ?>>50</option>
        </select>

        <input type="text"
            name="search"
            class="form-control form-control-sm  "
            placeholder="Search"
            value="<?= $search ?>"
            onblur="this.form.submit()"
            style="width: 200px; border:1px solid;margin-left: 990px;">
    </form>

    <table class="table table-bordered table-sm mx-auto" style="border:1px solid ; width : 85%;margin:auto;">
        <tr>
            <th style="width:104px">ID
                <a href="?sort=id&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 46px;">&#129029;</a>
                <a href="?sort=id&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 200px;">Username
                <a href="?sort=username&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 90px;">&#129029;</a>
                <a href="?sort=username&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 300px;">Email
                <a href="?sort=email&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 230px;">&#129029;</a>
                <a href="?sort=email&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 250px;">Mobile
                <a href="?sort=mobile&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 160px;">&#129029;</a>
                <a href="?sort=mobile&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 250px;">City
                <a href="?sort=city&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 185px;">&#129029;</a>
                <a href="?sort=city&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 120px;">Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['username'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['mobile'] ?></td>
                <td><?php echo $row['city'] ?></td>
                <td>
                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure delete this users?')" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div style="display: flex;margin-top: 15px;">

        <div style="margin-left: 113px;font-size :15px;width: 1085px;">
            <label>
                Showing <?php echo $startEntry; ?> to <?php echo $endEntry; ?>
                of <?php echo $countdata; ?> entries
                <?php if ($search != '') { ?>
                <?php
                $totalresult = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
                $totalrow = mysqli_fetch_assoc($totalresult);
                $totaldata = $totalrow['total']; ?>
                    (filtered from <?php echo $totaldata; ?> total entries)
                <?php } ?>
            </label>
        </div>

        <div class="justify-content-center">
            <ul class="pagination pagination-sm">

                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?page=<?php echo $page - 1 ?>&limit=<?php echo $limit ?>&search=<?php echo $search ?>&sort=<?php echo $sort ?>&order=<?php echo $order ?>">
                        Previous
                    </a>
                </li>

                <?php

                $startpage = $page - 2;
                $endpage = $page + 2;

                if ($startpage < 1) {
                    $startpage = 1;
                }

                if ($endpage > $totalPages) {
                    $endpage = $totalPages;
                }

                for ($i = $startpage; $i <= $endpage; $i++) {
                ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link"
                            href="?page=<?php echo $i ?>&limit=<?php echo $limit ?>&search=<?php echo $search ?>&sort=<?php echo $sort ?>&order=<?php echo $order ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php } ?>

                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?page=<?php echo $page + 1 ?>&limit=<?php echo $limit ?>&search=<?php echo $search ?>&sort=<?php echo $sort ?>&order=<?php echo $order ?>">
                        Next
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <a class="btn-close" data-bs-dismiss="modal"></a>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

                        <div class="mb-2">
                            <label>Username</label>
                            <input type="text" id="username" name="username" value="<?php echo $editData['username'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Email</label>
                            <input type="text" name="email" value="<?php echo $editData['email'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Mobile</label>
                            <input type="text" name="mobile" value="<?php echo $editData['mobile'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>City</label>
                            <input type="text" name="city" value="<?php echo $editData['city'] ?? ''; ?>" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($editData) { ?>
            var modal = document.getElementById('editModal');
            var myModal = new bootstrap.Modal(modal);

            modal.addEventListener('shown.bs.modal', function() {
                document.getElementById('username').focus();
            });

            myModal.show();
        <?php } ?>
    </script>
    <?php mysqli_close($conn); ?>
</body>

</html>
