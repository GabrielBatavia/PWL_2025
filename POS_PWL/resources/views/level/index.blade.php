@extends('layouts.template') 
{{-- misal Anda punya layouts/template.blade.php --}}

@section('content')
<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Data Level</h3>
    <div class="card-tools">
      <a href="{{ url('level/create') }}" class="btn btn-sm btn-primary">Tambah</a>
    </div>
  </div>
  <div class="card-body">
    {{-- Tampilkan pesan sukses/error --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Kode</th>
          <th>Nama Level</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $key => $lvl)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $lvl->level_kode }}</td>
          <td>{{ $lvl->level_nama }}</td>
          <td>
            <a href="{{ url('level/'.$lvl->level_id) }}" class="btn btn-info btn-sm">Detail</a>
            <a href="{{ url('level/'.$lvl->level_id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ url('level/'.$lvl->level_id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?');">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4">Tidak ada data</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
