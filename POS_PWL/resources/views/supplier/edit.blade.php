@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">{{ $breadcrumb->title ?? 'Edit Supplier' }}</h3>
  </div>
  <div class="card-body">
    @if(empty($supplier))
      <div class="alert alert-danger">Data tidak ditemukan.</div>
      <a href="{{ url('supplier') }}" class="btn btn-secondary btn-sm">Kembali</a>
    @else
      <form method="POST" action="{{ url('supplier/'.$supplier->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>Nama Supplier</label>
          <input type="text" name="nama_supplier" class="form-control"
                 value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
          @error('nama_supplier')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Alamat</label>
          <textarea name="alamat" class="form-control" required>{{ old('alamat', $supplier->alamat) }}</textarea>
          @error('alamat')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        <a href="{{ url('supplier') }}" class="btn btn-secondary btn-sm">Batal</a>
      </form>
    @endif
  </div>
</div>
@endsection
