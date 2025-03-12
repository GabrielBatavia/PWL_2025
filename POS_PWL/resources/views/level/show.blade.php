@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Detail Level</h3>
  </div>
  <div class="card-body">
    @if(empty($level))
      <div class="alert alert-danger">
        Data tidak ditemukan.
      </div>
    @else
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <td>{{ $level->level_id }}</td>
        </tr>
        <tr>
          <th>Kode</th>
          <td>{{ $level->level_kode }}</td>
        </tr>
        <tr>
          <th>Nama</th>
          <td>{{ $level->level_nama }}</td>
        </tr>
      </table>
    @endif
    <a href="{{ url('level') }}" class="btn btn-secondary btn-sm mt-2">Kembali</a>
  </div>
</div>
@endsection
