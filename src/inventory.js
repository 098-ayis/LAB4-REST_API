document.getElementById("addForm").addEventListener("submit", function(e) {
    e.preventDefault();

    fetch("http://localhost/fleurchase/backend/add_inventory.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            flower_name: document.getElementById("flower_name").value,
            flower_image: document.getElementById("flower_image").value,
            stock: document.getElementById("stock").value,
            base_price_per_stem: document.getElementById("price").value,
            date_arrived: document.getElementById("date_arrived").value,
            shelf_life: document.getElementById("shelf_life").value
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadInventory();
    });
});

function loadInventory() {
    fetch("http://localhost/fleurchase/backend/get_inventory.php")
    .then(res => res.json())
    .then(data => {
        let table = document.getElementById("inventoryTable");
        table.innerHTML = "";

        data.forEach(item => {
            table.innerHTML += `
                <tr>
                    <td>${item.inventory_id}</td>
                    <td>${item.flower_name}</td>
                    <td>${item.stock}</td>
                    <td>${item.base_price_per_stem}</td>
                    <td>
                        <button onclick="deleteItem(${item.inventory_id})">Delete</button>
                        <button onclick="updateItem(${item.inventory_id})">Update</button>
                    </td>
                </tr>
            `;
        });
    });
}

loadInventory();

function deleteItem(id) {
    fetch("http://localhost/fleurchase/backend/delete_inventory.php", {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ inventory_id: id })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadInventory();
    });
}

function updateItem(id) {
    let newStock = prompt("New stock:");
    let newPrice = prompt("New price:");

    fetch("http://localhost/fleurchase/backend/update_inventory.php", {
        method: "PUT",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            inventory_id: id,
            stock: newStock,
            base_price_per_stem: newPrice,
            flower_name: "Updated",
            flower_image: "",
            date_arrived: "2026-01-01",
            shelf_life: 7
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadInventory();
    });
}
