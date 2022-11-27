import HomeService from "./home.service.js";
import InterfaceService from "../service/interface.service.js";
import service from "../service/service";
import PathologyCtrl from './pathology/pathology.ctrl';
import pathologyCtrl from './pathology/pathology.ctrl';

class SearchType {
    constructor(content, filter) {
        this.content = content;
        this.filter = filter;
    }
}

class Patho {
    constructor(patho) {
        this.aggr = patho.aggr;
        this.desc = patho.desc;
        this.idp = patho.idp;
        this.ids = patho.ids;
        this.mer = patho.mer;
        this.type = patho.type;
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
            let svgIcon = "";
            let badgeClass = "";
            switch ($( "#filterType option:selected" ).text()) {
                case 'Symptomes':
                    svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bandaid-fill" viewBox="0 0 16 16">\n' +
                    '  <path d="m2.68 7.676 6.49-6.504a4 4 0 0 1 5.66 5.653l-1.477 1.529-5.006 5.006-1.523 1.472a4 4 0 0 1-5.653-5.66l.001-.002 1.505-1.492.001-.002Zm5.71-2.858a.5.5 0 1 0-.708.707.5.5 0 0 0 .707-.707ZM6.974 6.939a.5.5 0 1 0-.707-.707.5.5 0 0 0 .707.707ZM5.56 8.354a.5.5 0 1 0-.707-.708.5.5 0 0 0 .707.708Zm2.828 2.828a.5.5 0 1 0-.707-.707.5.5 0 0 0 .707.707Zm1.414-2.121a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707Zm1.414-.707a.5.5 0 1 0-.706-.708.5.5 0 0 0 .707.708Zm-4.242.707a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707Zm1.414-.707a.5.5 0 1 0-.707-.708.5.5 0 0 0 .707.708Zm1.414-2.122a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707ZM8.646 3.354l4 4 .708-.708-4-4-.708.708Zm-1.292 9.292-4-4-.708.708 4 4 .708-.708Z"/>\n' +
                    '</svg>';
                    badgeClass = 'red';
                    break;
                case 'Type':
                    svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16">\n' +
                        '  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>\n' +
                        '  <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>\n' +
                        '</svg>';
                    badgeClass = 'blue';
                    break;
                case 'Méridien':
                    svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-balloon-heart-fill" viewBox="0 0 16 16">\n' +
                        '  <path fill-rule="evenodd" d="M8.49 10.92C19.412 3.382 11.28-2.387 8 .986 4.719-2.387-3.413 3.382 7.51 10.92l-.234.468a.25.25 0 1 0 .448.224l.04-.08c.009.17.024.315.051.45.068.344.208.622.448 1.102l.013.028c.212.422.182.85.05 1.246-.135.402-.366.751-.534 1.003a.25.25 0 0 0 .416.278l.004-.007c.166-.248.431-.646.588-1.115.16-.479.212-1.051-.076-1.629-.258-.515-.365-.732-.419-1.004a2.376 2.376 0 0 1-.037-.289l.008.017a.25.25 0 1 0 .448-.224l-.235-.468ZM6.726 1.269c-1.167-.61-2.8-.142-3.454 1.135-.237.463-.36 1.08-.202 1.85.055.27.467.197.527-.071.285-1.256 1.177-2.462 2.989-2.528.234-.008.348-.278.14-.386Z"/>\n' +
                        '</svg>';
                    badgeClass = 'primary';
                    break;
                case 'Caractéristiques':
                    svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-body-text" viewBox="0 0 16 16">\n' +
                        '  <path fill-rule="evenodd" d="M0 .5A.5.5 0 0 1 .5 0h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 0 .5Zm0 2A.5.5 0 0 1 .5 2h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5Zm9 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm-9 2A.5.5 0 0 1 .5 4h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Zm5 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm7 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Zm-12 2A.5.5 0 0 1 .5 6h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5Zm8 0a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm-8 2A.5.5 0 0 1 .5 8h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5Zm7 0a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5Zm-7 2a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5Zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5Zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5Z"/>\n' +
                        '</svg>';
                    badgeClass = 'purple';
                    break;
            }
            badgeList.insertAdjacentHTML('afterbegin', '<div class="filter-badge filter-badge--' + badgeClass + '" id="' + id + '">' + svgIcon +
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
                console.log(response);
                this.displayResults(response);
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
        $('#search').on('input', () => {
            const filter = $( "#filterType option:selected" ).text();
            if(filter !== 'Symptomes') {
                return;
            }
            this.service.filterKeywords(filterInput.value).then((response) => {
                this.keywords = response.data;
                this.reloadSuggestions();
            });
        });

        PathologyCtrl.loadInterface();
    }

    displayResults(results) {
        const tableBody = $('#table-body');
        tableBody.empty();
        this.$scope.loadPathology = this.loadPathology;
        if(results.data.length === 0) {
            tableBody.append('<tr><td colspan="5">Aucun résultat</td></tr>');   
        } else {
            results.data.forEach((result) => {
                tableBody.append('<tr>\n' +
                    '            <td>' + result.patho_desc[0].toUpperCase() + result.patho_desc.substring(1) + '</td>\n' +
                    '            <td>' + result.patho_mer + '</td>\n' +
                    '            <td>' + result.patho_type + '</td>\n' +
                    '            <td><button class="details-button" onclick="loadPathology(' + result.patho_idp + ')">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">' +
                    '<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>' +
                    '</svg></button>' +
                    '        </td></tr>');
            });
        }
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

    loadPathology(id) {
        let controller = new PathologyCtrl(id);
        console.log('loadPathology', id);
    }

}


export default HomeCtrl;