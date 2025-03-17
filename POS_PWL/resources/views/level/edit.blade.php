@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Edit Level</h3>
  </div>
  <div class="card-body">
    @if(empty($level))
      <div class="alert alert-danger">
        Data tidak ditemukan.
      </div>
      <a href="{{ url('level') }}" class="btn btn-secondary btn-sm">Kembali</a>
    @else
      <form action="{{ url('level/'.$level->level_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>Kode Level</label>
          <input type="text" name="level_kode" class="form-control"
                 value="{{ old('level_kode', $level->level_kode) }}" required>
          @error('level_kode')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <div class="form-group">
          <label>Nama Level</label>
          <input type="text" name="level_nama" class="form-control"
                 value="{{ old('level_nama', $level->level_nama) }}" required>
          @error('level_nama')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        <a href="{{ url('level') }}" class="btn btn-secondary btn-sm">Batal</a>
      </form>
    @endif
  </div>
</div>
@endsection
