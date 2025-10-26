function loadOrders() {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "API/function.php?type=cart&action=totalorders", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
      let data = JSON.parse(xhr.responseText);
      let list = document.getElementById("order-list");
      list.innerHTML = "";
      let subtotal = 0;

      if (data.success && data.cart_items.length > 0) {
        data.cart_items.forEach(item => {
          let total = parseFloat(item.item_price) * parseInt(item.item_quantity);
          subtotal += total;
          list.innerHTML += `
            <div class="d-flex align-items-center mb-2">
              <img src="${item.image_url}" alt="">
              <div>
                <div><b>${item.item_name}</b></div>
                <small>₱${item.item_price} × ${item.item_quantity}</small>
              </div>
            </div>`;
        });
      } else {
        list.innerHTML = '<p class="text-muted text-center">No items in cart.</p>';
      }

      let delivery = 49;
      let total = subtotal + delivery;
      document.getElementById("subtotal").innerText = "₱" + subtotal.toFixed(2);
      document.getElementById("total").innerText = "₱" + total.toFixed(2);
    }
  };
  xhr.send();
}

document.getElementById("placeOrder").addEventListener("click", function() {
  const fname = document.getElementById("fname").value.trim();
  const lname = document.getElementById("lname").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const barangay = document.getElementById("barangay").value;
  const address = document.getElementById("address").value.trim();
  const notes = document.getElementById("notes").value.trim();

  if (!fname || !lname || !phone || !barangay || !address) {
    Swal.fire({
      icon: "warning",
      title: "Incomplete Information",
      text: "Please fill in all required fields before proceeding.",
      confirmButtonColor: "#c8102e"
    });
    return;
  }

  Swal.fire({
    title: "Confirm Order?",
    text: "Do you want to place this order with Cash on Delivery?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes, Proceed",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#c8102e"
  }).then((res) => {
    if (res.isConfirmed) {
      const x = new XMLHttpRequest();
      x.open("POST", "API/function.php?type=cart&action=orders", true);
      x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      x.onload = function() {
        if (x.status === 200) {
            const response = JSON.parse(x.responseText);
            if (response.success) {
                Swal.fire("Success!", response.message, "success")
                .then(() => window.location.href = "orders.php");
            } else {
                Swal.fire("Error", response.message, "error");
            }
        } else {
          Swal.fire("Error", "Something went wrong while placing your order.", "error");
        }
      };
      x.send(
        "fname=" + encodeURIComponent(fname) +
        "&lname=" + encodeURIComponent(lname) +
        "&phonenumber=" + encodeURIComponent(phone) +
        "&barangay=" + encodeURIComponent(barangay) +
        "&fulladdress=" + encodeURIComponent(address) +
        "&notes=" + encodeURIComponent(notes) +
        "&paymentmethod=cod"
      );
    }
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

loadOrders();
