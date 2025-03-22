import {Controller} from "@hotwired/stimulus";
import TomSelect from 'tom-select'
//const bootstrap = require('bootstrap');

export default class extends Controller {
    private tomSelects: Array<TomSelect> = [];

    connect(): void {
        this.initTomSelect()
        this.preventMultipleSubmissionOfForms();
        this.submitOnChange();
        this.initThemeColor();
    }

    public initTomSelect() {
        this.tomSelects.forEach((t: TomSelect) => t.destroy());
        this.tomSelects = [];

        const settings = {};

        const elements: Array<Element> = [...this.element.getElementsByClassName('tom-select')];
        for (let element of elements) {
            if (!(element instanceof HTMLSelectElement)) {
                continue;
            }

            this.tomSelects.push(new TomSelect(element, settings));
        }
    }

    private preventMultipleSubmissionOfForms(): void {
        const forms = [...this.element.getElementsByTagName('form')];

        for (const form of forms) {
            form.addEventListener('submit', () => {
                const submitButtons = [...form.getElementsByTagName('button')]
                    .filter((button: HTMLButtonElement) => 'submit' === button.type)
                    .filter((button: HTMLButtonElement) => !button.hasAttribute('data-prevent-disabling'));

                for (const submitButton of submitButtons) {
                    submitButton.disabled = true;
                    setTimeout(() => submitButton.disabled = false, 1000);
                }
            })
        }
    }

    private submitOnChange(): void {
        const forms: Array<HTMLFormElement> = [...this.element.getElementsByTagName('form')];

        for (const form of forms) {
            const selects = [...form.getElementsByTagName('select')]
                .filter((select: HTMLSelectElement) => select.classList.contains('submit-on-change'));

            for (const select of selects) {
                select.addEventListener('change', (e) => {
                    e.preventDefault();
                    form.submit();
                })
            }
        }
    }

    private initThemeColor(): void {
      let preferredTheme: string|null = document.documentElement.getAttribute('data-bs-preferred-theme');
      let theme: string = preferredTheme ?? 'auto';

      if ('auto' === preferredTheme) {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }

      document.documentElement.setAttribute('data-bs-theme', theme);
    }

    disconnect(): void {
        this.tomSelects.forEach((select: TomSelect) => select.destroy());
    }
}

