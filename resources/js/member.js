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
    initMemberBookFilter();
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
    const anggotaId = String(form.dataset.anggotaId || "guest");
    const selectedIdsStorageKey = `member_peminjaman_selected_ids_${anggotaId}`;
    const qtyStorageKey = `member_peminjaman_qty_${anggotaId}`;
    const legacySelectedIdsStorageKey = "member_peminjaman_selected_ids";
    const legacyQtyStorageKey = "member_peminjaman_qty";

    if (!submitBtn || !bukuIds) return;

    // One-time migration cleanup from old global keys.
    window.sessionStorage.removeItem(legacySelectedIdsStorageKey);
    window.sessionStorage.removeItem(legacyQtyStorageKey);

    const readSelectedIds = () => {
        try {
            const raw = window.sessionStorage.getItem(selectedIdsStorageKey);
            const parsed = raw ? JSON.parse(raw) : [];
            if (!Array.isArray(parsed)) return new Set();
            return new Set(parsed.map((id) => String(id)));
        } catch {
            return new Set();
        }
    };

    const readQtyMap = () => {
        try {
            const raw = window.sessionStorage.getItem(qtyStorageKey);
            const parsed = raw ? JSON.parse(raw) : {};
            if (!parsed || typeof parsed !== "object" || Array.isArray(parsed)) return {};
            return parsed;
        } catch {
            return {};
        }
    };

    const persistState = (selectedIdsSet, qtyMap) => {
        window.sessionStorage.setItem(
            selectedIdsStorageKey,
            JSON.stringify(Array.from(selectedIdsSet)),
        );
        window.sessionStorage.setItem(qtyStorageKey, JSON.stringify(qtyMap));
    };

    const selectedIdsSet = readSelectedIds();
    const qtyMap = readQtyMap();

    // Restore checked state for books rendered on current page.
    checkboxes.forEach((checkbox) => {
        const id = String(checkbox.dataset.id || "");
        if (!id || !selectedIdsSet.has(id)) return;

        checkbox.checked = true;

        const item = checkbox.closest("[data-book-item]") || checkbox.closest("tr");
        if (!item) return;
        const jumlahInput = item.querySelector(".jumlah-input");
        if (!jumlahInput) return;

        const max = Number.parseInt(jumlahInput.dataset.max || "1", 10);
        const savedQty = Number.parseInt(String(qtyMap[id] ?? "1"), 10);
        const clampedQty = Number.isNaN(savedQty) ? 1 : Math.max(1, Math.min(savedQty, Number.isNaN(max) ? savedQty : max));
        jumlahInput.value = String(clampedQty);
        qtyMap[id] = clampedQty;
    });

    const updateFormState = () => {
        checkboxes.forEach((checkbox) => {
            const id = String(checkbox.dataset.id || "");
            if (!id) return;

            const item = checkbox.closest("[data-book-item]") || checkbox.closest("tr");
            if (!item) return;

            const jumlahInput = item.querySelector(".jumlah-input");
            if (!jumlahInput) return;
            const qtyWrap = jumlahInput.closest(".member-qty-wrap") || jumlahInput.closest("td");

            if (checkbox.checked) {
                selectedIdsSet.add(id);
                jumlahInput.disabled = false;
                item.classList.add("is-active");
                qtyWrap?.classList.add("is-active");

                const max = Number.parseInt(jumlahInput.dataset.max || "1", 10);
                let value = Number.parseInt(jumlahInput.value || "1", 10);
                if (Number.isNaN(value) || value < 1) value = 1;
                if (!Number.isNaN(max) && value > max) value = max;
                jumlahInput.value = String(value);
                qtyMap[id] = value;
            } else {
                selectedIdsSet.delete(id);
                delete qtyMap[id];
                jumlahInput.disabled = true;
                jumlahInput.value = 1;
                item.classList.remove("is-active");
                qtyWrap?.classList.remove("is-active");
            }
        });

        persistState(selectedIdsSet, qtyMap);
        bukuIds.value = JSON.stringify(Array.from(selectedIdsSet));
        submitBtn.disabled = selectedIdsSet.size === 0;
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
        if (selected.length === 0 && selectedIdsSet.size === 0) {
            event.preventDefault();
            window.alert("Pilih minimal satu buku!");
            return;
        }

        // Clear persisted selection after user submits a borrowing request.
        window.sessionStorage.removeItem(selectedIdsStorageKey);
        window.sessionStorage.removeItem(qtyStorageKey);
        window.sessionStorage.removeItem(legacySelectedIdsStorageKey);
        window.sessionStorage.removeItem(legacyQtyStorageKey);
    });

    updateFormState();
}

function initMemberBookFilter() {
    const filterForm = document.getElementById("memberBookFilterForm");
    if (!filterForm) return;

    const searchInput = document.getElementById("memberFilterSearch");
    const kategoriSelect = document.getElementById("memberFilterKategori");
    const pengarangSelect = document.getElementById("memberFilterPengarang");
    const penerbitSelect = document.getElementById("memberFilterPenerbit");

    let debounceTimer = null;

    if (searchInput) {
        searchInput.addEventListener("input", () => {
            if (debounceTimer) {
                window.clearTimeout(debounceTimer);
            }

            debounceTimer = window.setTimeout(() => {
                filterForm.submit();
            }, 400);
        });
    }

    [kategoriSelect, pengarangSelect, penerbitSelect].forEach((selectNode) => {
        if (!selectNode) return;
        selectNode.addEventListener("change", () => {
            filterForm.submit();
        });
    });
}
