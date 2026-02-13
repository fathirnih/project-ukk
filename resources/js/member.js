import "bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebarContainer");

    if (sidebar) {
        const collapsed = localStorage.getItem("sidebarCollapsed") === "true";
        sidebar.classList.toggle("collapsed", collapsed);
        document.documentElement.classList.toggle("member-sidebar-collapsed", collapsed);

        window.requestAnimationFrame(() => {
            sidebar.classList.add("member-sidebar-anim");
        });
    }

    initMemberAutoRedirect();
    initPeminjamanForm();
});

window.toggleSidebar = function toggleSidebar() {
    const sidebar = document.getElementById("sidebarContainer");
    if (!sidebar) return;
    if (sidebar.classList.contains("is-toggling")) return;

    sidebar.classList.add("is-toggling");
    sidebar.classList.toggle("collapsed");
    const collapsed = sidebar.classList.contains("collapsed");
    document.documentElement.classList.toggle("member-sidebar-collapsed", collapsed);
    localStorage.setItem("sidebarCollapsed", String(collapsed));

    window.setTimeout(() => {
        sidebar.classList.remove("is-toggling");
    }, 320);
};

window.toggleMobileSidebar = function toggleMobileSidebar() {
    const mobileSidebar = document.getElementById("mobileSidebar");
    if (!mobileSidebar) return;

    mobileSidebar.style.display = mobileSidebar.style.display === "block" ? "none" : "block";
};

window.closeMobileSidebar = function closeMobileSidebar(event) {
    if (event && event.target.closest(".mobile-shell")) {
        return;
    }

    const mobileSidebar = document.getElementById("mobileSidebar");
    if (mobileSidebar) {
        mobileSidebar.style.display = "none";
    }
};

function initMemberAutoRedirect() {
    const redirectNode = document.querySelector("[data-auto-redirect-url]");
    if (!redirectNode) return;

    const targetUrl = redirectNode.getAttribute("data-auto-redirect-url");
    const delay = Number.parseInt(redirectNode.getAttribute("data-auto-redirect-delay") || "1000", 10);

    if (!targetUrl) return;

    window.setTimeout(() => {
        window.location.href = targetUrl;
    }, Number.isNaN(delay) ? 1000 : delay);
}

function initPeminjamanForm() {
    const form = document.getElementById("peminjamanForm");
    if (!form) return;

    const checkboxes = form.querySelectorAll(".buku-checkbox");
    const submitBtn = document.getElementById("submitBtn");
    const bukuIds = document.getElementById("bukuIds");

    if (!submitBtn || !bukuIds) return;

    const updateFormState = () => {
        const selectedIds = [];

        checkboxes.forEach((checkbox) => {
            const item = checkbox.closest("[data-book-item]") || checkbox.closest("tr");
            if (!item) return;

            const jumlahInput = item.querySelector(".jumlah-input");
            if (!jumlahInput) return;
            const qtyWrap = jumlahInput.closest(".member-qty-wrap") || jumlahInput.closest("td");

            if (checkbox.checked) {
                selectedIds.push(checkbox.dataset.id);
                jumlahInput.disabled = false;
                item.classList.add("is-active");
                qtyWrap?.classList.add("is-active");
            } else {
                jumlahInput.disabled = true;
                jumlahInput.value = 1;
                item.classList.remove("is-active");
                qtyWrap?.classList.remove("is-active");
            }
        });

        bukuIds.value = JSON.stringify(selectedIds);
        submitBtn.disabled = selectedIds.length === 0;
    };

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", updateFormState);
    });

    form.querySelectorAll(".jumlah-input").forEach((input) => {
        input.addEventListener("change", () => {
            const max = Number.parseInt(input.dataset.max || "1", 10);
            const value = Number.parseInt(input.value || "1", 10);

            if (value < 1 || Number.isNaN(value)) input.value = 1;
            if (!Number.isNaN(max) && value > max) input.value = String(max);

            updateFormState();
        });
    });

    form.addEventListener("submit", (event) => {
        const selected = Array.from(checkboxes).filter((checkbox) => checkbox.checked);
        if (selected.length === 0) {
            event.preventDefault();
            window.alert("Pilih minimal satu buku!");
        }
    });
}
