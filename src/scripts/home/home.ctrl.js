import HomeService from "./home.service.js";
import InterfaceService from "../service/interface.service.js";
import service from "../service/service";

class SearchType {
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
        this.filterValue = {};
        this.filterValue['Type'] = 'type';
        this.filterValue['symptome'] = 'symptome';
        this.filterValue['Méridien'] = 'mer';
        this.filterValue['Caractéristiques'] = 'desc';

        this.addBadgeFilter = function (badgeValue, id) {
            const badgeList = document.getElementById('search-badges');
            badgeList.insertAdjacentHTML('afterbegin', '<div class="filter-badge" id="' + id + '">' +
                '       <span>' + badgeValue + '</span>\n' +
                '            <button type="button" onclick="deleteFilter(\'' + id + '\')">\n' +
                '                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">\n' +
                '                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>\n' +
                '                </svg>' +
                '            </button>' +
                '        </div>');
        }

        // Récupération du champs de recherche & filtre
        const filterInput = document.getElementById('search');
        // Bind de l'action sur le bouton de filtre
        $('#filter-btn').click(() => {
            const selectFilter = document.getElementById('filterType');
            const filter = selectFilter.options[selectFilter.selectedIndex].text;
            console.log(filter);
            if(filter === 'Filtre' || filter === 'Symptomes' || filter === '' || filter === undefined) {
                return;
            }
            const id = this.filterValue[filter] + '-' + filterInput.value;
            if(!this.searchFilterList.has(id)) {
                this.searchFilterList.set(id, new SearchType(this.filterValue[filter], filterInput.value));
                this.addBadgeFilter(filterInput.value, id);
            }
        });

        // Bind de l'action sur le bouton de recherche
        $('#search-btn').click(() => {
            // TODO : Call vers le controller pour lancer la recherche
            this.service.search(Array.from(this.searchFilterList.values())).then((response) => {
                console.log(response)
            });
        });

        // Bind de l'action de supprimer un filtre
        this.$scope.deleteFilter = (filterKey) => {
            console.log(filterKey);
            this.searchFilterList.delete(filterKey);
            document.getElementById(filterKey).remove();
        }

        $(document).click(() => {
            if($( "#filterType option:selected" ).text() === 'Symptomes') {
                this.deleteSuggestions();
            }
        })

        $('#suggests').click((e) => {
            e.stopPropagation(); // This is the preferred method.
        });

        $('#filterType').on('change', () => {
            const value = $( "#filterType option:selected" ).text();
            if(value === 'Symptome') {
                $('#search').attr('placeholder', 'Rechercher par symptome');
                $('#filter-btn').prop('disabled', true);
            } else if(value !== 'Filtre') {
                $('#search').attr('placeholder', 'Rechercher par ' + value);
                $('#filter-btn').prop('disabled', false);
            }
        });

        // Quand l'utilisateur insère des lettres dans le champs de recherche on lance la recherche de keywords
        $('#search').on('input', () => {;
            const filter = $( "#filterType option:selected" ).text();
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
        this.addBadgeFilter(keyword, 'symptome' + '-' + keyword);
        this.searchFilterList.set('symptome' + '-' + keyword, new SearchType('symptome', keyword));
        this.reloadSuggestions();
    }

    /**
     * Supprime la liste de suggestions
     */
    deleteSuggestions() {
        const suggests = document.getElementById('suggest-list');
        if(suggests) {
            suggests.remove();
        }
    }

    /**
     * Recharge les suggestions en récupérant la liste des keywords
     * et met à jour la partie html
     */
    reloadSuggestions() {
        this.deleteSuggestions();
        const filterInput = document.getElementById('search');
        if(filterInput.value !== '' && this.keywords.length > 0) {
            const suggestList = document.createElement("div");
            suggestList.setAttribute("id", "suggest-list");
            suggestList.setAttribute("class", "content");
            for (const keyword of this.keywords) {
                if(this.searchFilterList.has('symptome' + '-' + keyword)) {
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