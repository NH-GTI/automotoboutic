import { defineStore } from "pinia";

const initialData = window.CARMAT_ADMIN_DATA || {};

export const useAdminStore = defineStore("admin", {
    state: () => ({
        brands: initialData.brands || [
            { id: 1, name: "Renault" },
            { id: 2, name: "Peugeot" },
            { id: 3, name: "Citroën" },
            { id: 4, name: "Volkswagen" },
        ],
    }),
    actions: {
        // Other admin actions
    },
});
