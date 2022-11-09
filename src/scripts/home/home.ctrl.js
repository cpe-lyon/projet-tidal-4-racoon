import HomeService from "./home.service.js";
import InterfaceService from "../service/interface.service.js";
import service from "../service/service";

class TypeSearch {
    constructor(content, filter) {
        this.content = value;
        this.filter = filter;
    }
}

class HomeCtrl {
    constructor() {
        // Initialisation
        this.$scope = window;
        this.service = new HomeService();
        this.searchFilterList = new Map();

        this.service.getKeywords().then((response) => {
            this.keywords = response.data;
        });

        // Récupération du champs de recherche & filtre
        const filterInput = document.getElementById('search');
        const selectFilter = document.getElementById('filterType');
        const filter = selectFilter.options[selectFilter.selectedIndex].value;

        // Bind de l'action sur le bouton de filtre
        document.getElementById('filter-btn').onclick = function () {


            const badgeList = document.getElementById('search-badges');
            badgeList.insertAdjacentHTML('afterbegin', '<div class="filter-badge" id="' + filterInput.value + '">' +
                '       <span>' + filterInput.value + '</span>\n' +
                '            <button type="button" onclick="deleteFilter(\'' + filterInput.value + '\')">\n' +
                '                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">\n' +
                '                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>\n' +
                '                </svg>' +
                '            </button>' +
                '        </div>');
            this.searchFilterList.push(new TypeSearch(filter, filterInput.value));
        }

        // Bind de l'action de supprimer un filtre
        this.$scope.deleteFilter = function (filterKey) {
            document.getElementById(filterKey).remove();
        }

        // Quand l'utilisateur insère des lettres dans le champs de recherche on lance la recherche de keywords
        filterInput.addEventListener('input', () => {
            console.log(filter);
            if(filter !== 'Symptomes') {
                return;
            }
            /* TODO : Attendre la possibilité de mettre un string en param d'url
            this.service.filterKeywords(filterInput.value).then((response) => {
                this.keywords = response.data;
            });*/
            this.keywords.push('test');
            const suggests = document.getElementById('suggest-list')
            if(suggests) {
                suggests.remove();
            }
            if(filterInput.value !== '') {
                const suggestList = document.createElement("div");
                suggestList.setAttribute("id", "suggest-list");
                suggestList.setAttribute("class", "content");
                for (const keyword of this.keywords) {
                    let keywordElement = document.createElement("a");
                    keywordElement.setAttribute("href", "#");
                    keywordElement.setAttribute("class", "keyword");
                    keywordElement.append(keyword.name);
                    suggestList.appendChild(keywordElement);
                }
                document.getElementById('suggests').appendChild(suggestList);
            }
        })
    }

}


export default HomeCtrl;