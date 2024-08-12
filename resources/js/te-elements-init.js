import { Select, initTE, Input, Ripple, LoadingManagement, Modal } from "tw-elements";

window.updateTwElements = function () {
  initTE({ Select });
  initTE({ Input });
  initTE({ Modal, Ripple })
  initTE({ LoadingManagement })
}
