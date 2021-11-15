import EditorJS from '@editorjs/editorjs';
import NestedList from '@editorjs/nested-list';
const Header = require('@editorjs/header');
const Quote = require('@editorjs/quote');
const Table = require('editorjs-table');

export default class Editor
{
    #editor;

    constructor(holder, data) {
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
            data: data
        });
    }
}