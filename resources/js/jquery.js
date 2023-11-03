import jQuery from "jquery";

window.addEventListener('resetField', function (e) {
  const array = e.detail
  array.forEach(element => {
    var selectElement = document.getElementById(element);

    // Reset its value to the default option value (assuming it is '')
    selectElement.value = '';

    // Get the visible input element created by tw-elements that shows the current value
    var visibleInputElement = selectElement.closest('.relative').querySelector('[data-te-select-input-ref]');

    // Reset the visible input element's display value
    if (visibleInputElement) {
      visibleInputElement.value = '';
    }

    // Create a new 'change' event
    var event = new Event('change', { bubbles: true });

    // Dispatch it on the select element
    selectElement.dispatchEvent(event);

    // If you have a label that changes based on the select state, you might need to update that too
    // Reset any active states on the label
    var label = selectElement.closest('.relative').querySelector('[data-te-select-label-ref]');
    if (label) {
      label.removeAttribute('data-te-input-state-active')
    }

    var notch = selectElement.closest('.relative').querySelector('[data-te-input-notch-ref]');
    if (notch) {
      notch.removeAttribute('data-te-input-state-active')
    }

  });

});