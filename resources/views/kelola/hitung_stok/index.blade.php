@extends('adminlte::page')

@section('title', 'Hitung Stok Vaksin')

@section('content_header')
    <h1>Hitung Stok Vaksin</h1>
@stop

@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.TempusDominus', true)

@php
    $config_stok = [
                "placeholder" => "Pilih Bulan...",
                "allowClear" => true,
            ];
@endphp

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Hitung Stok
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form method="post" action="{{ route('hitung_stok.semua') }}">
                @csrf
                <x-adminlte-select2 id="hitung_stok" name="hitung_stok[]"
                                    :config="$config_stok" multiple>
                    @if($daftar_bulan)
                        @foreach($daftar_bulan as $li)
                            <option value="{{ $li }}">{{ $li }}</option>
                        @endforeach
                    @endif
                </x-adminlte-select2>
                @if (Session::has('total'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5>Total Stok :</h5>
                        {{ Session::get('total') }}
                    </div>
                @endif
                <button type="submit" class="btn btn-primary">Hitung</button>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
@stop

