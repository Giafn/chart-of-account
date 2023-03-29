@extends('layouts.app')

@section('content')
<div class="row px-2 my-3">
    <div class="col">
        <h2>Dashboard</h2>
    </div>
</div>
<div class="row align-items-md-stretch">
    <div class="col-md-12 my-2">
      <div class="h-100 p-5 text-white bg-dark rounded-3">
        <h2>Profits of this month</h2>
        <h1 class="fw-bold">Rp{{number_format($pendapatanbln)}}</h1>
      </div>
    </div>
  </div>
  <div>
    <div class="row justify-content-center my-4 align-items-center">
        <div class="col-lg-7">
            <canvas id="myChart"></canvas>
        </div>
        <div class="col-lg-5 text-center">
            <h4>Download profit and loss reports</h4>

            <a href="{{url('/report')}}" class="btn btn-lg btn-dark">
                Go to Reports page
            </a>
        </div>
    </div>
  </div>


@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>

<script>
    const ctx = document.getElementById('myChart');
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: [
                @foreach(array_reverse($bulan) as $items)
                `{{date("Y-m",strtotime($items['bln']))}}`,
                @endforeach
        ],
        datasets: 
            [{
            label: 'Profits in the last 12 months',
            data: [
              @foreach(array_reverse($bulan) as $items)
                `{{$items['sum']}}`,
              @endforeach
            ],
            fill: false,
            hoverOffset: 0,
            borderColor: 'rgb(13 110 253)',
            tension: 0.1
            }]
    }});
  </script>
    
@endpush
