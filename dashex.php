<?php
include "dbex.php";

/* Total Records */
$total = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT COUNT(*) AS total FROM Motion_data"));

/* Latest Record */
$latest = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM Motion_data ORDER BY Id DESC LIMIT 1"));

/* All Records */
$data = mysqli_query($conn,
"SELECT * FROM Motion_data ORDER BY Id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="5">

<title>Smart Motion Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef2f7;
    font-family:Arial;
}

/* TITLE */
.title{
    text-align:center;
    font-size:26px;
    font-weight:bold;
    margin:15px;
    color:#0d6efd;
}

/* SMALL CARDS */
.small-card{
    background:white;
    border-radius:10px;
    padding:15px;
    box-shadow:0px 2px 8px rgba(0,0,0,0.1);
    text-align:center;
    height:100%;
}

.small-title{
    font-size:14px;
    color:gray;
    font-weight:600;
}

.small-value{
    font-size:24px;
    font-weight:bold;
    margin-top:5px;
}

/* LIVE STREAM */
.live-header{
    background:#1b1f27;
    color:white;
    padding:8px;
    font-size:13px;
    font-weight:bold;
}

.live-body{
    height:320px;
    background:black;
}

/* TABLE HEADER */
.table-header{
    background:#0d6efd;
    color:white;
    padding:8px;
    font-size:13px;
    font-weight:bold;
}

table td, table th{
    font-size:14px;
}

</style>

</head>

<body>

<div class="container-fluid">

<div class="title">
MOTION DETECTION & SECURITY SYSTEM
</div>

<!-- SMALL STATS ROW -->
<div class="row g-3 mb-3">

<div class="col-md-3">
<div class="small-card">
<div class="small-title">TOTAL RECORDS</div>
<div class="small-value text-primary">
<?= $total['total']; ?>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-card">
<div class="small-title">LATEST DISTANCE</div>
<div class="small-value text-danger">
<?= $latest['Distance'] ?? 0; ?> cm
</div>
</div>
</div>

</div>

<!-- MAIN CONTENT -->
<div class="row g-3">

<!-- ESP32 CAMERA -->
<div class="col-lg-8">

<div class="card">

<div class="live-header">
ESP32-CAM LIVE STREAM
</div>

<div class="live-body">

<img src="http://192.168.1.105:81/stream"
style="width: 100px;%; height:100%; object-fit:cover;">

</div>

</div>

</div>

<!-- TABLE -->
<div class="col-lg-4">

<div class="card">

<div class="table-header">
MOTION DATA TABLE
</div>

<div class="card-body">

<table class="table table-striped table-hover">

<tr>
<th>Id</th>
<th>Distance</th>
<th>Time</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)) { ?>

<tr>
<td><?php echo $row['Id']; ?></td>
<td><?php echo $row['motion_detected']; ?> cm</td>
<td><?php echo $row['timestamp']; ?></td>
</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</div>

</div>

</body>
</html>