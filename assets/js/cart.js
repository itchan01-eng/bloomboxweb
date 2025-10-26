var cartCount = document.getElementById("cart-count");
var cartDrop = document.getElementById("cart-dropdown");

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

                if (!res.cart_items.length) {
                    cartDrop.innerHTML = '<li class="text-center text-muted">Your cart is empty.</li>';
                    return;
                }

                res.cart_items.slice(0, 5).forEach(function(item) {
                    cartDrop.innerHTML +=
                        '<li class="d-flex align-items-center mb-2">' +
                        '<img src="' + item.image_url + '" style="width:40px; height:40px; object-fit:cover; border-radius:5px; margin-right:10px;">' +
                        '<div><small class="fw-bold">' + item.item_name + '</small><br>' +
                        '<small class="text-muted">₱' + parseFloat(item.item_price).toFixed(2) + ' × ' + item.item_quantity + '</small></div>' +
                        '</li>';
                });

                cartDrop.innerHTML +=
                    '<li><hr class="dropdown-divider"></li>' +
                    '<li><a class="dropdown-item text-center fw-bold text-danger" href="cart.php">View All</a></li>';
            }
        }
    };
    x.send();
}
updateCart();
setInterval(updateCart, 3000);
