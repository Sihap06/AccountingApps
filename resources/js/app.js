
window.Swal = require('sweetalert2')

import { Select, initTE, Input, Ripple, LoadingManagement, Modal } from "tw-elements";

require('./bootstrap');
require('./sweetalert');
require('./masked');
require('./jquery')
require('./chart')
// require('./te-elements-init')

initTE({ Select, Input, Modal, Ripple, LoadingManagement });

window.addEventListener('reInitTwElement', function () {
  initTE({ Select, Input, Modal, Ripple, LoadingManagement });
})
