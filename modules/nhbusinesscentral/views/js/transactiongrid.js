$(document).ready(function () {
    const grid = new window.prestashop.component.Grid('nhbusinesscentraltransactiongrid');
    grid.addExtension(new window.prestashop.component.GridExtensions.FiltersResetExtension());
    grid.addExtension(new window.prestashop.component.GridExtensions.SortingExtension());
    grid.addExtension(new window.prestashop.component.GridExtensions.BulkActionCheckboxExtension());
    grid.addExtension(new window.prestashop.component.GridExtensions.SubmitBulkActionExtension());
});
