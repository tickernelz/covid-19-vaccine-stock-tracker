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
function hitung_pengeluaran ($id, $tanggal, $dari)
{
    $stok_kabupaten = \App\Models\TransaksiKabupaten::where([
        ['detail_vaksin_id', '=', $id ?? null],
        ['tanggal_provinsi', '=', $tanggal ?? null],
        ['dari', '=', $dari ?? null],
        ])->get()->sum('penerimaan');
    return $stok_kabupaten;
}
@endphp

@section('content')
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
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Bulan Masuk Vaksin
            </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if (Session::has('success-ubah-tanggal'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ Session::get('success-ubah-tanggal') }}
                </div>
            @endif
            <form action="{{ route('lihat.cari.transaksi', [$barang->id]) }}">
                <x-adminlte-input value="{{ Request::get('tanggal') ?? '' }}" name="tanggal_lama" hidden=""/>
                <x-adminlte-input-date value="{{ $tanggal ?? '' }}" name="tanggal" :config="$conf_bulan_tahun">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-dark">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
                <div class="icheck-primary">
                    <input type="checkbox" name="ubah" id="ubah">
                    <label for="ubah">{{ __('Ubah Tanggal') }}</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">
                Hitung Stok Vaksin {{ $barang->nama ?? ''  }}
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
                    <hr>
                    <x-adminlte-input value="{{ $detail_vaksin->kemasan ?? '' }}" label="Kemasan" name="kemasan"/>
                    <x-adminlte-input value="{{ $detail_vaksin->batch ?? '' }}" label="No. Batch" name="batch"/>
                    <x-adminlte-input-date value="{{ $detail_vaksin->ed ?? '' }}" label="Tanggal Kadaluarsa" name="ed"
                                           :config="$conf_tgl">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input value="{{ $total ?? ''  }}" label="Total Stok" name="stok" readonly=""/>
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
                    <x-adminlte-input name="penerimaan" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
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
                    <x-adminlte-input name="penerimaan-edit" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
                    <x-adminlte-input name="petugas-edit" label="Petugas" placeholder="Masukkan Petugas..."/>
                    <x-adminlte-input name="penerima-edit" label="Penerima" placeholder="Masukkan Penerima..."/>
                    <x-adminlte-input name="hp-edit" type="number" label="No. HP" placeholder="Masukkan No.HP..."/>
                    <x-adminlte-input name="keterangan-edit" label="Keterangan" placeholder="Masukkan Keterangan..."/>
                </x-adminlte-modal>
            </form>

            <form method="post" action="{{ route('lihat.tambah.transaksi.kabupaten', [$detail_vaksin->id]) }}">
                @csrf
                <x-adminlte-modal id="tambah-transaksi-kabupaten" title="Tambah Transaksi"
                                  icon="fas fa-plus" size='lg'>
                    <x-slot name="footerSlot">
                        <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Simpan"/>
                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"/>
                    </x-slot>
                    <x-adminlte-select2 name="tanggal_provinsi" id="tanggal_provinsi-provinsi"
                                        label="Tanggal Transaksi Provinsi"
                                        data-placeholder="Pilih Tanggal...">
                        <option></option>
                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @if($provinsi_tanggal)
                            @foreach($provinsi_tanggal as $list)
                                <option
                                    value="{{ $list->tanggal }}">{{ \Carbon\Carbon::parse($list->tanggal)->formatLocalized('%d %B %Y') }}</option>
                            @endforeach
                        @endif
                    </x-adminlte-select2>
                    <x-adminlte-input-date name="tanggal-transaksi" id="tanggal-transaksi-kabupaten" :config="$conf_tgl"
                                           placeholder="Masukkan Tanggal..."
                                           label="Tanggal*" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input name="dokumen" label="Dokumen" placeholder="Masukkan Dokumen..."/>
                    <x-adminlte-select2 name="dari" id="dari-provinsi" label="Dari"
                                        data-placeholder="Pilih Sumber Vaksin...">
                        <option></option>
                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @if($provinsi_dari)
                            @foreach($provinsi_dari as $list)
                                <option
                                    value="{{ $list->dari }}">{{ $list->dari }}</option>
                            @endforeach
                        @endif
                    </x-adminlte-select2>
                    <x-adminlte-input name="kepada" label="Kepada" placeholder="Masukkan Kepada..."/>
                    <x-adminlte-input name="penerimaan" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
                    <x-adminlte-input name="petugas" label="Petugas" placeholder="Masukkan Petugas..."/>
                    <x-adminlte-input name="penerima" label="Penerima" placeholder="Masukkan Penerima..."/>
                    <x-adminlte-input name="hp" type="number" label="No. HP" placeholder="Masukkan No.HP..."/>
                    <x-adminlte-input name="keterangan" label="Keterangan" placeholder="Masukkan Keterangan..."/>
                </x-adminlte-modal>
            </form>

            <form method="post" action="{{ route('lihat.edit.transaksi.kabupaten') }}">
                @csrf
                <x-adminlte-modal id="edit-transaksi-kabupaten" title="Edit Transaksi"
                                  icon="fas fa-plus" size='lg'>
                    <x-slot name="footerSlot">
                        <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Simpan"/>
                        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal"/>
                    </x-slot>
                    <x-adminlte-input name="id-kabupaten-edit" label="ID" readonly=""/>
                    <x-adminlte-select2 name="tanggal_provinsi-edit" id="tanggal_provinsi-provinsi-edit"
                                        label="Tanggal Transaksi Provinsi"
                                        data-placeholder="Pilih Tanggal...">
                        <option></option>
                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @if($provinsi_tanggal)
                            @foreach($provinsi_tanggal as $list)
                                <option
                                    value="{{ $list->tanggal }}">{{ \Carbon\Carbon::parse($list->tanggal)->formatLocalized('%d %B %Y') }}</option>
                            @endforeach
                        @endif
                    </x-adminlte-select2>
                    <x-adminlte-input-date name="tanggal-transaksi-edit" id="tanggal-transaksi-kabupaten-edit"
                                           :config="$conf_tgl"
                                           placeholder="Masukkan Tanggal..."
                                           label="Tanggal*" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input name="dokumen-kabupaten-edit" label="Dokumen" placeholder="Masukkan Dokumen..."/>
                    <x-adminlte-select2 name="dari-kabupaten-edit" id="dari-provinsi-edit" label="Dari"
                                        data-placeholder="Pilih Sumber Vaksin...">
                        <option></option>
                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        @if($provinsi_dari)
                            @foreach($provinsi_dari as $list)
                                <option
                                    value="{{ $list->dari }}">{{ $list->dari }}</option>
                            @endforeach
                        @endif
                    </x-adminlte-select2>
                    <x-adminlte-input name="kepada-kabupaten-edit" label="Kepada" placeholder="Masukkan Kepada..."/>
                    <x-adminlte-input name="penerimaan-kabupaten-edit" type="number" label="Penerimaan"
                                      placeholder="Masukkan Penerimaan..."/>
                    <x-adminlte-input name="petugas-kabupaten-edit" label="Petugas" placeholder="Masukkan Petugas..."/>
                    <x-adminlte-input name="penerima-kabupaten-edit" label="Penerima"
                                      placeholder="Masukkan Penerima..."/>
                    <x-adminlte-input name="hp-kabupaten-edit" type="number" label="No. HP"
                                      placeholder="Masukkan No.HP..."/>
                    <x-adminlte-input name="keterangan-kabupaten-edit" label="Keterangan"
                                      placeholder="Masukkan Keterangan..."/>
                </x-adminlte-modal>
            </form>
        @endif

        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Tabel Transaksi Provinsi</h3>
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
                <div class="table-responsive">
                    <table id="table-transaksi" class="table table-bordered table-hover dataTable no-footer"
                           role="grid">
                        <thead>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Dokumen</th>
                        <th>Alokasi Pusat</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        <th>Sisa Stok</th>
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
                                <td>{!! $li->penerimaan !!}</td>
                                <td>{!! hitung_pengeluaran($detail_vaksin->id, $li->tanggal, $li->dari)!!}</td>
                                <td>{!! $li->penerimaan - hitung_pengeluaran($detail_vaksin->id, $li->tanggal, $li->dari)!!}</td>
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
                                           href="{{ route('hapus.transaksi', [$detail_vaksin->id, $li->id]) }}"
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
            </div>
            <!-- /.card-body -->
        </div>

        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Tabel Transaksi Kabupaten</h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <x-adminlte-button label="Tambah Transaksi" data-toggle="modal"
                                           data-target="#tambah-transaksi-kabupaten"
                                           class="bg-blue"/>
                    </li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (Session::has('success-transaksi-kabupaten'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ Session::get('success-transaksi-kabupaten') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="table-transaksi-kabupaten"
                           class="table table-bordered table-hover dataTable no-footer"
                           role="grid">
                        <thead>
                        <th>#</th>
                        <th>Tanggal Transaksi Provinsi</th>
                        <th>Tanggal</th>
                        <th>Dokumen</th>
                        <th>Dari</th>
                        <th>Kepada</th>
                        <th>Penerimaan</th>
                        <th>Petugas</th>
                        <th>Penerima</th>
                        <th>No. HP</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                        </thead>
                        <tbody>
                        @foreach($transaksi_kabupaten as $li)
                            <tr>
                                <td>{!! $loop->iteration !!}</td>
                                <td>{!! \Carbon\Carbon::parse($li->tanggal_provinsi)->formatLocalized('%d %B %Y'); !!}</td>
                                <td>{!! \Carbon\Carbon::parse($li->tanggal)->formatLocalized('%d %B %Y'); !!}</td>
                                <td>{!! $li->dokumen !!}</td>
                                <td>{!! $li->dari !!}</td>
                                <td>{!! $li->kepada !!}</td>
                                <td>{!! $li->penerimaan !!}</td>
                                <td>{!! $li->petugas !!}</td>
                                <td>{!! $li->penerima !!}</td>
                                <td>{!! $li->hp !!}</td>
                                <td>{!! $li->keterangan !!}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a type="button" class="btn btn-secondary"
                                           onclick="editKabFunc({{ $li->id }})">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a type="button" class="btn btn-secondary"
                                           href="{{ route('hapus.transaksi.kabupaten', $li->id) }}"
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
            </div>
            <!-- /.card-body -->
        </div>
    @endif
@stop

@push('css')
    <style>
        .select2-container {
            width: 100% !important;
            padding: 0;
        }
    </style>
@endpush

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
                        "extend": "excel",
                        "className": "btn-default",
                        "title": "Laporan Transaksi Vaksin {{ $barang->nama ?? '' }}",
                        "messageTop": "Bulan {{ $tanggal ?? ''  }}",
                        "text": "\u003Ci class=\u0022fas fa-fw fa-lg fa-file-excel text-success\u0022\u003E\u003C\/i\u003E",
                        "titleAttr": "Export to Excel",
                        "exportOptions": {"columns": ":not(:last-child)"}
                    }]
                }
            });
            $('#table-transaksi-kabupaten').DataTable({
                "order": [[0, "asc"]],
                "columns": [null, null, null, null, null, null, null, null, null, null, null, {
                    "orderable": false,
                    "className": "text-center"
                }],
                "dom": "\u003C\u0022row\u0022 \u003C\u0022col-sm-6\u0022 B\u003E \u003C\u0022col-sm-6\u0022 f\u003E \u003E\n                \u003C\u0022row\u0022 \u003C\u0022col-12\u0022 tr\u003E \u003E\n                \u003C\u0022row\u0022 \u003C\u0022col-sm-5\u0022 i\u003E \u003C\u0022col-sm-7\u0022 p\u003E \u003E",
                "buttons": {
                    "dom": {"button": {"className": "btn"}},
                    "buttons": [{"extend": "pageLength", "className": "btn-default"}, {
                        "extend": "excel",
                        "className": "btn-default",
                        "title": "Laporan Transaksi Vaksin {{ $barang->nama ?? '' }}",
                        "messageTop": "Bulan {{ $tanggal ?? ''  }}",
                        "text": "\u003Ci class=\u0022fas fa-fw fa-lg fa-file-excel text-success\u0022\u003E\u003C\/i\u003E",
                        "titleAttr": "Export to Excel",
                        "exportOptions": {"columns": ":not(:last-child)"}
                    }]
                }
            });
            $('#dari-provinsi').select2({
                dropdownParent: $('#tambah-transaksi-kabupaten')
            });
            $('#dari-provinsi-edit').select2({
                dropdownParent: $('#edit-transaksi-kabupaten')
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
                    $('[name="petugas-edit"]').val(response.data['petugas']);
                    $('[name="penerima-edit"]').val(response.data['penerima']);
                    $('[name="hp-edit"]').val(response.data['hp']);
                    $('[name="keterangan-edit"]').val(response.data['keterangan']);
                }
            });
            return false;
        }

        function editKabFunc(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('lihat.get_transaksi_kabupaten') }}",
                dataType: "JSON",
                data: {id: id},
                success: function (response) {
                    $('#edit-transaksi-kabupaten').modal('show');
                    $('[name="tanggal-transaksi-edit"]').val(response.tanggal);
                    $('[name="id-kabupaten-edit"]').val(response.data['id']);
                    $('[name="dokumen-kabupaten-edit"]').val(response.data['dokumen']);
                    $('[name="kepada-kabupaten-edit"]').val(response.data['kepada']);
                    $('[name="penerimaan-kabupaten-edit"]').val(response.data['penerimaan']);
                    $('[name="petugas-kabupaten-edit"]').val(response.data['petugas']);
                    $('[name="penerima-kabupaten-edit"]').val(response.data['penerima']);
                    $('[name="hp-kabupaten-edit"]').val(response.data['hp']);
                    $('[name="keterangan-kabupaten-edit"]').val(response.data['keterangan']);
                }
            });
            return false;
        }
    </script>
@endpush
