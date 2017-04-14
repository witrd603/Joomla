jQueryFactory(window).unload(function () {
    if (typeof GUnload == 'function') {
        GUnload();
    }
});
