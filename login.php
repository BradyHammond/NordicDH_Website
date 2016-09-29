<!--==================================================
                    LOGIN PAGE                                                                                 
===================================================-->

<?php
  require "database.php";

  session_start();

  if( isset($_SESSION['user_id']) )
  {
    header("Location:home");
  }

  if(!empty($_POST['username']) && !empty($_POST['password'])):
    $records = $connection_I->prepare('SELECT id,username,password FROM Admin_Login WHERE username = :username');
    $records->bindParam(":username", $_POST["username"]);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';
	
    if(count($results) > 0 && MD5($_POST["password"]) == $results["password"])
    {
      $_SESSION['user_id'] = $results['id'];
      header("Location:home");
    }
	
    else
    {
      $message =  "Sorry, that login information was incorrect. Please try again.";
    }

  endif;
?>

<!--==================================================-->

<!DOCTYPE html>
<html>
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
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
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

    <div class="container login">
      <h1 class="login">
	Administrator Login
      </h1>
      <?php if(!empty($message)): ?>
	<p class="white-text">
          <?=$message ?>
	</p>
      <?php endif; ?>
	<div>
	  <form action="login.php" method="POST">
	    <input type="text" placeholder="Enter Username" name="username">
	    <br>
	    <br>
	    <input type="password" placeholder="Enter Password" name="password">
	    <br>
	    <br>
	    <input type="submit">
          </form>
	</div>
      </div>

<!--==================================================-->

      <div class="container">
        <hr>
	<footer>
	  <p>
	    <a href="http://home.byu.edu/home/" class="white-text pointer">
	      Brigham Young University.
	    </a> 
	    © All Rights Reserved.
	  </p>
	</footer>
      </div> 

<!--==================================================-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">
    </script>
    <script>
      window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous">
    </script>
  </body>
</html>

<!--==================================================-->