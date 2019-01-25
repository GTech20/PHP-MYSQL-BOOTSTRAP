<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try  {
    $connection = new PDO($dsn, $username, $password, $options);
    
    $new_user = array(
      "firstname" => $_POST['firstname'],
      "lastname"  => $_POST['lastname'],
      "email"     => $_POST['email'],
      "age"       => $_POST['age'],
      "location"  => $_POST['location']
    );

    $sql = sprintf(
      "INSERT INTO %s (%s) values (%s)",
      "users",
      implode(", ", array_keys($new_user)),
      ":" . implode(", :", array_keys($new_user))
    );
    
    $statement = $connection->prepare($sql);
    $statement->execute($new_user);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}
?>
<?php require "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $statement) : ?>
<span class='badge badge-success'> <?php echo escape($_POST['firstname']); ?> successfully added.</span>
<?php endif; ?>

<h2>Add a user</h2>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <div class="form-group">
        <label for="firstname">Firstname</label>
        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter Firstname">
    </div>
    <div class="form-group">
        <label for="lastname">Lastname</label>
        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Lastname">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
    </div>
    <div class="form-group">
        <label for="age">Age</label>
        <textarea class="form-control" name="age" id="age" placeholder="Enter Address"></textarea>
    </div>
    <div class="form-group">
        <label for="location">Location</label>
        <textarea class="form-control" name="location" id="location" placeholder="Enter Location"></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </div>
</form>
<div class="form-group">
    <a class="btn btn-outline-info" href="index.php">Back to home</a>
</div>


<?php require "templates/footer.php"; ?>