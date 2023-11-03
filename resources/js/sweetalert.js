
import Swal from 'sweetalert2';

window.addEventListener('swal', function (e) {
  Swal.fire(e.detail);
});

window.addEventListener('swal:delete', function (e) {
  Swal.fire(e.detail).then((willDelete) => {
    if (willDelete) {
      window.livewire.emitTo('delete', e.detail.id);
    }
  })
})


window.confirmMessage = function (id, formId) {
  Swal.fire({
    title: 'Are You Sure?',
    html: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
  }).then((result) => {
    if (result.value) {
      livewire.emitTo('dashboard.reporting.expenditure', 'delete', id)
    }
  });
}