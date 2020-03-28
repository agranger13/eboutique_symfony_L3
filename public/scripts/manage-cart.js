

function addToCart(id){
    let cart = {
        ids : []
    };
    if(localStorage.getItem("cart")){
        cart = JSON.parse(localStorage.getItem("cart"));
    }
    cart.ids.push(id);
    localStorage.setItem("cart",JSON.stringify(cart));
}