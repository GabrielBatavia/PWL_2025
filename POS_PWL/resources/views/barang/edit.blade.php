@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Edit Barang' }}</h3>
  </div>
  <div class="card-body">
    @if(empty($barang))
      <div class="alert alert-danger">Data tidak ditemukan.</div>
      <a href="{{ url('barang') }}" class="btn btn-secondary btn-sm">Kembali</a>
    @else
      <form action="{{ url('barang/'.$barang->barang_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori_id" class="form-control" required>
            <option value="">- Pilih Kategori -</option>
            @foreach($kategori as $kat)
              <option value="{{ $kat->kategori_id }}" 
                {{ (old('kategori_id',$barang->kategori_id) == $kat->kategori_id) ? 'selected' : '' }}>
                {{ $kat->kategori_nama }}
              </option>
            @endforeach
          </select>
          @error('kategori_id')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Kode Barang</label>
          <input type="text" name="barang_kode" class="form-control"
                 value="{{ old('barang_kode', $barang->barang_kode) }}" required>
          @error('barang_kode')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Nama Barang</label>
          <input type="text" name="barang_nama" class="form-control"
                 value="{{ old('barang_nama', $barang->barang_nama) }}" required>
          @error('barang_nama')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Harga Beli</label>
          <input type="number" name="harga_beli" class="form-control"
                 value="{{ old('harga_beli', $barang->harga_beli) }}" required>
          @error('harga_beli')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Harga Jual</label>
          <input type="number" name="harga_jual" class="form-control"
                 value="{{ old('harga_jual', $barang->harga_jual) }}" required>
          @error('harga_jual')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        <a href="{{ url('barang') }}" class="btn btn-secondary btn-sm">Batal</a>
      </form>
    @endif
  </div>
</div>
@endsection
