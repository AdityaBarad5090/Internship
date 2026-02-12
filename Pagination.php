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

$query = "
    SELECT * FROM users WHERE username LIKE '%$search%'
       OR email LIKE '%$search%'
       OR mobile LIKE '%$search%'
       OR city LIKE '%$search%'
";

if (!empty($sort) && !empty($order)) {
    $query .= " ORDER BY $sort $order ";
}

$query .= " LIMIT $start, $limit ";

$result = mysqli_query($conn, $query);

$countQuery = "
    SELECT COUNT(*) AS total
    FROM users 
    WHERE username LIKE '%$search%'
       OR email LIKE '%$search%'
       OR mobile LIKE '%$search%'
       OR city LIKE '%$search%'
";

$countResult = mysqli_query($conn, $countQuery);
$totalRow = mysqli_fetch_assoc($countResult);
$totalData = $totalRow['total'];

$totalPages = ceil($totalData / $limit);

?>
<!DOCTYPE html>
<html>

<head>   
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        a{
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>                
    <form method="GET" class="d-flex mb-3 mt-3 justify-content-center">
 
        <select name="limit" class="form-select w-auto" onchange="this.form.submit()">
            <option value="5" <?= ($limit == 5)  ? 'selected' : '' ?>>5</option>
            <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
            <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
            <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
        </select>

        <input type="text"
            name="search"
            class="form-control"
            placeholder="Search"
            value="<?= $search ?>"
            onblur="this.form.submit()"
            style="width:250px; border:1px solid;margin-left: 446px;">
    </form>
               
    <table class="table table-bordered mx-auto" style="width:800px;border:1px solid">
        <tr>
            <th style="width: 80px;">ID
                <a href="?sort=id&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 18px;">&#129029;</a>
                <a href="?sort=id&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 140px;">Username
                <a href="?sort=username&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 21px;">&#129029;</a>
                <a href="?sort=username&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 200px;">Email
                <a href="?sort=email&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 116px;">&#129029;</a>
                <a href="?sort=email&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 175px;">Mobile
                <a href="?sort=mobile&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 80px;">&#129029;</a>
                <a href="?sort=mobile&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
            <th style="width: 180px;">City
                <a href="?sort=city&order=asc&limit=<?php echo $limit ?>&search=<?php echo $search ?>" style="margin-left: 107px;">&#129029;</a>
                <a href="?sort=city&order=desc&limit=<?php echo $limit ?>&search=<?php echo $search ?>">&#129031;</a>
            </th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id'] ?></td>
                <td><?php echo $row['username'] ?></td>                                                         
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['mobile'] ?></td>  
                <td><?php echo $row['city'] ?></td>
            </tr>
        <?php } ?>
    </table>

        <ul class="pagination justify-content-center"s>
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <li class="page-item <?php echo($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?php echo $i ?>&limit=<?php echo $limit ?>&search=<?php echo $search ?>&sort=<?php echo $sort ?>&order=<?php echo $order ?>">
                        <?php echo $i ?>
                    </a>
                </li>
            <?php } ?>
        </ul>                                                                                                                

    <?php mysqli_close($conn); ?>

</body>

</html>