@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Tambah Supplier' }}</h3>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ url('supplier') }}">
      @csrf
      <div class="form-group">
        <label>Nama Supplier</label>
        <input type="text" name="nama_supplier" class="form-control" required
               value="{{ old('nama_supplier') }}">
        @error('nama_supplier')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
      <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
        @error('alamat')
          <small class="text-danger">{{ $message }}</small>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
      <a href="{{ url('supplier') }}" class="btn btn-secondary btn-sm">Batal</a>
    </form>
  </div>
</div>
@endsection
