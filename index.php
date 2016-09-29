<!--==================================================
                    MAIN PAGE                                                                                 
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

    <div id="main-page" class="">
      <div class="jumbotron">
        <div class="container">
          <h1>
            Welcome to the Nordic Digital Humanities Lab!
          </h1>
          <p>
            BYU's Nordic Digital Humanities Lab is focused on topic modelling corpora of different Nordic and Scandinavian authors. Below are a list of parameters that will help you find the topics you are looking for.
          </p>
        </div>
      </div>
      <p>
        <div class="panel panel-default" id="main-panel">
          <div class="panel-heading">
            Parameters
          </div>
          <div class="panel-body">
            <p id="instructions">
              To find topics: select a corpus, which parts of speech should be included, how the corpus should be broken down, and the number of desired topics. After you're finished selecting the parameters, just click GO.
            </p>
            <div class="col-xs-6 col-md-4">
            </div>
            <div class="col-xs-6 col-md-4">
              <form id="parameter-form" role="form" method="post" action="combined_topics">
                <div class="form-group">
                  <label for="corpus">
                    <u>
                      Corpus
                    </u>
                  </label>
                  <select id="corpus" name="corpus" class="form-control">
                    <option value="Lagerlöf" selected="selcted">
                      Selma Lagerlöf
                    </option>
                  </select>
                  <label for="part-of-speech">
                    <u>
                      Part of Speech
                    </u>
                  </label>
                  <select id="part-of-speech" name="part-of-speech" class="form-control">
                    <option value="N" selected="selcted">
                      Nouns Only
                    </option>
                    <option value="NA">
                      Nouns and Adjectives
                    </option>
                  </select>
                  <label for="chunk-size">
                    <u>
                      Chunk Size
                    </u>
                  </label>
                  <select id="chunk-size" name="chunk-size" class="form-control">
                    <option value="1000" selected="selcted">
                      1000 Word Chunks
                    </option>
                    <option value="1500">
                      1500 Word Chunks
                    </option>
                    <option value="2000">
                      2000 Word Chunks
                    </option>
                  </select>
                  <label for="topic-number">
                    <u>
                      Number of Topics
                    </u>
                  </label>
                  <select id="topic-number" name="topic-number" class="form-control">
                    <option value="25" selected="selcted">
                      25 Topics
                    </option>
                    <option value="40">
                      40 Topics
                    </option>
                    <option value="55">
                      55 Topics
                    </option>
                    <option value="70">
                      70 Topics
                    </option>
                  </select>
                  <button type="submit" class="btn btn-default" id="go-button">
                    GO
                  </button>
                </div>
              </form>
            </div>
            <div class="col-xs-6 col-md-4">
            </div>
          </div>
        </div>
      </p>
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
  </body>
</html>