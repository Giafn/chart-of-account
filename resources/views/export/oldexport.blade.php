@extends('layouts.app')

@section('content')

<div class="row justify-content-center p-3 gy-2 align-items-center">
    <div class="col-12">
        <h3 class="fw-bold">Report Page</h3>
        <h5 class="fw-bold mt-4">filter data</h5>
    </div>
    <div class="d-flex">
        <div class="item">
            <form action="{{url('/report')}}" method="POST">
                @csrf
                <input type="hidden" name="search" value="1">
                <div class="form-group">
                    <div class="row gy-3">
                        <div class="col-lg-5">
                            @php
                                $bulan=['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                            @endphp
                            <select name="month" class="form-control {{ $errors->has('month') ? 'is-invalid':'' }}">
                                <option disabled selected>--Month--</option>
                                @for ($i = 0; $i < 12; $i++)
                                <?php $index=$i+1; ?>
                                <option value="{{$i+1}}">{{$bulan[$i]}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="number" name="years" 
                            class="form-control {{ $errors->has('years') ? 'is-invalid':'' }}"
                            id="tahun"
                            value="{{ date('Y') }}"
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
        <div class="item">
            <form method="POST" action="{{url('/report')}}" enctype="multipart/form-data">
                <input type="hidden" name="search" value="1">
                <div class="row gy-3">
                    @csrf
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="month" class="form-control {{ $errors->has('tgl_awal') ? 'is-invalid':'' }}" name="tgl_awal"
                            placeholder="Dari Tanggal">
                            
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="month" class="form-control {{ $errors->has('tgl_akhir') ? 'is-invalid':'' }}" name="tgl_akhir"
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
        <div class="item">
            <button class="btn btn-dark" id="btnExport" onclick="fnExcelReport();"> Export to Exel </button>
        </div>
    </div>
</div>


@php
    $maxrow = 0;
    $jumlahkolom = 0;
    for ($i = 0; $i < count($perbulan); $i++){
        $row =4;
        foreach ($data[$perbulan[$i]][0] as $item){
            $row++;
        }
        foreach ($data[$perbulan[$i]][1] as $item){
            $row++;
        }
        if ($maxrow < $row) {
            $maxrow = $row;
        }
    }
@endphp
{{-- tabel tampilan --}}
<div class="row p-3">
    @for ($i = 0; $i < count($perbulan); $i++)
    @if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1)
    <div class="col-md-6">
        <table class="table">
                <tr class="bg-primary text-white" id='head'>
                    <th>Category</th>
                    @php
                        $date=date_create($perbulan[$i]);
                    @endphp
                    <th>{{date_format($date,'Y-m')}} <br> Amount(Rp)</th>
                </tr>
                @foreach ($data[$perbulan[$i]][0] as $item)
                <tr>
                    <td>{{$item->category}}</td>
                    <td>{{number_format($item->amount)}}</td>
                </tr>
                @endforeach
                @if ($data[$perbulan[$i]][0]->sum('amount') > 1)
                <tr>
                    <th>total income</th>
                    <th>{{number_format($data[$perbulan[$i]][0]->sum('amount'))}}</th>
                </tr>
                @else
                @endif
                @foreach ($data[$perbulan[$i]][1] as $item)
                <tr>
                    <td>{{$item->category}}</td>
                    <td>{{number_format($item->amount)}}</td>
                </tr>
                @endforeach
                @if ($data[$perbulan[$i]][1]->sum('amount') > 1)
                <tr>
                    <th>total Expense</th>
                    <th>{{number_format($data[$perbulan[$i]][1]->sum('amount'))}}</th>
                </tr>
                @else
                @endif
                <tr>
                    @php
                        $total = $data[$perbulan[$i]][0]->sum('amount')-$data[$perbulan[$i]][1]->sum('amount');
                    @endphp
                    <th>@if($total >= 0) Net Income @else Loss @endif</th>
                    <th>{{number_format($total)}}</th>
                </tr>
        </table>
    </div>
        @php
            $jumlahkolom++;
        @endphp
        @endif
    @endfor
    @if ($jumlahkolom == 0)
        
    <div class="col text-center">
        <h4>No data available</h4>
    </div>
    @endif
</div>




{{-- tabel for exel --}}
<div class="wrapper">
    <div id="txtArea1" style="display:none"></div>
</div>
<table class="table d-none" id="tabel">
    @for ($i = 0; $i < count($perbulan); $i++)
    @if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1)
    <tr>
        <td class="fw-bold"><b>Category<b></td>
        @php
            $row =4;
        @endphp
        @foreach ($data[$perbulan[$i]][0] as $item)
            <td>{{$item->category}}</td>
            @php $row++ @endphp
        @endforeach
        <td class="fw-bold"><b style="color :rgb(73, 209, 31)">total income</b></td>
        @foreach ($data[$perbulan[$i]][1] as $item)
            <td>{{$item->category}}</td>
            @php $row++ @endphp
        @endforeach
        <td class="fw-bold"><b style="color :rgb(255, 115, 0)">Total Expense</b></td>
        <td>
            @if($data[$perbulan[$i]][0]->sum('amount') - $data[$perbulan[$i]][1]->sum('amount') > 0)
            <b style="color :rgb(58, 45, 167)">Net Income</b>
            @else
            <b style="color :rgb(255, 0, 0)">Loss</b>
            @endif
        </td>
        @for ($rows = $row; $rows < $maxrow; $rows++)
            <td></td>
        @endfor
    </tr>
    <tr>
        @php
                $date=date_create($perbulan[$i]);
        @endphp
        <td><b>{{date_format($date,'Y-m')}} <br> Amount(Rp)</b></td>
        @foreach ($data[$perbulan[$i]][0] as $item)
        <td>{{number_format($item->amount)}}</td>
        @endforeach
        <td class="fw-bold"><b style="color :rgb(73, 209, 31)">{{number_format($data[$perbulan[$i]][0]->sum('amount'))}}</b></td>
        @foreach ($data[$perbulan[$i]][1] as $item)
        <td>{{number_format($item->amount)}}</td>
        @endforeach
        <td class="fw-bold"><b style="color :rgb(255, 115, 0)">{{number_format($data[$perbulan[$i]][1]->sum('amount'))}}</b></td>
        <td class="fw-bold text-success">
            @if($data[$perbulan[$i]][0]->sum('amount') - $data[$perbulan[$i]][1]->sum('amount') > 0)
            <b  style="color :rgb(58, 45, 167)">
                {{number_format($data[$perbulan[$i]][0]->sum('amount')-$data[$perbulan[$i]][1]->sum('amount'))}}
            </b>
            @else
            <b  style="color :rgb(255, 0, 0)">
                {{number_format($data[$perbulan[$i]][0]->sum('amount')-$data[$perbulan[$i]][1]->sum('amount'))}}
            </b>
            @endif
        </td>
        @for ($rows = $row; $rows < $maxrow; $rows++)
            <td></td>
        @endfor
    </tr>
    <tr>
        @for ($rows = 0; $rows < $maxrow; $rows++)
            <td></td>
        @endfor
    </tr>
    @endif
    @endfor
</table>
    
    

@endsection


 @push('js')
 <script>
    // transpose table
     $(document).ready(function(){
        if('{{$jumlahkolom}}' < 1){
            $('#btnExport').prop('disabled', true);
        }
        if('{{$errors->first()}}'){
            toastr.error('please fill the filter correctly', 'Error!');
        }

        $("#tabel").each(function() {
            var $this = $(this);
            var newrows = [];
            $this.find("tr").each(function(){
                var i = 0;
                $(this).find("td").each(function(){
                    i++;
                    if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
                    newrows[i].append($(this));
                });
            });
            $this.find("tr").remove();
            $.each(newrows, function(){
                $this.append(this);
            });
        })
    });




    // export to exel
    function fnExcelReport()
    {
        var tab_text="<table><tr><th bgcolor='#0d6efd' colspan='{{$jumlahkolom*3-1}}'><b style='color :#ffffff'>Profit and Loss Data per {{$pertanggal}}</b></th></tr><tr>";
        var textRange; var j=0;
        tab = document.getElementById('tabel'); // id of table

        for(j = 0 ; j < tab.rows.length ; j++) 
        {     
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
        }  
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

        return (sa);
    }
 </script>
 @endpush