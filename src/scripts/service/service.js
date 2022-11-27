class Service {
    static api = 'http://racoon/api';

    serialize(obj) {
        let str = [];
        for(let p in obj)
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        return str.join("&");
    }

    get(url, data = null){
        if(data){
            url = url + '?' + this.serialize(data);
        }
        return this.request('GET', url);
    }
    post(url, data){
        return this.request('POST', url, data);
    }
    update(url, data){
        return this.request('PUT', url, data);
    }
    delete(url, data){
        if(data){
            url = url + '?' + this.serialize(data);
        }
        return this.request('DELETE', url);
    }

    // make fetch to the server
    // return a promise
    request(method, url, data){
        switch (method){
            case 'POST':
            case 'PUT':
                let formData = new FormData();
                for(let key in data){
                    formData.append(key, data[key]);
                }
                data = formData;
                break;
            case 'GET':
            case 'DELETE':
                data = null;
                break;
        }
        return fetch(Service.api + url, {
            method: method,
            headers: {
                'Accept': 'application/json',
            },
            mode: 'cors',
            cache: 'default',
            body: data
        }).then((response) => {
            try{
                return response.json();
            } catch (e) {
                return response.text();
            }
        });
    }
}

export default Service;