import Service from "../service/service.js";

class MenuService extends Service {
    getMenu() {
        return this.get('/menu/account');
    }
}

export default MenuService;