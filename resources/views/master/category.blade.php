@extends('layouts.app')

@section('content')
            <div class="container py-3">
                <div class="title mb-4 text-center">
                    <h3 class="fw-bold">Kategori COA</h3>
                </div>
                <button class="btn btn-primary">tambah kategori</button>
                <div class="card my-3">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                              <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">nama</th>
                                <th scope="col">Indicator</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr class="text-center">
                                <th scope="row">1</th>
                                <td>Other Income</td>
                                <td>Debit</td>
                                <td>
                                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash2"></i></button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>         
@endsection
