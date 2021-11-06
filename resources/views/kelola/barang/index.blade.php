@extends('adminlte::page')

@section('title', 'List Vaksin')

@section('content_header')
    <h1>List Vaksin</h1>
@stop

@section('plugins.Datatables', true)

@php
    $heads = [
        '#',
        'Tanggal Masuk',
        'Nama Vaksin',
        'Kemasan',
        'No Batch',
        'Expired Date',
        'Aksi',
    ];

$config = [
    'order' => [[0, 'asc']],
    'columns' => [null, null, null, null, null, null, ['orderable' => false, 'className' => 'text-center']],
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
                        <td>{!! \Carbon\Carbon::parse($li->tanggal_masuk)->formatLocalized('%d %B %Y'); !!}</td>
                        <td>{!! $li->nama !!}</td>
                        <td>{!! $li->kemasan !!}</td>
                        <td>{!! $li->batch !!}</td>
                        <td>{!! \Carbon\Carbon::parse($li->ed)->formatLocalized('%d %B %Y'); !!}</td>
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

