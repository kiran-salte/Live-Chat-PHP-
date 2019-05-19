<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
 <?php
      include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."setting.php";
         $userid = $_REQUEST['id'];
         $sql = "select * from banword_count where id = ".$userid;
    //echo $sql;
    $query = mysqli_query($conn,$sql);

    $row = mysqli_fetch_array($query);

    $type1 = $row['type1'];
    $type2 = $row['type2'];
    $type3 = $row['type3'];   
        ?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
          <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Types', 'Count'],
          ["TYPE1 ", <?php echo $type1; ?> ],
          ["TYPE2 ", <?php echo $type2; ?> ],
          ["TYPE3 ", <?php echo $type3; ?>],
        ]);

        var options = {
          title: 'Ban words Count',
          width: 500,
          legend: { position: 'none' },
          chart: { title: 'Ban words Count Graph',
                   },
          bars: 'vertical', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Count'} // Top x-axis.
            }
          },
          bar: { groupWidth: "60%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
    </script>
  </head>
  <body>
    <div id="top_x_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>
