import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';

document.addEventListener('DOMContentLoaded', async () => {
    const searchableSelects: NodeListOf<HTMLSelectElement> = document.querySelectorAll('.select-dropdown');

    for (const select of searchableSelects) {
        await initializeChoice(select);
    }
});

async function initializeChoice(selectElement: HTMLSelectElement) {

    const choices = new Choices(selectElement, {
        searchEnabled: true,
        searchFloor: 2,
        searchFields: ['label'],
        allowHTML: true,
        loadingText: 'Carregando...',
        noResultsText: 'Nenhum resultado encontrado',
        noChoicesText: 'Nenhuma opção disponível',
        itemSelectText: 'Pressione para selecionar',
        searchPlaceholderValue: 'Digite para pesquisar',
    });
}
