<button type="button" class="btn btn-success p-2 mb-2" id="uploadFontBtn">
    <i class="fa-solid fa-cloud-arrow-up"></i>&nbsp;Select TTF/OTF File
</button>
<input type="file" id="uploadFont" accept=".ttf,.otf" style="display: none;" required />
<br><br>
<ul id="fontList"></ul>

<script>
    document.getElementById("uploadFontBtn").addEventListener("click", () => {
        document.getElementById('uploadFont').click(); // Trigger the hidden file input
    });

    document.getElementById('uploadFont').addEventListener("change", async (e) => {
        const fileInput = e.target.files[0];

        const formData = new FormData();
        formData.append("fontFile", fileInput);

        document.getElementById("uploadFontBtn").innerHTML = `<div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;

        setTimeout(async () => {

            const response = await fetch("watermarks/settings/functionFonts.php", {
                method: "POST",
                body: formData
            });

            const {
                message,
                status
            } = await response.json();

            if (status === 200) {
                const dataWatermarks = {
                    fontFile: message
                };

                // Retrieve existing data from LocalStorage
                let existingData = localStorage.getItem('tbl_font');

                let watermarksArray = existingData ? JSON.parse(existingData) : [];


                // Determine the next ID
                const nextId = watermarksArray.length > 0 ? watermarksArray[watermarksArray.length - 1].id + 1 : 1;

                // Add ID to the new watermark data
                dataWatermarks.id = nextId;

                // Push the new watermark data to the array
                watermarksArray.push(dataWatermarks);

                // Save the updated array back to LocalStorage
                localStorage.setItem('tbl_font', JSON.stringify(watermarksArray));

                loadFontList(); // Refresh font list

                document.getElementById("uploadFontBtn").innerHTML = `<i class="fa-solid fa-check"></i>&nbsp; Select Select TTF File`
            } else {
                alert("Upload failed: " + message);
            }
        }, 500);
    });

    async function loadFonts(fonts) {
        const fontPromises = fonts.map(async font => {

            const fontName = font.fontFile.replace(/\.[^/.]+$/, ""); // Cleaned font name
            const style = document.createElement('style');

            style.textContent = `
            @font-face {
                font-family: '${fontName}'; 
                src: url('./watermarks/fonts/${font.fontFile}') format('truetype'); 
            }
        `;

            document.head.appendChild(style);
        });

        await Promise.all(fontPromises); // Wait for all fonts to be loaded
    }

    async function loadFontList() {

        const storedWatermarks = JSON.parse(localStorage.getItem('tbl_font'));
        const fontList = document.getElementById("fontList");

        if (!fontList) {
            console.error("Font list element not found!");
            return;
        }

        fontList.innerHTML = '';

        await loadFonts(storedWatermarks)

        if (storedWatermarks) {
            storedWatermarks.forEach(async (font, index) => {

                const li = document.createElement("li");
                const span = document.createElement("span");

                const fontName = font.fontFile.replace(/\.[^/.]+$/, ""); // Cleaned font name
                span.textContent = fontName; // Display the font name
                span.style.fontFamily = fontName; // Apply the cleaned font name

                li.appendChild(span);


                const deleteBtn = document.createElement("button");

                deleteBtn.setAttribute("class", "btn btn-sm btn-danger")
                deleteBtn.setAttribute("style", "height:40px")

                deleteBtn.innerHTML = `<i class="fa-solid fa-trash"></i>`;
                deleteBtn.onclick = () => confirmDelete(font.id); // Assuming 'fonts_id' is the font ID
                li.appendChild(deleteBtn);
                fontList.appendChild(li);
            });
        }
    }


    async function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this font?")) {

            // Retrieve existing data from LocalStorage
            let existingData = localStorage.getItem('tbl_font');
            let watermarksArray = existingData ? JSON.parse(existingData) : [];
            // Filter the array to remove the item with the specified ID
            watermarksArray = watermarksArray.filter(watermark => watermark.id !== id);


            // Save the updated array back to LocalStorage
            localStorage.setItem('tbl_font', JSON.stringify(watermarksArray));

            alert("Success remove font..");
            loadFontList();
            loadFontListEditorView();
        }
    }

    // Load the font list on page load
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => {
            loadFontList();
        }, 1000);
    });
</script>