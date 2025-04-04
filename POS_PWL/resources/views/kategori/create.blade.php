@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Tambah Kategori' }}</h3>
  </div>
  <div class="card-body">
    <form action="{{ url('kategori') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Kode Kategori</label>
        <input type="text" name="kategori_kode" class="form-control" 
               value="{{ old('kategori_kode') }}" required>
        @error('kategori_kode') 
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="kategori_nama" class="form-control" 
               value="{{ old('kategori_nama') }}" required>
        @error('kategori_nama')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
      <a href="{{ url('kategori') }}" class="btn btn-secondary btn-sm">Batal</a>
    </form>
  </div>
</div>
@endsection
