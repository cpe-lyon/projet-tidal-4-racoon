import Service from "../../service/service.js";

class PathologyService extends Service {
    getPathology(id)
    {
        return this.get("/patho/get/" + id);
    }


    getInterface()
    {
        return this.get("/patho/interface");
    }
}

export default PathologyService;