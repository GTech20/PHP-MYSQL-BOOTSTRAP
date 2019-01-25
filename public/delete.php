<?php

/**
 * Delete a user
 */

require "../config.php";
require "../common.php";

$success = null;

if (isset($_POST["submit"])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try {
    $connection = new PDO($dsn, $username, $password, $options);
  
    $id = $_POST["submit"];

    $sql = "DELETE FROM users WHERE id = :id";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();

    $success = "<span class='badge badge-success'>User successfully deleted</span>";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM users";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Delete users</h2>

<?php if ($success) echo $success; ?>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Age</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) : ?>
                <tr>
                    <td>
                        <?php echo escape($row["id"]); ?>
                    </td>
                    <td>
                        <?php echo escape($row["firstname"]); ?>
                    </td>
                    <td>
                        <?php echo escape($row["lastname"]); ?>
                    </td>
                    <td>
                        <?php echo escape($row["email"]); ?>
                    </td>
                    <td>
                        <?php echo escape($row["age"]); ?>
                    </td>
                    <td>
                        <?php echo escape($row["location"]); ?>
                    </td>
                    <td>
                        <?php echo escape($row["date"]); ?>
                    </td>
                    <td><button type="submit" name="submit" class="btn btn-danger" value="<?php echo escape($row["id"]); ?>">Delete</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</form>

<div class="form-group">
    <a class="btn btn-outline-info" href="index.php">Back to home</a>
</div>


<?php require "templates/footer.php"; ?>