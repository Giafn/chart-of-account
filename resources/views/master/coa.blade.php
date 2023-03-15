@extends('layouts.app')

@section('content')
            <div class="container py-3">
                <div class="title mb-4 text-center">
                    <h3 class="fw-bold">Chart Of Account</h3>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-modal">Tambah Account</button>
                <div class="card my-3">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                              <tr class="text-center">
                                <th scope="col">Kode</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Category</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse  ($data as $item)
                                  <tr class="text-center" id="{{'index_'.$item->id}}">
                                    <td scope="row" id="nomor_{{$item->id}}">{{ $item->kode}}</td>
                                    <td>{{ $item->nama}}</td>
                                    <td>{{ $item->category->nama}}</td>
                                    {{-- <td>@if($item->indicator == 1) Debit @else Kredit @endif</td> --}}
                                    <td>
                                        <button class="btn btn-sm btn-warning" id="btn-edit" data-id="{{$item->id}}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger" id="btn-delete" data-id="{{$item->id}}"><i class="bi bi-trash2"></i></button>
                                    </td>
                                  </tr>
                              @empty
                              <tr>
                                <td colspan="5" class="text-center">- no data available -</td>
                              </tr>
                              @endforelse
                            </tbody>
                          </table>
                    </div>
                    <div class="row mx-3 my-3">
                      <div class="col-md-6">
                        <p> All data {{$data->total()}} - Page {{$data->currentPage()}}</p>
                      </div>
                      <div class="col-md-6">
                        <div class="pagination float-end">
                          {{ $data->links('vendor\pagination\bootstrap-4') }}
                        </div>
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
                            <label class="form-label">Kode</label>
                            <input type="text" class="form-control" id="add-kode" name="add-kode">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="add-nama" name="add-nama">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select id="add-category" class="form-select" name="add-category">
                              <option value="null" disabled >-silahkan pilih-</option>
                              @for($i = 0; $i < $row; $i++)
                              <option value="{{$category[$i]['id']}}">{{$category[$i]['nama']}}</option>
                              @endfor
                            </select>
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
                    <h5 class="text-center">Edit Account</h5>
                    <form>
                      @csrf
                      <input type="hidden" name="id" id="edit-id">
                      <div class="row justify-content-center mb-3">
                        <div class="col-md-8">
                          <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" class="form-control" id="edit-kode" name="edit-kode">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit-nama" name="edit-nama">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select id="edit-category" class="form-select" name="edit-category">
                              <option disabled value="null">-pilih-</option>
                              @for($i = 0; $i < $row; $i++)
                              <option value="{{$category[$i]['id']}}">{{$category[$i]['nama']}}</option>
                              @endfor
                            </select>
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

  $('#store').click(function(e) {
        e.preventDefault();

        //define variable
        let addkode   = $('#add-kode').val();
        let addnama   = $('#add-nama').val();
        let addcategory   = $('#add-category').val();
        let token     = $("meta[name='csrf-token']").attr("content");
        // if(addtype == 'null')
        //ajax
        $.ajax({

            url: `/add-coa`,
            type: "POST",
            cache: false,
            data: {
                "kode": addkode,
                "nama": addnama,
                "category_id": addcategory,
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
              if(error.responseJSON.kode[0]){
                  toastr.error(error.responseJSON.kode[0], 'Error!');
                }
                if(error.responseJSON.nama[0]){
                  toastr.error(error.responseJSON.nama[0], 'Error!');
                }
                if(error.responseJSON.category_id[0]){
                  toastr.error(error.responseJSON.category_id[0], 'Error!');
                }
            }
          });
  });

  $('body').on('click', '#btn-edit', function () {
  
    let data_id = $(this).data('id');
    $.ajax({
          url: `/coa/${data_id}`,
          type: "GET",
          cache: false,
          success:function(response){
              $('#edit-id').val(response.data.id);
              $('#edit-kode').val(response.data.kode);
              $('#edit-nama').val(response.data.nama);
              $('#edit-category').val(response.data.category_id);

              //open modal
              $('#modal-edit').modal('show');
          }
      });
  });

  $('#update').click(function(e) {
        e.preventDefault();

        //define variable
        let id = $('#edit-id').val();
        let kode   = $('#edit-kode').val();
        let nama   = $('#edit-nama').val();
        let category = $('#edit-category').val();
        let token   = $("meta[name='csrf-token']").attr("content");
        
        //ajax
        $.ajax({

            url: `/coaupdate/${id}`,
            type: "PUT",
            cache: false,
            data: {
                'kode': kode,
                "nama": nama,
                "category_id": category,
                "_token": token
            },
            success:function(response){
                //pesan sukses
                toastr.success(response.message, 'Success');

                //data post
                let data1 = `
                    <tr id="index_${response.id}">
                        <td class="text-center">${response.kode}</td>
                        <td class="text-center">${response.nama}</td>
                        <td class="text-center">${response.category}</td>
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

                if(error.responseJSON.kode[0]) {
                  toastr.error(error.responseJSON.kode[0], 'Error!');
                } 
                
                if(error.responseJSON.nama[0]) {

                  toastr.error(error.responseJSON.nama[0], 'Error!');
                } 

                if(error.responseJSON.category_id[0]) {
                  toastr.error(error.responseJSON.category_id[0], 'Error!');
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
          url: `/coadelete/${id}`,
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

</script>
@endpush
