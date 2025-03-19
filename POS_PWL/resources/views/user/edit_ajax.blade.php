@empty($user)
    <!-- Jika data user tidak ditemukan -->
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body alert alert-danger">
                Data user tidak ditemukan
            </div>
        </div>
    </div>
@else
    <!-- Form Edit User -->
    <form action="{{ url('/user/' . $user->user_id . '/update_ajax') }}" method="POST" id="form-edit">
        @method('PUT')
        @csrf

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="modal-body">
                    <!-- Level -->
                    <div class="form-group">
                        <label>Level</label>
                        <select name="level_id" id="level_id" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach($level as $l)
                                <option value="{{ $l->level_id }}" 
                                    {{ $user->level_id == $l->level_id ? 'selected' : '' }}>
                                    {{ $l->level_nama }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-level_id" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control" 
                               value="{{ $user->username }}" required>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Nama -->
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" 
                               value="{{ $user->nama }}" required>
                        <small id="error-nama" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small id="error-password" class="error-text form-text text-danger">
                            Abaikan jika tidak ingin mengubah password
                        </small>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Script jQuery Validation & AJAX submit -->
    <script>
        $(document).ready(function() {
            // Validasi form
            $("#form-edit").validate({
                rules: {
                    level_id: {
                        required: true,
                        number: true
                    },
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    password: {
                        minlength: 6,
                        maxlength: 20
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: $(form).attr("method"),
                        data: $(form).serialize(),
                        success: function(response) {
                            // Tutup modal
                            $('#myModal').modal('hide');
                            // Reload DataTables (contoh variabel dataUser)
                            dataUser.ajax.reload();
                            // Tampilkan notifikasi sukses (SweetAlert)
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil diupdate!'
                            });
                        },
                        error: function(xhr) {
                            // Tangani error validasi dari server
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                $.each(errors, function(field, val) {
                                    $("#error-" + field).text(val[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: 'Tidak dapat memproses permintaan.'
                                });
                            }
                        }
                    });
                    return false; // Supaya form tidak submit secara normal
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    // Letakkan error di <small id="error-{field}"> ...
                    error.appendTo("#error-" + element.attr("id"));
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                }
            });
        });
    </script>
@endempty
