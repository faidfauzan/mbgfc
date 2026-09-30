@props(['id' => 'dataTable'])

<!-- DataTables CSS & JS (Hanya diload jika komponen ini dipanggil) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    /* Sembunyikan element default DataTables yang tidak diperlukan karena pakai custom */
    .dataTables_filter { display: none; }
    .dataTables_length { display: none; }
    .dataTables_info { font-size: 0.875rem; color: #64748b !important; font-weight: 500; }
    
    /* Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.875rem;
        margin-top: 0;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.4rem 0.85rem !important;
        border-radius: 0.5rem !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #475569 !important;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
        background: #f8fafc !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #10b981 !important;
        color: white !important;
        border-color: #059669 !important;
        box-shadow: 0 1px 3px 0 rgba(16, 185, 129, 0.4);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8fafc !important;
        color: #94a3b8 !important;
        box-shadow: none;
    }
    
    /* Empty State Styling */
    td.dataTables_empty {
        padding: 0 !important;
        background-color: #f8fafc !important;
        border-bottom: none !important;
    }
    
    table.dataTable.no-footer { border-bottom: none; }
    .dataTables_wrapper { padding-bottom: 0.5rem; }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Template untuk state kosong (empty state)
        const emptyStateHTML = `
            <div class="flex flex-col items-center justify-center py-12 px-4">
                <div class="w-16 h-16 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Tidak ada data</h3>
                <p class="text-sm text-gray-500 text-center max-w-sm">
                    Data yang Anda cari tidak ditemukan atau belum tersedia.
                </p>
            </div>
        `;

        window.initStandardDataTable = function(tableId) {
            return $('#' + tableId).DataTable({
                "dom": 'rt<"flex flex-col sm:flex-row justify-between items-center mt-5 px-1 gap-4"ip>',
                "pageLength": 10,
                "language": {
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "emptyTable": emptyStateHTML,
                    "zeroRecords": emptyStateHTML,
                    "paginate": {
                        "first": "«",
                        "last": "»",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        }

        // Auto initialize if data-table class is present
        if ($('#{{ $id }}').length) {
            window.table_{{ $id }} = window.initStandardDataTable('{{ $id }}');
        }
    });
</script>
