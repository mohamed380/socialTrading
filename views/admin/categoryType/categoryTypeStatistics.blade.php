<div class="rowContainer">
    <div class="chart">
    <canvas id="myChart"></canvas>
    </div>
</div>
<script src="{{asset('js')}}/Chart.min.js"></script>
<script type="text/javascript">
   var ctx = document.getElementById('myChart').getContext('2d');
       var chart = new Chart(ctx, {
   // The type of chart we want to create
   type: 'line',

   // The data for our dataset
   data: {
       labels: ['Projects', 'Users', 'Offers', 'Categories', 'Tags', 'Orange'],
       datasets: [{
           label: 'General Statistics',
           data: [12, 19, 10, 5, 5, 3],
           backgroundColor: [
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
           ],
           borderColor: [
               'rgba(255, 99, 132, 1)',
               'rgba(54, 162, 235, 1)',
               'rgba(255, 206, 86, 1)',
               'rgba(75, 192, 192, 1)',
               'rgba(153, 102, 255, 1)',
               'rgba(255, 159, 64, 1)'
           ],
           borderWidth: 1
       }]
   },

   // Configuration options go here
   options: {

   }
});
</script>