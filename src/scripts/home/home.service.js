import Service from "../service/service.js";

class HomeService extends Service {
    getKeywords() {
        return this.get('/keywords/get');
    }

    filterKeywords(filter) {
        return this.get('/keywords/keyword', {filter: filter});
        //return this.get('/keywords/', {'filter': filter} );
    }

    search(searchFilterList) {
        return this.post('/patho/get', {filters: JSON.stringify(searchFilterList)});
    }

    ping() {
        return this.get('/keywords/ping');
    }
}

export default HomeService;