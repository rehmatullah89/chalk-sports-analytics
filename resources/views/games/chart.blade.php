<div id="piechart"></div>
<script type="text/javascript" src="{!! asset('js/gstatic-loader.js') !!}"></script>
<?php $chart_data = array_chunk(json_decode(json_encode(@$chart_data[0]), true), 1);
$wins = (int)count($data2) - (int)count($data);
$title = 'Percent Correct Predictions: '.(count($data2)>0?number_format(($wins/count($data2))*100):0).'% \n Correct Predictions: '.$wins.'\n Wrong Predictions:'.count($data).'\n All Predictions:'.count($data2);
?>

<script type="text/javascript">
    // Load google charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    // Draw the chart and set the chart values
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Result', 'Count'],
            ['Wins', {{(int)@$chart_data[0][0]}}],
            ['Lost', {{(int)@$chart_data[1][0]}}]
        ]);

        // Optional; add a title and set the width and height of the chart
        var options = {'title':'{{$title}}', 'width':550, 'height':400};

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>
