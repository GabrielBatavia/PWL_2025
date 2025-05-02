{{-- resources/views/penjualan/create_ajax.blade.php --}}
<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-penjualan-ajax">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                {{-- ===== Header ===== --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Kasir / User</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">- Pilih User -</option>
                            @foreach($users as $u)
                                <option value="{{ $u->user_id }}">{{ $u->nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-user_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Pembeli</label>
                        <input type="text" name="pembeli" id="pembeli" class="form-control" required>
                        <small id="error-pembeli" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Kode Penjualan</label>
                        <input type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                        <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Tanggal</label>
                        <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal"
                               class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                {{-- ===== Detail ===== --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                        <tr>
                            <th style="width:40%">Barang</th>
                            <th style="width:15%">Jumlah</th>
                            <th style="width:25%">Harga Satuan</th>
                            <th style="width:20%">Stok</th>
                        </tr>
                        </thead>
                        <tbody id="detail-wrapper"></tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="add-row">
                    <i class="fa fa-plus"></i> Tambah Detail
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<style>
    .table td, .table th { vertical-align: middle; }
    .table input[readonly] { background:#f9fafb;border:none; }
</style>

<script>
/* ------------------------------------------------------------------
   1. Persiapan CSRF untuk semua request ajax
------------------------------------------------------------------ */
$.ajaxSetup({headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});

/* ------------------------------------------------------------------
   2. Dropdown Barang Statis
------------------------------------------------------------------ */
let idx = 0;
const opsiBarang = `@foreach($barangs as $b)
    <option value="{{ $b->barang_id }}"
            data-stok="{{ $b->stok_sum_stok_jumlah ?? 0 }}"
            data-harga="{{ $b->harga_jual }}">
        {{ $b->barang_nama }}
    </option>
@endforeach`;

/* ------------------------------------------------------------------
   3. Tombol "Tambah Detail" - delegated supaya tetap aktif
------------------------------------------------------------------ */
$(document).on('click', '#add-row', function () {
    $('#detail-wrapper').append(`
        <tr>
            <td>
                <select name="details[${idx}][barang_id]" class="form-control barang-select" required>
                    <option value="">-- pilih --</option>${opsiBarang}
                </select>
            </td>
            <td><input name="details[${idx}][jumlah]" type="number" min="1" class="form-control" required></td>
            <td><input name="details[${idx}][harga]"  type="text"  class="form-control" readonly></td>
            <td class="stok-tersedia text-muted small">-</td>
        </tr>`);
    idx++;
});

/* ------------------------------------------------------------------
   4. Saat barang diganti → isi harga & stok
------------------------------------------------------------------ */
$(document).on('change', '.barang-select', function(){
    const opt = $(this).find(':selected');
    $(this).closest('tr').find('input[readonly]').val(opt.data('harga'));
    $(this).closest('tr').find('.stok-tersedia').text('Stok: '+opt.data('stok'));
});

/* ------------------------------------------------------------------
   5. Validasi & Submit Ajax
------------------------------------------------------------------ */
$('#form-penjualan-ajax').validate({
    rules:{
        user_id:{required:true},
        pembeli:{required:true},
        penjualan_kode:{required:true,minlength:3},
        penjualan_tanggal:{required:true}
    },
    submitHandler:function(form){
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success:function(res){
                if(res.status){
                    $('#myModal').modal('hide');
                    if(typeof dataPenjualan!=='undefined') dataPenjualan.ajax.reload();
                    Swal.fire({icon:'success',title:'Berhasil',text:res.message});
                }else{
                    $('.error-text').text('');
                    $.each(res.msgField,function(key,val){
                        $('#error-'+key.replace(/\./g,'-')).text(val[0]);
                    });
                    Swal.fire({icon:'error',title:'Gagal',text:res.message});
                }
            },
            error:function(xhr){
                Swal.fire({icon:'error',title:'Error',text:xhr.responseText||'Terjadi kesalahan'});
            }
        });
        return false;
    },
    errorElement:'span',
    errorPlacement:function(err,el){err.addClass('invalid-feedback');el.closest('.form-group').append(err);},
    highlight:function(el){$(el).addClass('is-invalid');},
    unhighlight:function(el){$(el).removeClass('is-invalid');}
});
</script>
