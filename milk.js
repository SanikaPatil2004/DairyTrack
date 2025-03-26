document.addEventListener("DOMContentLoaded", () => {
    const farmerTable = document.getElementById("farmerTable");
    const saveButton = document.getElementById("saveButton");

    // Fetch farmers and populate table
    fetch("fetch_farmers.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(farmer => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${farmer.id}</td>
                    <td>${farmer.username}</td>
                    <td><input type="number" name="milk_${farmer.id}" min="0" step="0.1"></td>
                    <td><input type="number" name="fat_${farmer.id}" min="0" step="0.1"></td>
                    <td><input type="number" name="snf_${farmer.id}" min="0" step="0.1"></td>
                    <td>
                        <input type="checkbox" id="saved_${farmer.id}" disabled>
                        <label for="saved_${farmer.id}">Saved</label>
                    </td>
                `;

                farmerTable.appendChild(row);
            });
        });

    // Handle form submission
    document.getElementById("milkProductionForm").addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);

        fetch("save_milk_production.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(result => {
            if (result === "success") {
                alert("Data saved successfully!");
                saveButton.disabled = true;
                localStorage.setItem("lastSavedDate", new Date().toISOString().split("T")[0]);
            } else {
                alert("Error saving data.");
            }
        });
    });

    // Disable Save button if already submitted today
    const lastSavedDate = localStorage.getItem("lastSavedDate");
    const today = new Date().toISOString().split("T")[0];

    if (lastSavedDate === today) {
        saveButton.disabled = true;
    }
});
