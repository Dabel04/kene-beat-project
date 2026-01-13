// document.addEventListener('DOMContentLoaded', () => {
    
//     // --- 1. NAVIGATION & ANIMATIONS ---
//     const menuBtn = document.getElementById("menu-btn");
//     const navLinks = document.getElementById("nav-links");
//     const menuBtnIcon = menuBtn ? menuBtn.querySelector("i") : null;

//     if (menuBtn && navLinks && menuBtnIcon) {
//         menuBtn.addEventListener("click", () => {
//             navLinks.classList.toggle("open");
//             const isOpen = navLinks.classList.contains("open");
//             menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
//         });
//         navLinks.addEventListener("click", () => {
//             navLinks.classList.remove("open");
//             menuBtnIcon.setAttribute("class", "ri-menu-line");
//         });
//     }

//     // ScrollReveal
//     if (typeof ScrollReveal !== 'undefined') {
//         const scrollRevealOption = { distance: "50px", origin: "bottom", duration: "1000" };
//         ScrollReveal().reveal(".header__container h1", { ...scrollRevealOption, delay: 500 });
//         // (Add other animations as needed)
//     }

//     // --- 2. CART SYSTEM STARTUP ---
//     // Load data from DB immediately
//     initCart(); 

//     // Sidebar Toggles
//     const cartBtn = document.getElementById('cart-btn');
//     const closeCartBtn = document.getElementById('close-cart');
//     const closeCartBtn2 = document.getElementById('close-cart-btn');
    
//     if (cartBtn) cartBtn.addEventListener('click', (e) => { e.preventDefault(); openCart(); });
//     if (closeCartBtn) closeCartBtn.addEventListener('click', closeCart);
//     if (closeCartBtn2) closeCartBtn2.addEventListener('click', closeCart);

//     // --- 3. GLOBAL FUNCTIONS ---
    
//     // RENAMED FUNCTION: Bypasses the conflict in footer.php
//     window.addKitToCart = function(item) {
//         console.log("Adding Kit:", item); // Debugging
//         let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
        
//         // Prevent duplicates
//         if (item.type === 'kit') {
//             if (cart.find(i => i.id === item.id)) {
//                 alert("This kit is already in your cart!");
//                 openCart();
//                 return;
//             }
//         }

//         cart.push(item);
        
//         // 1. Update UI immediately
//         localStorage.setItem('cartItems', JSON.stringify(cart));
//         updateCartCount();
//         renderCart();
//         openCart();
        
//         // 2. Save to Database (Crucial)
//         saveCartToSession(cart); 
//     };

//     // Remove Item
//     window.removeFromCart = function(index) {
//         let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
//         cart.splice(index, 1);
//         localStorage.setItem('cartItems', JSON.stringify(cart));
//         updateCartCount();
//         renderCart();
//         saveCartToSession(cart);
//     };
// });

// // --- HELPER FUNCTIONS ---

// function openCart() {
//     const sidebar = document.getElementById('cart-sidebar');
//     if (sidebar) sidebar.classList.add('open');
//     renderCart();
// }

// function closeCart() {
//     const sidebar = document.getElementById('cart-sidebar');
//     if (sidebar) sidebar.classList.remove('open');
// }

// function updateCartCount() {
//     const cart = JSON.parse(localStorage.getItem('cartItems')) || [];
//     const countElements = document.querySelectorAll('.cart-count, #cart-item-count'); 
//     countElements.forEach(el => {
//         el.innerText = cart.length;
//         el.style.display = cart.length > 0 ? 'flex' : 'none'; 
//     });
// }

// function initCart() {
//     fetch('includes/get_cart.php')
//         .then(res => res.json())
//         .then(data => {
//             if (data.success && Array.isArray(data.items)) {
//                 if(data.items.length > 0) {
//                     localStorage.setItem('cartItems', JSON.stringify(data.items));
//                     updateCartCount();
//                     if(document.getElementById('cart-sidebar').classList.contains('open')) renderCart();
//                 }
//             }
//         })
//         .catch(err => console.error('Error syncing cart:', err));
// }

// function renderCart() {
//     const cartContainer = document.querySelector('.cart-items, #cart-items-container');
//     const totalEl = document.querySelector('.cart-total-price, #cart-total-price');
    
//     if (!cartContainer) return;

//     let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
//     cartContainer.innerHTML = '';
    
//     let total = 0;

//     if (cart.length === 0) {
//         cartContainer.innerHTML = '<p class="empty-cart-message" style="text-align:center; padding:20px; color:#666;">Your cart is empty.</p>';
//     } else {
//         cart.forEach((item, index) => {
//             total += parseFloat(item.price);
            
//             let metaHtml = '';
//             if (item.type === 'kit') {
//                 metaHtml = `<span style="color:#2bee79; font-size:11px; font-weight:bold;">Sound Kit (Royalty Free)</span>`;
//             } else {
//                 metaHtml = `<span style="color:#aaa; font-size:11px;">${item.licenseName || 'License'}</span>`;
//             }

//             const img = item.img || 'image/disk.png';

//             cartContainer.innerHTML += `
//             <div class="cart-item" style="display:flex; gap:10px; padding:10px; border-bottom:1px solid #222; align-items:center;">
//                 <img src="${img}" style="width:50px; height:50px; object-fit:cover; border-radius:4px;" onerror="this.src='image/disk.png'">
//                 <div style="flex:1;">
//                     <h4 style="color:white; font-size:14px; margin:0; font-weight:bold;">${item.name}</h4>
//                     ${metaHtml}
//                     <div style="color:white; font-weight:bold;">$${parseFloat(item.price).toFixed(2)}</div>
//                 </div>
//                 <button onclick="removeFromCart(${index})" style="background:none; border:none; color:#ff4757; font-size:18px; cursor:pointer;">&times;</button>
//             </div>`;
//         });
//     }

//     if(totalEl) totalEl.innerText = '$' + total.toFixed(2);
// }

// function saveCartToSession(cart) {
//     fetch('includes/save_cart.php', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify({ cart: cart })
//     })
//     .catch(error => console.error('Error saving cart:', error));
// }