@extends('layouts.app')

@section('content')
            <div class="container py-3">
                <div class="title mb-4 text-center">
                    <h3 class="fw-bold">Kategori COA</h3>
                    @foreach ($data as $item)
                        {{ $item->nama}}
                    @endforeach
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-modal">tambah kategori</button>
                <div class="card my-3">
                    <div class="card-body">
                        <table class="table" id="myTable">
                            <thead>
                              <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">nama</th>
                                <th scope="col">Type +</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody id="tablebody">
                              @php $i=1 @endphp
                              @foreach ($data as $item)
                                  <tr class="text-center" id="{{'index_'.$item->id}}">
                                    <th scope="row">{{$i}}</th>
                                    <td>{{ $item->nama}}</td>
                                    <td>@if($item->indicator == 1) Debit @else Kredit @endif</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" id="btn-edit" data-id="{{$item->id}}" data-no="{{$i}}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash2"></i></button>
                                    </td>
                                  </tr>
                                  @php
                                      $i++;
                                  @endphp
                              @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
            


            <!-- Modal add-->
            <div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="add-modalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5 " id="add-modalLabel">Modal title</h1> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <h5 class="text-center">Tambah Kategori</h5>
                    <form>
                      @csrf
                      <div class="row justify-content-center mb-3">
                        <div class="col-md-8">
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="add-nama" name="add-nama">
                          </div>
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Type transaksi</label>
                            <select id="add-type" class="form-select" name="add-type">
                              <option disabled selected>-silahkan pilih-</option>
                              <option value="1">Debit</option>
                              <option value="0">Kredit</option>
                            </select>
                            <div id="emailHelp" class="form-text">Jika Jenis Account Ini bertambah apakah menambah debit atau kredit</div>
                          </div>
                          <button type="submit" class="btn btn-primary" id="store">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal edit-->
            <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="add-modalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5 " id="add-modalLabel">Modal title</h1> --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <h5 class="text-center">Edit Kategori</h5>
                    {{-- <form action="/add-category" method="POST"> --}}
                    <form>
                      @csrf
                      <input type="hidden" name="id" id="edit-id">
                      <input type="hidden" name="no" id="nomor">
                      <div class="row justify-content-center mb-3">
                        <div class="col-md-8">
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit-nama" name="add-nama">
                          </div>
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Type transaksi</label>
                            <select id="edit-type" class="form-select" name="add-type">
                              <option disabled value="null">-pilih-</option>
                              <option value="1">Debit</option>
                              <option value="0">Kredit</option>
                            </select>
                            <div id="emailHelp" class="form-text">Jika Jenis Account Ini bertambah apakah menambah debit atau kredit</div>
                          </div>
                          <button type="submit" class="btn btn-primary" id="update">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
@endsection

@push('js')
<script>

  let table = new DataTable('#myTable');

  $('#store').click(function(e) {
        e.preventDefault();

        //define variable
        let addnama   = $('#add-nama').val();
        let addtype = $('#add-type').val();
        let token   = $("meta[name='csrf-token']").attr("content");
        
        //ajax
        $.ajax({

            url: `/add-category`,
            type: "POST",
            cache: false,
            data: {
                "nama": addnama,
                "type": addtype,
                "_token": token
            },
            success:function(response){

                toastr.success(response.message, 'Success');

                //data baru
                let row = `
                    <tr id="index_${response.id}">
                        <td class="fw-bold text-center">${response.nomor}</td>
                        <td class="text-center">${response.nama}</td>
                        <td class="text-center">${response.type}</td>
                        <td class="text-center">
                          <button class="btn btn-sm btn-warning" id="btn-edit" data-id="${response.id} data-no="${response.nomor}"><i class="bi bi-pencil"></i></button>
                          <button class="btn btn-sm btn-danger"><i class="bi bi-trash2"></i></button>
                        </td>
                    </tr>
                `;
                
                //append to table
                $('#tablebody').append(row);
                
                //clear tulisan modal
                $('#add-nama').val('');
                $('#add-type').val('');

                

                //close modal
                $('#add-modal').modal('hide');
                

            },
            error:function(error){
                if(error.responseJSON.nama[0]){
                  toastr.error(error.responseJSON.nama[0], 'Error!');
                }
                if(error.responseJSON.type[0]){
                  toastr.error(error.responseJSON.type[0], 'Error!');
                }

            }
          });
  });


  $('body').on('click', '#btn-edit', function () {
  
    let data_id = $(this).data('id');
    let nomor = $(this).data('no');
    $.ajax({
          url: `/category/${data_id}`,
          type: "GET",
          cache: false,
          success:function(response){
              $('#nomor').val(nomor);
              $('#edit-id').val(response.data.id);
              $('#edit-nama').val(response.data.nama);
              $('#edit-type').val(response.data.indicator);

              //open modal
              $('#modal-edit').modal('show');
          }
      });
  });

  
  $('#update').click(function(e) {
        e.preventDefault();

        //define variable
        let nomor = $('#nomor').val();
        let id = $('#edit-id').val();
        let nama   = $('#edit-nama').val();
        let type = $('#edit-type').val();
        let token   = $("meta[name='csrf-token']").attr("content");
        
        //ajax
        $.ajax({

            url: `/categoryupdate/${id}`,
            type: "PUT",
            cache: false,
            data: {
                'nomor': nomor,
                "nama": nama,
                "type": type,
                "_token": token
            },
            success:function(response){
                //pesan sukses
                toastr.success(response.message, 'Success');

                //data post
                let data1 = `
                    <tr id="index_${response.id}">
                        <td class="fw-bold text-center">${response.nomor}</td>
                        <td class="text-center">${response.nama}</td>
                        <td class="text-center">${response.type}</td>
                        <td class="text-center">
                          <button class="btn btn-sm btn-warning" id="btn-edit" data-id="${response.id}" data-no="${response.nomor}"><i class="bi bi-pencil"></i></button>
                          <button class="btn btn-sm btn-danger"><i class="bi bi-trash2"></i></button>
                        </td>
                    </tr>
                `;
                
                //append to post data
                $(`#index_${response.id}`).replaceWith(data1);

                //close modal
                $('#modal-edit').modal('hide');
                

            },
            error:function(error){
                
                if(error.responseJSON.nama[0]) {

                  toastr.error(error.responseJSON.nama[0], 'Error!');
                } 

                if(error.responseJSON.type[0]) {
                  toastr.error(error.responseJSON.type[0], 'Error!');
                } 

            }

        });

  });

</script>
@endpush
