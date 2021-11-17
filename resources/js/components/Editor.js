import EditorJS from '@editorjs/editorjs';
import NestedList from '@editorjs/nested-list';
const Header = require('@editorjs/header');
const Quote = require('@editorjs/quote');
const Table = require('editorjs-table');

export default class Editor
{
    #holder;
    #editor;
    #form;

    constructor(holder, data) {
        this.#holder = document.getElementById(holder);
        this.#editor = new EditorJS({
            /**
             * Id of Element that should contain Editor instance
             */
            holder: holder,
            tools: {
                header: {
                    class: Header,
                    shortcut: 'CMD+SHIFT+H',
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    shortcut: 'CMD+SHIFT+O',
                    config: {
                        quotePlaceholder: 'Enter a quote',
                        captionPlaceholder: 'Quote\'s author',
                    },
                },
                list: {
                    class: NestedList,
                    inlineToolbar: true,
                },
                table: {
                    class: Table,
                    inlineToolbar: true,
                    config: {
                        rows: 2,
                        cols: 3,
                    },
                },
            },
            data: JSON.parse(data)
        });
        this.#form = this.#holder.closest('form');

        this.#form.addEventListener('submit', event => {
            this.#editor.save().then((outputData) => {
                let input = this.#createHiddenInput(JSON.stringify(outputData));
                this.#addInputToForm(input);
            }).catch((error) => {
                console.log('Saving failed: ', error)
            });
        });
    }

    /**
     * @param {string} value
     *
     * @return {HTMLInputElement}
     */
    #createHiddenInput(value)
    {
        let input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', this.#holder.dataset.name);
        input.setAttribute('value', value);

        return input;
    }

    /**
     * @param {HTMLInputElement} input
     */
    #addInputToForm(input)
    {
        this.#form.appendChild(input);
    }
}