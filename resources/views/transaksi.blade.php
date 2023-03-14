@extends('layouts.app')

@section('content')
            <div class="container py-3">
                <div class="title mb-4 text-center">
                    <h3 class="fw-bold">Transaksi</h3>
                </div>
                <button class="btn btn-primary">tambah transaksi baru</button>
                <div class="card my-3">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                              <tr class="text-center">
                                <th scope="col">#</th>
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
                              <tr class="text-center">
                                <th scope="row">1</th>
                                <td>20-4-2003</td>
                                <td>432432</td>
                                <td>Gaji Karyawan</td>
                                <td>Gaji Di perushaan</td>
                                <td>0</td>
                                <td>700000</td>
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
