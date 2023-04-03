
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    
        <title>Chart Of Account | Export</title>
        <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon"/>
    
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
        {{-- css select2 --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    </head>
    <body>
        <?php
            function in_array_r($needle, $haystack, $strict = false) {
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                        return true;
                    }
                }
        
                return false;
            }
        
            $jmldata = 0;
            // hitung data
            for ($i = 0; $i < count($perbulan); $i++) {
                if ($data[$perbulan[$i]][0]->sum('amount') > 1 | $data[$perbulan[$i]][1]->sum('amount') > 1){
                    $jmldata++;
                }
            }
        ?>
        
        
        <div class="row overflow-auto">
            <div class="col">
                <table class="table table-responsive @if($jmldata < 1) d-none @endif" id="tabel" border="1px dashed #CCC">
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
                                <?php $h = $e ?>
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    @if (count($data[$perbulan[$e]][0]) > 0)
                                        @foreach ($data[$perbulan[$e]][0] as $item)
                                        @if(in_array_r($listCategory['income'][$i],$data[$perbulan[$e]][0]->toArray()))
                                            @if($item->category == $listCategory['income'][$i])
                                                <td>{{$item->amount}}</td>
                                            @endif
                                        @elseif($e == $h)
                                            <td>0</td>
                                            <?php $h = -1 ?>
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
                            <td style="background-color: green"><b>Total Income</b></td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                            @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                            <td style="background-color: green">{{$data[$perbulan[$e]][0]->sum('amount')}}</td>
                            @endif
                            @endfor
                        </tr>
        
                        @for ($i = 0; $i < count($listCategory['expense']); $i++) {{--looping kategori type income--}}
                        <tr>
                            <td>{{$listCategory['expense'][$i]}}</td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                <?php $h = $e ?>
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    @if (count($data[$perbulan[$e]][1]) > 0)
                                        @foreach ($data[$perbulan[$e]][1] as $item)
                                        @if(in_array_r($listCategory['expense'][$i],$data[$perbulan[$e]][1]->toArray()))
                                            @if($item->category == $listCategory['expense'][$i])
                                                <td>{{$item->amount}}</td>
                                            @endif
                                        @elseif($e == $h)
                                            <td>0</td>
                                            <?php $h = -1 ?>
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
                            <td style="background-color: orange"><b>Total Expense</b></td>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                            @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                            <td style="background-color: orange">{{$data[$perbulan[$e]][1]->sum('amount')}}</td>
                            @endif
                            @endfor
                        </tr>
                        <tr>
                            <th><b>Net income</b></th>
                            @for ($e = 0; $e < count($perbulan); $e++) 
                                @if ($data[$perbulan[$e]][0]->sum('amount') > 1 | $data[$perbulan[$e]][1]->sum('amount') > 1)
                                    <?php
                                        $netincome = $data[$perbulan[$e]][0]->sum('amount') - $data[$perbulan[$e]][1]->sum('amount')
                                    ?>
                                    <th @if($netincome < 0) style="color: red" @endif><b>{{$netincome}}</b></th>
                                @endif
                            @endfor
                        </tr>
                </table>
            </div>
            <div class="col-12 text-center my-4">
                @if($jmldata < 1) <h4>No data available</h4> @endif
            </div>
        </div>
    </body>
</html>


