// @ts-ignore
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ["container"];

  declare readonly containerTarget: HTMLDivElement;
  private timeoutId: number | null = null;

  connect(): void {
    console.log("⚡️ Flash message controller connected.");
    this.startAutoHide();
  }

  private startAutoHide(): void {
    this.timeoutId = window.setTimeout(() => this.hideContainer(), 8000);
  }

  public close(): void {
    if (this.timeoutId) {
      clearTimeout(this.timeoutId);
      this.timeoutId = null;
    }

    this.hideContainer();
  }

  private hideContainer(): void {
    console.log("💥 Clear flash container")

    const target = this.containerTarget;

    target.style.transition = "transform 0.5s ease-out, opacity 0.5s ease-out";
    target.style.transform = "translateX(100%)";
    target.style.opacity = "0";

    setTimeout(() => {
      target.style.visibility = "hidden";
    }, 500);
  }
}

