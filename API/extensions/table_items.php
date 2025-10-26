<!-- ===== Meals Section ===== -->
  <h3 class="fw-bold mb-4 text-left">Meals</h3>
  <div class="row g-4 mb-4">
    <?php
      $sql = "SELECT * FROM items WHERE item_type='meals' ORDER BY id DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '
            <div class="col-md-4">
              <div class="product-card shadow-sm" data-item-id="'.$row['item_id'].'">
                <img src="'.$row["item_img"].'" alt="Meal">
                <h5 class="product-title" data-item-name="'.$row['item_name'].'">'.$row["item_name"].'</h5>
                <p class="product-price">₱'.number_format($row["item_price"], 2).'</p>
                <div class="quantity-selector">
                  <button type="button" class="btn btn-sm btn-danger" onclick="changeQty(this, -1)">−</button>
                  <span class="qty-value fw-bold mx-2">1</span>
                  <button type="button" class="btn btn-sm btn-success" onclick="changeQty(this, 1)">+</button>
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-cart-plus"></i> Add to Cart</button>
              </div>
            </div>';
        }
      } else { echo "<p class='text-center text-muted'>No products available.</p>"; }
    ?>
  </div>

  <!-- ===== Snacks Section ===== -->
  <h3 class="fw-bold mb-4 text-left">Snacks</h3>
  <div class="row g-4 mb-4">
    <?php
      $sql = "SELECT * FROM items WHERE item_type='snacks' ORDER BY id DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '
            <div class="col-md-4">
              <div class="product-card shadow-sm" data-item-id="'.$row['item_id'].'">
                <img src="'.$row["item_img"].'" alt="Meal">
                <h5 class="product-title" data-item-name="'.$row['item_name'].'">'.$row["item_name"].'</h5>
                <p class="product-price">₱'.number_format($row["item_price"], 2).'</p>
                <div class="quantity-selector">
                  <button type="button" class="btn btn-sm btn-danger" onclick="changeQty(this, -1)">−</button>
                  <span class="qty-value fw-bold mx-2">1</span>
                  <button type="button" class="btn btn-sm btn-success" onclick="changeQty(this, 1)">+</button>
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-cart-plus"></i> Add to Cart</button>
              </div>
            </div>';
        }
      } else { echo "<p class='text-center text-muted'>No products available.</p>"; }
    ?>
  </div>

  <!-- ===== Drinks Section ===== -->
  <h3 class="fw-bold mb-4 text-left">Drinks</h3>
  <div class="row g-4 mb-4">
    <?php
      $sql = "SELECT * FROM items WHERE item_type='drinks' ORDER BY id DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '
            <div class="col-md-4">
              <div class="product-card shadow-sm" data-item-id="'.$row['item_id'].'">
                <img src="'.$row["item_img"].'" alt="Drink">
                <h5 class="product-title" data-item-name="'.$row['item_name'].'">'.$row["item_name"].'</h5>
                <p class="product-price">₱'.number_format($row["item_price"], 2).'</p>
                <div class="quantity-selector">
                  <button type="button" class="btn btn-sm btn-danger" onclick="changeQty(this, -1)">−</button>
                  <span class="qty-value fw-bold mx-2">1</span>
                  <button type="button" class="btn btn-sm btn-success" onclick="changeQty(this, 1)">+</button>
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-cart-plus"></i> Add to Cart</button>
              </div>
            </div>';
        }
      } else { echo "<p class='text-center text-muted'>No products available.</p>"; }
    ?>
  </div>

  <!-- ===== Addons Section ===== -->
  <h3 class="fw-bold mb-4 text-left">Addons</h3>
  <div class="row g-4 mb-4">
    <?php
      $sql = "SELECT * FROM items WHERE item_type='addons' ORDER BY id DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '
            <div class="col-md-4">
              <div class="product-card shadow-sm" data-item-id="'.$row['item_id'].'">
                <img src="'.$row["item_img"].'" alt="Addon">
                <h5 class="product-title" data-item-name="'.$row['item_name'].'">'.$row["item_name"].'</h5>
                <p class="product-price">₱'.number_format($row["item_price"], 2).'</p>
                <div class="quantity-selector">
                  <button type="button" class="btn btn-sm btn-danger" onclick="changeQty(this, -1)">−</button>
                  <span class="qty-value fw-bold mx-2">1</span>
                  <button type="button" class="btn btn-sm btn-success" onclick="changeQty(this, 1)">+</button>
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-cart-plus"></i> Add to Cart</button>
              </div>
            </div>';
        }
      } else { echo "<p class='text-center text-muted'>No products available.</p>"; }
    ?>
  </div>