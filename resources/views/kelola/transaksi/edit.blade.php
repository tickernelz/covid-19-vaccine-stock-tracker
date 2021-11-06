@extends('adminlte::page')

@section('title', 'Kelola Transaksi')

@section('content_header')
    <h1>Kelola Transaksi</h1>
@stop

@section('plugins.Select2', true)
@section('plugins.TempusDominus', true)

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Detail Vaksin
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <p class="lead">Vaksin {{ $barang->nama }}</p>

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Kemasan:</th>
                        <td>{{ $barang->kemasan }}</td>
                    </tr>
                    <tr>
                        <th>No Batch:</th>
                        <td>{{ $barang->batch }}</td>
                    </tr>
                    <tr>
                        <th>Expired Date:</th>
                        <td>{{ $barang->ed }}</td>
                    </tr>
                    <tr>
                        <th>Total Stok:</th>
                        <td>{{ $total }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="col-md-6" style="float:none;margin:auto;">
        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Form Edit</h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <a href="{{ route('index.transaksi') }}">
                            <button type="button" class="btn btn-primary">Kembali</button>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url()->current()}}/post" method="post">
                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Success!</h5>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if (session('errors'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <x-adminlte-input-date value="{{ $tanggal }}" name="tanggal" :config="$conf_tgl"
                                           placeholder="Masukkan Tanggal..."
                                           label="Tanggal*">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input value="{{ $transaksi->dokumen }}" name="dokumen" label="Dokumen" placeholder="Masukkan Dokumen..."/>
                    <x-adminlte-input value="{{ $transaksi->dari }}" name="dari" label="Dari" placeholder="Masukkan Dari..."/>
                    <x-adminlte-input value="{{ $transaksi->kepada }}" name="kepada" label="Kepada" placeholder="Masukkan Kepada..."/>
                    <x-adminlte-input value="{{ $transaksi->penerimaan }}" name="penerimaan" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
                    <x-adminlte-input value="{{ $transaksi->pengeluaran }}" name="pengeluaran" type="number" label="Pengeluaran"
                                      placeholder="Masukkan Pengeluaran..."/>
                    <x-adminlte-input value="{{ $transaksi->petugas }}" name="petugas" label="Petugas" placeholder="Masukkan Petugas..."/>
                    <x-adminlte-input value="{{ $transaksi->penerima }}" name="penerima" label="Penerima" placeholder="Masukkan Penerima..."/>
                    <x-adminlte-input value="{{ $transaksi->hp }}" name="hp" type="number" label="No. HP" placeholder="Masukkan No.HP..."/>
                    <x-adminlte-input value="{{ $transaksi->keterangan }}" name="keterangan" label="Keterangan" placeholder="Masukkan Keterangan..."/>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
@stop

