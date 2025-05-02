{{-- resources/views/penjualan/index.blade.php --}}
@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $page->title ?? 'Data Penjualan' }}</h3>
    <div class="card-tools">
      {{-- <button onclick="modalAction('{{ url('/penjualan/create') }}')" class="btn btn-info">
        <i class="fa fa-plus"></i> Tambah Penjualan
      </button> --}}
      <a href="{{ url('penjualan/export_excel') }}" class="btn btn-primary">
        <i class="fa fa-file-excel"></i> Export Excel
      </a>
      <a href="{{ url('penjualan/export_pdf') }}" class="btn btn-warning">
        <i class="fa fa-file-pdf"></i> Export PDF
      </a>
      <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-success">
        <i class="fa fa-plus"></i> Tambah Penjualan
      </button>
    </div>
  </div>

  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <div class="row mb-3">
      <div class="col-md-4">
        <label for="user_id">User</label>
        <select id="user_id" class="form-control">
          <option value="">- Semua User -</option>
          @foreach($users as $u)
            <option value="{{ $u->user_id }}">{{ $u->username }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label for="date_from">Dari Tanggal</label>
        <input type="date" id="date_from" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="date_to">Sampai Tanggal</label>
        <input type="date" id="date_to" class="form-control">
      </div>
    </div>

    <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
      <thead>
        <tr>
          <th>No</th>
          <th>User</th>
          <th>Pembeli</th>
          <th>Kode</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>

  {{-- Modal container --}}
  <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
       data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
</div>
@endsection

@push('js')
<script>
  // fungsi global untuk load content ke dalam #myModal
  function modalAction(url = '') {
    $('#myModal').load(url, function() {
      $('#myModal').modal('show');
    });
  }

  let dataPenjualan;
  $(document).ready(function() {
    // inisialisasi DataTable
    dataPenjualan = $('#table_penjualan').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('penjualan/list') }}",
        type: "POST",
        data: function(d) {
          d.user_id    = $('#user_id').val();
          d.date_from  = $('#date_from').val();
          d.date_to    = $('#date_to').val();
        }
      },
      columns: [
        { data: "DT_RowIndex",         className: "text-center", orderable: false, searchable: false },
        { data: "user.username",       orderable: true, searchable: true },
        { data: "pembeli",             orderable: true, searchable: true },
        { data: "penjualan_kode",      orderable: true, searchable: true },
        { data: "penjualan_tanggal",   orderable: true, searchable: true },
        { data: "aksi",                orderable: false, searchable: false }
      ]
    });

    // reload jika filter berubah
    $('#user_id, #date_from, #date_to').on('change', function() {
      dataPenjualan.ajax.reload();
    });

    // cari on enter
    $('#table_penjualan_filter input')
      .unbind()
      .bind('keyup', function(e) {
        if (e.keyCode === 13) {
          dataPenjualan.search(this.value).draw();
        }
      });
  });
</script>
@endpush
