
import Swal from 'sweetalert2';

require('jquery-mask-plugin')

window.deleteConfirm = function (formId) {
  Swal.fire({
    icon: 'warning',
    text: 'Do you want to delete this?',
    showCancelButton: true,
    confirmButtonText: 'Delete',
    confirmButtonColor: '#e3342f',

  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById(formId).submit();
    }
  });
}

window.payment = function () {
  Swal.fire({
    title: '',
    input: 'text',
    inputLabel: 'Nominal Pembayaran',
    inputPlaceholder: 'Masukkan nominal pembayaran',
    showCancelButton: true,
    confirmButtonText: 'Submit',
    cancelButtonText: 'Batal',
    inputAttributes: {
      id: 'myInput'
    },
    didOpen: function () {
      $('#myInput').mask('000.000.000.000.000,00', { reverse: true });
    },
    inputValidator: () => {

      const value = $('#myInput').cleanVal()

      if (!value) {
        return 'You need to write something!'
      }
    }
  });
}