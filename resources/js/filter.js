document.addEventListener("alpine:init", () => {
    Alpine.data("filterData", () => ({
        productType:
            new URLSearchParams(window.location.search).get("product_type") ||
            "",
        type: new URLSearchParams(window.location.search).get("type") || "",
        location:
            new URLSearchParams(window.location.search).get("location") || "",

        applyFilter() {
            console.log(
                "Filter diterapkan:",
                this.productType,
                this.productType,
                this.location
            ); // Debugging

            let url = new URL(window.location.href);
            if (this.type) {
                url.searchParams.set("type", this.type);
            } else {
                url.searchParams.delete("type");
            }
            if (this.productType) {
                url.searchParams.set("product_type", this.productType);
            } else {
                url.searchParams.delete("product_type");
            }
            if (this.location) {
                url.searchParams.set("location", this.location);
            } else {
                url.searchParams.delete("location");
            }

            window.location.href = url.toString();
        },
    }));
});
