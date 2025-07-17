import { Controller } from '@hotwired/stimulus';


export default class extends Controller {

  static targets = ["select"];

  declare readonly selectTarget: HTMLSelectElement;

  connect(): void {
    console.log("📋 Pagination choice per page controller connected.");

    this.selectTarget.addEventListener('change', this.change);
  }

  private change = (): void => {
    const newUrl = this.selectTarget.selectedOptions[0].getAttribute('data-url');

    if (newUrl) {
      window.location.href = newUrl;
    }
  }

}

