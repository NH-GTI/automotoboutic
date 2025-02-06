// src/stores/carStore.js
import { defineStore } from "pinia";

const initialData = window.CARMAT_INITIAL_DATA || {};
const token = window.CARMAT_TOKEN;

export const useCarMatStore = defineStore("car", {
    state: () => ({
        brands: initialData.brands || [
            { id: 1, name: "Renault" },
            { id: 2, name: "Peugeot" },
            { id: 3, name: "Citroën" },
            { id: 4, name: "Volkswagen" },
        ],
        models: {
            1: [
                // Renault models
                { id: 1, name: "Clio" },
                { id: 2, name: "Megane" },
                { id: 3, name: "Captur" },
            ],
            2: [
                // Peugeot models
                { id: 4, name: "208" },
                { id: 5, name: "308" },
                { id: 6, name: "2008" },
            ],
        },
        versions: {
            1: [
                // Versions pour Clio
                { id: 1, name: "Clio 5 (2010-2015)" },
                { id: 2, name: "Clio 5 (2015-2019)" },
                { id: 3, name: "Clio 5 (2019+)" },
            ],
            4: [
                // Versions pour 208
                { id: 4, name: "208 (2012-2019)" },
                { id: 5, name: "208 II (2019+)" },
            ],
            // ... autres versions
        },
        carbodies: [
            { id: 1, name: "Véhicule 2 places" },
            { id: 2, name: "Véhicule 5 places" },
            { id: 3, name: "Break" },
            { id: 4, name: "SUV" },
        ],
        attachments: [
            { id: 1, name: "Universelle" },
            { id: 2, name: "Opel" },
            { id: 3, name: "Volvo" },
        ],
        gammes: [
            { id: 1, name: "Basique", rating: 1 },
            { id: 2, name: "Premium", rating: 2 },
            { id: 3, name: "Grand Tourisme", rating: 3 },
            { id: 4, name: "Carat", rating: 4 },
            { id: 5, name: "Elite", rating: 5 },
        ],
        configurations: [
            { id: 1, name: "2 avants" },
            { id: 2, name: "2 avants + 2 arrière" },
            { id: 3, name: "2 avants + 2 arrière + coffre" },
            { id: 4, name: "2 avants + 1 grand tapis arrière" },
            { id: 5, name: "1 grand tapis avant + 1 grand tapis arrière" },
            { id: 6, name: "1 grand tapis qui recouvre le véhicule" },
        ],
        colors: {
            1: [{ id: 1, name: "Basique noir", hexColor: "#000000" }],
            2: [
                { id: 2, name: "Premium beige", hexColor: "#f5f5dc" },
                { id: 3, name: "Premium gris", hexColor: "#808080" },
                { id: 4, name: "Premium marron", hexColor: "#800000" },
                { id: 5, name: "Premium noir", hexColor: "#000000" },
            ],
            3: [
                { id: 6, name: "Grand Tourisme beige", hexColor: "#f5f5dc" },
                {
                    id: 7,
                    name: "Grand Tourisme bleu marine",
                    hexColor: "#003366",
                },
                { id: 8, name: "Grand Tourisme gris", hexColor: "#808080" },
                { id: 9, name: "Grand Tourisme noir", hexColor: "#000000" },
            ],
            4: [
                { id: 10, name: "Elite beige", hexColor: "#f5f5dc" },
                { id: 11, name: "Elite bleu marine", hexColor: "#003366" },
                { id: 12, name: "Elite bleu tuning", hexColor: "#0066cc" },
                { id: 13, name: "Elite gris", hexColor: "#808080" },
                { id: 14, name: "Elite marron", hexColor: "#800000" },
                { id: 15, name: "Elite noir", hexColor: "#000000" },
                { id: 16, name: "Elite rouge", hexColor: "#ff0000" },
            ],
            5: [
                {
                    id: 17,
                    name: "Carat gris anthracite surpiqure bleu",
                    hexColor: "#003366",
                },
                {
                    id: 18,
                    name: "Carat gris anthracite surpiqure grise",
                    hexColor: "#808080",
                },
                {
                    id: 19,
                    name: "Carat gris anthracite surpiqure rouge",
                    hexColor: "#ff0000",
                },
            ],
        },
        token: token,
        selectedBrand: { id: null, name: null },
        selectedModel: { id: null, name: null },
        selectedVersion: { id: null, name: null, gabarit: null },
        selectedCarbody: { id: null, name: null },
        selectedAttachment: null,
        selectedGamme: { id: null, name: null },
        selectedConfiguration: { id: null, name: null },
        selectedColor: { id: null, name: null },
        availableGammes: [],
        availableConfigurations: [],
        availableColors: [],
        productToAdd: [],
        cartSummaryVisible: true,
    }),
    actions: {
        resetSelections() {
            this.$patch({
                selectedGamme: null,
                selectedConfiguration: null,
                selectedColor: null,
                availableGammes: [],
                availableConfigurations: [],
                availableColors: [],
            });
        },
        setSelectedBrand(brandId) {
            this.selectedBrand = Number(brandId); // Ensure it's a number
        },
        async fetchModels(brandId) {
            try {
                const formData = new FormData();
                formData.append("ajax", "1");
                formData.append("brandId", brandId);

                const response = await fetch(window.CARMAT_AJAX_URL, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                console.log("Models result:", result);

                if (result.success) {
                    // Update the models in the store
                    this.models[brandId] = result.models;
                    return result.models;
                } else {
                    console.error("Error fetching models:", result);
                    return [];
                }
            } catch (error) {
                console.error("Error fetching models:", error);
                return [];
            }
        },
        async fetchVersions(modelId) {
            try {
                const formData = new FormData();
                formData.append("ajax", "1");
                formData.append("modelId", modelId);

                const response = await fetch(window.CARMAT_AJAX_URL, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                console.log("Versions result:", result);

                if (result.success) {
                    // Update the versions in the store
                    this.versions[modelId] = result.versions;
                    return result.versions;
                } else {
                    console.error("Error fetching versions:", result.versions);
                    return [];
                }
            } catch (error) {
                console.error("Error fetching versions:", error);
                return [];
            }
        },
        async fetchGammesByCarbody(carbody) {
            try {
                const formData = new FormData();
                formData.append("ajax", "1");
                formData.append("carbody", carbody);

                const response = await fetch(window.CARMAT_AJAX_URL, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                console.log("Gammes result:", result);

                if (result.success && result.gammes) {
                    this.$patch({
                        availableGammes: result.gammes,
                    });
                    return result.gammes;
                } else {
                    console.error("Error fetching gammes:", result);
                    return [];
                }
            } catch (error) {
                console.error("Error fetching gammes:", error);
                return [];
            }
        },
        async fetchConfigurationsByGamme(gamme) {
            try {
                const formData = new FormData();
                formData.append("ajax", "1");
                formData.append("gammeForConfig", gamme);
                formData.append("carbodyForConfig", this.selectedCarbody.id);

                const productArray = [
                    this.selectedGamme.id,
                    this.selectedCarbody.id,
                ];
                formData.append("productArray", productArray);
                const response = await fetch(window.CARMAT_AJAX_URL, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                console.log("Configurations result:", result);

                if (result.success && result.configurations) {
                    this.$patch({
                        availableConfigurations: result.configurations,
                    });
                    return result.configurations;
                } else {
                    console.error("Error fetching configurations:", result);
                    return [];
                }
            } catch (error) {
                console.error("Error fetching configurations:", error);
                return [];
            }
        },
        async fetchColorsByGamme(gamme) {
            try {
                const formData = new FormData();
                formData.append("ajax", "1");
                formData.append("gammeForColor", gamme);

                const response = await fetch(window.CARMAT_AJAX_URL, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                console.log("Colors result:", result.colors);

                if (result.success && result.colors) {
                    this.$patch({
                        availableColors: result.colors,
                    });
                    return result.colors;
                } else {
                    console.error("Error fetching colors:", result);
                    return [];
                }
            } catch (error) {
                console.error("Error fetching colors:", error);
                return [];
            }
        },
        async fetchProduct() {
            try {
                const formData = new FormData();
                formData.append("ajax", "1");
                formData.append("product", 1);

                const productArray = [
                    [this.selectedBrand.id, this.selectedBrand.name],
                    [
                        this.selectedVersion.id,
                        this.selectedVersion.name,
                        this.selectedVersion.gabarit,
                    ],
                    [this.selectedGamme.id, this.selectedGamme.name],
                    [this.selectedCarbody.id, this.selectedCarbody.name],
                    [
                        this.selectedConfiguration.id,
                        this.selectedConfiguration.name,
                    ],
                    [this.selectedColor.id, this.selectedColor.name],
                ];

                formData.append("productArray", productArray);

                const response = await fetch(window.CARMAT_AJAX_URL, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();
                console.log("Product result:", result);

                if (result.success && result) {
                    this.productToAdd["id"] = result?.product[0]?.id_product;
                    this.productToAdd["name"] = result?.product[0]?.name;
                    this.productToAdd["price"] = result?.product[0]?.price;
                    this.productToAdd["rate"] = result?.product[0]?.rate;

                    return result;
                } else {
                    console.error("Error fetching product:", result);
                    return [];
                }
            } catch (error) {
                console.error("Error fetching product:", error);
                return [];
            }
        },
        updateSelectedCarbody(carbody, carbody_name) {
            this.$patch({
                selectedCarbody: { id: carbody, name: carbody_name }, // Fixed
                selectedGamme: { id: null, name: null },
                selectedConfiguration: { id: null, name: null },
                selectedColor: { id: null, name: null },
                availableGammes: [],
                availableConfigurations: [],
                availableColors: [],
            });
        },
    },
    getters: {
        availableModels: (state) => {
            return state.selectedBrand.id
                ? state.models[state.selectedBrand.id] || []
                : [];
        },
        availableVersions: (state) => {
            return state.selectedModel.id
                ? state.versions[state.selectedModel.id] || []
                : [];
        },
    },
});
