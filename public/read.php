<?php

/**
 * Function to query information based on 
 * a parameter: in this case, location.
 *
 */

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try  {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * 
            FROM users
            WHERE location = :location";

    $location = $_POST['location'];
    $statement = $connection->prepare($sql);
    $statement->bindParam(':location', $location, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php require "templates/header.php"; ?>

<?php  
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
<h2>Results</h2>
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
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php } else { ?>
<span class='badge badge-danger'>No results found for
    <?php echo escape($_POST['location']); ?>.</span>
<?php } 
} ?>

<h2>Find user based on location</h2>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <div class="form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control" name="location" id="location" placeholder="Enter Location">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="submit">View Results</button>
    </div>
</form>

<div class="form-group">
    <a class="btn btn-outline-info" href="index.php">Back to home</a>
</div>


<?php require "templates/footer.php"; ?>