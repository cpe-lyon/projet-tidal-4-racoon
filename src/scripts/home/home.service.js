import Service from "../service/service.js";

class HomeService extends Service {
    getKeywords() {
        return this.get('/keywords/get');
    }

    filterKeywords(filter) {
        return this.get('/keywords/filter', {'filter': filter} );
    }

    ping() {
        return this.get('/keywords/ping');
    }
}

export default HomeService;