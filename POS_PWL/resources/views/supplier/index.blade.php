@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Data Supplier' }}</h3>
    <div class="card-tools">
      <a href="{{ url('supplier/create') }}" class="btn btn-sm btn-primary">Tambah</a>
    </div>
  </div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-hover" id="table_supplier">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Supplier</th>
          <th>Alamat</th>
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
  $('#table_supplier').DataTable({
    serverSide: true,
    processing: true,
    ajax: {
      url: "{{ url('supplier/list') }}",
      type: "POST",
      data: { _token: "{{ csrf_token() }}" }
    },
    columns: [
      { data: "DT_RowIndex", className:"text-center", orderable:false, searchable:false },
      { data: "nama_supplier" },
      { data: "alamat" },
      { 
        data: "created_at",
        render: function(d){
          return d ? new Date(d).toLocaleString() : '-';
        }
      },
      { data: "aksi", orderable:false, searchable:false }
    ]
  });
});
</script>
@endpush
