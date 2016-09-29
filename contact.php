<!--==================================================
                   CONTACT PAGE                                                                                 
===================================================-->

<?php
  session_start();

  require 'database.php';

  if(isset($_SESSION['user_id']) )
  {
    $records = $connection_I->prepare('SELECT id,username,password FROM Admin_Login WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = NULL;

    if( count($results) > 0)
    {
      $user = $results;
    }
  }
  
  $error = false;
  
  if(isset($_POST['submit']))
  {
    if(empty($_POST['name']) === false && empty($_POST['email']) === false && empty($_POST['message']) === false)
    {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $message = $_POST['message'];
    
      if(ctype_alpha($name) === false)
      {
        $empty_name = true;
        $error = true;
      }
    
      else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false)
      {
        $empty_email = true;
        $error = true;
      }
    
      $error = false;
    }
  
    if(empty($_POST['name']) === true)
    {
      $empty_name = true;
      $error = true;
    }
  
    if(empty($_POST['email']) === true)
    {
      $empty_email = true;
      $error = true;
    }
  
    if(empty($_POST['message']) === true)
    {
      $empty_message = true;
      $error = true;
    }
   
    if($error === false)
    {
      mail('bradymh23@gmail.com', 'Contact Form', $message, 'From: ' . $email);
      header('Location: contact?sent');
      exit();
    }
  }
  
  else
  {
    $empty_name = false;
    $empty_email = false;
    $empty_message = false;
    $error = false;
  }
?>

<!--==================================================-->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Nordic, Digital Humanities, Scandinavia, Corpus Linguistics, Christopher Oscarson, Brady Hammond, Merrill Asp, Selma Lagerlöf">
    <meta name="description" content="Selma Lagerlöf corpus linguistics project for the BYU Nordic Digital Humanities Lab">
    <meta name="author" content="Christopher Oscarson, Brady Hammond, Merrill Asp">
    <title>
      Nordic Digital Humanitites Lab
    </title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">    
    <link href="default.css" rel="stylesheet">
  </head>
  
<!--==================================================-->
  
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">
              Toggle navigation
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
          </button>
          <a class="navbar-brand" href="home">
            Nordic Digital Humanities Lab
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <?php if (!empty($user)): ?>
            <h3 id="admin" class="nav navbar-nav navbar-middle">
              Administrator
            </h3>
          <?php endif; ?>
          <ul class="nav navbar-nav navbar-right">
            <li class="" id="home">
              <a href="home">
                Home
              </a>
            </li>
            <li class="" id="about">
              <a href="about">
                About
              </a>
            </li>
            <li class="" id="contact">
              <a href="contact">
                Contact
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

<!--==================================================-->

    <div class="container anti-nav-margin">
      <div class="col-xs-6 col-md-4">
      </div>
      <div class="col-xs-6 col-md-4 white-text">
        <div class="panel panel-default">
          <div class="panel-heading">
            Contact Form
          </div>
          <div class="panel-body">
            <?php
              if (isset($_GET['sent']) === true)
              {
                echo "<div id='contact-message'><p class='white-text small-padding'>Thank you for contacting us. We will be sure to take your comments into consideration.</p></div>";
              }
              
              else
              {
            ?>
            <form role="form" method="post" action="contact">
	      <div class="form-group">
	        <?php
	          if($empty_name == true)
	          {
	            echo "<p class='red-text'>*You must enter a valid name before continuing</p>";
	          }
	        ?>
	        <label for="name">
	          Name:
	        </label>
 	        <input type="text" class="form-control" id="name" name="name" placeholder="Name"
 	          <?php 
 	            if (isset($_POST['name']) == true)
 	            {
 	              echo "value='", strip_tags($_POST['name']), "'";
 	            }
 	          ?>
 	        >
	      </div>
	      <div class="form-group">
	        <?php
	          if($empty_email == true)
	          {
	            echo "<p class='red-text'>*You must enter a valid email before continuing</p>";
	          }
	        ?>
	        <label for="email">
	          Email:
	        </label>
	        <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com"
	        <?php 
 	            if (isset($_POST['email']) == true)
 	            {
 	              echo "value='", strip_tags($_POST['email']), "'";
 	            }
 	          ?>
 	        >
	      </div>
	      <div class="form-group">
	        <?php
	          if($empty_message == true)
	          {
	            echo "<p class='red-text'>*You must enter a message before continuing</p>";
	          }
	        ?>
	        <label for="message">
	          Message:
	        </label>
	          <textarea class="form-control text-left" rows="4" name="message"><?php if (isset($_POST['message']) == true){
echo strip_tags($_POST['message']);} ?></textarea>
	      </div>
	      <div class="form-group">
	        <input id="submit" name="submit" type="submit" value="Send" class="btn btn-default">
	      </div>
            </form>
            <div id="contact-message">
              <p class="white-text small-padding">
                We're always looking to improve the Nordic Digital Humanities lab. Please leave us your comments and/or suggestions by filling out the form above.
              </p>
            </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-4">
      </div>
    </div>
      
<!--==================================================-->

    <div class="container">
      <hr>
        <footer>
          <p>
            <a href="http://home.byu.edu/home/" class="white-text">
              Brigham Young University.
            </a> 
            © All Rights Reserved. |
            <?php if (!empty($user)): ?>
              <a href="logout.php" class="white-text">
                Administrator Logout
              </a>
            <?php else: ?>
              <a href="login" class="white-text">
                Administrator Login
              </a>
            <?php endif; ?>
          </p>
        </footer>
      </div> 
    </div>

<!--==================================================-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
    </script>
    <script>
      window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous">
    </script>
    <script src="../../assets/js/ie10-viewport-bug-workaround.js">
    </script>
  </body>
</html>

<!--==================================================-->