<!-- Bootstrap & Core JavaScript -->
<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Core Plugin JavaScript -->
<script src="<?= base_url('assets/sbadmin2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

<!-- SB Admin 2 Scripts -->
<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>

<!-- DataTables Plugin JavaScript -->
<script src="<?= base_url('assets/sbadmin2/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inisialisasi DataTable dengan bahasa Indonesia
    if ($('.dataTable').length > 0) {
        $('.dataTable').DataTable({
            language: {
                search: "Cari Data:",
                searchPlaceholder: "Ketik kata kunci...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fas fa-chevron-right'></i>",
                    previous: "<i class='fas fa-chevron-left'></i>"
                }
            },
            pageLength: 25,
            responsive: true
        });
    }

    // Konfirmasi Hapus dengan SweetAlert2
    $(document).on('click', '.btn-delete, .btn-hapus', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    // Konfirmasi Tarik Dapodik
    $(document).on('click', '.btn-tarik-dapo', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const title = $(this).data('title') || 'Tarik Data Dapodik';

        Swal.fire({
            title: title + '?',
            html: 'Data akan ditarik secara <b>READ-ONLY</b> dari Dapodik lokal ke PEKAEL.<br><small class="text-success"><i class="fas fa-shield-alt mr-1"></i>Aman: Database Dapodik tidak akan diubah sedikitpun.</small>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-sync-alt mr-1"></i> Ya, Mulai Tarik Data!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sedang Menarik Data...',
                    html: 'Mohon tunggu, sistem sedang membaca database Dapodik lokal.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = url;
            }
        });
    });

    // Toggle Theme (Dark / Light Mode)
    $('#btnToggleTheme').on('click', function(e) {
        e.preventDefault();
        const isDark = $('body').hasClass('theme-dark');
        const newTheme = isDark ? 'light' : 'dark';

        $.ajax({
            url: '<?= site_url('pengaturan/set_theme') ?>',
            type: 'POST',
            data: { theme: newTheme },
            success: function() {
                window.location.reload();
            },
            error: function() {
                window.location.href = '<?= site_url('pengaturan/set_theme?theme=') ?>' + newTheme;
            }
        });
    });
});
</script>

</body>
</html>
