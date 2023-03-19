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
        </div>
        <div class="item mx-1">
            <div class="card p-2">
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
        </div>
    </div>
    <div class="col-12 text-start d-flex">
            <a class="btn btn-dark mx-1" id="btnExport"><i class="bi bi-file-earmark-arrow-down"></i> xls</a>
            <a class="btn btn-dark mx-1" id="Exportpdf"><i class="bi bi-file-earmark-arrow-down"></i> pdf</a>
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

    $jmldata = 0;
@endphp
{{-- hitung jumlah data --}}
@for ($i = 0; $i < count($perbulan); $i++) 
    @if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1)
        @php
            $jmldata++;
        @endphp
    @endif
@endfor


<div class="row">
    <div class="col-12">displaying data from {{$pertanggal}}</div>
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
                    <td style="background-color: rgb(198, 224, 180)">{{$listCategory['income'][$i]}}</td>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                        @php $h = $e @endphp
                        @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                            @if (count($data[$perbulan[$e]][0]) > 0)
                                @foreach ($data[$perbulan[$e]][0] as $item)
                                @if(in_array_r($listCategory['income'][$i],$data[$perbulan[$e]][0]->toArray()))
                                    @if($item->category == $listCategory['income'][$i])
                                        <td style="background-color: rgb(198, 224, 180)">{{number_format($item->amount)}}</td>
                                    @endif
                                @elseif($e == $h)
                                    <td style="background-color: rgb(198, 224, 180)">0</td>
                                    @php $h = -1 @endphp
                                @endif
                                @endforeach
                            @else
                                <td style="background-color: rgb(198, 224, 180)">0</td>
                            @endif
                        @endif
                    @endfor
                </tr>
                @endfor
                
                <tr>
                    <td style="background-color: rgb(169, 208, 142)"><b>Total Income</b></td>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                    @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                    <td style="background-color: rgb(169, 208, 142)">{{number_format($data[$perbulan[$e]][0]->sum('amount'))}}</td>
                    @endif
                    @endfor
                </tr>

                @for ($i = 0; $i < count($listCategory['expense']); $i++) {{--looping kategori type income--}}
                <tr>
                    <td style="background-color: rgb(248, 203, 173)">{{$listCategory['expense'][$i]}}</td>
                    @for ($e = 0; $e < count($perbulan); $e++) 
                        @php $h = $e @endphp
                        @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                            @if (count($data[$perbulan[$e]][1]) > 0)
                                @foreach ($data[$perbulan[$e]][1] as $item)
                                @if(in_array_r($listCategory['expense'][$i],$data[$perbulan[$e]][1]->toArray()))
                                    @if($item->category == $listCategory['expense'][$i])
                                        <td style="background-color: rgb(248, 203, 173)">{{number_format($item->amount)}}</td>
                                    @endif
                                @elseif($e == $h)
                                    <td style="background-color: rgb(248, 203, 173)">0</td>
                                    @php $h = -1 @endphp
                                @endif
                                @endforeach
                            @else
                                <td style="background-color: rgb(248, 203, 173)">0</td>
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
                    <th @if($netincome < 0) style="color: rgb(243, 0, 0)" @endif>{{number_format($netincome)}}</th>
                    @endif
                    @endfor
                </tr>
        </table>
    </div>
    <div class="col-12 text-center my-4">
        @if($jmldata < 1) <h4>No data available</h4> @endif
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

        // export xls
        $(document).on('click','#btnExport',function() {

            var tab_text="<table border = '2px'><tr>";
            var textRange; var j=0;
            tab = document.getElementById('tabel');

            for(j = 0 ; j < tab.rows.length ; j++) 
            {     
                tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            }

            tab_text=tab_text+"</table>";
            tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");
            tab_text= tab_text.replace(/<img[^>]*>/gi,""); 
            tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); 

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE "); 

            var result = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
            var link = document.createElement("a");
            document.body.appendChild(link);
            link.download = "report-{{$pertanggal}}-coa.xls"; //namafile nya
            link.href = result;
            link.click();
        });


        // export pdf
        $(document).on('click','#Exportpdf',function(){
            var doc = new jsPDF('landscape');
            doc.autoTable({ html: '#tabel' })
            doc.save('report-{{$pertanggal}}-coa.pdf')
        });
    });
 </script>
 @endpush