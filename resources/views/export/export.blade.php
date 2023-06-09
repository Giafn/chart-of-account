@extends('layouts.app')

@section('content')
<div class="row justify-content-center p-3 gy-2 align-items-center">
    <div class="col-12">
        <h3 class="fw-bold">Report Page</h3>
        <h5 class="fw-bold mt-4">filter data</h5>
    </div>
    <div class="col-12 d-flex justify-content-center align-items-end">
        <div class="item mx-1">
            <div class="card p-2">
                <form action="{{url('/report')}}" method="POST">
                    @csrf
                    <input type="hidden" name="search" value="bulan">
                    <div class="form-group">
                        <div class="row gy-3">
                            <div class="col-lg-5">
                                @php
                                    $bulan=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                                @endphp
                                <select name="month" class="form-control {{ $errors->has('month') ? 'is-invalid':'' }}" >
                                    <option disabled @if(!isset($request->month)) selected @endif>--Month--</option>
                                    @for ($i = 0; $i < 12; $i++)
                                        <?php $index=$i+1; ?>
                                        <option value="{{$i+1}}" @isset($request->month) @if ($i+1 == $request->month) selected  @endif @endisset >
                                            {{$bulan[$i]}}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="number" name="years" 
                                class="form-control {{ $errors->has('years') ? 'is-invalid':'' }}"
                                id="tahun"
                                @if (isset($request->years)) 
                                    value="{{ $request->years }}"
                                @else
                                    value="{{ date('Y') }}"
                                @endif 
                                placeholder="years">
                            </div>
                            <div class="col-lg-4 d-flex">
                                <button type="submit" class="btn btn-dark mx-1"><i class="bi bi-search"></i></button>
                                <a class="btn btn-dark mx-1" onClick="window.location.href=window.location.href"><i class="bi bi-arrow-clockwise"></i></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="item mx-1">
            <div class="card p-2">
                <form method="POST" action="{{url('/report')}}" enctype="multipart/form-data">
                    <input type="hidden" name="search" value="range">
                    <div class="row gy-3">
                        @csrf
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="month" class="form-control {{ $errors->has('tgl_awal') ? 'is-invalid':'' }}" name="tgl_awal"
                                @if (isset($request->tgl_awal)) 
                                    value="{{ $request->tgl_awal }}"
                                @endif 
                                placeholder="Dari Tanggal">
                                
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="month" class="form-control {{ $errors->has('tgl_akhir') ? 'is-invalid':'' }}" name="tgl_akhir"
                                @if (isset($request->tgl_akhir)) 
                                    value="{{ $request->tgl_akhir }}"
                                @endif 
                                placeholder="Sampai Tanggal">
                            </div>
                        </div>
                        <div class="col-lg-4 d-flex">
                            <button class="btn btn-dark mx-1" type="submit" name="action"><i class="bi bi-search"></i></button>
                            <a class="btn btn-dark mx-1" onClick="window.location.href=window.location.href"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 text-start d-flex">
            <form method="POST" action="{{url('/report')}}">
                <input type="hidden" name="export" value="1">
                @csrf
                @if (isset($request->search))
                    @if ($request->search == "bulan")
                        <input type="hidden" name="month" value="{{$request->month}}">
                        <input type="hidden" name="years" value="{{$request->years}}">
                        <input type="hidden" name="search" value="{{$request->search}}">
                    @elseif ($request->search == "range")
                        <input type="hidden" name="tgl_awal" value="{{$request->tgl_awal}}">
                        <input type="hidden" name="tgl_akhir" value="{{$request->tgl_akhir}}">
                        <input type="hidden" name="search" value="{{$request->search}}">
                    @endif
                @endif
                <button type="submit" class="btn btn-dark mx-1"><i class="bi bi-file-earmark-arrow-down"></i> xls</button>
            </form>
            <a class="btn btn-dark mx-1" id="Exportpdf"><i class="bi bi-file-earmark-arrow-down"></i> pdf</a>
    </div>
