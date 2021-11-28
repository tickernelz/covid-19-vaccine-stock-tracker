@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('plugins.Select2', true)

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $vaksin }}</h3>

                    <p>Jenis Vaksin</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fw fa-archive"></i>
                </div>
                <a href="{{ route('index.barang') }}" class="small-box-footer">Selebihnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($penerimaan, 0, ',' , '.') }}</h3>

                    <p>Penerimaan Bulan Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fw fa-arrow-down"></i>
                </div>
                <a href="{{ route('index.hitung_stok') }}" class="small-box-footer">Selebihnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($pengeluaran, 0, ',' , '.') }}</h3>

                    <p>Pengeluaran Bulan Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fw fa-arrow-up"></i>
                </div>
                <a href="{{ route('index.hitung_stok') }}" class="small-box-footer">Selebihnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($sisa, 0, ',' , '.') }}</h3>

                    <p>Sisa Stok Bulan Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-fw fa-boxes"></i>
                </div>
                <a href="{{ route('index.hitung_stok') }}" class="small-box-footer">Selebihnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <form method="post" action="{{ route('lihat.index.transaksi.post') }}">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-retweet"></i>
                            Transaksi
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <x-adminlte-select2 id="vaksin" name="vaksin">
                            @foreach($list_vaksin as $li)
                                <option value="{{ $li->id }}">{{ $li->nama }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
