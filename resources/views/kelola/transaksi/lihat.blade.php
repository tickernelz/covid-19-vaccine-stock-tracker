@extends('adminlte::page')

@section('title', 'Kelola Transaksi')

@section('content_header')
    <h1>Kelola Transaksi</h1>
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
                Bulan Masuk Vaksin
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('lihat.cari.transaksi', [$barang->id]) }}">
                <x-adminlte-input-date value="{{ $tanggal ?? '' }}" name="tanggal" :config="$conf_bulan_tahun">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-dark">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Hitung Stok
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form method="post" action="{{ route('lihat.hitung_stok', [$barang->id]) }}">
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

    @if(Request::has('tanggal'))
        <div class="card card-default">
            <form method="post" action="{{ route('lihat.cari.detail_vaksin.transaksi', [$barang->id, $tanggal]) }}">
                @csrf
                <x-adminlte-input value="{{ $detail_vaksin->id ?? '' }}" name="id" hidden=""/>
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Detail Vaksin</h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </li>
                    </ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i> Success!</h5>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <p class="lead">Vaksin {{ $barang->nama ?? ''  }}</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Kemasan:</th>
                                <td>
                                    <x-adminlte-input value="{{ $detail_vaksin->kemasan ?? '' }}" name="kemasan"/>
                                </td>
                            </tr>
                            <tr>
                                <th>No Batch:</th>
                                <td>
                                    <x-adminlte-input value="{{ $detail_vaksin->batch ?? '' }}" name="batch"/>
                                </td>
                            </tr>
                            <tr>
                                <th>Expired Date:</th>
                                <td>
                                    <x-adminlte-input-date value="{{ $detail_vaksin->ed ?? '' }}" name="ed"
                                                           :config="$conf_tgl">
                                        <x-slot name="appendSlot">
                                            <div class="input-group-text bg-dark">
                                                <i class="fas fa-calendar-day"></i>
                                            </div>
                                        </x-slot>
                                    </x-adminlte-input-date>
                                </td>
                            </tr>
                            <tr>
                                <th>Total Stok:</th>
                                <td>{{ $total ?? ''  }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>

        @if($detail_vaksin)
            <form method="post" action="{{ route('lihat.tambah.transaksi', [$detail_vaksin->id]) }}">
                @csrf
                <x-adminlte-modal id="tambah-transaksi" title="Tambah Transaksi"
                                  icon="fas fa-plus" size='lg'>
                    <x-slot name="footerSlot">
                        <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Simpan"/>
                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"/>
                    </x-slot>
                    <x-adminlte-input-date name="tanggal-transaksi" :config="$conf_tgl"
                                           placeholder="Masukkan Tanggal..."
                                           label="Tanggal*" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input name="dokumen" label="Dokumen" placeholder="Masukkan Dokumen..."/>
                    <x-adminlte-input name="dari" label="Dari" placeholder="Masukkan Dari..."/>
                    <x-adminlte-input name="kepada" label="Kepada" placeholder="Masukkan Kepada..."/>
                    <x-adminlte-input name="penerimaan" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
                    <x-adminlte-input name="pengeluaran" type="number" label="Pengeluaran"
                                      placeholder="Masukkan Pengeluaran..."/>
                    <x-adminlte-input name="petugas" label="Petugas" placeholder="Masukkan Petugas..."/>
                    <x-adminlte-input name="penerima" label="Penerima" placeholder="Masukkan Penerima..."/>
                    <x-adminlte-input name="hp" type="number" label="No. HP" placeholder="Masukkan No.HP..."/>
                    <x-adminlte-input name="keterangan" label="Keterangan" placeholder="Masukkan Keterangan..."/>
                </x-adminlte-modal>
            </form>

            <form method="post" action="{{ route('lihat.edit.transaksi') }}">
                @csrf
                <x-adminlte-modal id="edit-transaksi" title="Edit Transaksi"
                                  icon="fas fa-plus" size='lg'>
                    <x-slot name="footerSlot">
                        <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Simpan"/>
                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"/>
                    </x-slot>
                    <x-adminlte-input name="id-edit" label="ID" readonly=""/>
                    <x-adminlte-input-date name="tanggal-transaksi-edit" :config="$conf_tgl"
                                           placeholder="Masukkan Tanggal..."
                                           label="Tanggal*" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input name="dokumen-edit" label="Dokumen" placeholder="Masukkan Dokumen..."/>
                    <x-adminlte-input name="dari-edit" label="Dari" placeholder="Masukkan Dari..."/>
                    <x-adminlte-input name="kepada-edit" label="Kepada" placeholder="Masukkan Kepada..."/>
                    <x-adminlte-input name="penerimaan-edit" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
                    <x-adminlte-input name="pengeluaran-edit" type="number" label="Pengeluaran"
                                      placeholder="Masukkan Pengeluaran..."/>
                    <x-adminlte-input name="petugas-edit" label="Petugas" placeholder="Masukkan Petugas..."/>
                    <x-adminlte-input name="penerima-edit" label="Penerima" placeholder="Masukkan Penerima..."/>
                    <x-adminlte-input name="hp-edit" type="number" label="No. HP" placeholder="Masukkan No.HP..."/>
                    <x-adminlte-input name="keterangan-edit" label="Keterangan" placeholder="Masukkan Keterangan..."/>
                </x-adminlte-modal>
            </form>
        @endif

        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Tabel Transaksi</h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <x-adminlte-button label="Tambah Transaksi" data-toggle="modal" data-target="#tambah-transaksi"
                                           class="bg-blue"/>
                    </li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (Session::has('success-transaksi'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ Session::get('success-transaksi') }}
                    </div>
                @endif
                <table id="table-transaksi" class="table table-bordered table-hover dataTable no-footer"
                       role="grid">
                    <thead>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Dokumen</th>
                    <th>Dari</th>
                    <th>Kepada</th>
                    <th>Penerimaan</th>
                    <th>Pengeluaran</th>
                    <th>Petugas</th>
                    <th>Penerima</th>
                    <th>No. HP</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                    </thead>
                    <tbody>
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
                                       onclick="editFunc({{ $li->id }})">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a type="button" class="btn btn-secondary"
                                       href="{{ route('hapus.transaksi', $li->id) }}"
                                       onclick="return confirm('Yakin Mau Dihapus?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    @endif
@stop

@push('js')
    <script>
        $(document).ready(function () {
            $('#table-transaksi').DataTable({
                "order": [[0, "asc"]],
                "columns": [null, null, null, null, null, null, null, null, null, null, null, {
                    "orderable": false,
                    "className": "text-center"
                }],
                "dom": "\u003C\u0022row\u0022 \u003C\u0022col-sm-6\u0022 B\u003E \u003C\u0022col-sm-6\u0022 f\u003E \u003E\n                \u003C\u0022row\u0022 \u003C\u0022col-12\u0022 tr\u003E \u003E\n                \u003C\u0022row\u0022 \u003C\u0022col-sm-5\u0022 i\u003E \u003C\u0022col-sm-7\u0022 p\u003E \u003E",
                "buttons": {
                    "dom": {"button": {"className": "btn"}},
                    "buttons": [{"extend": "pageLength", "className": "btn-default"}, {
                        "extend": "print",
                        "className": "btn-default",
                        "title": "Laporan Transaksi Vaksin {{ $barang->nama ?? '' }}",
                        "messageTop": "Bulan {{ $tanggal ?? ''  }}",
                        "text": "\u003Ci class=\u0022fas fa-fw fa-lg fa-print\u0022\u003E\u003C\/i\u003E",
                        "titleAttr": "Print",
                        "exportOptions": {"columns": ":not(:last-child)"}
                    }, {
                        "extend": "csv",
                        "className": "btn-default",
                        "title": "Laporan Transaksi Vaksin {{ $barang->nama ?? '' }}",
                        "messageTop": "Bulan {{ $tanggal ?? ''  }}",
                        "text": "\u003Ci class=\u0022fas fa-fw fa-lg fa-file-csv text-primary\u0022\u003E\u003C\/i\u003E",
                        "titleAttr": "Export to CSV",
                        "exportOptions": {"columns": ":not(:last-child)"}
                    }, {
                        "extend": "excel",
                        "className": "btn-default",
                        "title": "Laporan Transaksi Vaksin {{ $barang->nama ?? '' }}",
                        "messageTop": "Bulan {{ $tanggal ?? ''  }}",
                        "text": "\u003Ci class=\u0022fas fa-fw fa-lg fa-file-excel text-success\u0022\u003E\u003C\/i\u003E",
                        "titleAttr": "Export to Excel",
                        "exportOptions": {"columns": ":not(:last-child)"}
                    }, {
                        "extend": "pdf",
                        "className": "btn-default",
                        "orientation": 'landscape',
                        "title": "Laporan Transaksi Vaksin {{ $barang->nama ?? '' }}",
                        "messageTop": "Bulan {{ $tanggal ?? ''  }}",
                        "text": "\u003Ci class=\u0022fas fa-fw fa-lg fa-file-pdf text-danger\u0022\u003E\u003C\/i\u003E",
                        "titleAttr": "Export to PDF",
                        "exportOptions": {"columns": ":not(:last-child)"}
                    }]
                }
            });
        });

        function editFunc(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('lihat.get_transaksi') }}",
                dataType: "JSON",
                data: {id: id},
                success: function (response) {
                    $('#edit-transaksi').modal('show');
                    $('[name="tanggal-transaksi-edit"]').val(response.tanggal);
                    $('[name="id-edit"]').val(response.data['id']);
                    $('[name="dokumen-edit"]').val(response.data['dokumen']);
                    $('[name="dari-edit"]').val(response.data['dari']);
                    $('[name="kepada-edit"]').val(response.data['kepada']);
                    $('[name="penerimaan-edit"]').val(response.data['penerimaan']);
                    $('[name="pengeluaran-edit"]').val(response.data['pengeluaran']);
                    $('[name="petugas-edit"]').val(response.data['petugas']);
                    $('[name="penerima-edit"]').val(response.data['penerima']);
                    $('[name="hp-edit"]').val(response.data['hp']);
                    $('[name="keterangan-edit"]').val(response.data['keterangan']);
                }
            });
            return false;
        }
    </script>
@endpush
