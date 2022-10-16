import MenuService from "./menu.service";
import InterfaceService from './../service/interface.service';

class MenuCtrl {

    constructor() {
        this.$scope = window;
        this.menu = InterfaceService.select('.menu-nav');
        this.menuIcon = InterfaceService.select('.menu-nav .menu-nav-icon');
        this.menuList = InterfaceService.select('.menu-nav-content');
        this.loadMenu();
    }

    loadMenu() {
        this.$scope.onclick = (event) => {
            if (event.target === this.menu || event.target === this.menuIcon) {
                this.menu.classList.toggle('menu-nav-content--active');
            } else {
                this.menu.classList.remove('menu-nav-content--active');
            }
        }
        for (const li of this.menuList.children)
        {
            if(InterfaceService.select('a', li).href === document.location.href)
            {
                li.classList.add('hidden');
            }
        }

    }
}


export default MenuCtrl;