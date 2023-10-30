require('./bootstrap');
require('./sweetalert');
require('./masked');

import { Select, initTE, Input, Ripple, LoadingManagement, Modal } from "tw-elements";
initTE({ Select });
initTE({ Input });
initTE({ Modal, Ripple })
initTE({ LoadingManagement })