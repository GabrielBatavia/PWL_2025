@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Detail Barang' }}</h3>
  </div>
  <div class="card-body">
    @if(empty($barang))
      <div class="alert alert-danger">Data tidak ditemukan.</div>
    @else
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <td>{{ $barang->barang_id }}</td>
        </tr>
        <tr>
          <th>Kategori</th>
          <td>{{ $barang->kategori->kategori_nama ?? '-' }}</td>
        </tr>
        <tr>
          <th>Kode Barang</th>
          <td>{{ $barang->barang_kode }}</td>
        </tr>
        <tr>
          <th>Nama Barang</th>
          <td>{{ $barang->barang_nama }}</td>
        </tr>
        <tr>
          <th>Harga Beli</th>
          <td>{{ $barang->harga_beli }}</td>
        </tr>
        <tr>
          <th>Harga Jual</th>
          <td>{{ $barang->harga_jual }}</td>
        </tr>
        <tr>
          <th>Dibuat Pada</th>
          <td>{{ $barang->created_at }}</td>
        </tr>
        <tr>
          <th>Diupdate Pada</th>
          <td>{{ $barang->updated_at }}</td>
        </tr>
      </table>
    @endif
    <a href="{{ url('barang') }}" class="btn btn-secondary btn-sm mt-2">Kembali</a>
  </div>
</div>
@endsection
