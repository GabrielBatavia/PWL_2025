@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Detail Kategori' }}</h3>
  </div>
  <div class="card-body">
    @if(empty($kategori))
      <div class="alert alert-danger">Data tidak ditemukan.</div>
    @else
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <td>{{ $kategori->kategori_id }}</td>
        </tr>
        <tr>
          <th>Kode Kategori</th>
          <td>{{ $kategori->kategori_kode }}</td>
        </tr>
        <tr>
          <th>Nama Kategori</th>
          <td>{{ $kategori->kategori_nama }}</td>
        </tr>
        <tr>
          <th>Dibuat Pada</th>
          <td>{{ $kategori->created_at }}</td>
        </tr>
        <tr>
          <th>Diupdate Pada</th>
          <td>{{ $kategori->updated_at }}</td>
        </tr>
      </table>
    @endif
    <a href="{{ url('kategori') }}" class="btn btn-secondary btn-sm mt-2">Kembali</a>
  </div>
</div>
@endsection
