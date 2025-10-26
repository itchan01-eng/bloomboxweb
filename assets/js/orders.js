function logoutConfirmation() {
    Swal.fire({
        title: "Are you sure?",
        html: "Do you want to logout and leave us hanging?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, proceed!",
        cancelButtonText: "No, cancel it!",
        customClass: {
            confirmButton: "btn btn-primary m-2",
            cancelButton: "btn btn-primary"
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "orders.php";
        }
    });
}

const backTop = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
        backTop.style.display = window.scrollY > 400 ? "block" : "none";
    });
    
    backTop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
    
    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const orderId = btn.getAttribute("data-id");
            Swal.fire({
                title: "Cancel Order?",
                text: "Are you sure you want to cancel Order #" + orderId + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, cancel it",
                cancelButtonText: "No",
                confirmButtonColor: "#c8102e"
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire("Cancelled!", "Your order #" + orderId + " has been cancelled.", "success");
                }
            });
        });
    });
    
    document.querySelectorAll(".view-status-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("modalOrderId").textContent = btn.dataset.id;
            document.getElementById("modalStatus").textContent = btn.dataset.status;
            document.getElementById("modalName").textContent = btn.dataset.fname + " " + btn.dataset.lname;
            document.getElementById("modalPhone").textContent = btn.dataset.phone;
            document.getElementById("modalBarangay").textContent = btn.dataset.barangay;
            document.getElementById("modalAddress").textContent = btn.dataset.address;
            document.getElementById("modalNote").textContent = btn.dataset.notes || "No notes provided.";
            new bootstrap.Modal(document.getElementById("statusModal")).show();
        });
    });