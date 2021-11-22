@extends('adminlte::page')

@section('title', 'List Vaksin')

@section('content_header')
    <h1>List Vaksin</h1>
@stop

@section('plugins.Datatables', true)

@php
    $heads = [
        '#',
        'Nama Vaksin',
        'Aksi',
    ];

$config = [
    'order' => [[0, 'asc']],
    'columns' => [null, null, ['orderable' => false, 'className' => 'text-center']],
];
@endphp

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Tabel
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <x-adminlte-datatable id="table" :config="$config" :heads="$heads" hoverable bordered beautify>
                @foreach($data as $li)
                    <tr>
                        <td>{!! $loop->iteration !!}</td>
                        <td>{!! $li->nama !!}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a type="button" class="btn btn-secondary"
                                   href="{{ route('edit.index.barang', $li->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a type="button" class="btn btn-secondary"
                                   href="{{ route('hapus.barang', $li->id) }}"
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

