<!--==================================================
                    ABOUT PAGE                                                                                 
===================================================-->

<?php
  session_start();

  require 'database.php';

  if( isset($_SESSION['user_id']) )
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
    <h1 class="text-center" id="coming-soon">
      COMING SOON
    </h1>

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