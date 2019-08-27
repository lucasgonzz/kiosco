@extends('app')
@section('content')
		<canvas id="myChart"></canvas>
	<div id="resumen-ventas">
	</div>
@endsection

@section('scripts')
<script>
	var ctx = document.getElementById('myChart');
	var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Enero', 'Febrero', 'Mayo', 'Abril', 'Marzo', 'Junio', 'Julio', 'Agosto', 'Septiembre'],
        datasets: [{
            label: '# of Votes',
            data: [100, 19, 3, 5, 2, 3],
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
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
<script>
	new Vue({
	el: "#resumen-ventas",
	data: {
	},
	methods: {

	}
});

</script>
@endsection
