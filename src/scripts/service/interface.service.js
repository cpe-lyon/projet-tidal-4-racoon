class InterfaceService {
    // function to add a Node to the DOM
    static new(type, text, id, classes, parent) {
        let element = document.createElement(type);
        if (text) {
            element.textContent = text;
        }
        if (id) {
            element.id = id;
        }
        if (classes) {
            for (const classe of classes) {
                element.classList.add(classe);
            }
        }
        if (parent) {
            parent.appendChild(element);
        }
        return element;
    }

    // function to select a Node from the DOM
    static select(selector) {
        return document.querySelector(selector);
    }

    // function to select all Nodes from the DOM
    static selectAll(selector) {
        return document.querySelectorAll(selector);
    }
}

export default InterfaceService;