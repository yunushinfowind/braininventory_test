@extends('layouts.default')
@section('content')
<div class="store">
  <div class="row">
    <div class="col-lg-6">
      <label for="cars">Choose a year:</label>
      <select name="cars" id="booking_year" onchange="getGraphData('booking')">
        <option value="">Select</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2020">2020</option>
      </select>

      <label for="cars">Choose a month:</label>
      <select name="cars" id="booking_month" onchange="getGraphData('booking')">
        <option value="">Select</option>
        <option value="1">Jan</option>
        <option value="2">Feb</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">Jun</option>
        <option value="7">July</option>
        <option value="8">Agust</option>
        <option value="9">Sep</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">Decemberf</option>

      </select>
      <label for="cars">Choose a week:</label>
      <select name="cars" id="booking_week" onchange="getGraphData('booking')">
        <option value="">Seelct</option>
        <option value="1">week1</option>
        <option value="2">week2</option>
        <option value="3">week3</option>
        <option value="4">week4</option>
      </select>

      <div id="donutchart_bookin_date" style="width: 800px; height: 400px;"></div>
    </div>


    <div class="col-lg-6">
      <label for="cars">Choose a year:</label>
      <select name="cars" id="trip_year" onchange="getGraphData('trip')">
        <option value="">Select</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2020">2020</option>
      </select>

      <label for="cars">Choose a month:</label>
      <select name="cars" id="trip_month" onchange="getGraphData('trip')">
        <option value="">Select</option>
        <option value="1">Jan</option>
        <option value="2">Feb</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">Jun</option>
        <option value="7">July</option>
        <option value="8">Agust</option>
        <option value="9">Sep</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">Decemberf</option>

      </select>
      <label for="cars">Choose a week:</label>
      <select name="cars" id="trip_week" onchange="getGraphData('trip')">
        <option value="">Seelct</option>
        <option value="1">week1</option>
        <option value="2">week2</option>
        <option value="3">week3</option>
        <option value="4">week4</option>
      </select>

      <div id="donutchart_trip_date" style="width: 800px; height: 400px;"></div>
    </div>
    <div class="row">
    <label for="cars">Choose a year:</label>
      <select name="cars" id="bar_year" onchange="getBarData()">
        <option value="">Select</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2020">2020</option>
      </select>

      <label for="cars">Choose a month:</label>
      <select name="cars" id="bar_month" onchange="getBarData()">
        <option value="">Select</option>
        <option value="1">Jan</option>
        <option value="2">Feb</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">Jun</option>
        <option value="7">July</option>
        <option value="8">Agust</option>
        <option value="9">Sep</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">Decemberf</option>

      </select>
      <label for="cars">Choose a week:</label>
      <select name="cars" id="bar_week" onchange="getBarData()">
        <option value="">Seelct</option>
        <option value="1">week1</option>
        <option value="2">week2</option>
        <option value="3">week3</option>
        <option value="4">week4</option>
      </select>

      <div class="col-lg-12">
        <div id="barchart_material" style="width: 100%; height: 400px;"></div>
      </div>
      <div class="col-lg-12">
        <div id="line_top_x" style="width: 100%; height: 400px;"></div>
      </div>
    </div>

  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      getGraphData('booking');
      getGraphData('trip');
      getBarData();
      getLineData();
    });
// line graph data
    function getLineData() {
      $.ajax({
        url: "{{url('graph-line-data')}}",
        data: {
          type: 'bar'
        },
        cache: false,
        success: function(res) {
          google.charts.load('current', {
            'packages': ['line']
          });
          google.charts.setOnLoadCallback(drawChart);
          var response = [];
          for (const [key, value] of Object.entries(res.data)) {
            console.log(value.month)
            response.push([value.month, parseInt(value.booking_cost)]);
          }
          function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Cost');
            data.addRows(response);
            var options = {
              chart: {
                title: 'Sales performance',
                subtitle: ''
              },
              width: 900,
              height: 500,
              axes: {
                x: {
                  0: {
                    side: 'bottom'
                  }
                }
              }
            };
            var chart = new google.charts.Line(document.getElementById('line_top_x'));
            chart.draw(data, google.charts.Line.convertOptions(options));
          }
        }
      });
    }
    
// bar graph data
    function getBarData() {
      $.ajax({
        url: "{{url('graph-bar-data')}}",
        data: {
          type: 'bar',"year":$('#bar_year').val(),'month':$('#bar_month').val(),'week':$('#bar_week').val()
        },
        cache: false,
        success: function(res) {
          google.charts.load('current', {
            'packages': ['bar']
          });
          google.charts.setOnLoadCallback(drawChart);
          var response = [];
          response.push(['Trip', 'Booking', 'Commission']);
          for (const [key, value] of Object.entries(res.data)) {
            response.push([value.trip, parseInt(value.booking_cost), parseInt(value.commission_cost)]);
            console.log(key, value);
          }

          function drawChart() {
            var data = google.visualization.arrayToDataTable(response);
            var options = {
              chart: {
                title: '',
                subtitle: '',
              },
              bars: 'vertical'
            };
            var chart = new google.charts.Bar(document.getElementById('barchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      });
    }

// donut graph data
    function getGraphData(subtype) {
      $.ajax({
        url: "{{url('graph-data')}}",
        data: {
          type: 'donut',
          'subtype': subtype,
          'week': $('#' + subtype + '_week').val(),
          'month': $('#' + subtype + '_month').val(),
          'year': $('#' + subtype + '_year').val()
        },
        cache: false,
        success: function(res) {
          console.log(res);
          var response = [];
          response.push(['Task', 'Trip Quotations']);
          for (const [key, value] of Object.entries(res.data)) {
            response.push([key, parseInt(value)]);
            console.log(key, value);
          }
          google.charts.load("current", {
            packages: ["corechart"]
          });
          google.charts.setOnLoadCallback(drawChart);
          if (subtype == 'booking') {
            var title = 'Trip Quotations based on booking date';
            var id = 'donutchart_bookin_date';
          } else {
            var title = 'Trip Quotations based on trip date';
            var id = 'donutchart_trip_date';
          }
          function drawChart() {
            var data = google.visualization.arrayToDataTable(response);
            var options = {
              title: title,
              pieHole: 0.5,
            };
            var chart = new google.visualization.PieChart(document.getElementById(id));
            chart.draw(data, options);
          }
        }
      });
    }
  </script>
  <style>
    .store {
      margin-top: 15px;
      ;
    }
  </style>
  @stop