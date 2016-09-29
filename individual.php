<!--==================================================
                  INDIVIDUAL PAGE                                                                                 
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
  
  function right($str, $length) 
  {
     return substr($str, -$length);
  }
  
  $main_topic_number = $_REQUEST['topic_number'];
  $global_id = $_REQUEST['global_id'];
  
  $topic_name_query = $connection_II->prepare('SELECT name FROM main WHERE global_id = :global_id');
  $topic_name_query->bindParam(":global_id", $global_id);
  $topic_name_query->execute();
  $topic_name_query_results = $topic_name_query->fetch(PDO::FETCH_ASSOC);
  
  $image_query = $connection_II->prepare('SELECT scatterplot, word_cloud, unique_word_cloud FROM images WHERE global_id= :global_id');
  $image_query->bindParam(":global_id", $global_id);
  $image_query->execute();
  $image_query_results = $image_query->fetch(PDO::FETCH_ASSOC);
  
  $word_query = $connection_II->prepare('SELECT word, rank, weight FROM top_words WHERE global_id= :global_id ORDER BY rank');
  $word_query->bindParam(":global_id", $global_id);
  $word_query->execute();
  $word_query_results = $word_query->fetchAll();
  $word_query_results_size = count($word_query_results);
  
  if($topic_name_query_results["name"] != " ")
  {
    $topic_name = ": ";
    $topic_name .= $topic_name_query_results["name"];
  }
  
  else
  {
    $topic_name = "";
  }
   
  if(substr($global_id, 0, 1) == "L")
  {
    $SP_path = "/Lagerlöf/Images/";
    $WC_path = "/Lagerlöf/Images/";
    $UWC_path = "/Lagerlöf/Images/";
    $header = "Lagerlöf: ";
    $corpus = "Lagerlöf";
  }
  
  else
  {
    $SP_path;
    $WC_path;
    $UWC_path;
  }
  
  if(substr($global_id, 2, 4) == "1000")
  {
    $header .= "1000 Word Chunks";
    $chunk_size = "1000";
  }
  
  else if(substr($global_id, 2, 4) == "1500")
  {
    $header .= "1500 Word Chunks";
    $chunk_size = "1500";
  }
  
  else
  {
    $header .= "2000 Word Chunks";
    $chunk_size = "2000";
  }
  
  if(substr($global_id, 6, 2) != "NA")
  {
    $header .= "(Nouns Only), ";
    $part_of_speech = "N";
  }
  
  else
  {
    $header .= "(Nouns and Adjectives), ";
    $part_of_speech = "NA";
  }
  
  if(right($global_id, 2) == "25")
  {
    $header .= "25 Topics";
    $topic_number = "25";
  }
  
  else if(right($global_id, 2) == "40")
  {
    $header .= "40 Topics";
    $topic_number = "40";
  }
  
  else if(right($global_id, 2) == "55")
  {
    $header .= "55 Topics";
    $topic_number = "55";
  }
  
  else
  {
    $header .= "70 Topics";
    $topic_number = "70";
  }
  
  $SP_path .= $image_query_results["scatterplot"];
  $WC_path .= $image_query_results["word_cloud"];
  $UWC_path .= $image_query_results["unique_word_cloud"];
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

    <div id="individual-topic-page"class="container-fluid">
      <div class="page-header">
      	<h4 id="parameters-tag">
      	  <a href="combined_topics?corpus=<?php echo $corpus ?>&part-of-speech=<?php echo $part_of_speech ?>&chunk-size=<?php echo $chunk_size ?>&topic-number=<?php echo $topic_number ?>" class="white-text pointer">
      	    <?php echo $header; ?>
      	  </a>
      	</h4>
      	<?php
      	  if(empty($user))
      	  {
      	    $header = "Topic ";
      	    $header .= $main_topic_number;
      	    $header .= $topic_name;
            echo "<h1>";
            echo "$header";
            echo "</h1>";
          }
          
          else
          {
            $header = "Topic ";
      	    $header .= $main_topic_number;
      	    $header .= $topic_name;
            echo "<h1 id='transform' onClick='transform_tag()' class='pointer'>";
            echo "$header";
            echo "</h1>";
          }
        ?>
      </div>
      <div class="col-xs-6 col-md-4">
        <image class="img-responsive img-rounded extra-small-margin" id="scatter-plot" src="<?=$SP_path?>">
        <image class="img-responsive img-rounded extra-small-margin" id="word-cloud" src="<?=$WC_path?>">
        <image class="img-responsive img-rounded" id="unique-word-cloud" src="<?=$UWC_path?>">
      </div>
      <div class="col-xs-6 col-md-4">
        <table class="table table-inverse" id="topic-table">
          <thead class="table-inverse">
            <tr>
              <th class="text-center">
                Topic Words
              </th>
              <th class="text-center">
                Word Rank
              </th>
              <th class="text-center">
                Word Weight
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
              for($i = 0; $i <= $word_query_results_size - 1; $i++)
              {
                echo "<tr>";

                for($column = 0; $column < 3; $column++) 
                {
		  
		  if($column == 0)
                  {
                    echo "<td><a class='pointer white-text' href='words?word=".$word_query_results[$i][$column]."&global_id=".$global_id."'>".$word_query_results[$i][$column]."</a></td>";
		  }
                    
                  else if($column == 1)
                  {
                    echo "<td>".$word_query_results[$i][$column]."</td>";
                  }
    
                  else
                  {
                    $weight = round($word_query_results[$i][$column], 5, PHP_ROUND_HALF_UP);
                    echo "<td>".$weight."</td>";
                  }
                }
  
                echo"</tr>";
              }  
            ?>
          </tbody>
        </table>
        <div class="return-button-center">
          <a href="combined_topics?corpus=<?php echo $corpus ?>&part-of-speech=<?php echo $part_of_speech ?>&chunk-size=<?php echo $chunk_size ?>&topic-number=<?php echo $topic_number ?>" class="btn btn-lg btn-default" id="return-home-button">
            Return to Combined Topics
          </a>
        </div>
      </div>
      <div class="col-xs-6 col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            Relevant Passages
          </div>
          <div class="panel-body" id="passage-select-panel">
            <table class="table table-inverse">
              <thead class="table-inverse">
              </thead>
              <tbody>
                <?php
              	  $chunks_query = $connection_II->prepare('SELECT chunk_name,weight FROM top_chunks WHERE global_id = :global_id ORDER BY weight DESC');
                  $chunks_query->bindParam(":global_id", $global_id);
                  $chunks_query->execute();
                  $chunks_query_results = $chunks_query->fetchAll();
                  $chunks_query_results_size = count($chunks_query_results);
  
                  for($i = 0; $i <= $chunks_query_results_size - 1; $i++)
                  {
                    $weight = $chunks_query_results[$i][1];
                    $chunks = $chunks_query_results[$i][0];
    
                    $chunks = str_replace("./", "", $chunks);
                    $chunks = str_replace("_", " ", $chunks);
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
                    $chunks = substr($chunks, 2);
    
                    echo "<tr>";
                    echo "<td><img src='graph.php?i=$i&global_id=$global_id&weight=$weight'/></td>";
                    echo "<td class='text-center'><a id=".str_replace('./', '', $chunks_query_results[$i][0])." onclick='link_click(this.id)' class='white-text pointer'>".$chunks."</a></td>";
                    echo "</tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel panel-default" id="passage-view-panel">
          <div class="panel-heading" id="passage">
            Passage viewer
          </div>
          <div class="panel-body" id="passage-view-panel-body" type="text">
          </div>
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
      function link_click(object)
      {
        document.getElementById("passage-view-panel-body").innerHTML = "";
        var path;
        var parameters = document.getElementById("parameters-tag").innerHTML;

        if(parameters.indexOf("1000"))
        {
          var path = "Lagerlöf/Passages/1000/";
        }
          
        else if(parameters.indexOf("1500"))
        {
          var path = "Lagerlöf/Passages/1500/";
        }
  
        else
        {
          var path = "Lagerlöf/Passages/2000/";
        }
	
        var passage = path.concat(object);
        $("#passage-view-panel-body").load(passage);
        
        document.getElementById("passage").innerHTML = document.getElementById(object).innerHTML;
      };
      
      function transform_tag()
      {
        var global_id = "<?php echo $global_id ?>";
	      var topic_number = "<?php echo $topic_number ?>";
        
      	var parent = $("#transform").parent();
      	var text = document.getElementById("transform").innerHTML;

      	if(text.includes(":"))
      	{
      	  var number = text.substr(0, text.indexOf(":"));
      	  var name = text.substr(text.indexOf(":") + 2, text.length -1);
      	  $("#transform").remove();
          parent.append("<form method='post' action='individual_name.php?global_id="+global_id+"&topic_number="+topic_number+"' id='transform-back'><label for='submit_name' onclick='transform_tag_back()' class='pointer'>"+number+"</label><input name='submit_name' type='text' class='input-standard' placeholder='"+name+"'><button type='submit' class='btn btn-default'>Submit</button></form>");
        }
        
        else
        {
          $("#transform").remove();
          parent.append("<form method='post' action='individual_name.php?global_id="+global_id+"&topic_number="+topic_number+"' id='transform-back'><label for='submit_name' onclick='transform_tag_back()' class='pointer'>"+text+"</label><input name='submit_name' type='text' class='input-standard' placeholder='Enter Name Here'><button type='submit' class='btn btn-default'>Submit</button></form>");
        }
      }
      
      function transform_tag_back()
      {
        var page_header = "<?php echo $header ?>";
        var parent = $("#transform-back").parent();
        
        $("#transform-back").remove();
        parent.append("<h1 id='transform' onClick='transform_tag()' class='pointer'>"+page_header+"</h1>");
      }
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