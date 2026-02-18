import './bootstrap';
import 'bootstrap';

// Import jQuery and expose it
import $ from 'jquery';
window.$ = window.jQuery = $;

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Select2 (Import and initialize)
import select2 from 'select2';
select2(); // Attaches to jQuery

// DataTables
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';
import JSZip from 'jszip';
window.JSZip = JSZip;
window.DataTable = DataTable;

// Gentelella Minimal Bundle
import '@gentelella/main-minimal.js';

// Global Initializations
$(document).ready(function () {
    // Initialize Select2 for all .select2 elements
    const initSelect2 = () => {
        $('.select2').each(function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
            }
        });
    }

    initSelect2();

    // Initialize DataTables for .datatable class
    $('.datatable').each(function () {
        if (!$.fn.DataTable.isDataTable(this)) {
            $(this).DataTable({
                responsive: true,
                dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        }
    });

    // Handle sidebar toggle event if needed
    $('#menu_toggle').on('click', function () {
        // Force relayout of datatables if they are in the view
        setTimeout(() => {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
        }, 300);
    });
});
