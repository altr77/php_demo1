<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirect to login if session not set
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit();
}

include_once 'dbConnection.php';

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'];
$name = $_SESSION['name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="css/font.css"/>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body style="background:#eee;">
<div class="header">
    <div class="row">
        <div class="col-lg-6">
            <span class="logo">Online Exam System</span>
        </div>
        <div class="pull-right top title1">
            <span class="log1"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;&nbsp;Hello,</span>
            <a href="#" class="log log1"><?= $name ?></a>&nbsp;|&nbsp;
            <a href="logout.php?q=account.php" class="log">
                <span class="glyphicon glyphicon-log-out"></span>&nbsp;Logout
            </a>
        </div>
    </div>
</div>

<nav class="navbar navbar-default title1">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="dash.php?q=0"><b>Dashboard</b></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?= @$_GET['q']==0 ? 'class="active"' : '' ?>><a href="dash.php?q=0">Home</a></li>
                <li <?= @$_GET['q']==1 ? 'class="active"' : '' ?>><a href="dash.php?q=1">Users</a></li>
                <li <?= @$_GET['q']==2 ? 'class="active"' : '' ?>><a href="dash.php?q=2">User Rankings</a></li>
                <li <?= @$_GET['q']==3 ? 'class="active"' : '' ?>><a href="dash.php?q=3">Feedback</a></li>
                <li <?= @$_GET['q']==4 ? 'class="active"' : '' ?>><a href="dash.php?q=4">Add Exams</a></li>
                <li><a href="adminchangepass.php">Change Password</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
<div class="row">
<div class="col-md-12">

<?php
$q = @$_GET['q'] ?? 0;

// --- HOME: List Quizzes ---
if ($q == 0) {
    $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die("Error: " . mysqli_error($con));
    echo '<div class="panel"><table class="table table-striped title1">
    <tr><td><b>S.N.</b></td><td><b>Topic</b></td><td><b>Total Questions</b></td><td><b>Marks</b></td><td><b>Time</b></td><td></td></tr>';
    $c = 1;
    while ($row = mysqli_fetch_array($result)) {
        $title = $row['title'];
        $total = $row['total'];
        $sahi = $row['sahi'];
        $time = $row['time'];
        $eid = $row['eid'];
        echo "<tr><td>$c</td><td>$title</td><td>$total</td><td>" . ($sahi * $total) . "</td><td>$time min</td>
        <td><a href='update.php?q=rmquiz&eid=$eid' class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span> Remove</a></td></tr>";
        $c++;
    }
    echo '</table></div>';
}

// --- USERS ---
elseif ($q == 1) {
    $result = mysqli_query($con, "SELECT * FROM user") or die("Error: " . mysqli_error($con));
    echo '<div class="panel"><table class="table table-striped title1">
    <tr><td><b>S.N.</b></td><td><b>Name</b></td><td><b>Gender</b></td><td><b>College</b></td><td><b>Email</b></td><td><b>Mobile</b></td><td></td></tr>';
    $c = 1;
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td>$c</td><td>{$row['name']}</td><td>{$row['gender']}</td><td>{$row['college']}</td><td>{$row['email']}</td><td>{$row['mob']}</td>
        <td><a href='update.php?demail={$row['email']}' title='Delete User'><span class='glyphicon glyphicon-trash' style='color:red;'></span></a></td></tr>";
        $c++;
    }
    echo '</table></div>';
}

// --- RANKINGS ---
elseif ($q == 2) {
    $q1 = mysqli_query($con, "SELECT * FROM rank ORDER BY score DESC") or die("Error: " . mysqli_error($con));
    echo '<div class="panel"><table class="table table-striped title1"><tr><td><b>Rank</b></td><td><b>Name</b></td><td><b>Score</b></td></tr>';
    $c = 1;
    while ($row = mysqli_fetch_array($q1)) {
        $e = $row['email'];
        $s = $row['score'];
        $q2 = mysqli_query($con, "SELECT name FROM user WHERE email='$e'") or die("Error: " . mysqli_error($con));
        $user = mysqli_fetch_array($q2);
        echo "<tr><td>$c</td><td>{$user['name']}</td><td>$s</td></tr>";
        $c++;
    }
    echo '</table></div>';
}

// --- FEEDBACK ---
elseif ($q == 3) {
    $result = mysqli_query($con, "SELECT * FROM feedback ORDER BY date DESC") or die("Error: " . mysqli_error($con));
    echo '<div class="panel"><table class="table table-striped title1"><tr><td><b>Subject</b></td><td><b>Email</b></td><td><b>Date</b></td><td><b>By</b></td><td></td></tr>';
    while ($row = mysqli_fetch_array($result)) {
        $date = date("d-m-Y", strtotime($row['date']));
        echo "<tr><td><a href='dash.php?q=3&fid={$row['id']}'>{$row['subject']}</a></td><td>{$row['email']}</td><td>$date</td><td>{$row['name']}</td>
        <td><a href='update.php?fdid={$row['id']}' title='Delete Feedback'><span class='glyphicon glyphicon-trash' style='color:red;'></span></a></td></tr>";
    }
    echo '</table></div>';
}

// --- VIEW INDIVIDUAL FEEDBACK ---
if (isset($_GET['fid'])) {
    $id = $_GET['fid'];
    $res = mysqli_query($con, "SELECT * FROM feedback WHERE id='$id'") or die("Error: " . mysqli_error($con));
    if ($row = mysqli_fetch_array($res)) {
        $date = date("d-m-Y", strtotime($row['date']));
        echo "<div class='panel'><h3>{$row['subject']}</h3>
        <p><strong>By:</strong> {$row['name']} | <strong>Date:</strong> $date | <strong>Time:</strong> {$row['time']}</p>
        <p>{$row['feedback']}</p></div>";
    }
}
?>
</div>
</div>
</div>
</body>
</html>
