document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".menu-item");
  const cartSummary = document.querySelector(".cart-summary");

  function updateCartSummary() {
    const cartItems = document.querySelector(".cart-items");
    cartItems.innerHTML = "";
    let total = 0;
    let hasItems = false;

    menuItems.forEach((item) => {
      const quantity = parseInt(item.querySelector(".quantity-input").value);
      if (quantity > 0) {
        hasItems = true;
        const name = item.querySelector("h3").textContent;
        const price = parseInt(item.querySelector(".price").dataset.price);
        const itemTotal = price * quantity;
        total += itemTotal;

        cartItems.innerHTML += `
                <div class="cart-item">
                    <span>${name} x ${quantity}</span>
                    <span>Rp ${itemTotal.toLocaleString("id-ID")}</span>
                </div>
            `;
      }
    });

    document.getElementById(
      "total-price"
    ).innerText = `Total: Rp ${total.toLocaleString("id-ID")}`;

    if (hasItems) {
      cartSummary.classList.add("show");
    } else {
      cartSummary.classList.remove("show");
    }
  }

  function showAddedBadge(item) {
    const badge = item.querySelector(".badge");
    badge.classList.add("show");
    setTimeout(() => badge.classList.remove("show"), 1500);
  }

  menuItems.forEach((item) => {
    const minusBtn = item.querySelector(".minus");
    const plusBtn = item.querySelector(".plus");
    const quantityDisplay = item.querySelector(".quantity-display");
    const quantityInput = item.querySelector(".quantity-input");

    minusBtn.addEventListener("click", () => {
      let quantity = parseInt(quantityDisplay.textContent);
      if (quantity > 0) {
        quantity--;
        quantityDisplay.textContent = quantity;
        quantityInput.value = quantity;
        updateCartSummary();
      } else {
        const cartSummary = document.querySelector('.cart-summary');
        cartSummary.style.opacity = '0';
        
        if (window.innerWidth <= 768) {
            cartSummary.style.transform = 'translateX(50%) translateY(100%)';
        } else {
            cartSummary.style.transform = 'translateY(100%)';
        }
      }
    });

    plusBtn.addEventListener("click", () => {
      let quantity = parseInt(quantityDisplay.textContent);
      quantity++;
      quantityDisplay.textContent = quantity;
      quantityInput.value = quantity;
      item.classList.add("cart-animation");
      showAddedBadge(item);
      setTimeout(() => item.classList.remove("cart-animation"), 500);
      updateCartSummary();
      const cartSummary = document.querySelector('.cart-summary');
      cartSummary.style.opacity = '1';
      
      if (window.innerWidth <= 768) {
          cartSummary.style.transform = 'translateX(50%) translateY(0)';
      } else {
          cartSummary.style.transform = 'translateY(0)';
      }
    });
  });

  document.getElementById("orderForm").addEventListener("submit", (e) => {
    let hasItems = false;
    menuItems.forEach((item) => {
      const quantity = parseInt(item.querySelector(".quantity-input").value);
      if (quantity > 0) hasItems = true;
    });

    if (!hasItems) {
      e.preventDefault();
      Swal.fire({
        title: "Cart Empty",
        text: "Please add some items to your cart before checking out.",
        icon: "warning",
        confirmButtonColor: "#ff1493",
      });
    }
  });
});
