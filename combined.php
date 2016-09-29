<!--==================================================
                  COMBINED PAGE                                                                                 
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

  $corpus = $_REQUEST['corpus'];
  $part_of_speech = $_REQUEST['part-of-speech'];
  $chunk_size = $_REQUEST['chunk-size'];
  $topic_number = $_REQUEST['topic-number'];

  $CT_name .= $corpus;
  $CT_name .= ": ";
  $CT_name .= $chunk_size;
  $CT_name .= " Word Chunks(";
  
  if($part_of_speech == "N")
  {
    $CT_name .= "Nouns Only), ";
  }
  
  else
  {
    $CT_name .= "Nouns and Adjectives), ";
  }
  
  $CT_name .= $topic_number;
  $CT_name .= " Topics";
  
  $topic_range;
  
  if($topic_number == "25")
  {
    $topic_range = 25;
  }
  
  else if($topic_number == "40")
  {
    $topic_range = 40;
  }
  
  else if($topic_number == "55")
  {
    $topic_range = 55;
  }
  
  else
  {
    $topic_range = 70;
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

    <div id="combined-topic-page" class="container-fluid" >
      <div class="page-header">
        <h1 id="parameters-tag">
          <?php echo $CT_name; ?>
        </h1>
      </div>
        <table class="table table-inverse" id="topic-table">
          <thead class="table-inverse">
            <tr>
              <th class="text-center">
                Word Clouds
              </th>
              <th class="text-center">
                Scatter Plots
              </th>
              <th class="text-center">
                Topic Number
              </th>
              <th class="text-center">
                Topic Name
              </th>
              <th class="text-center">
                Top Words
              </th>
              <th class="text-center">
                Relevant Passages
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
              for($i = 1; $i <= $topic_range; $i++)
              {
                $global_id = "L_";
                $global_id .= $chunk_size;
                $global_id .= $part_of_speech;
                $global_id .= "_";
                $global_id .= $i;
                $global_id .= "/";
                $global_id .= $topic_number;
                
                $table_id = "T_";
                $table_id .= $global_id;
                
		$chunks_query = $connection_II->prepare('SELECT chunk_name FROM top_chunks WHERE global_id = :global_id');
		$chunks_query->bindParam(":global_id", $global_id);
		$chunks_query->execute();
		$chunks_query_results = $chunks_query->fetchAll();
		$chunks_query_results_size = count($chunks_query_results);

		$name_query = $connection_II->prepare('SELECT name FROM main WHERE global_id = :global_id');
		$name_query->bindParam(":global_id", $global_id);
		$name_query->execute();
		$name_query_results = $name_query->fetch();

		$word_query = $connection_II->prepare('SELECT word FROM top_words WHERE global_id = :global_id ORDER BY rank');
		$word_query->bindParam(":global_id", $global_id);
		$word_query->execute();
		$word_query_results = $word_query->fetchAll();
		$word_query_results_size = count($word_query_results);

		$image_query = $connection_II->prepare('SELECT scatterplot, word_cloud FROM images WHERE global_id = :global_id');
		$image_query->bindParam(":global_id", $global_id);
		$image_query->execute();
		$image_query_results = $image_query->fetch(PDO::FETCH_ASSOC);

		$SP_name = "/";
		$SP_name .= $corpus;
                $SP_name .= "/Images/";
                $SP_name .= $image_query_results["scatterplot"];

                $WC_name = "/";
                $WC_name .= $corpus;
                $WC_name .= "/Images/";
                $WC_name .= $image_query_results["word_cloud"];
				
                echo "<tr>";
                echo "<td><img src=".$WC_name." alt='Word Cloud' class='thumbnail' height='150px' width='auto'/></td>";
                echo "<td><img src=".$SP_name." alt='Scatter Plot' class='thumbnail' onmoseover=\"this.height='400px'\” onmousout=\"this.height='100px'\" height='150px' width='auto'/></td>";
                echo "<td id='$table_id'><a href='individual_topic?global_id=$global_id&topic_number=$i' class='white-text pointer' id='$global_id'>Topic $i</a></td>";

		if(!empty($user))
                {
                  echo "<td id='$global_id' onclick='transform_tag(this)' class='pointer'>";
                  
                  if($name_query_results[0] == " ")
                  {
                    echo "TBD</td>";
                  }
                  
                  else
                  {
                    echo "$name_query_results[0]</td>";
                  }
                }
                
                else
                {
                  echo "<td>";
                  
                  if($name_query_results[0] == " ")
                  {
                    echo "TBD</td>";
                  }
                  
                  else
                  {
                    echo "$name_query_results[0]</td>";
                  }
                }
                
                echo "<td class='row-data'><div class='row-height'>";

                $words;
                
                foreach($word_query_results as $key => $value)
                {
                  if($key < $word_query_results_size - 1)
                  {
                    $words .= $value[0];
                    $words .= ", ";
                  }
  
                  else
                  {
                    $words .= $value[0];
                  }
                  
                  echo "<a class='pointer white-text' id=".$value[0]." href='words?word=".$value[0]."&global_id=".$global_id."'>".$words."</a>";
                  $words = "";
                } 
		
                echo "</div></td>";
                echo "<td class='row-data'><div class='row-height'>";
		
		$chunks;
		            
                foreach($chunks_query_results as $key => $value)
                {
                  if($key < $chunks_query_results_size - 11)
                  {
                    $chunks .= $value[0];
                    $chunks .= ", ";
                    $chunks = str_replace("_", " ", $chunks);
                    $chunks = str_replace("./", "", $chunks);
                    $chunks = str_replace(".txt", "", $chunks);
                    $chunks = str_replace("LagerlofS", "", $chunks);
                    $chunks = str_replace("of", " of ", $chunks);
                    $chunks = preg_replace("/\d{4}/", "", $chunks);
                    $chunks = preg_replace("/(?<!\ )[A-Z]/", " $0", $chunks);
                    $chunks = str_replace("Gosta Berlings Saga", "Gösta Berlings Saga", $chunks);
                    $chunks = str_replace("Osynliga Lankar", "Osynliga Länkar", $chunks);
                    $chunks = str_replace("Korkarlen", "Körkarlen", $chunks);
                    $chunks = str_replace("Troll Och Mann", "Troll Och Männ", $chunks);
                    $chunks = str_replace("Marbacka", "Mårbacka", $chunks);
                    $chunks = str_replace("Lowenskoldska R", "Löwensköldska", $chunks);
                    $chunks = str_replace("Charlotte Lowenskold", "Charlotte Löwensköld", $chunks);
                    $chunks = str_replace("Anna Svard", "Anna Svärd", $chunks);
                    $chunks = str_replace("Fran Skilda Tider", "Från Skilda Tider", $chunks);
                  }
  
                  else if($key == 10)
                  {
                    $chunks .= $value[0];
                    $chunks = str_replace("_", " ", $chunks);
                    $chunks = str_replace("./", "", $chunks);
                    $chunks = str_replace(".txt", "", $chunks);
                    $chunks = str_replace("LagerlofS", "", $chunks);
                    $chunks = str_replace("of", " of ", $chunks);
                    $chunks = preg_replace("/\d{4}/", "", $chunks);
                    $chunks = preg_replace("/(?<!\ )[A-Z]/", " $0", $chunks);
                    $chunks = str_replace("Gosta Berlings Saga", "Gösta Berlings Saga", $chunks);
                    $chunks = str_replace("Osynliga Lankar", "Osynliga Länkar", $chunks);
                    $chunks = str_replace("Korkarlen", "Körkarlen", $chunks);
                    $chunks = str_replace("Troll Och Mann", "Troll Och Männ", $chunks);
                    $chunks = str_replace("Marbacka", "Mårbacka", $chunks);
                    $chunks = str_replace("Lowenskoldska R", "Löwensköldska", $chunks);
                    $chunks = str_replace("Charlotte Lowenskold", "Charlotte Löwensköld", $chunks);
                    $chunks = str_replace("Anna Svard", "Anna Svärd", $chunks);
                    $chunks = str_replace("Fran Skilda Tider", "Från Skilda Tider", $chunks);
                  }
  
                  echo "<a class='passage-link' id=".str_replace('./', '', $value[0])."  onclick='link_click(this.id)'>".$chunks."</a>";
                  $chunks = "";
                }

                echo "</div></td>";
                echo "</tr>";
                
                $chunks = "";
                $words = "";
                $WC_name = "";
                $SP_name = "";
              }
            ?>
          </tbody>
        </table>
        <div class="return-button-center">
          <a href="home" class="btn btn-lg btn-default" id="return-home-button">
            Return Home
          </a>
        </div>
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

    <script type="text/javascript">
      function transform_tag(object)
      {
        var global_id = object.id;
        var corpus = "<?php echo $corpus ?>";
        var part_of_speech = "<?php echo $part_of_speech ?>";
        var chunk_size = "<?php echo $chunk_size ?>";
	      var topic_number = "<?php echo $topic_number ?>";
	      var table_id = "T_" + global_id;
	
        var node = document.getElementById(table_id);
	
      	var parent = object.parentNode;
      	var text = object.innerHTML;

      	if(text == "TBD")
      	{
          object.remove();
          $("<td style='vertical-align: middle;'><form method='post' action='name.php?global_id="+global_id+"&corpus="+corpus+"&part_of_speech="+part_of_speech+"&chunk_size="+chunk_size+"&topic_number="+topic_number+"' id="+global_id+"><input name='submit_name' type='text' class='input-standard' placeholder='Enter Name Here'><br><button type='submit' class='btn btn-default'>Submit</button></form></td>").insertAfter(node);
        }
        
        else
        {
          object.remove();
          $("<td style='vertical-align: middle;'><form method='post' action='name.php?global_id="+global_id+"&corpus="+corpus+"&part_of_speech="+part_of_speech+"&chunk_size="+chunk_size+"&topic_number="+topic_number+"' id="+global_id+"><input name='submit_name' type='text' class='input-standard' placeholder="+text+"><br><button type='submit' class='btn btn-default'>Submit</button></form></td>").insertAfter(node);
        }
      };
      
      function link_click(object)
      {
        var path;
        var parameters = document.getElementById("parameters-tag").innerHTML;
        
        if(parameters.indexOf("1000"))
        {
          var path = "passage_window.php?file=Lagerlöf/Passages/1000/";
        }
          
        else if(parameters.indexOf("1500"))
        {
      	  var path = "passage_window.php?file=Lagerlöf/Passages/1500/";
      	}
	  
        else
        {
          var path = "passage_window.php?file=Lagerlöf/Passages/2000/";
        }
	
        var passage = path.concat(object);
        passage = passage.concat("&name=");
        var name = document.getElementById(object).innerHTML;
        passage = passage.concat(name);
	      var passage_window = window.open(decodeURIComponent(passage), "_blank", "scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
      };
    </script>
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
