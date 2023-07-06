function gtmInit() {
    window.dataLayer = window.dataLayer || [];
}

function gtmPush(obj) {
    if (isDev()) {
        console.log(obj);
    }
    else {
        dataLayer.push(obj);
    }
}