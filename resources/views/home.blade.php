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
        <h2>Profits this month</h2>
        <h1 class="fw-bold">Rp{{number_format($Profitbulan)}}</h1>
      </div>
    </div>
    <div class="col-md-12 my-2">
      <div class="h-100 p-5 bg-light border rounded-3">
        <h2>Profits this Year</h2>
        <h1 class="fw-bold">Rp.{{number_format($Profittahun)}}</h1>
      </div>
    </div>
  </div>
  <div>
    <div class="row justify-content-center my-4 align-items-center">
        <div class="col-lg-10">
            <canvas id="myChart"></canvas>
        </div>
        <div class="col-md-12 text-center">
            <h4>Download profit and loss reports</h4>
        </div>
        <div class="col-12 text-center">
            <button type="button" class="btn btn-lg btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Download Reports
            </button>
        </div>
    </div>
  </div>

  {{-- modal download report --}}
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{url('admin/laporan/cetak')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="">Download Perbulan</label>
                    <div class="row ">
                        <div class="col-md-5">
                            @php
                                $bulan=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                            @endphp
                            <select name="bulan" class="form-control">
                                <option value="" selected>Bulan</option>
                                @for ($i = 0; $i < 12; $i++)
                                <?php $index=$i+1; ?>
                                <option value="{{$i+1}}" @if ($index == date('m')) selected @endif>{{$bulan[$i]}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-5">
                            @php
                                $bulan=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                            @endphp
                            <select name="bulan" class="form-control">
                                <option value="" selected>Bulan</option>
                                @for ($i = 0; $i < 12; $i++)
                                <?php $index=$i+1; ?>
                                <option value="{{$i+1}}" @if ($index == date('m')) selected @endif>{{$bulan[$i]}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="tahun" 
                            class="form-control {{ $errors->has('tahun') ? 'is-invalid':'' }}"
                            id="tahun"
                            value="{{ date('Y') }}"
                            placeholder="tahun">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 2%">Download</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>

<script>
    const ctx = document.getElementById('myChart');
    let bulan = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
    let label = [
        bulan['{{$bln[0]-1}}'],
        bulan[`{{$bln[1]-1}}`],
        bulan['{{$bln[2]-1}}'],
    ];
    let i = 0;
    let isi;

    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: label,
        datasets: 
            [{
            label: 'Profits in the last 3 months',
            data: [
                `{{$summonth[0]}}`, 
                `{{$summonth[1]}}`, 
                `{{$summonth[2]}}`,
            ],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
    }});
  </script>
    
@endpush
