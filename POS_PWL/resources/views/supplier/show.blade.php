@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Detail Supplier' }}</h3>
  </div>
  <div class="card-body">
    @if(empty($supplier))
      <div class="alert alert-danger">Data tidak ditemukan.</div>
    @else
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <td>{{ $supplier->id }}</td>
        </tr>
        <tr>
          <th>Nama Supplier</th>
          <td>{{ $supplier->nama_supplier }}</td>
        </tr>
        <tr>
          <th>Alamat</th>
          <td>{{ $supplier->alamat }}</td>
        </tr>
        <tr>
          <th>Dibuat Pada</th>
          <td>{{ $supplier->created_at }}</td>
        </tr>
        <tr>
          <th>Diupdate Pada</th>
          <td>{{ $supplier->updated_at }}</td>
        </tr>
      </table>
    @endif
    <a href="{{ url('supplier') }}" class="btn btn-secondary btn-sm mt-2">Kembali</a>
  </div>
</div>
@endsection
