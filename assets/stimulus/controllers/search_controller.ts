// @ts-ignore
import { Controller } from '@hotwired/stimulus';
import { useClickOutside, useDebounce } from 'stimulus-use';

export default class extends Controller {
  static values = {
    searchUrl: String,
    defaultEmtpyText: String,
  }

  static targets = ["form", "input", "dropdown"];
  static debounces = ['onSearchInput'];

  declare readonly formTarget: HTMLFormElement;
  declare readonly inputTarget: HTMLInputElement;
  declare readonly dropdownTarget: HTMLDivElement;
  declare readonly searchUrlValue: string;
  declare readonly defaultEmtpyTextValue: string;
  defaultEmptyResult!: string;

  connect(): void {
    console.log("📝 Search controller connected.");
    this.inputTarget.value = '';
    this.defaultEmptyResult = `<div class="dropdown-header noti-title"><h5 class="text-overflow mb-2">${this.defaultEmtpyTextValue.toString()}</h5></div>`;

    useClickOutside(this);
    useDebounce(this);

    this.inputTarget.addEventListener('keydown', this.handleKeyDown);
    this.formTarget.addEventListener('submit', function (event: any) {
      event.preventDefault();
      this.onSearchInput();
    })
  }

  disconnect(): void {
    this.inputTarget.removeEventListener('keydown', this.handleKeyDown);
  }

  private handleKeyDown = (event: KeyboardEvent): void => {
    if (event.key === 'Enter') {
      event.preventDefault();
      this.onSearchInput();
    }
  }

  public onSearchInput() {
    this.search(this.inputTarget.value);
  }

  public async search(query: any): Promise<void>
  {
    if (this.inputTarget.value.length < 3) {
      this.dropdownTarget.innerHTML = this.defaultEmptyResult;
      return;
    }

    try {
      const url = new URL(this.searchUrlValue.toString());
      url.searchParams.set("query", query);

      const response = await fetch(url.toString(), {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });

      if (!response.ok) throw new Error('Failed to search api');

      this.dropdownTarget.innerHTML = await response.text();

    } catch (error) {
      console.error("❌ Failed to search :", error);
      this.dropdownTarget.innerHTML = this.defaultEmptyResult;
    }
  }

  public clickOutside() {
    this.dropdownTarget.innerHTML = this.defaultEmptyResult;
  }

}

