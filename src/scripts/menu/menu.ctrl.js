import MenuService from "./menu.service.js";

class MenuCtrl {
    service = new MenuService();

    constructor() {
        console.log('hello world');
        this.service.getMenu();
    }
}


export default MenuCtrl;