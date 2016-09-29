<!--==================================================
                    WORDS PAGE                                                                                 
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
  
  $word = $_REQUEST['word'];
  $global_id = $_REQUEST['global_id'];
  
  if(substr($global_id, 0, 1) == "L")
  {
    $header = "Lagerlöf: ";
    $corpus = "Lagerlöf";
  }
  
  else
  {
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
  
  $check_id_I = "/^";
  $check_id_I .= substr($global_id, 0, 8);
  $check_id_I .= "/";
  
  $check_id_II = "/";
  $check_id_II .= $topic_number;
  $check_id_II .= "$/";
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

    <div id="words-page" class="container-fluid">
      <div class="words-header">
      	<div class="col-xs-12 col-md-8 align-left">
      	  <h5 id="parameters-tag">
      	    <a href="combined_topics?corpus=<?php echo $corpus; ?>&part-of-speech=<?php echo $part_of_speech; ?>&chunk-size=<?php echo $chunk_size; ?>&topic-number=<?php echo $topic_number; ?>" class="white-text pointer">
      	      <?php echo $header; ?>
      	    </a>
      	  </h5>
      	  <h1>
      	    Topics containing <?php echo $word; ?>:
      	  </h1>
      	</div>
      	<div class="col-xs-6 col-md-4 small-margin">
          <form class="form-inline" action="words_search?header=<?php echo $header; ?>" method="post">
            <div class="input-group">
    	      <div class="form-group">
                <div class="form-group">
                  <input type="text" class="form-control input-lg" placeholder="Search" name="word"/>
                  <span class="input-group-btn">
                    <input class="btn btn-info btn-lg" type="submit"/>
                  </span>
                </div>
              </div>
            </div>
          </form>
      	</div>
      </div>
      <table class="table table-inverse" id="topic-table">
        <thead class="table-inverse">
          <tr>
            <th class="text-center">
              Topic Number
            </th>
            <th class="text-center">
              Topic Name
            </th>
            <th class="text-center">
              Topic Words
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $id_query = $connection_II->prepare('SELECT word, weight FROM top_words WHERE global_id= :global_id ORDER BY rank');
            $id_query->bindParam(":global_id", $global_id);
            $id_query->execute();
            $id_query_results = $id_query->fetchAll();
            $id_query_results_size = count($id_query_results);

            $id_name_query = $connection_II->prepare('SELECT name FROM main WHERE global_id= :global_id');
            $id_name_query->bindParam(":global_id", $global_id);
            $id_name_query->execute();
            $id_name_query_results = $id_name_query->fetchAll();
            $id_name_query_results_size = count($id_name_query_results);
            
            $id_name = "";
            
            if($id_name_query_results[0][0] != " ")
            {
              $id_name = $id_name_query_results[0][0];
            }
            
            else
            {
              $id_name = "TBD";
            }
            
            $list_topic_number = "Topic ";
            $number = right($global_id, 5);
            $number = str_replace("_", "", $number);
            $number = substr($number, 0, 2);
            $number = str_replace("/", "", $number);
            $list_topic_number .= $number;
            $list_topic_number = str_replace("/", "", $list_topic_number);       
            
            echo "<tr>";
            echo "<td style='vertical-align: middle;' id='T_".$global_id."'><a class='white-text pointer' href='individual_topic?global_id=".$global_id."&topic_number=".$number."'>".$list_topic_number."</a></td>";
            if(!empty($user))
            {
              echo "<td style='vertical-align: middle;' class ='pointer' onclick='transform_tag(this)' id='".$global_id.",".$number."'>".$id_name."</td>";
            }
            
            else
            {
              echo "<td style='vertical-align: middle;' id='".$global_id.",".$number."'>".$id_name."</td>";
            }
            
            echo "<td id='query' width='90%'>";
            echo "</td>";
            echo "</tr>";
            
            $data = array();
            $word_query_part_I = $connection_II->prepare('SELECT global_id FROM top_words WHERE word= :word');
            $word_query_part_I->bindParam(":word", $word);
            $word_query_part_I->execute();
            $word_query_part_I_results = $word_query_part_I->fetchAll();
            $word_query_part_I_results_size = count($word_query_part_I_results);
            
            $i = 0;
            foreach($word_query_part_I_results as $key => $value)
            {
              if($value[0] != $global_id && preg_match($check_id_I, $value[0]) && preg_match($check_id_II, $value[0]))
              {
                $id = $value[0];
                 
                $list_topic_number = "Topic ";
                $number = right($id, 5);
                $number = str_replace("_", "", $number);
            	  $number = substr($number, 0, 2);
            	  $number = str_replace("/", "", $number);
            	  $list_topic_number .= $number;
                $list_topic_number = str_replace("/", "", $list_topic_number);
                   
                $word_query_part_II = $connection_II->prepare('SELECT word, weight, global_id FROM top_words WHERE global_id= :global_id ORDER BY rank');
            	  $word_query_part_II->bindParam(":global_id", $value[0]);
            	  $word_query_part_II->execute();
            	  $word_query_part_II_results = $word_query_part_II->fetchAll();
            	  $word_query_part_II_results_size = count($word_query_part_II_results);
            	  array_push($data, $word_query_part_II_results);
            	
                $word_query_part_III = $connection_II->prepare('SELECT name FROM main WHERE global_id= :global_id');
            	  $word_query_part_III->bindParam(":global_id", $value[0]);
            	  $word_query_part_III->execute();
            	  $word_query_part_III_results = $word_query_part_III->fetchAll();
            	  $word_query_part_III_results_size = count($word_query_part_III_results);           	
            	
            	  echo "<tr>";
                echo "<td style='vertical-align: middle;' id='T_".$word_query_part_II_results[0][2]."'><a class='white-text pointer' href='individual_topic?global_id=".$id."&topic_number=".$number."'>".$list_topic_number."</a></td>";
                
            	  $name = "";
            	
            	  if($word_query_part_III_results[0][0] != " ")
            	  {
            	    $name = $word_query_part_III_results[0][0];
            	  }
            	
            	  else
            	  {
            	    $name = "TBD";
            	  }
            	
            	  if(!empty($user))
            	  {
            	    echo "<td style='vertical-align: middle;' class='pointer' id='".$word_query_part_II_results[0][2].",".$number."' onclick='transform_tag(this)'>".$name."</td>";
            	  }
            	
            	  else
            	  {
            	    echo "<td style='vertical-align: middle;' id='".$word_query_part_II_results[0][2].",".$number."'>".$name."</td>";
            	  }
            	
		            echo "<td class='bar_graphic_".$i."' width='90%'>";
                echo "</td>";
                echo "</tr>";
                
                $i = $i + 1;
	            }
            }
          ?>
        </tbody>
      </table>
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
    <script src="https://d3js.org/d3.v4.min.js">
    </script>
    <script type="text/javascript">
      var dataset = <?php echo json_encode($id_query_results); ?>;
      var data_array = [<?php 
        echo json_encode($data);
      ?>];
      
      var width = "90%";
      var height = 175;
      var true_width = document.getElementById("query").clientWidth;
      true_width = true_width - 150;
      var scale = d3.scaleLinear()
        .range([100, 0]);
      
      var svg = d3.select('#query')
        .append('svg')
        .attr('width', width)
        .attr('height', height);
        
      svg.selectAll('rect.bars')
        .data(dataset)
        .enter()
        .append('rect')
          .attr('width', function(d,i)
          {
            return true_width/dataset.length - 5;
          })
       	  .attr('height', function(d,i)
       	  {
       	    return (d.weight*1000);
       	  })
       	  .attr('x', function(d,i)
       	  {
       	    return i*(true_width/dataset.length);
       	  })
       	  .attr('y', function(d,i)
       	  {
       	    return height - (d.weight*1000);
       	  })
       	  .attr('fill', function(d, i)
       	  {
       	    if (d.word == "<?php echo $word; ?>")
       	    {
       	      return "rgb(255, 0, 0)";
       	    }
       	    else
       	    {
       	      return "rgb(100, 255, " + (255 - (i * 5)) + ")";
       	    }
       	  })
       	  .on("mouseover", function () 
       	  {
            d3.select(this).classed("hover", true);
          })
          .on("mouseout", function () 
          {
            d3.select(this).classed("hover", false);
          })
          .on("click", function (d) 
          {
            window.location = "words?word="+ d.word + "&global_id=<?php echo $global_id; ?>";
          })
          
       svg.selectAll("text")
        .data(dataset)
        .enter()
       	.append('text')
       	  .text(function(d) 
       	  {
            return d.word;
          })
       	  .attr('x', function(d,i)
       	  {
       	    return i*(true_width/dataset.length) + 15;
       	  })
       	  .attr('y', function(d,i)
       	  {
       	    return height - (d.weight*1000);
       	  })
       	  .attr('fill', function(d, i)
       	  {
       	    if (d.word == "<?php echo $word; ?>")
       	    {
       	      return "rgb(255, 0, 0)";
       	    }
       	    else
       	    {
       	      return "rgb(100, 255, " + (255 - (i * 5)) + ")";
       	    }
       	  })
       	  .attr('transform', function(d,i)
       	  {
       	    var x = i*(true_width/dataset.length);
       	    var y = height - (d.weight*1000);
       	    return "rotate(-45 " + x +", " + y + ")";
       	  })
       	  .on("mouseover", function () 
       	  {
            d3.select(this).classed("hover", true);
          })
          .on("mouseout", function () 
          {
            d3.select(this).classed("hover", false);
          })
          .on("click", function (d) 
          {
            window.location = "words?word="+ d.word + "&global_id=<?php echo $global_id; ?>";
          });
          
        for (i = 0; i < data_array[0].length; i++)
        {
          var selector = ".bar_graphic_" + (i).toString();
          dataset = data_array[0][i];
          
          svg = d3.select(selector)
            .append('svg')
            .attr('width', width)
            .attr('height', height);
        
          svg.selectAll('rect.bars')
            .data(dataset)
            .enter()
            .append('rect')
              .attr('width', function(d,i)
              {  
                return true_width/dataset.length - 5;
              })
       	      .attr('height', function(d,i)
       	      {
       	        return (d.weight*1000);
       	      })
       	      .attr('x', function(d,i)
       	      {
       	        return i*(true_width/dataset.length);
       	      })
       	      .attr('y', function(d,i)
       	      {
       	        return height - (d.weight*1000);
       	      })
       	      .attr('fill', function(d, i)
       	      {
       	        if (d.word == "<?php echo $word; ?>")
       	        {
       	          return "rgb(255, 0, 0)";
       	        }
       	        else
       	        {
       	          return "rgb(100, 255, " + (255 - (i * 5)) + ")";
       	        }
       	      })
       	      .on("mouseover", function () 
       	      {
                d3.select(this).classed("hover", true);
              })
              .on("mouseout", function () 
              {
                d3.select(this).classed("hover", false);
              })
              .on("click", function (d) 
              {
                window.location = "words?word="+ d.word + "&global_id=" + d.global_id;
              })
           svg.selectAll("text")
            .data(dataset)
            .enter()
       	    .append('text')
       	      .text(function(d) 
       	      {
                return d.word;
              })
       	      .attr('x', function(d,i)
       	      {
       	        return i*(true_width/dataset.length) + 15;
       	      })
       	      .attr('y', function(d,i)
       	      {
       	        return height - (d.weight*1000);
       	      })
       	      .attr('fill', function(d, i)
       	      {
       	        if (d.word == "<?php echo $word; ?>")
       	        {
       	          return "rgb(255, 0, 0)";
       	        }
       	        else
       	        {
       	          return "rgb(100, 255, " + (255 - (i * 5)) + ")";
       	        }
       	      })
       	      .attr('transform', function(d,i)
       	      {
       	        var x = i*(true_width/dataset.length);
       	        var y = height - (d.weight*1000);
       	        return "rotate(-45 " + x +", " + y + ")";
       	      })
       	      .on("mouseover", function () 
       	      {
                d3.select(this).classed("hover", true);
              })
              .on("mouseout", function () 
              {
                d3.select(this).classed("hover", false);
              })
              .on("click", function (d) 
              {
                window.location = "words?word="+ d.word + "&global_id=" + d.global_id;
              });
            }	
      function transform_tag(object)
      {
        var page_header = "<?php echo $header; ?>";
        var global_id = object.id.split(',')[0];
        var word = "<?php echo $word; ?>";
	     var table_id = "T_" + global_id;
	
        var node = document.getElementById(table_id);
	
      	var parent = object.parentNode;
      	var text = object.innerHTML;

	      if(text == "TBD")
	      {
          object.remove();
          $("<td style='vertical-align: middle;'><form method='post' action='words_name.php?global_id="+global_id+"&header="+page_header+"&word="+word+"' id="+global_id+"><div class='form-group'><input name='submit_name' type='text' class='input-standard' placeholder='Enter Name Here'></div><div class='form-group'><button type='submit' class='btn btn-default'>Submit</button></div></form></td>").insertAfter(node);
        }
        
        else
        {
          object.remove();
          $("<td style='vertical-align: middle;'><form method='post' action='words_name.php?global_id="+global_id+"&header="+page_header+"&word="+word+"' id="+global_id+"><div class='form-group'><input name='submit_name' type='text' class='input-standard' placeholder="+text+"></div><div class='form-group'><button type='submit' class='btn btn-default'>Submit</button></div></form></td>").insertAfter(node);
        }
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