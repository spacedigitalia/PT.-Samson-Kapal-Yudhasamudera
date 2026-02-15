<?php if (!defined('FOOTER_INCLUDED')): ?>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.1/dist/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('lg:translate-x-0');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('[onclick="toggleSidebar()"]');
            const closeButton = document.getElementById('close-sidebar');

            if (window.innerWidth < 1024 &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target) &&
                !closeButton.contains(event.target)) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('lg:translate-x-0');
            }
        });

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any tooltips if needed
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip-target]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new Tooltip(tooltipTriggerEl);
            });

            // Auto-close alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert-auto-close');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            });
        });

        // Delete confirmation
        function confirmDelete(event, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        }

        // Image preview for file inputs
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const preview = document.getElementById('imagePreview');

                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="mt-2 relative inline-block">
                            <img src="${e.target.result}" class="h-40 w-auto rounded-lg border border-slate-200" alt="Preview">
                            <button type="button" onclick="document.getElementById('${input.id}').value = ''; preview.innerHTML = '';" 
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                <i class='bx bx-x text-sm'></i>
                            </button>
                        </div>
                    `;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <?php if (isset($successMessage) && $successMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '<?= addslashes($successMessage) ?>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    <?php if (isset($errorMessage) && $errorMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '<?= addslashes($errorMessage) ?>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    </body>

    </html>
    <?php define('FOOTER_INCLUDED', true); ?>
<?php endif; ?>