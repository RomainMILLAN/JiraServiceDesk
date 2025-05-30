import {Controller} from "@hotwired/stimulus";
import init, {convert} from "htmltoadf";
import Quill from 'quill';

export default class extends Controller {
  static targets = ['editor', 'input']

  declare toolbarValue: Array<any>;
  declare readonly editorTarget: HTMLDivElement;
  declare readonly inputTarget: HTMLInputElement;


  connect() {
    console.log("QuillEditor connected 🖋")
    init();
    this.editorTarget.style.height = this.inputTarget.style.height;

    const quillEditor = new Quill(this.editorTarget, {
      theme: 'snow',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
          ['blockquote', 'code-block'],
          ['link'],

          [{ 'header': 1 }, { 'header': 2 }],               // custom button values
          [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
          [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
          [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
          [{ 'direction': 'rtl' }],                         // text direction

          [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

          [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
          [{ 'font': [] }],
          [{ 'align': [] }],

          ['clean']                                         // remove formatting button
        ],
      },
      formats:  [
        'background',
        'bold',
        'color',
        'font',
        'code',
        'italic',
        'link',
        'size',
        'strike',
        'script',
        'underline',
        'blockquote',
        'header',
        'indent',
        'list',
        'align',
        'direction',
        'code-block',
        'formula'
        // 'image'
        // 'video'
      ]
    });

    quillEditor.on('text-change', (): void => {
      // console.log(convert(this.normalize(quillEditor.root.innerHTML)));
      this.inputTarget.value = convert(this.normalize(quillEditor.root.innerHTML));
    })
  }

  private normalize(html: string): string {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    doc.querySelectorAll('span.ql-ui[contenteditable="false"]').forEach(span => span.remove());
    const lists = doc.querySelectorAll('ol, ul');

    lists.forEach(list => {
      if (list.tagName.toLowerCase() === 'ol' && list.querySelector('li[data-list="bullet"]')) {
        const ul = document.createElement('ul');
        Array.from(list.children).forEach(li => ul.appendChild(li));
        list.parentNode?.replaceChild(ul, list);
        list = ul;
      }

      list.querySelectorAll('li').forEach(li => {
        const indentClass = Array.from(li.classList).find(cls => cls.startsWith('ql-indent-'));
        const indentLevel = indentClass ? parseInt(indentClass.replace('ql-indent-', ''), 10) : 0;

        li.classList.forEach(cls => {
          if (cls.startsWith('ql-indent-')) li.classList.remove(cls);
        });

        if (indentLevel > 0) {
          let prev = li.previousElementSibling;
          while (prev && !prev.matches('li')) prev = prev.previousElementSibling;
          if (!prev) return;
          let container = prev;

          for (let i = 1; i < indentLevel; i++) {
            let nestedList = container.querySelector(list.tagName.toLowerCase());
            if (!nestedList) {
              nestedList = document.createElement(list.tagName.toLowerCase());
              container.appendChild(nestedList);
            }
            const nestedLis = nestedList.querySelectorAll('li');
            container = nestedLis[nestedLis.length - 1];
            if (!container) break;
          }

          let nestedList = container.querySelector(list.tagName.toLowerCase());
          if (!nestedList) {
            nestedList = document.createElement(list.tagName.toLowerCase());
            container.appendChild(nestedList);
          }
          nestedList.appendChild(li);
        }

        if (!li.querySelector('p')) {
          const p = document.createElement('p');
          while (li.firstChild) {
            p.appendChild(li.firstChild);
          }
          li.appendChild(p);
        }
      });
    });

    return doc.body.innerHTML;
  }



}
