import HomeService from "./home.service.js";
import InterfaceService from "../service/interface.service.js";
import interfaceService from "../service/interface.service.js";

class TypeSearch {
    constructor(content, filter) {
        this.content = value;
        this.filter = filter;
    }
}

class HomeCtrl {

    constructor() {
        this.$scope = window;

        document.getElementById('filter-btn').onclick = function () {
            var filterContent = document.getElementById('search');
            const badgeList = document.getElementById('search-badges');
            badgeList.insertAdjacentHTML('afterbegin', '<div class="filter-badge" id="' + filterContent.value + '">' +
                '       <span>' + filterContent.value + '</span>\n' +
                '            <button type="button" onclick="deleteFilter(\'' + filterContent.value + '\')">\n' +
                '                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">\n' +
                '                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>\n' +
                '                </svg>' +
                '            </button>' +
                '        </div>');
        }
        this.$scope.deleteFilter = function (filterKey) {
            document.getElementById(filterKey).remove();
        }
    }

}


export default HomeCtrl;