@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Data Kategori' }}</h3>
    <div class="card-tools">
      <a href="{{ url('kategori/create') }}" class="btn btn-sm btn-primary">Tambah</a>
    </div>
  </div>
  <div class="card-body">

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover table-sm" id="tabel_kategori">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama Kategori</th>
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
    $('#tabel_kategori').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('kategori/list') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}" }
      },
      columns: [
        { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
        { data: "kategori_kode" },
        { data: "kategori_nama" },
        { data: "created_at", 
          render: function(d){
            return d ? new Date(d).toLocaleString() : '-';
          }
        },
        { data: "aksi", orderable: false, searchable: false },
      ]
    });
  });
</script>
@endpush
