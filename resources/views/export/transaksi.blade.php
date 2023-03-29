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

      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="myTable">
              <thead>
                <tr>
                  <th class="text-center"><b>Tanggal</b></th>
                  <th class="text-center"><b>COA Code</b></th>
                  <th class="text-center"><b>COA nama</b></th>
                  <th class="text-center"><b>category nama</b></th>
                  <th class="text-center"><b>Desc</b></th>
                  <th class="text-center"><b>Debit</b></th>
                  <th class="text-center"><b>Credit</b></th>
                </tr>
              </thead>
              <tbody>
                @forelse ($data as $item)
                <tr class="text-center" id="index_{{$item->id}}">
                  <td>{{date_format($item->created_at,"d/m/Y")}}</td>
                  <td>{{$item->kode}}</td>
                  <td>{{$item->nama_coa}}</td>
                  <td>{{$item->category}}</td>
                  <td>{{$item->desc}}</td>
                  <td>@if ($item->indicator == 1)
                    Rp.{{number_format($item->nominal)}}
                  @else
                      0
                  @endif
                  </td>
                  <td>@if ($item->indicator == 0)
                    Rp.{{number_format($item->nominal)}}
                  @else
                      0
                  @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center">- no data available -</td>
                </tr>
                @endforelse
              </tbody>
          </table>
        </div>
      </div>
  
    </body>
</html>
