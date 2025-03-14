import { Controller } from "@hotwired/stimulus";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="app" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * app_controller.js -> "app"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  connect() {
    console.log("App controller");

    // Chargement select2js
    $(document).ready(function () {
      $("select[multiple]").select2();
    });
  }
}
