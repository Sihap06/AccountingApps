require('./bootstrap');
require('./sweetalert');
require('./masked');
require('./jquery')

window.Swal = require('sweetalert2')

import { Select, initTE, Input, Ripple, LoadingManagement, Modal } from "tw-elements";
initTE({ Select });
initTE({ Input });
initTE({ Modal, Ripple })
initTE({ LoadingManagement })