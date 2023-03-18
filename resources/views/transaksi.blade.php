@extends('layouts.app')

@section('content')
            <div class="container py-3">
                <div class="title mb-4 text-center">
                    <h3 class="fw-bold">Transaksi</h3>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-modal">Tambah Transaksi</button>
                <div class="card my-3">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead>
                              <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">COA Code</th>
                                <th scope="col">COA nama</th>
                                <th scope="col">Desc</th>
                                <th scope="col">Debit</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse ($data as $item)
                              <tr class="text-center" id="index_{{$item->id}}">
                                <td>{{date_format($item->created_at,"d/m/Y")}}</td>
                                <td>{{$item->coa->kode}}</td>
                                <td>{{$item->coa->nama}}</td>
                                <td>{{$item->desc}}</td>
                                <td>@if ($item->coa->category->indicator == 1)
                                  Rp.{{number_format($item->nominal)}}
                                @else
                                    0
                                @endif
                                </td>
                                <td>@if ($item->coa->category->indicator == 0)
                                  Rp.{{number_format($item->nominal)}}
                                @else
                                    0
                                @endif
                                </td>
                                <td>
                                  <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-warning m-1" id="btn-edit" data-id="{{$item->id}}"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger m-1" id="btn-delete" data-id="{{$item->id}}"><i class="bi bi-trash2"></i></button>
                                  </div>
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
                </div>
            </div>     
            
            

            {{-- modal-add --}}
            <div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="add-modalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <h5 class="text-center">Tambah Account</h5>
                    <form>
                      @csrf
                      <div class="row justify-content-center mb-3">
                        <div class="col-md-8">
                          <div class="mb-3">
                            <label class="form-label">Nama Account</label>
                            <select id="add-coa_id" class="form-select" name="add-coa_id">
                              <option value="null" disabled >-silahkan pilih-</option>
                              @for($i = 0; $i < $row; $i++)
                              <option value="{{$coa[$i]['id']}}">{{$coa[$i]['nama']}}</option>
                              @endfor
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Desc</label>
                            <input type="text" class="form-control" id="add-desc" name="add-desc">
                          </div>
                          <div class="input-group mb-3">
                            <span class="input-group-text" id="nominal-group">Rp</span>
                            <input type="number" class="form-control" placeholder="Tulis nominal" aria-describedby="nominal-group" id="add-nominal" name="add-nominal">
                          </div>
                          <button type="submit" class="btn btn-primary" id="store">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            {{-- modal-edit --}}
            <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="add-modalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <h5 class="text-center">Tambah Account</h5>
                    <form>
                      <input type="hidden" name="id" id='edit-id'>
                      @csrf
                      <div class="row justify-content-center mb-3">
                        <div class="col-md-8">
                          <div class="mb-3">
                            <label class="form-label">Nama Account</label>
                            <select id="edit-coa_id" class="form-select" name="edit-coa_id">
                              <option value="null" disabled >-silahkan pilih-</option>
                              @for($i = 0; $i < $row; $i++)
                              <option value="{{$coa[$i]['id']}}">{{$coa[$i]['nama']}}</option>
                              @endfor
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Desc</label>
                            <input type="text" class="form-control" id="edit-desc" name="edit-desc">
                          </div>
                          <div class="input-group mb-3">
                            <span class="input-group-text" id="nominal-group">Rp</span>
                            <input type="number" class="form-control" placeholder="Tulis nominal" aria-describedby="nominal-group" id="edit-nominal" name="edit-nominal">
                          </div>
                          <button type="submit" class="btn btn-primary" id="update">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            {{-- modal-delete --}}
            <div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="add-modalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                  <div class="modal-body">
                    <h5 class="text-center">Confirm Delete?</h5>
                      <div class="row justify-content-center mb-3">
                        <div class="col-6 text-end">
                          <form>
                            @csrf
                            <input type="hidden" name="delete-id" id="delete-id">
                            <button class="btn btn-danger" id="confirm-delete" onclick="deleteData()">Delete</button>
                          </form>
                        </div>
                        <div class="col-6 text-start">
                          <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</a>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
@endsection

