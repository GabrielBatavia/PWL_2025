@extends('layouts.template')

@push('css')
<style>
  .table-detail th,.table-detail td{vertical-align:middle;}
  .table-detail input[readonly]{background:#f9fafb;border:none;}
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-header bg-white border-0">
    <h5 class="mb-0 fw-bold">Tambah Penjualan</h5>
  </div>
  <div class="card-body">
    <form id="form-penjualan" action="{{ url('/penjualan') }}" method="POST">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
      @csrf
      {{-- ===== Header Transaksi ===== --}}
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">Kasir / User<span class="text-danger">*</span></label>
          <select name="user_id" class="form-control" required>
            <option value="">-- pilih --</option>
            @foreach($users as $u)
              <option value="{{ $u->user_id }}">{{ $u->nama }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Pembeli<span class="text-danger">*</span></label>
          <input type="text" name="pembeli" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Kode Penjualan<span class="text-danger">*</span></label>
          <input type="text" name="penjualan_kode" class="form-control" placeholder="PJ001" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Tanggal Penjualan<span class="text-danger">*</span></label>
          <input type="datetime-local" name="penjualan_tanggal" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
        </div>
      </div>

      {{-- ===== Detail Transaksi ===== --}}
      <div class="table-responsive">
        <table class="table table-bordered table-detail">
          <thead class="table-light">
            <tr>
              <th style="width:40%">Barang</th>
              <th style="width:15%">Jumlah</th>
              <th style="width:25%">Harga Satuan</th>
              <th style="width:20%">Stok Tersedia</th>
            </tr>
          </thead>
          <tbody id="detail-wrapper"></tbody>
        </table>
      </div>
      <button type="button" class="btn btn-sm btn-success mb-3" id="add-row"><i class="fas fa-plus"></i> Tambah Detail</button>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ url('/penjualan') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('js')
<script>
  let idx = 0;
  const opsiBarang = `@foreach($barangs as $b)<option value="{{$b->barang_id}}" data-stok="{{$b->stok_sum_stok_jumlah ?? 0}}" data-harga="{{$b->harga_jual}}">{{$b->barang_nama}}</option>@endforeach`;

  $('#add-row').on('click', () => {
      $('#detail-wrapper').append(`
        <tr>
          <td>
            <select name="details[${idx}][barang_id]" class="form-control barang-select" required>
              <option value="">-- pilih --</option>
              ${opsiBarang}
            </select>
          </td>
          <td><input type="number" name="details[${idx}][jumlah]" class="form-control jumlah-input" min="1" required></td>
          <td><input type="text" name="details[${idx}][harga]" class="form-control harga-input" readonly></td>
          <td class="stok-tersedia text-muted small">-</td>
        </tr>`);
      idx++;
  });

  $(document).on('change','.barang-select',function(){
     const opt=$(this).find(':selected');
     $(this).closest('tr').find('.harga-input').val(opt.data('harga'));
     $(this).closest('tr').find('.stok-tersedia').text('Stok: '+opt.data('stok'));
  });

  // jQuery Validate basic
  $(function(){
    $('#form-penjualan').validate({
      errorElement:'span',
      errorClass:'text-danger small',
      highlight:function(el){$(el).addClass('is-invalid');},
      unhighlight:function(el){$(el).removeClass('is-invalid');}
    });
  });
</script>
@endpush
