@extends('adminlte::page')

@section('title', 'Kelola Transaksi')

@section('content_header')
    <h1>Kelola Transaksi</h1>
@stop

@section('plugins.Datatables', true)

@php
    $heads = [
        '#',
        'Tanggal',
        'Dokumen',
        'Dari',
        'Kepada',
        'Penerimaan',
        'Pengeluaran',
        'Petugas',
        'Penerima',
        'No. HP',
        'Keterangan',
        'Aksi',
    ];

$config = [
    'order' => [[0, 'asc']],
    'columns' => [null, null, null, null, null, null, null, null, null, null, null, ['orderable' => false, 'className' => 'text-center']],
];
@endphp

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
    <div class="card card-default">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Tabel Transaksi</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item">
                    <a href="{{ url()->current() }}/tambah ">
                        <button type="button" class="btn btn-primary">Tambah Transaksi</button>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <x-adminlte-datatable id="table" :config="$config" :heads="$heads" hoverable bordered beautify>
                @foreach($transaksi as $li)
                    <tr>
                        <td>{!! $loop->iteration !!}</td>
                        <td>{!! \Carbon\Carbon::parse($li->tanggal)->formatLocalized('%d %B %Y'); !!}</td>
                        <td>{!! $li->dokumen !!}</td>
                        <td>{!! $li->dari !!}</td>
                        <td>{!! $li->kepada !!}</td>
                        <td>{!! $li->penerimaan !!}</td>
                        <td>{!! $li->pengeluaran !!}</td>
                        <td>{!! $li->petugas !!}</td>
                        <td>{!! $li->penerima !!}</td>
                        <td>{!! $li->hp !!}</td>
                        <td>{!! $li->keterangan !!}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a type="button" class="btn btn-secondary"
                                   href="{{ Request::url() }}/edit/{{$li->id}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a type="button" class="btn btn-secondary"
                                   href="{{ Request::url() }}/hapus/{{$li->id}}"
                                   onclick="return confirm('Yakin Mau Dihapus?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
        <!-- /.card-body -->
    </div>
@stop