@push('js')
    <script>
    $(document).ready( function () {
        $('#myTable').DataTable({
          "ordering": false
        });
    });

    $('#store').click(function(e) {
            e.preventDefault();


            //define variable
            let addcoa_id   = $('#add-coa_id').val();
            let adddesc   = $('#add-desc').val();
            let addnominal   = $('#add-nominal').val();
            let token     = $("meta[name='csrf-token']").attr("content");
            
            // console.log(category_id);
            //ajax
            $.ajax({

                url: `/add-transaksi`,
                type: "POST",
                cache: false,
                data: {
                    "coa_id": addcoa_id,
                    "desc": adddesc,
                    "nominal": addnominal,
                    "_token": token
                },
                success:function(response){

                    toastr.success(response.message, 'Success');

                    //close modal
                    $('#add-modal').modal('hide');
                    //data baru
                    location.reload();
                    

                },
                error:function(error){
                  if(error.responseJSON.coa_id){
                      toastr.error(error.responseJSON.coa_id[0], 'Error!');
                  }
                  if(error.responseJSON.desc){
                      toastr.error(error.responseJSON.desc[0], 'Error!');
                    }
                  if(error.responseJSON.nominal){
                    toastr.error(error.responseJSON.nominal[0], 'Error!');
                  }
                    
                }
            });
    });


    $('body').on('click', '#btn-edit', function () {
  
      let data_id = $(this).data('id');
      $.ajax({
            url: `/transaksi/${data_id}`,
            type: "GET",
            cache: false,
            success:function(response){
                $('#edit-id').val(response.data.id);
                $('#edit-coa_id').val(response.data.coa_id);
                $('#edit-desc').val(response.data.desc);
                $('#edit-nominal').val(response.data.nominal);

                //open modal
                $('#modal-edit').modal('show');
            }
        });
    });

    $('#update').click(function(e) {
          e.preventDefault();

          //define variable
          let id = $('#edit-id').val();
          let coa_id = $('#edit-coa_id').val();
          let desc   = $('#edit-desc').val();
          let nominal   = $('#edit-nominal').val();
          let token   = $("meta[name='csrf-token']").attr("content");
          
          //ajax
          $.ajax({

              url: `/transaksiupdate/${id}`,
              type: "PUT",
              cache: false,
              data: {
                  'coa_id': coa_id,
                  "desc": desc,
                  "nominal": nominal,
                  "_token": token
              },
              success:function(response){
                  //pesan sukses
                  toastr.success(response.message, 'Success');


                  //cek jenis
                  let debit;
                  let credit;
                  if(response.category == 1){
                    debit = 'Rp.' + uangFormat(response.nominal);
                    credit = 0;
                  }
                  if(response.category == 0){
                    debit = 0;
                    credit = 'Rp.' + uangFormat(response.nominal);
                  }
                  //data ganti
                  let data1 = `
                      <tr id="index_${response.id}">
                          <td class="text-center">${response.tanggal}</td>
                          <td class="text-center">${response.kode}</td>
                          <td class="text-center">${response.nama}</td>
                          <td class="text-center">${response.desc}</td>
                          <td class="text-center">${debit}</td>
                          <td class="text-center">${credit}</td>
                          <td class="text-center">
                            <button class="btn btn-sm btn-warning" id="btn-edit" data-id="${response.id}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger" id="btn-delete" data-id="${response.id}"><i class="bi bi-trash2"></i></button>
                          </td>
                      </tr>
                  `;
                  
                  //append to post data
                  $(`#index_${response.id}`).replaceWith(data1);

                  //close modal
                  $('#modal-edit').modal('hide');
                  

              },
              error:function(error){
                  if(error.responseJSON.coa_id){
                      toastr.error(error.responseJSON.coa_id[0], 'Error!');
                  }
                  if(error.responseJSON.desc){
                      toastr.error(error.responseJSON.desc[0], 'Error!');
                    }
                  if(error.responseJSON.nominal){
                    toastr.error(error.responseJSON.nominal[0], 'Error!');
                  }
                    
                }

          });

    });

    $('body').on('click', '#btn-delete', function() {

      let id = $(this).data('id');

      $('#delete-id').val(id);
      $('#modal-delete').modal('show');

    });

    function deleteData(){
      let id = $('#delete-id').val();
      let token   = $("meta[name='csrf-token']").attr("content");

      $.ajax({
            url: `/transaksidelete/${id}`,
            type: "DELETE",
            cache: false,
            data: {
                "_token": token
            },
            success:function(response){ 
                //notifikasi
                toastr.success(response.message, 'Success');
                //refresh data on table
                location.reload();
            }
        });
    }

    // buat angka bikin biar jadi format
    function uangFormat(val) {
      var sign = 1;
      if (val < 0) {
        sign = -1;
        val = -val;
      }

      // trim the number decimal point if it exists
      let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();

      while (/(\d+)(\d{3})/.test(num.toString())) {
        // insert comma to 4th last position to the match number
        num = num.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
      }

      // add number after decimal point
      if (val.toString().includes('.')) {
        num = num + '.' + val.toString().split('.')[1];
      }

      // return result with - sign if negative
      return sign < 0 ? '-' + num : num;
    }

    </script>
@endpush
