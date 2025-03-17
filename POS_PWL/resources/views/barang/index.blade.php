@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Data Barang' }}</h3>
    <div class="card-tools">
      <a href="{{ url('barang/create') }}" class="btn btn-sm btn-primary">Tambah</a>
    </div>
  </div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-hover table-sm" id="table_barang">
      <thead>
        <tr>
          <th>No</th>
          <th>Kategori</th>
          <th>Kode</th>
          <th>Nama Barang</th>
          <th>Harga Beli</th>
          <th>Harga Jual</th>
          <th>Dibuat Pada</th>
          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
  $('#table_barang').DataTable({
    serverSide: true,
    processing: true,
    ajax: {
      url: "{{ url('barang/list') }}",
      type: "POST",
      data: { _token: "{{ csrf_token() }}" }
    },
    columns: [
      { data: "DT_RowIndex", className:"text-center", orderable:false, searchable:false },
      { 
        data: "kategori_nama",
        defaultContent: "-" // jika kategori null
      },
      { data: "barang_kode" },
      { data: "barang_nama" },
      { data: "harga_beli" },
      { data: "harga_jual" },
      {
        data: "created_at",
        render: function(d){
          return d ? new Date(d).toLocaleString() : '-';
        }
      },
      { data: "aksi", orderable:false, searchable:false },
    ]
  });
});
</script>
@endpush