</div>
@php
    function InArray($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && InArray($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

    $jmldata = 0;
    for ($i = 0; $i < count($perbulan); $i++) {
        if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1){
            $jmldata++;
        }
    }
@endphp

<div class="card">
    <div class="card-body">
        <div class="row overflow-auto">
            <div class="col-12 my-3 h5">displaying data from {{$pertanggal}}</div>
            <div class="col">
                <table class="table table-responsive @if($jmldata < 1) d-none @endif" id="tabel">
                        <tr>
                            <th style="background-color: yellow"><b>Category <br></b></th>
                            @for ($i = 0; $i < count($perbulan); $i++) 
                                @if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1)
                                    <th style="background-color: yellow"><b>{{date('Y-m',strtotime($perbulan[$i]))}} <br>amount</b></th>
                                @endif
                            @endfor
                        </tr>
                        @for ($i = 0; $i < count($listCategory['income']); $i++) {{--looping kategori type income--}}
                        <tr>
                            <td>{{$listCategory['income'][$i]}}</td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                @php $h = $e @endphp
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    @if (count($data[$perbulan[$e]][0]) > 0)
                                        @foreach ($data[$perbulan[$e]][0] as $item)
                                            @if (InArray($listCategory['income'][$i],$data[$perbulan[$e]][0]->toArray()))
                                                @if ($item->category == $listCategory['income'][$i])
                                                    <td>{{number_format($item->amount)}}</td>
                                                @endif
                                            @elseif ($e == $h)
                                                <td>0</td>
                                                @php $h = -1 @endphp
                                            @endif
                                        @endforeach
                                    @else
                                        <td>0</td>
                                    @endif
                                @endif
                            @endfor
                        </tr>
                        @endfor
                        
                        <tr>
                            <td style="background-color: rgb(0, 255, 0)"><b>Total Income</b></td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    <td style="background-color: rgb(0, 255, 0)">{{number_format($data[$perbulan[$e]][0]->sum('amount'))}}</td>
                                @endif
                            @endfor
                        </tr>
        
                        @for ($i = 0; $i < count($listCategory['expense']); $i++) {{--looping kategori type income--}}
                        <tr>
                            <td>{{$listCategory['expense'][$i]}}</td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                @php
                                    $h = $e
                                @endphp
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    @if (count($data[$perbulan[$e]][1]) > 0)
                                        @foreach ($data[$perbulan[$e]][1] as $item)
                                            @if (InArray($listCategory['expense'][$i],$data[$perbulan[$e]][1]->toArray()))
                                                @if ($item->category == $listCategory['expense'][$i])
                                                    <td>{{number_format($item->amount)}}</td>
                                                @endif
                                            @elseif ($e == $h)
                                                <td>0</td>
                                                @php
                                                    $h = -1
                                                @endphp
                                            @endif
                                        @endforeach
                                    @else
                                        <td>0</td>
                                    @endif
                                @endif
                            @endfor
                        </tr>
                        @endfor
        
                        <tr>
                            <td style="background-color: rgb(244, 176, 132)"><b>Total Expense</b></td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    <td style="background-color: rgb(244, 176, 132)">{{number_format($data[$perbulan[$e]][1]->sum('amount'))}}</td>
                                @endif
                            @endfor
                        </tr>
                        <tr>
                            <th>Net income</th>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    @php
                                        $netincome = $data[$perbulan[$e]][0]->sum('amount') - $data[$perbulan[$e]][1]->sum('amount')
                                    @endphp
                                <th @if ($netincome < 0) style="color: rgb(243, 0, 0)" @endif>{{number_format($netincome)}}</th>
                                @endif
                            @endfor
                        </tr>
                </table>
            </div>
            <div class="col-12 text-center my-4">
                @if($jmldata < 1)
                    <h4>No data available</h4>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

 @push('js')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
 <script>
     $(document).ready(function(){
        if('{{$jmldata}}' < 1){
            $('#btnExport').prop('disabled', true);
        }
        // validation
        if('{{$errors->first()}}'){
            toastr.error('please fill the filter correctly', 'Error!');
        }

        $(document).on('click','#Exportpdf',function(){
            var doc = new jsPDF('landscape');
            doc.autoTable({ html: '#tabel' })
            doc.save('report-{{$pertanggal}}-coa.pdf')
        });
    });
 </script>
 @endpush