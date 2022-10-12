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

    // make XMLHttpRequest to the server
    // return a promise
    request(method, url, data){
        return new Promise((resolve, reject) => {
            let xhr = new XMLHttpRequest();
            xhr.open(method, Service.api + url);
            xhr.onload = () => {
                if(xhr.status >= 200 && xhr.status < 300){
                    const response = JSON.parse(xhr.response);
                    resolve(response);
                } else {
                    reject(xhr.statusText);
                }
            };
            xhr.onerror = () => reject(xhr.statusText);
            xhr.send(data);
        });
    }
}

export default Service;