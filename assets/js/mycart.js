function loadCart() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "API/function.php?type=cart&action=totalorders", true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      var data = JSON.parse(xhr.responseText);
      var tbody = document.getElementById("cart-body");
      tbody.innerHTML = "";

      if (!data.success || data.cart_items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Your cart is empty.</td></tr>';
      } else {
        var subtotal = 0;
        data.cart_items.forEach(function(item) {
          var price = parseFloat(item.item_price);
          var qty = parseInt(item.item_quantity);
          var total = price * qty;
          subtotal += total;

          var row = document.createElement("tr");
          row.innerHTML = `
            <td class="text-start">
              <div class="d-flex align-items-center">
                <img src="${item.image_url}" style="width:60px; height:60px; object-fit:cover;">
                <span class="ms-2 fw-semibold">${item.item_name}</span>
              </div>
            </td>
            <td>₱${price.toFixed(2)}</td>
            <td><input type="number" value="${qty}" min="1" readonly></td>
            <td class="item-total">₱${total.toFixed(2)}</td>
            <td>
              <button class="btn btn-sm btn-danger" onclick="removeItem('${item.item_id}', this)">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          `;
          tbody.appendChild(row);
        });

        updateSummary(subtotal);
      }
    }
  };
  xhr.send();
}

function updateTotal(input) {
  let price = parseFloat(input.parentNode.parentNode.children[1].innerText.replace('₱', ''));
  let qty = input.value;
  let total = price * qty;
  input.parentNode.parentNode.querySelector(".item-total").innerText = "₱" + total;

  let subtotal = 0;
  document.querySelectorAll(".item-total").forEach(t => {
    subtotal += parseFloat(t.innerText.replace('₱', ''));
  });

  updateSummary(subtotal);
}

function updateSummary(subtotal) {
  let delivery = 49;
  let total = subtotal + delivery;

  document.getElementById("subtotal").innerText = "₱" + subtotal.toFixed(2);
  document.getElementById("delivery").innerText = "₱" + delivery.toFixed(2);
  document.getElementById("total").innerText = "₱" + total.toFixed(2);
}

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

function removeItem(itemId, btn) {
  Swal.fire({
    title: '<b>Remove Item?</b>',
    text: 'Are you sure you want to remove this item from your cart?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#c8102e',
    cancelButtonColor: '#6c757d',
    confirmButtonText: '<b>Yes</b>',
    cancelButtonText: '<b>No</b>'
  }).then((result) => {
    if (result.isConfirmed) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "API/function.php?type=cart&action=remove", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onload = function() {
        if (xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          if (data.success) {
            Swal.fire('<b>Removed!</b>', 'Item has been removed.', 'success');
            btn.closest("tr").remove();
            let subtotal = 0;
            document.querySelectorAll(".item-total").forEach(td => {
              subtotal += parseFloat(td.textContent.replace('₱', ''));
            });

            // Refresh everything
            updateSummary(subtotal);
            loadCart();
          }
        }
      };
      xhr.send("item_id=" + encodeURIComponent(itemId));
    }
  });
}
loadCart();