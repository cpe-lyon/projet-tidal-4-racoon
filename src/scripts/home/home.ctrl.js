import HomeService from "./home.service.js";
import InterfaceService from "../service/interface.service.js";
import service from "../service/service";

class TypeSearch {
    constructor(content, filter) {
        this.content = content;
        this.filter = filter;
    }
}

class HomeCtrl {
    keywords = [];

    constructor() {
        // Initialisation
        this.$scope = window;
        this.service = new HomeService();
        this.searchFilterList = new Map();

        this.addBadgeFilter = function (badgeValue) {
            const badgeList = document.getElementById('search-badges');
            badgeList.insertAdjacentHTML('afterbegin', '<div class="filter-badge" id="' + badgeValue + '">' +
                '       <span>' + badgeValue + '</span>\n' +
                '            <button type="button" onclick="deleteFilter(\'' + badgeValue + '\')">\n' +
                '                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">\n' +
                '                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>\n' +
                '                </svg>' +
                '            </button>' +
                '        </div>');
        }

        // Récupération du champs de recherche & filtre
        const filterInput = document.getElementById('search');
        // Bind de l'action sur le bouton de filtre
        document.getElementById('filter-btn').onclick = () => {
            const selectFilter = document.getElementById('filterType');
            const filter = selectFilter.options[selectFilter.selectedIndex].value;
            console.log(filter);
            if(filter === 'Filtre' || filter === 'Symptome') {
                return;
            }
            this.searchFilterList.set(filterInput.value, new TypeSearch(filter, filterInput.value));
            this.addBadgeFilter(filterInput.value);
        }

        // Bind de l'action sur le bouton de recherche
        document.getElementById('search-btn').onclick = () => {
            // TODO : Call vers le controller pour lancer la recherche
            console.log(this.searchFilterList);
        }

        // Bind de l'action de supprimer un filtre
        this.$scope.deleteFilter = (filterKey) => {
            console.log(filterKey);
            this.searchFilterList.delete(filterKey);
            document.getElementById(filterKey).remove();
        }

        // Quand l'utilisateur insère des lettres dans le champs de recherche on lance la recherche de keywords
        $('#search').on('input', (event) => {
            const selectFilter = document.getElementById('filterType');
            const filter = selectFilter.options[selectFilter.selectedIndex].text;
            if(filter !== 'Symptomes') {
                return;
            }
            this.service.filterKeywords(filterInput.value).then((response) => {
                this.keywords = response.data;
                this.reloadSuggestions();
            });
        });

    }

    /**
     * Ajoute un mot clé au filtre
     * @param keyword
     */
    addKeywordToFiler(keyword) {
        this.addBadgeFilter(keyword);
        this.searchFilterList.set(keyword, new TypeSearch('symptome', keyword));
        this.reloadSuggestions();
    }

    /**
     * Recharge les suggestions en récupérant la liste des keywords
     * et met à jour la partie html
     */
    reloadSuggestions() {
        const suggests = document.getElementById('suggest-list');
        const filterInput = document.getElementById('search');
        if(suggests) {
            suggests.remove();
        }
        if(filterInput.value !== '' && this.keywords.length > 0) {
            const suggestList = document.createElement("div");
            suggestList.setAttribute("id", "suggest-list");
            suggestList.setAttribute("class", "content");
            for (const keyword of this.keywords) {
                if(this.searchFilterList.has(keyword)) {
                    continue;
                }
                let keywordElement = document.createElement("button");
                keywordElement.setAttribute("class", "keyword");
                keywordElement.addEventListener('click', () => {
                    this.addKeywordToFiler(keyword);
                });
                keywordElement.append(keyword);
                suggestList.appendChild(keywordElement);
            }
            document.getElementById('suggests').appendChild(suggestList);
        }
    }

}


export default HomeCtrl;