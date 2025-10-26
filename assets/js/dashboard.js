var backTop = document.getElementById("backToTop");
document.querySelectorAll(".btn.btn-primary").forEach(function(btn) {
    btn.addEventListener("click", function() {
        var card = this.closest(".product-card");
        var name = card.querySelector(".product-title").innerHTML;
        var itemId = card.getAttribute("data-item-id");
        var qtyElem = card.querySelector(".qty-value");
        var qty = qtyElem ? parseInt(qtyElem.textContent) : 1;

        Swal.fire({
            title: "Add to Cart?",
            html: "Do you want to add <b>" + name + "</b> (x" + qty + ") to your cart?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, add to cart!",
            cancelButtonText: "No, cancel it!",
            customClass: {
                confirmButton: "btn btn-primary m-2",
                cancelButton: "btn btn-primary"
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                var x = new XMLHttpRequest();
                x.open("POST", "API/function.php?type=cart&action=add", true);
                x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                x.onload = function() {
                    if (x.status === 200) {
                        var res = JSON.parse(x.responseText);
                        if (res.success) {
                            Swal.fire({
                                icon: "success",
                                html: "<b>" + name + "</b> (x" + qty + ") added to cart!",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            updateCart();
                        }
                    }
                };
                x.send("item_id=" + itemId + "&quantity=" + qty);
            }
        });
    });
});

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

function updateCart() {
    var x = new XMLHttpRequest();
    x.open("GET", "API/function.php?type=cart&action=totalorders", true);
    x.onload = function() {
        if (x.status === 200) {
            var res = JSON.parse(x.responseText);
            if (res.success) {
                cartCount.innerText = res.total_orders;
                cartDrop.innerHTML = "";

                if (res.cart_items.length === 0) {
                    cartDrop.innerHTML = '<li class="text-center text-muted">Your cart is empty.</li>';
                } else {
                    res.cart_items.slice(0, 5).forEach(function(item) {
                        var li = document.createElement("li");
                        li.className = "d-flex align-items-center mb-2";
                        li.innerHTML =
                            '<img src="' + item.image_url + '" style="width:40px; height:40px; object-fit:cover; border-radius:5px; margin-right:10px;">' +
                            '<div><small class="fw-bold">' + item.item_name + '</small><br>' +
                            '<small class="text-muted">₱' + parseFloat(item.item_price).toFixed(2) + ' × ' + item.item_quantity + '</small></div>';
                        cartDrop.appendChild(li);
                    });

                    cartDrop.innerHTML +=
                        '<li><hr class="dropdown-divider"></li>' +
                        '<li><a class="dropdown-item text-center fw-bold text-danger" href="cart.php">View All</a></li>';
                }
            }
        }
    };
    x.send();
}

function changeQty(btn, val) {
    var qtyElem = btn.parentNode.querySelector(".qty-value");
    var qty = parseInt(qtyElem.textContent);
    qty = Math.max(1, qty + val);
    qtyElem.textContent = qty;
}

window.addEventListener("scroll", function() {
    backTop.style.display = window.scrollY > 400 ? "block" : "none";
});

backTop.addEventListener("click", function() {
    window.scrollTo({ top: 0, behavior: "smooth" });
});
