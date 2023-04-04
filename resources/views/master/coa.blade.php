@extends('layouts.app')

@section('content')
            <div class="container py-3">
                <div class="title mb-4 text-center">
                    <h3 class="fw-bold">Chart Of Account</h3>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-modal">Tambah Account</button>
                <div class="card my-3">
                    <div class="card-body">
                        <table class="table" id="myTable">
                            <thead>
                              <tr>
                                <th scope="col"class="text-center">Kode</th>
                                <th scope="col"class="text-center">Nama</th>
                                <th scope="col"class="text-center">Category</th>
                                <th scope="col"class="text-center">Type</th>
                                <th scope="col"class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse  ($data as $item)
                                  <tr class="text-center" id="{{'index_'.$item->id}}">
                                    <td scope="row" id="nomor_{{$item->id}}">{{ $item->kode}}</td>
                                    <td>{{ $item->nama}}</td>
                                    <td>{{ $item->category}}</td>
                                    <td>
                                      @if ($item->indicator == 1)
                                        Debit 
                                      @else 
                                        Kredit 
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
                                <td colspan="5" class="text-center">- no data available -</td>
                              </tr>
                              @endforelse
                            </tbody>
                          </table>
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
                            <input type="text" class="form-control" value="-code generated automatically-" disabled>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="add-nama" name="add-nama">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select id="add-category" class="form-select" name="add-category" placeholder="-silahkan pilih-">
                              @php
                                  $typecategory[0] = 'Credit';
                                  $typecategory[1] = 'Debit';
                              @endphp
                              @for ($f = 0; $f < 2; $f++)
                                <optgroup label="type - {{$typecategory[$f]}}">
                                  @for ($i = 0; $i < $row; $i++)
                                    @if ($category[$i]['indicator'] == $f)
                                    <option value="{{$category[$i]['id']}}">{{$category[$i]['nama']}}</option>
                                    @endif
                                  @endfor
                                </optgroup>
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
                            <input type="text" class="form-control" id='edit-kode' name="edit-kode" disabled>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit-nama" name="edit-nama">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select id="edit-category" class="form-select" name="edit-category">
                              <option disabled value="null">-pilih-</option>
                              @for($f = 0; $f < 2; $f++)
                                <optgroup label="type - {{$typecategory[$f]}}">
                                  @for($i = 0; $i < $row; $i++)
                                    @if ($category[$i]['indicator'] == $f)
                                    <option value="{{$category[$i]['id']}}">{{$category[$i]['nama']}}</option>
                                    @endif
                                  @endfor
                                </optgroup>
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
                            <a class="btn btn-danger" id="confirm-delete" onclick="deleteData()">Delete</a>
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
    let table = $('#myTable').DataTable();

    $( '#add-category' ).select2( {
      dropdownParent: $("#add-modal"),
      theme: "bootstrap-5",
      width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
      placeholder: $( this ).data( 'placeholder' ),
    } );

    $( '#edit-category' ).select2( {
      dropdownParent: $("#modal-edit"),
      theme: "bootstrap-5",
      width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
      placeholder: $( this ).data( 'placeholder' ),
    } );
  } );

  

  $('#store').click(function(e) {
        e.preventDefault();

        //define variable
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
              $('#edit-category').trigger('change');
              //open modal
              $('#modal-edit').modal('show');
          }
      });
  });

  $('#update').click(function(e) {
        e.preventDefault();

        //define variable
        let id = $('#edit-id').val();
        // let kode   = $('#edit-kode').val();
        let nama   = $('#edit-nama').val();
        let category = $('#edit-category').val();
        let token   = $("meta[name='csrf-token']").attr("content");
        
        //ajax
        $.ajax({

            url: `/coaupdate/${id}`,
            type: "PUT",
            cache: false,
            data: {
                // 'kode': kode,
                "nama": nama,
                "category_id": category,
                "_token": token
            },
            success:function(response){
                //pesan sukses
                toastr.success(response.message, 'Success');

                //data post
                let type;
                if(response.type == 0){
                  type = "Kredit";
                }else if(response.type == 1){
                  type = "Debit";
                }
                let data1 = `
                    <tr id="index_${response.id}">
                        <td class="text-center">${response.kode}</td>
                        <td class="text-center">${response.nama}</td>
                        <td class="text-center">${response.category}</td>
                        <td class="text-center">${type}</td>
                        <td class="text-center">
                          <div class="d-flex flex-row justify-content-center">
                          <button class="btn btn-sm btn-warning m-1" id="btn-edit" data-id="${response.id}"><i class="bi bi-pencil"></i></button>
                          <button class="btn btn-sm btn-danger m-1" id="btn-delete" data-id="${response.id}"><i class="bi bi-trash2"></i></button>
                          </div>
                        </td>
                    </tr>
                `;
                
                //append to post data
                $(`#index_${response.id}`).replaceWith(data1);

                //close modal
                $('#modal-edit').modal('hide');
                if(response.refresh == 1){
                  location.reload();
                }
                

            },
            error:function(error){

                // if(error.responseJSON.kode[0]) {
                //   toastr.error(error.responseJSON.kode[0], 'Error!');
                // } 
                
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
              if(response.success == true){
                toastr.success(response.message, 'Success');
                location.reload();
              }else{
                toastr.error(response.message, 'Error!');
                $('#modal-delete').modal('toggle');
              }
          }
      });
  }

</script>
@endpush
