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
    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

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


<div class="row">
    <div class="col">
        <table class="table table-responsive" id="tabel">
                <tr>
                    <th>Category</th>
                    @for ($i = 0; $i < count($perbulan); $i++) 
                        @if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1)
                            <th>{{date('Y-m',strtotime($perbulan[$i]))}} <br>amount</th>
                            @php
                                $jumlahkolom++;
                            @endphp
                        @endif
                    @endfor
                </tr>
                @for ($i = 0; $i < count($listCategory['income']); $i++) {{--looping kategori type income--}}
                <tr>
                    <td>{{$listCategory['income'][$i]}}</td>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                        @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                            @if (count($data[$perbulan[$e]][0]) > 0)
                                @foreach ($data[$perbulan[$e]][0] as $item)
                                @if(in_array_r($listCategory['income'][$i],$data[$perbulan[$e]][0]->toArray()))
                                    @if($item->category == $listCategory['income'][$i])
                                        <td>Rp.{{number_format($item->amount)}}</td>
                                    @endif
                                @else
                                    <td>0</td>
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
                    <th style="color: blue">Total Income</th>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                    @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                    <th>Rp.{{number_format($data[$perbulan[$e]][0]->sum('amount'))}}</th>
                    @endif
                    @endfor
                </tr>

                @for ($i = 0; $i < count($listCategory['expense']); $i++) {{--looping kategori type income--}}
                <tr>
                    <td>{{$listCategory['expense'][$i]}}</td>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                        @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                            @if (count($data[$perbulan[$e]][1]) > 0)
                                @foreach ($data[$perbulan[$e]][1] as $item)
                                @if(in_array_r($listCategory['expense'][$i],$data[$perbulan[$e]][1]->toArray()))
                                    @if($item->category == $listCategory['expense'][$i])
                                        <td>Rp.{{number_format($item->amount)}}</td>
                                    @endif
                                @else
                                    <td>0</td>
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
                    <th style="color: rgb(194, 132, 0)">Total Expense</th>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                    @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                    <th>Rp.{{number_format($data[$perbulan[$e]][1]->sum('amount'))}}</th>
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
                    <th @if($netincome < 0) style="color: rgb(243, 0, 0)" @endif>Rp.{{number_format($netincome)}}</th>
                    @endif
                    @endfor
                </tr>
        </table>
    </div>
</div>


{{-- @if($data[$perbulan[$i]][0]->count() < count($listCategory['income']))
                                        @php $t0 =  count($listCategory['income']) - $data[$perbulan[$i]][0]->count(); @endphp
                                        @for ($t = 0; $t < $t0; $t++)
                                            <td></td>
                                        @endfor
                                    @endif --}}


{{-- tabel for exel --}}
    
    

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
    });




    // export to exel
    function fnExcelReport()
    {
        var tab_text="<table border='2px'><tr><th bgcolor='#0d6efd' colspan='{{$jumlahkolom+1}}'><b style='color :#ffffff'>Profit and Loss Data per {{$pertanggal}}</b></th></tr><tr>";
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