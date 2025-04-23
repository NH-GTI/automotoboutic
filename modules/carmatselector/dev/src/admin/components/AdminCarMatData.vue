<template>
    <div v-if="formSuccess == 1" class="alert alert-success">
        <p class="text-success text-lg">Formulaire enregistré avec succès !</p>
    </div>
    <div class="px-8 mb-4">
        <a :href="carMatBrandLink" class="btn btn-primary mx-2">Marques</a>
        <a :href="carMatModelLink" class="btn btn-primary mx-2">Modèles</a>
        <a :href="carMatVersionLink" class="btn btn-primary mx-2">Versions</a>
        <a :href="carMatColorLink" class="btn btn-primary mx-2">Couleurs</a>
        <a :href="carMatGammeLink" class="btn btn-primary mx-2">Gammes</a>
        <a :href="carMatConfigurationLink" class="btn btn-primary mx-2"
            >Configurations</a
        >
        <a :href="carMatCarBodyLink" class="btn btn-primary mx-2"
            >Carrosseries</a
        >
        <a :href="carMatAttachmentLink" class="btn btn-primary mx-2"
            >Fixations</a
        >
    </div>
    <div class="mb-4 px-8 flex gap-4">
        <div class="flex flex-col">
            <label class="text-sm text-gray-500 mb-1"
                >Rechercher par identifiant</label
            >
            <input
                v-model="searchId"
                type="text"
                placeholder="Entrer l'identifiant..."
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
            />
        </div>
        <div class="flex flex-col">
            <label class="text-sm text-gray-500 mb-1">Rechercher par nom</label>
            <input
                v-model="searchName"
                type="text"
                placeholder="Entrer le nom..."
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
            />
        </div>
        <div class="flex items-end">
            <button
                @click="resetSearch"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md transition-colors"
            >
                Réinitialiser
            </button>
        </div>
        <div class="flex items-end">
            <a
                :href="carMatVersionFormLink"
                class="px-4 py-2 bg-white text-white rounded-md pointer-events-auto text-base"
            >
                Ajouter un nouveau véhicule
            </a>
        </div>
        <div class="flex items-end">
            <a
                :href="carMatModelFormLink"
                class="px-4 py-2 bg-white text-white rounded-md pointer-events-auto text-base"
            >
                Ajouter un nouveau modèle
            </a>
        </div>
    </div>

    <table class="w-full table-auto border-collapse text-sm">
        <thead>
            <tr>
                <th
                    class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200"
                >
                    Identifiant
                </th>
                <th
                    class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200"
                >
                    Nom
                </th>
                <th
                    v-if="type === '2'"
                    class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200"
                >
                    Marque
                </th>
                <th
                    class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200"
                >
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800">
            <tr v-for="brand in paginatedData" :key="brand.id">
                <td
                    class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400"
                >
                    {{ brand.id }}
                </td>
                <td
                    class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400"
                >
                    {{ brand.name }}
                </td>
                <td
                    v-if="type === '2'"
                    class="border-b border-gray-100 p-4 pl-8 text-gray-500 dark:border-gray-700 dark:text-gray-400"
                >
                    {{ brand.brand_name }}
                </td>
                <td
                    class="border-b border-gray-100 p-4 pl-8 text-white dark:border-gray-700 dark:text-white"
                >
                    <a
                        :href="`${carMatModelEditLink}&action=editForm&id=${brand.id}&type=${type}`"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                        Edit
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- pagination -->
    <div class="flex justify-between items-center mt-4 px-8">
        <div class="text-sm text-gray-500">
            Affichage {{ startIndex + 1 }} à {{ endIndex }} sur
            {{ totalItems }} entrées
        </div>
        <div class="flex items-center gap-2">
            <button
                @click="previousPage"
                :disabled="currentPage === 1"
                :class="[
                    'px-2 py-1 rounded-md',
                    currentPage === 1
                        ? 'bg-gray-100 text-gray-400'
                        : 'bg-gray-200 dark:bg-gray-700',
                ]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>

            <div class="flex gap-1">
                <button
                    v-for="pageNum in displayedPages"
                    :key="pageNum"
                    @click="goToPage(pageNum)"
                    :class="[
                        'px-3 py-1 rounded-md',
                        currentPage === pageNum
                            ? 'bg-blue-500 text-white'
                            : 'bg-gray-200 dark:bg-gray-700',
                    ]"
                >
                    {{ pageNum }}
                </button>
            </div>

            <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                :class="[
                    'px-2 py-1 rounded-md',
                    currentPage === totalPages
                        ? 'bg-gray-100 text-gray-400'
                        : 'bg-gray-200 dark:bg-gray-700',
                ]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";

    const initialData = ref(window.CARMAT_ADMIN_DATA);
    const formSuccess = ref(window.CARMAT_ADMIN_FORM_SUCCESS);

    const carMatBrandLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=1&configure=carmatselector"
    );
    const carMatModelLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=2&configure=carmatselector"
    );
    const carMatVersionLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=3&configure=carmatselector"
    );
    const carMatColorLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=4&configure=carmatselector"
    );
    const carMatGammeLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=5&configure=carmatselector"
    );
    const carMatConfigurationLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=6&configure=carmatselector"
    );
    const carMatCarBodyLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=7&configure=carmatselector"
    );
    const carMatAttachmentLink = ref(
        window.CARMAT_ADMIN_LINK_URL + "&type=8&configure=carmatselector"
    );

    const carMatVersionFormLink = ref(
        window.CARMAT_ADMIN_AJAX_URL + "&action=versionForm"
    );

    const carMatModelFormLink = ref(
        window.CARMAT_ADMIN_AJAX_URL + "&action=modelForm"
    );

    const carMatModelEditLink = ref(window.CARMAT_ADMIN_AJAX_URL);
    const type = ref(window.CARMAT_ADMIN_TYPE);

    const itemsPerPage = 10;
    const currentPage = ref(1);
    const searchId = ref("");
    const searchName = ref("");

    // Filter data based on search inputs
    const filteredData = computed(() => {
        return initialData.value.filter((item) => {
            const idMatch = item.id
                .toString()
                .toLowerCase()
                .includes(searchId.value.toLowerCase());
            const nameMatch = item.name
                .toLowerCase()
                .includes(searchName.value.toLowerCase());
            return idMatch && nameMatch;
        });
    });

    // Reset search inputs
    const resetSearch = () => {
        searchId.value = "";
        searchName.value = "";
        currentPage.value = 1; // Reset to first page after clearing search
    };

    // Computed properties for pagination
    const totalItems = computed(() => filteredData.value.length);
    const totalPages = computed(() =>
        Math.ceil(totalItems.value / itemsPerPage)
    );

    const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
    const endIndex = computed(() =>
        Math.min(startIndex.value + itemsPerPage, totalItems.value)
    );

    const paginatedData = computed(() => {
        return filteredData.value.slice(startIndex.value, endIndex.value);
    });

    // Generate array of page numbers to display
    const displayedPages = computed(() => {
        const pages = [];
        const maxPagesToShow = 5;
        let start = Math.max(
            1,
            currentPage.value - Math.floor(maxPagesToShow / 2)
        );
        let end = Math.min(totalPages.value, start + maxPagesToShow - 1);

        if (end - start + 1 < maxPagesToShow) {
            start = Math.max(1, end - maxPagesToShow + 1);
        }

        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
        return pages;
    });

    // Navigation methods
    const previousPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--;
        }
    };

    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++;
        }
    };

    const goToPage = (page) => {
        currentPage.value = page;
    };
</script>

<style scoped>
    .dot {
        transition: all 0.3s ease-in-out;
    }
</style>
