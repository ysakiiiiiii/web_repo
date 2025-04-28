<?php
// Database Connection
$servername = "sql110.infinityfree.com";
$username= "if0_38834503";
$password = "R7e80cWwsH";
$database= "if0_38834503_dbContacts";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE TABLE IF NOT EXISTS tblSMS (
   sms_ID INT AUTO_INCREMENT PRIMARY KEY,
   studno VARCHAR(20) NOT NULL UNIQUE,
   name VARCHAR(100) NOT NULL,
   cpno VARCHAR(15) NOT NULL UNIQUE
)");

$conn->query("INSERT IGNORE INTO tblSMS (studno, name, cpno)
   VALUES 
   ('23-140001', 'Jose Rizal', '639123456789'),
   ('23-140002', 'Andres Bonifacio', '639987654321')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Records</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<h3>Menu Links:</h3>
<div id="menu">
  <a href="?action=add">Add Record</a> |
  <a href="?action=update">Update Record</a> |
  <a href="?action=delete">Delete Record</a>
</div>

<?php
$action = isset($_GET['action']) ? $_GET['action'] : 'add';

if ($action === 'add') {
?>
<!-- Add Record Form -->
<form method="post">
  <h3>Add Record</h3>
  <label>Student Number: <input type="text" name="studentNumber" required></label><br>
  <label>Name: <br><input type="text" name="name" style="width: 300px;" required></label><br>
  <label>CP No.: <input type="text" name="cpNumber" value="63" maxlength="13" required> (ex. 639201234567)</label><br>
  <button type="submit" name="save">Save</button>
</form>

<?php
  if (isset($_POST['save'])) {
    $stdnum = $_POST['studentNumber'];
    $name = $_POST['name'];
    $cpNumber = $_POST['cpNumber'];

    $stmt = $conn->prepare("INSERT INTO tblSMS (studno, name, cpno) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $stdnum, $name, $cpNumber);

    if ($stmt->execute()) {
      echo "<script>alert('Successfully Added!'); window.location.href='?action=add';</script>";
    } else {
      echo "<script>alert('Failed to add! Maybe duplicate?');</script>";
    }
    $stmt->close();
  }
} elseif ($action === 'update') {
?>

<!-- Update Record Form -->
<form method="post">
  <h3>Update Record</h3>
  <div class="inline-input">
     <label>Student Number: <input type="text" name="studentNumber" value="<?php echo isset($_POST['studentNumber']) ? $_POST['studentNumber'] : ''; ?>" required></label>
     <button type="submit" name="searchUpdate">Search</button>
  </div>

<?php
  if (isset($_POST['searchUpdate'])) {
    $stdnum = $_POST['studentNumber'];
    $stmt = $conn->prepare("SELECT name, cpno FROM tblSMS WHERE studno = ?");
    $stmt->bind_param("s", $stdnum);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($name, $cpno);
      $stmt->fetch();
    } else {
      echo "<script>alert('Student not found!');</script>";
    }
    $stmt->close();
  }
?>
  <label>Name: <br><input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" style="width: 300px;" placeholder="Enter name"></label><br>
  <label>CP No.: <input type="text" name="cpNumber" value="<?php echo isset($cpno) ? $cpno : '63'; ?>" maxlength="13" required> (ex. 639201234567)</label><br>
  <button type="submit" name="update">Update</button>
</form>

<?php
  if (isset($_POST['update'])) {
    $stdnum = $_POST['studentNumber'];
    $name = $_POST['name'];
    $cpNumber = $_POST['cpNumber'];

    $stmt = $conn->prepare("UPDATE tblSMS SET name = ?, cpno = ? WHERE studno = ?");
    $stmt->bind_param("sss", $name, $cpNumber, $stdnum);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      echo "<script>alert('Record Updated Successfully!'); window.location.href='?action=update';</script>";
    } else {
      echo "<script>alert('Update Failed. Student not found or No Updates are Committed');</script>";
    }
    $stmt->close();
  }
} elseif ($action === 'delete') {
?>

<!-- Delete Record Form -->
<form method="post">
  <h3>Delete Record</h3>
  <div class="inline-input">
     <label>Student Number: <br><input type="text" name="studentNumber" value="<?php echo isset($_POST['studentNumber']) ? $_POST['studentNumber'] : ''; ?>" required></label>
     <button type="submit" name="searchDelete">Search</button><br>
  </div>


<?php
  if (isset($_POST['searchDelete'])) {
    $stdnum = $_POST['studentNumber'];
    $stmt = $conn->prepare("SELECT name, cpno FROM tblSMS WHERE studno = ?");
    $stmt->bind_param("s", $stdnum);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($name, $cpno);
      $stmt->fetch();
    } else {
      echo "<script>alert('Student not found!');</script>";
    }
    $stmt->close();
  }
?>
  <label>Name: <br><input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" style="width: 300px;" disabled></label><br>
  <label>CP No.: <input type="text" name="cpNumber" value="<?php echo isset($cpno) ? $cpno : '63'; ?>" maxlength="13" disabled></label><br>
  <button type="submit" name="delete">Delete</button>
</form>

<?php
  if (isset($_POST['delete'])) {
    $stdnum = $_POST['studentNumber'];

    $stmt = $conn->prepare("DELETE FROM tblSMS WHERE studno = ?");
    $stmt->bind_param("s", $stdnum);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      echo "<script>alert('Record Deleted Successfully!'); window.location.href='?action=delete';</script>";
    } else {
      echo "<script>alert('Delete Failed. Student not found.');</script>";
    }
    $stmt->close();
  }
}
?>

</body>
</html>
