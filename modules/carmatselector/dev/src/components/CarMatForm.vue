<!-- src/components/CarMatForm.vue -->
<template>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div
                    class="bg-gradient-to-r from-orange-600 to-orange-800 px-6 py-4"
                >
                    <h2 class="text-2xl font-bold text-white">
                        Configurateur de tapis sur mesure
                    </h2>
                    <p class="mt-1 text-blue-100">
                        Sélectionnez les caractéristiques de votre véhicule
                    </p>
                </div>

                <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
                    <!-- Brand Select -->
                    <div class="form-group">
                        <label class="form-label">Marque du véhicule</label>
                        <select
                            v-model="store.selectedBrand"
                            class="form-select"
                            required
                            @change="handleBrandChange"
                        >
                            <option value="">Sélectionnez une marque</option>
                            <option
                                v-for="brand in store.brands"
                                :key="brand.id"
                                :value="brand.id"
                            >
                                {{ brand.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Model Select -->
                    <div class="form-group">
                        <label class="form-label">Modèle</label>
                        <select
                            v-model="store.selectedModel"
                            class="form-select"
                            :disabled="!store.selectedBrand || isLoading"
                            required
                            @change="handleModelChange"
                        >
                            <option value="">
                                {{
                                    isLoading ? "Chargement..." : "Sélectionner"
                                }}
                            </option>
                            <option
                                v-for="model in store.availableModels"
                                :key="model.id"
                                :value="model.id"
                            >
                                {{ model.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Version Select -->
                    <div class="form-group">
                        <label class="form-label">Version</label>
                        <select
                            v-model="store.selectedVersion"
                            class="form-select"
                            :disabled="!store.selectedModel"
                            required
                            @change="handleVersionChange"
                        >
                            <option value="">Sélectionnez une version</option>
                            <option
                                v-for="version in store.availableVersions"
                                :key="version.id"
                                :value="version.id"
                            >
                                {{ version.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Gamme Select -->
                    <div class="form-group">
                        <label class="form-label mb-4">Gamme</label>
                        <div
                            v-if="!store.selectedCarbody"
                            class="text-gray-500 italic"
                        >
                            Sélectionnez d'abord une version pour voir les
                            gammes disponibles
                        </div>
                        <div v-else class="grid grid-cols-2 gap-4">
                            <div
                                v-for="gamme in store.availableGammes"
                                :key="gamme.id"
                                @click="handleGammeChange(gamme.id)"
                                :class="[
                                    'cursor-pointer p-4 border rounded-lg transition-all duration-200',
                                    store.selectedGamme === gamme.id
                                        ? 'border-blue-500 bg-blue-50 shadow-md'
                                        : 'border-gray-200 hover:border-blue-300 hover:shadow-sm',
                                ]"
                            >
                                <div
                                    class="aspect-video bg-gray-100 rounded-md mb-3"
                                >
                                    <!-- Emplacement pour l'image future -->
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-400"
                                    >
                                        <span>Image {{ gamme.name }} </span>
                                    </div>
                                </div>
                                <h3 class="font-medium text-center">
                                    <!-- Rating is gamme.rating * ⭐-->
                                    {{ gamme.name }}
                                    {{ "⭐".repeat(gamme.rating) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="configuration" class="form-label mb-4"
                            >Configuration</label
                        >
                        <div
                            v-if="!store.selectedGamme"
                            class="text-gray-500 italic"
                        >
                            Sélectionnez d'abord une gamme pour voir les
                            configurations disponibles
                        </div>
                        <div v-else class="grid grid-cols-2 gap-4">
                            <div
                                v-for="configuration in store.availableConfigurations"
                                :key="configuration.id"
                                @click="
                                    store.selectedConfiguration =
                                        configuration.id
                                "
                                :class="[
                                    'cursor-pointer p-4 border rounded-lg transition-all duration-200',
                                    store.selectedConfiguration ===
                                    configuration.id
                                        ? 'border-blue-500 bg-blue-50 shadow-md'
                                        : 'border-gray-200 hover:border-blue-300 hover:shadow-sm',
                                ]"
                            >
                                <div
                                    class="aspect-video bg-gray-100 rounded-md mb-3"
                                >
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-400"
                                    >
                                        <span
                                            >Image
                                            {{ configuration.name }}</span
                                        >
                                    </div>
                                </div>
                                <h3 class="font-medium text-center">
                                    {{ configuration.name }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="color" class="form-label mb-4"
                            >Couleur</label
                        >
                        <div
                            v-if="!store.selectedConfiguration"
                            class="text-gray-500 italic"
                        >
                            Sélectionnez d'abord une configuration pour voir les
                            couleurs disponibles
                        </div>
                        <div v-else class="grid grid-cols-2 gap-4">
                            <div
                                v-for="color in store.availableColors"
                                :key="color.id"
                                @click="store.selectedColor = color.id"
                                :class="[
                                    'cursor-pointer p-4 border rounded-lg transition-all duration-200',
                                    store.selectedColor === color.id
                                        ? 'border-blue-500 bg-blue-50 shadow-md'
                                        : 'border-gray-200 hover:border-blue-300 hover:shadow-sm',
                                ]"
                            >
                                <div
                                    class="aspect-video rounded-md mb-3"
                                    :style="{
                                        backgroundColor: color.hexColor,
                                    }"
                                >
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-400"
                                    >
                                        <span>Image {{ color.name }}</span>
                                    </div>
                                </div>
                                <h3 class="font-medium text-center">
                                    {{ color.name }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02]"
                        >
                            Rechercher les tapis disponibles
                        </button>
                    </div>
                    <div v-if="store.productToAdd.id != 0" class="pt-4">
                        <p>
                            Nous avons trouvé pour vous les tapis qui
                            correspondent parfaitement à votre véhicule. En
                            cliquant sur le bouton ci-dessous, le produit
                            suivant sera ajouté à votre panier :
                            {{ store.productToAdd.name }}
                        </p>
                        <a
                            title="Add to cart"
                            :href="cartUrl"
                            rel="ajax_id_product_1"
                            class=""
                            ><button
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02]"
                            >
                                Add to cart
                            </button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div
            class="fixed top-40 right-8 w-72 bg-white rounded-lg shadow-xl p-6 space-y-4"
        >
            <h3 class="font-bold text-lg border-b pb-2">Récapitulatif</h3>

            <!-- Marque -->
            <div v-if="store.selectedBrand" class="space-y-1">
                <p class="text-sm text-gray-500">Marque</p>
                <p class="font-medium">
                    {{
                        store.brands.find((b) => b.id === store.selectedBrand)
                            ?.name
                    }}
                </p>
            </div>

            <!-- Modèle -->
            <div v-if="store.selectedModel" class="space-y-1">
                <p class="text-sm text-gray-500">Modèle</p>
                <p class="font-medium">
                    {{
                        store.availableModels.find(
                            (m) => m.id === store.selectedModel
                        )?.name
                    }}
                </p>
            </div>

            <!-- Version -->
            <div v-if="store.selectedVersion" class="space-y-1">
                <p class="text-sm text-gray-500">Version</p>
                <p class="font-medium">
                    {{
                        store.availableVersions.find(
                            (v) => v.id === store.selectedVersion
                        )?.name
                    }}
                </p>
            </div>

            <!-- Gamme -->
            <div v-if="store.selectedGamme" class="space-y-1">
                <p class="text-sm text-gray-500">Gamme</p>
                <p class="font-medium">
                    {{
                        store.availableGammes.find(
                            (g) => g.id === store.selectedGamme
                        )?.name
                    }}
                </p>
            </div>

            <!-- Configuration -->
            <div v-if="store.selectedConfiguration" class="space-y-1">
                <p class="text-sm text-gray-500">Configuration</p>
                <p class="font-medium">
                    {{
                        store.availableConfigurations.find(
                            (c) => c.id === store.selectedConfiguration
                        )?.name
                    }}
                </p>
            </div>

            <!-- Couleur -->
            <div v-if="store.selectedColor" class="space-y-1">
                <p class="text-sm text-gray-500">Couleur</p>
                <p class="font-medium">
                    {{
                        store.availableColors.find(
                            (c) => c.id === store.selectedColor
                        )?.name
                    }}
                </p>
            </div>

            <!-- Message si rien n'est sélectionné -->
            <div v-if="!hasAnySelection" class="text-gray-400 italic text-sm">
                Sélectionnez des options pour voir le récapitulatif
            </div>
        </div>
    </div>
</template>

<script setup>
import { useCarMatStore } from "../stores/carmat";
import { computed, ref } from "vue";

const store = useCarMatStore();
const isLoading = ref(false);
const brandOptions = computed(() => {
    return store.brands.map((brand) => ({
        value: brand.id,
        label: brand.name,
    }));
});

const cartUrl = computed(() => {
    return `index.php?controller=cart&add=1&id_product=${store.productToAdd.id}&token=${store.token}`;
});

const handleBrandChange = async () => {
    store.selectedModel = null;
    store.selectedVersion = null;
    console.log(brandOptions.value);

    if (store.selectedBrand) {
        console.log("Fetching models for brand:", store.selectedBrand);
        isLoading.value = true;
        try {
            const models = await store.fetchModels(store.selectedBrand);
            console.log("Fetched models:", models);
        } catch (error) {
            console.error("Error in handleBrandChange:", error);
        } finally {
            isLoading.value = false;
        }
    }
};

const handleModelChange = async () => {
    store.selectedVersion = null;

    if (store.selectedModel) {
        console.log("Fetching versions for model:", store.selectedModel);
        isLoading.value = true;
        try {
            const versions = await store.fetchVersions(store.selectedModel);
            console.log("Fetched versions:", versions);
        } catch (error) {
            console.error("Error in handleBrandChange:", error);
        } finally {
            isLoading.value = false;
        }
    }
};

const handleVersionChange = async () => {
    const selectedVersion = store.availableVersions.find(
        (v) => v.id === store.selectedVersion
    );

    if (selectedVersion?.carbody) {
        isLoading.value = true;
        try {
            store.updateSelectedCarbody(selectedVersion.carbody);

            // Fetch data in parallel
            await Promise.all([
                store.fetchGammesByCarbody(selectedVersion.carbody),
                store.fetchConfigurationsByCarbody(selectedVersion.carbody),
            ]);
        } catch (error) {
            console.error("Error in handleVersionChange:", error);
        } finally {
            isLoading.value = false;
        }
    } else {
        store.resetSelections();
    }
};

const handleGammeChange = async (gamme) => {
    store.selectedColor = null;
    store.selectedGamme = gamme;

    if (store.selectedGamme) {
        console.log("Fetching colors for gamme:", store.selectedGamme);
        isLoading.value = true;
        try {
            const colors = await store.fetchColorsByGamme(store.selectedGamme);
            console.log("Fetched colors:", colors);
        } catch (error) {
            console.error("Error in handleGammeChange:", error);
        } finally {
            isLoading.value = false;
        }
    }
};

const handleSubmit = async () => {
    try {
        const product = await store.fetchProduct();
        console.log("Fetched product:", product);
        console.log("Stored product:", store.productToAdd);
    } catch (error) {
        console.error("Error in handleGammeChange:", error);
    } finally {
        isLoading.value = false;
    }
    console.log({
        brand: store.selectedBrand,
        model: store.selectedModel,
        version: store.selectedVersion,
        carbody: store.selectedCarbody,
        attachment: store.selectedAttachment,
        gamme: store.selectedGamme,
        color: store.selectedColor,
    });
};

const hasAnySelection = computed(() => {
    return (
        store.selectedBrand ||
        store.selectedModel ||
        store.selectedVersion ||
        store.selectedCarbody ||
        store.selectedAttachment ||
        store.selectedGamme ||
        store.selectedConfiguration ||
        store.selectedColor
    );
});
</script>

<style scoped>
.form-group {
    @apply space-y-1;
}

.form-label {
    @apply block text-sm font-medium text-gray-700;
}

.form-select {
    @apply mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md;
}

.form-select[disabled] {
    @apply bg-gray-100 cursor-not-allowed;
}
</style>
