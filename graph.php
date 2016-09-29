<?php
  require 'jpgraph/jpgraph/src/jpgraph.php';
  require 'jpgraph/jpgraph/src/jpgraph_pie.php';
  require 'jpgraph/jpgraph/src/jpgraph_pie3d.php';

  $i = $_REQUEST['i'];
  $global_id = $_REQUEST['global_id'];
  $weight = $_REQUEST['weight'];
	    

  $data = array($weight*10, (1 - $weight)*10);
  $graph = new PieGraph(50,50);
  $graph->setMarginColor('#333');
  $graph->SetShadow();
  $plot1 = new PiePlot($data);
  $plot1->SetLabelType(PIE_VALUE_PER);
  $label = array("","");
  $plot1->SetLabels($label);
  $plot1->SetStartAngle(0);
  $graph->Add($plot1);
  $plot1->SetSliceColors(array('#0000ff','#00c5ff'));
  $image = $graph->Stroke();		    
?>