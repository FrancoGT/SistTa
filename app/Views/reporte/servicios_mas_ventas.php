<h1>Servicios con m&aacute;s ventas</h1>

<?php
  if ($mensaje != '')
  {
    echo $mensaje;
  }
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
// Load the Visualization API and the piechart package.
google.charts.load('current', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {
      var jsonData = $.ajax({
          url: "<?php echo base_url();?>index.php/reporte/get_data_servicios_pagados",
          dataType: "json",
          async: false
          }).responseText;
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, {height: 400, chartArea: {width: "90%", height: "80%"}, legend: {position: "right"}});
    }

    </script>

    <div id="chart_div" class="chart-box"></div>
