<!-- src/components/CarMatForm.vue -->
<template>
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r bg-slate-900 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white">
                        Configurateur de tapis sur mesure
                    </h2>
                    <p class="mt-1 text-white">
                        Sélectionnez les caractéristiques de votre véhicule
                    </p>
                </div>

                <form @submit.prevent="handleSubmit" class="p-6">
                    <!-- Brand Select -->
                    <div class="form-group">
                        <label class="form-label">Marque du véhicule</label>
                        <select
                            v-model="store.selectedBrand.id"
                            class="form-select min-w-xl"
                            required
                            @change="handleBrandChange"
                        >
                            <!-- Selected default brand -->
                            <option value="0">Sélectionner une marque</option>
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
                            v-model="store.selectedModel.id"
                            class="form-select min-w-xl"
                            :disabled="!store.selectedBrand.id || isLoading"
                            required
                            @change="handleModelChange"
                        >
                            <option value="0">
                                {{
                                    isLoading
                                        ? "Chargement..."
                                        : "Sélectionner un modèle"
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
                            v-model="store.selectedVersion.id"
                            class="form-select min-w-xl"
                            :disabled="!store.selectedModel.id"
                            required
                            @change="handleVersionChange"
                        >
                            <option value="0">Sélectionnez une version</option>
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
                        <label class="form-label mb-4" id="gamme-title"
                            >Gamme</label
                        >
                        <div
                            v-if="!store.selectedCarbody.id"
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
                                    store.selectedGamme.id === gamme.id
                                        ? 'border-blue-500 bg-blue-50 shadow-md'
                                        : 'border-gray-200 hover:border-blue-300 hover:shadow-sm',
                                ]"
                            >
                                <div class="aspect-video rounded-md">
                                    <!-- Emplacement pour l'image future -->
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-400"
                                    >
                                        <img
                                            :id="`img-gamme-${gamme.id}`"
                                            :src="`/modules/carmatselector/assets/img/gamme/tapis-auto-gamme-${gamme.id}.jpg`"
                                            :alt="`Tapis auto gamme ${gamme.name}`"
                                        />
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-center mb-2">
                                    <div
                                        v-for="color in store.availableGammeColors.filter(
                                            (color) =>
                                                color.id_gamme == gamme.id
                                        )"
                                        :key="color.id"
                                        :style="{
                                            backgroundColor: color.hex_color,
                                        }"
                                        :title="color.name"
                                        @click.stop="
                                            changeGammeColorImage(
                                                gamme.id,
                                                color.id
                                            )
                                        "
                                        class="w-6 h-6 rounded-full inline-block hover:border-black border-2 border-solid"
                                    ></div>
                                </div>
                                <div class="flex flex-row justify-center">
                                    <h3
                                        class="font-medium text-center text-lg pr-1"
                                    >
                                        <!-- Rating is gamme.rating * ⭐-->
                                        {{ gamme.name }}
                                        <!-- {{ "⭐".repeat(gamme.rating) }} -->
                                    </h3>
                                    <img
                                        v-for="i in gamme.rating"
                                        src="{{ asset('../../../../../../assets/img/icons/star.png')}}"
                                        alt="⭐"
                                        :key="i"
                                        class="w-6 h-6"
                                    />
                                </div>
                                <div class="flex flex-col text-left mt-1">
                                    <ul class="list-disc ml-4">
                                        <li class="text-slate-700 mb-1">
                                            {{ gamme.carpeting }}
                                        </li>
                                        <li class="text-slate-700 mb-1">
                                            {{ gamme.outline }}
                                        </li>
                                        <li class="text-slate-700 mb-1">
                                            {{ gamme.material }}
                                        </li>
                                        <li class="text-slate-700 mb-1">
                                            {{ gamme.undercoat }}
                                        </li>
                                    </ul>
                                </div>
                                <p
                                    class="text-neutral-950 text-center mt-2 text-base font-bold"
                                >
                                    {{ gamme.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label
                            for="configuration"
                            class="form-label mb-4"
                            id="configuration-title"
                            >Configuration</label
                        >
                        <div
                            v-if="!store.selectedGamme.id"
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
                                    handleConfigurationChange(configuration.id)
                                "
                                :class="[
                                    'cursor-pointer p-4 border rounded-lg transition-all duration-200',
                                    store.selectedConfiguration.id ===
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
                                        <img
                                            :src="`/modules/carmatselector/assets/img/configuration/configuration-${store.selectedGamme.id}-${configuration.id}.png`"
                                            :alt="`Tapis auto configuration ${configuration.name}`"
                                        />
                                    </div>
                                </div>
                                <h3 class="font-medium text-center">
                                    <span>{{ configuration.name }}</span> <br />
                                    <span class="font-bold text-lg mt-2">
                                        {{
                                            calculatePrice(
                                                configuration.price,
                                                configuration.rate
                                            )
                                        }}</span
                                    >
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label
                            for="color"
                            class="form-label mb-4"
                            id="color-title"
                            >Couleur</label
                        >
                        <div
                            v-if="!store.selectedConfiguration.id"
                            class="text-gray-500 italic"
                        >
                            Sélectionnez d'abord une configuration pour voir les
                            couleurs disponibles
                        </div>
                        <div v-else class="grid grid-cols-2 gap-4">
                            <div
                                v-for="color in store.availableColors"
                                :key="color.id"
                                @click="handleColorChange(color)"
                                :class="[
                                    'cursor-pointer p-4 border rounded-lg transition-all duration-200',
                                    store.selectedColor.id === color.id
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
                                        <img
                                            :src="`/modules/carmatselector/assets/img/color/color-${color.id}.jpg`"
                                            :alt="`Tapis auto configuration ${color.name}`"
                                        />
                                    </div>
                                </div>
                                <h3 class="font-medium text-center">
                                    {{ convertToPascalCase(color.name) }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div
                        v-if="isLoading"
                        class="pt-4 flex flex-col justify-center items-center"
                    >
                        <div class="loader mx-auto"></div>
                    </div>
                    <div
                        v-if="isLoading"
                        class="pt-4 flex flex-col justify-center"
                    >
                        <p class="mb-4 text-neutral-950 text-center">
                            Chargement des informations...
                        </p>
                    </div>
                    <div
                        v-if="store.productToAdd['id']"
                        id="product-price"
                        class="pt-4 flex flex-col justify-center"
                    >
                        <p class="mb-4 text-neutral-950 text-center">
                            Prix de votre configuration :
                            <span class="font-bold text-lg">
                                {{
                                    calculatePrice(
                                        store.productToAdd.price,
                                        store.productToAdd.rate
                                    )
                                }}</span
                            >
                        </p>
                        <div class="flex justify-center mt-4">
                            <input
                                :disabled="isLoading"
                                type="submit"
                                class="btn btn-primary"
                                :value="
                                    isLoading
                                        ? 'Ajout en cours...'
                                        : 'Ajouter au panier'
                                "
                            />
                        </div>
                    </div>
                    <div
                        v-else-if="
                            store.productToAdd['id'] == null &&
                            store.selectedColor.id != null
                        "
                        id="product-price"
                        class="pt-4 flex flex-col justify-center"
                    >
                        <p class="mb-4 text-red-600 text-center text-lg">
                            La configuration demandée n'est pas disponible pour
                            votre véhicule.
                        </p>
                    </div>
                </form>
                <div v-if="cartModal" class="fixed inset-0 z-10">
                    <div
                        class="fixed inset-0 bg-neutral-950 bg-opacity-50 flex justify-center items-center p-4"
                    >
                        <div
                            class="w-full max-w-2xl bg-white rounded-lg shadow-xl p-6 py-8"
                        >
                            <div class="flex justify-end text-lg font-bold">
                                <button @click="cartModal = false">X</button>
                            </div>
                            <h3
                                class="text-xl font-bold text-orange-500 text-center"
                            >
                                Vos tapis sur mesure ont été ajoutés au panier !
                            </h3>
                            <div class="flex flex-col gap-4 mt-8 ml-8">
                                <p>
                                    Pour votre
                                    <span class="font-bold text-justify">{{
                                        store.selectedVersion.name
                                    }}</span>
                                </p>
                                <p>
                                    <span class="text-orange-500"
                                        >Configuration : </span
                                    >{{ store.selectedConfiguration.name }}
                                </p>
                                <p>
                                    <span class="text-orange-500"
                                        >Finition : </span
                                    >Gamme {{ store.selectedGamme.name }}
                                </p>
                                <p>
                                    <span class="text-orange-500"
                                        >Couleur : </span
                                    >{{ store.selectedColor.name }}
                                </p>
                            </div>
                            <div class="flex justify-around mt-8">
                                <a
                                    href=""
                                    class="bg-orange-500 rounded-md transition duration-300 text-white p-4 hover:bg-orange-300"
                                >
                                    Continuer vos achats
                                </a>
                                <a
                                    href="/panier?action=show"
                                    class="bg-orange-500 rounded-md transition duration-300 text-white p-4 hover:bg-orange-300"
                                >
                                    Aller au panier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="!store.cartSummaryVisible"
            class="fixed top-40 right-8 w-72 bg-white rounded-lg shadow-xl p-6 space-y-4"
        >
            <button @click="toggleCartSummary">Montrer le récapitulatif</button>
        </div>
        <div
            v-else
            class="fixed top-40 right-8 w-72 bg-white rounded-lg shadow-xl p-6 space-y-4"
        >
            <div></div>
            <div>
                <h3 class="font-bold text-lg border-b pb-2">Récapitulatif</h3>
            </div>
            <button @click="toggleCartSummary" class="text-blue-500">
                Cacher le récapitulatif
            </button>

            <!-- Marque -->
            <div v-if="store.selectedBrand.id" class="space-y-1">
                <p class="text-sm text-gray-500">Marque</p>
                <p class="font-medium">
                    {{
                        store.brands.find(
                            (b) => b.id === store.selectedBrand.id
                        )?.name
                    }}
                </p>
            </div>

            <!-- Modèle -->
            <div v-if="store.selectedModel.id" class="space-y-1">
                <p class="text-sm text-gray-500">Modèle</p>
                <p class="font-medium">
                    {{
                        store.availableModels.find(
                            (m) => m.id === store.selectedModel.id
                        )?.name
                    }}
                </p>
            </div>

            <!-- Version -->
            <div v-if="store.selectedVersion.id" class="space-y-1">
                <p class="text-sm text-gray-500">Version</p>
                <p class="font-medium">
                    {{
                        store.availableVersions.find(
                            (v) => v.id === store.selectedVersion.id
                        )?.name
                    }}
                </p>
            </div>

            <!-- Gamme -->
            <div v-if="store.selectedGamme.id" class="space-y-1">
                <p class="text-sm text-gray-500">Gamme</p>
                <p class="font-medium">
                    {{
                        store.availableGammes.find(
                            (g) => g.id === store.selectedGamme.id
                        )?.name
                    }}
                </p>
            </div>

            <!-- Configuration -->
            <div v-if="store.selectedConfiguration.id" class="space-y-1">
                <p class="text-sm text-gray-500">Configuration</p>
                <p class="font-medium">
                    {{
                        store.availableConfigurations.find(
                            (c) => c.id === store.selectedConfiguration.id
                        )?.name
                    }}
                </p>
            </div>

            <!-- Couleur -->
            <div v-if="store.selectedColor.id" class="space-y-1">
                <p class="text-sm text-gray-500">Couleur</p>
                <p class="font-medium">
                    {{
                        store.availableColors.find(
                            (c) => c.id === store.selectedColor.id
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
const cartModal = ref(false);

const toggleCartSummary = () => {
    store.cartSummaryVisible = !store.cartSummaryVisible;
};

const handleBrandChange = async () => {
    // Reset subsequent selections
    store.selectedModel = { id: 0, name: null };
    store.selectedVersion = { id: 0, name: null };
    store.productToAdd = [];

    if (store.selectedBrand.id) {
        const selectedBrandData = store.brands.find(
            (b) => b.id === store.selectedBrand.id
        );
        store.selectedBrand.name = selectedBrandData?.name;

        console.log("Fetching models for brand:", store.selectedBrand);
        isLoading.value = true;
        try {
            const models = await store.fetchModels(store.selectedBrand.id);
            console.log("Fetched models:", models);
        } catch (error) {
            console.error("Error in handleBrandChange:", error);
        } finally {
            isLoading.value = false;
        }
    }
};

const handleModelChange = async () => {
    store.selectedVersion = { id: 0, name: null };
    store.productToAdd = [];

    if (store.selectedModel.id) {
        const selectedModelData = store.availableModels.find(
            (m) => m.id === store.selectedModel.id
        );
        store.selectedModel.name = selectedModelData?.name;

        console.log("Fetching versions for model:", store.selectedModel);
        isLoading.value = true;
        try {
            const versions = await store.fetchVersions(store.selectedModel.id);
            console.log("Fetched versions:", versions);
        } catch (error) {
            console.error("Error in handleModelChange:", error);
        } finally {
            isLoading.value = false;
        }
    }
};

const handleVersionChange = async () => {
    const selectedVersionData = store.availableVersions.find(
        (v) => v.id === store.selectedVersion.id
    );

    store.selectedVersion.name = selectedVersionData?.name;
    store.selectedVersion.gabarit = selectedVersionData?.gabarit;
    store.productToAdd = [];

    if (selectedVersionData?.carbody) {
        isLoading.value = true;
        try {
            store.updateSelectedCarbody(
                selectedVersionData.carbody,
                selectedVersionData.carbody_name
            );
            await Promise.all([
                store.fetchGammesByCarbody(selectedVersionData.carbody),
                // store.fetchConfigurationsByGamme(selectedVersionData.carbody),
            ]);
        } catch (error) {
            console.error("Error in handleVersionChange:", error);
        } finally {
            isLoading.value = false;
            scrollToNextStep("gamme-title");
        }
    } else {
        store.resetSelections();
    }
};

const handleGammeChange = async (gammeId) => {
    console.log("Gamme ID:", gammeId);

    store.selectedConfiguration = { id: null, name: null };
    store.selectedColor = { id: null, name: null };

    const selectedGammeData = store.availableGammes.find(
        (g) => g.id === gammeId
    );
    store.selectedGamme = {
        id: gammeId,
        name: selectedGammeData?.name,
    };
    store.productToAdd = [];

    if (store.selectedGamme.id) {
        console.log("Fetching configurations for gamme:", store.selectedGamme);
        isLoading.value = true;
        try {
            await store.fetchConfigurationsByGamme(store.selectedGamme.id);
        } catch (error) {
            console.error("Error in handleGammeChange:", error);
        } finally {
            isLoading.value = false;
            scrollToNextStep("configuration-title");
        }
    }
};

const handleConfigurationChange = async (configurationId) => {
    store.selectedColor = { id: null, name: null };
    const selectedConfigData = store.availableConfigurations.find(
        (c) => c.id === configurationId
    );
    store.selectedConfiguration = {
        id: configurationId,
        name: selectedConfigData?.name,
    };

    store.productToAdd = [];

    if (store.selectedConfiguration.id) {
        console.log("Fetching colors for gamme:", store.selectedGamme);
        isLoading.value = true;
        try {
            await store.fetchColorsByGamme(store.selectedGamme.id);
        } catch (error) {
            console.error("Error in handleGammeChange:", error);
        } finally {
            isLoading.value = false;
            scrollToNextStep("color-title");
        }
    }
};

const handleColorChange = async (color) => {
    isLoading.value = true;
    // wait 5 seconds before fetching the product
    store.selectedColor.id = color.id;
    store.selectedColor.name = color.name;
    store.productToAdd = [];
    try {
        await store.fetchProduct();
    } catch (error) {
        console.error("Error in handleGammeChange:", error);
    } finally {
        isLoading.value = false;
        scrollToNextStep("product-price");
    }
};

const handleSubmit = async () => {
    isLoading.value = true;
    try {
        await new Promise((resolve) => setTimeout(resolve, 1500));
        await store.addToCart();
    } catch (error) {
        console.error("Error in handleGammeChange:", error);
    } finally {
        isLoading.value = false;
        cartModal.value = true;
    }
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

const changeGammeColorImage = (gammeId, colorId) => {
    document.getElementById(
        `img-gamme-${gammeId}`
    ).src = `/modules/carmatselector/assets/img/color/color-${colorId}.jpg`;
};

const convertToPascalCase = (str) => {
    return str.replace(/(?:^\w|\b\w)/g, (letter) => {
        return letter.toUpperCase();
    });
};

const calculatePrice = (productPrice, productRate) => {
    const calculatedPrice =
        Number(productPrice) +
        Number(productPrice) * (Number(productRate) / 100);
    return calculatedPrice.toFixed(2) + "€";
};

const scrollToNextStep = (id) => {
    window.scrollTo({
        top: document.getElementById(id).offsetTop,
        behavior: "smooth",
    });
};
</script>

<style scoped>
.form-select {
    margin: 0.5em 0 0.5em 1em;
    border: 1px solid #ccc;
    padding: 0.5em;
    background-color: #fff;
}

.form-label {
    font-size: 1.1em;
    margin-right: 1em;
}

.form-select:disabled {
    background-color: #f3f4f6;
}
</style>
