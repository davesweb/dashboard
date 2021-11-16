export default class Sidebar
{
    #sidebarElement;
    #mainPageElement;

    /**
     * @param {HTMLElement} sidebarElement
     * @param {HTMLElement} mainPageElement
     */
    constructor(sidebarElement, mainPageElement) {
        this.#sidebarElement = sidebarElement;
        this.#mainPageElement = mainPageElement;
    }

    bindBootstrapEvents() {
        this.#sidebarElement.addEventListener('hidden.bs.collapse', event => {
            this.#close();
        });

        this.#sidebarElement.addEventListener('shown.bs.collapse', event => {
            this.#open();
        });
    }

    #open() {
        // We don't actually need to show or hide the sidebar, Bootstrap does this. We only need to add a class to
        // the main page element to tell it the sidebar is open or closed.
        this.#mainPageElement.classList.remove('sidebar-closed');
    }

    #close() {
        this.#mainPageElement.classList.add('sidebar-closed');
    }
}