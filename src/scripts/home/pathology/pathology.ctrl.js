import PathologyService from "./pathology.service.js";
import InterfaceService from "../../service/interface.service.js";

class PathologyCtrl
{
    static interface;

    static loadInterface() {
        let pathologyService = new PathologyService();
        pathologyService.getInterface().then((inter) => {
            PathologyCtrl.interface = inter.data;
        });
    }

    constructor(id) {
        this.$scope = window;
        this.service = new PathologyService();
        this.interfaceService = new InterfaceService();
        this.id = id;
        this.init();
    }

    setInterface() {
        if (PathologyCtrl.interface === undefined) {
            this.interfaceService.getInterface().then((inter) => {
                PathologyCtrl.interface = inter.data;
                return PathologyCtrl.interface;
            });
        }
    }

    init() {
        this.service.getPathology(this.id).then((pathology) => {
            this.pathology = pathology.data;
            this.getInterface(this.pathology);
        });
    }

    getInterface(pathology) {
        console.log(pathology);

        let inter = PathologyCtrl.interface;
        const parent = InterfaceService.select('#pathology');
        const closeButton = InterfaceService.select('#pathology__close', parent);
        // replace the id of the pathology in the interface
        inter = inter.replace(/{pathology\.description}/g, pathology.desc[0].toUpperCase() + pathology.desc.substring(1));
        inter = inter.replace(/{pathology\.meridien}/g, pathology.mer.toUpperCase());
        inter = inter.replace(/{pathology\.type}/g, pathology.type.toUpperCase());
        const node = InterfaceService.newRaw(inter);
        const table = node.querySelector('#pathology_symptomes-table');

        const symptomes = new Map(Object.entries(pathology.symptomes));
        symptomes.forEach((symptome, key) => {
            let row = InterfaceService.new('tr', '', '', [], table);
            InterfaceService.new('td', '#' + symptome.ids, '', [], row);
            InterfaceService.new('td', symptome.desc[0].toUpperCase() + symptome.desc.substring(1), '', [], row);
            let keyWords = '';
            const keywords = new Map(Object.entries(symptome.keywords));
            keywords.forEach((keyword, key) => {
                keyWords += keyword.name + ', ';
            });
            keyWords = keyWords.substring(0, keyWords.length - 2);
            InterfaceService.new('td', keyWords, '', [], row);
        });

        // get scroll position
        parent.appendChild(node);

        this.display();
        closeButton.addEventListener('click', () => {
            this.hide();
            // remove the interface if not button
            if(InterfaceService.select('#pathology').lastChild !== closeButton) {
                InterfaceService.select('#pathology').removeChild(InterfaceService.select('#pathology').lastChild);
            }
        });
    }

    display() {
        const scrollPosition = window.pageYOffset;
        this.$scope.document.getElementById('pathology').style.display = 'flex';
        this.$scope.document.getElementById('pathology').style.top = (scrollPosition + 0.025 * scrollPosition) + 'px';
        this.$scope.document.body.style.overflow = 'hidden';

    }

    hide() {
        this.$scope.document.getElementById('pathology').style.display = 'none';
        this.$scope.document.body.style.overflow = 'auto';
    }
}


export default PathologyCtrl;