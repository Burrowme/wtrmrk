<div class="row d-flex justify-content-center align-item-center">
    <div class="col-md-5 p-5">
        <!-- controls view -->
        <div class="contols-view text-center">
            <form id="uploadForm">
                <button type="button" class="btn btn-success p-2 mb-2" id="fileButton"><i class="fa-solid fa-cloud-arrow-up"></i></i>&nbsp;Select Watermark File</button>
                <input type="file" id="upload" accept="image/*" style="display: none;" required />
                <br>
                <div class="slider-container">
                    <input type="range" class="form-range" id="opacity" min="0" max="100" step="0" value="100" />
                    <span class="percentage" id="percentageDisplay">100%</span>
                </div>
                <br>
                <br>
                <input type="number" class="form-control text-center" id="copyCount" min="1" required placeholder="Number of Copies">
            </form>
        </div>

        <div class="conatainer-text mt-4 text-center">
            <p>Click and drag logos to move or scale it in the canvas, <br>
                change will be visible in Fortify page.</p>

            <p>Ideal watermarks are text-based and not logo based <br>
                with minimal colors as to not draw attention to it. <br>
                Recommended colors: Black and White.</p>
        </div>
        <div class="conatainer-list mt-4 p-4 row" id="listItem">
        </div>
    </div>
    <div class="col-md-7 p-5">
        <!-- screen view -->
        <div class="view-screen-wt">
            <canvas id="canvas" width="650" height="450" style="background: transparent;"></canvas>

            <div class="row">
                <div class="col-md-6">
                    <div id="colorButtons">
                        <button id="blackButton">Black</button>
                        <button id="whiteButton">White</button>
                        <button id="greenButton">Green</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <button id="save" class="btn btn-primary" style="float: right;position: relative;top: 10px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Save Watermark"><i class="fa-solid fa-floppy-disk"></i></button>
                    <button id="resetButton" class="btn btn-danger" style="float: right; margin-right:10px;position: relative;top: 10px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Watermark"><i class="fa-solid fa-trash"></i></span>
                </div></button>
            </div>

            <div id="image-controls" class="image-controls"></div>
        </div>
    </div>
</div>


<script>
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const upload = document.getElementById('upload');
    const opacitySlider = document.getElementById('opacity');
    const percentageDisplay = document.getElementById('percentageDisplay');
    const saveButton = document.getElementById('save');
    const imageControls = document.getElementById('image-controls');
    const copyCountInput = document.getElementById("copyCount");
    const resetButton = document.getElementById("resetButton");

    let images = [];
    let selectedIndex = null;
    let offsetX = 0,
        offsetY = 0;
    let zoomLevel = 1;

    const blackButton = document.getElementById("blackButton");
    const whiteButton = document.getElementById("whiteButton");
    const greenButton = document.getElementById("greenButton");
    const frameCanva = document.querySelector('.view-screen-wt');

    blackButton.addEventListener('click', () => {
        frameCanva.style.backgroundColor = "black"; // Change canvas background to black
    });

    whiteButton.addEventListener('click', () => {
        frameCanva.style.backgroundColor = "white"; // Change canvas background to white
    });

    greenButton.addEventListener('click', () => {
        frameCanva.style.backgroundColor = "#4CAF50"; // Change canvas background to green
    });

    document.getElementById("fileButton").addEventListener("click", function() {
        document.getElementById("upload").click();
    });

    // Reset function
    resetButton.addEventListener('click', (e) => {

        const file = upload.files[0];

        if (file) {

            resetButton.innerHTML = `<div class="spinner-border text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>`;

            setTimeout(() => {
                resetButton.innerHTML = `<i class="fa-solid fa-trash"></i>`
                resetFrame();
            }, 500);
        }
    });


    const resetFrame = () => {
        images.length = 0; // Clear the images array
        imageControls.innerHTML = ''; // Clear the controls
        frameCanva.style.backgroundColor = "#4CAF50";
        drawImages(); // Redraw the canvas (assuming it clears it)
        copyCountInput.value = '';
    }
////////////////////////// TWEAKING MOUSE DRAG////////////////////////// TWEAKING MOUSE DRAG
    const addImages = (file, count) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            images.length = 0; // Clear previous images
            imageControls.innerHTML = ''; // Clear previous controls

            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                for (let i = 0; i < count; i++) {
                    const newImage = {
                        img,
                        x: 100 + i * 50, // Adjust positioning as needed
                        y: 100,
                        rotation: 0
                    };
                    images.push(newImage);
                    createImageControls(i); // Create controls for each image
                }
                drawImages(); // Draw all images after loading
            };
        };
        reader.readAsDataURL(file);
    };

    const createImageControls = (index) => {
        const button = document.createElement('button');
        button.innerHTML = `<i class="fa-solid fa-rotate-right"></i> ${index + 1}`;
        button.addEventListener('click', () => {
            images[index].rotation = (images[index].rotation + 90) % 360;
            drawImages();
        });
        imageControls.appendChild(button);
    };

    copyCountInput.addEventListener("input", () => {
        const count = parseInt(copyCountInput.value);
        const file = upload.files[0];

        if (file && count > 0) {
            addImages(file, count);
        }
    });

    upload.addEventListener('change', () => {
        const file = upload.files[0];
        const count = parseInt(copyCountInput.value) || 1; // Default to 1 if input is empty

        if (file) {

            document.getElementById("fileButton").innerHTML = `<div class="spinner-border text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>`;

            setTimeout(() => {
                addImages(file, count);
                document.getElementById("fileButton").innerHTML = `<i class="fa-solid fa-check"></i>&nbsp;Select Watermark File`
            }, 500);
        }
    });

    function drawImages() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.globalAlpha = opacitySlider.value / 100;

        // Get the current background color
        let backgroundColor = document.body.style.backgroundColor;

        // Set the filter based on the background color
        if (backgroundColor === "black") {
            ctx.filter = "brightness(1.2) contrast(1.2)"; // Enhance brightness and contrast for black background
        } else if (backgroundColor === "white") {
            ctx.filter = "brightness(0.8)"; // Darken images slightly for white background
        } else if (backgroundColor === "green") {
            ctx.filter = "hue-rotate(90deg) brightness(1.1)"; // Adjust hue and brighten for green background
        } else {
            ctx.filter = "none"; // No filter for other backgrounds
        }

        ctx.save();
        ctx.scale(zoomLevel, zoomLevel);

        images.forEach((image) => {
            ctx.save();
            ctx.translate(image.x / zoomLevel + image.img.width / 2, image.y / zoomLevel + image.img.height / 2);
            ctx.rotate(image.rotation * Math.PI / 180);
            ctx.drawImage(image.img, -image.img.width / 2, -image.img.height / 2);
            ctx.restore();
        });

        ctx.restore();
        ctx.filter = "none"; // Reset filter after drawing
    }

    opacitySlider.addEventListener('input', drawImages);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && selectedIndex !== null) {
            const originalImage = images[selectedIndex];
            const newImage = {
                img: originalImage.img,
                x: originalImage.x + 10,
                y: originalImage.y + 10,
                rotation: originalImage.rotation
            };
            images.push(newImage);
            createImageControls(images.length - 1);
            drawImages();
        }
    });

    saveButton.addEventListener('click', async () => {
        const file = upload.files[0];

        if (file) {

            saveButton.innerHTML = `<div class="spinner-border text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>`;

            const fileName = new Date().toISOString().slice(0, 19).replace('T', ' ') + '.png';

            // Convert the canvas to a data URL
            const imageDataURL = canvas.toDataURL('image/png');

            const dataWatermarks = {
                image: imageDataURL,
                filename: fileName,
                opacity: opacitySlider.value,
                g_amount: copyCountInput.value
            };

            // Retrieve existing data from LocalStorage
            let existingData = localStorage.getItem('tbl_generate_img');
            let watermarksArray = existingData ? JSON.parse(existingData) : [];
            // Determine the next ID
            const nextId = watermarksArray.length > 0 ? watermarksArray[watermarksArray.length - 1].id + 1 : 1;
            // Add ID to the new watermark data
            dataWatermarks.id = nextId;
            // Push the new watermark data to the array
            watermarksArray.push(dataWatermarks);
            // Save the updated array back to LocalStorage
            localStorage.setItem('tbl_generate_img', JSON.stringify(watermarksArray));

            setTimeout(() => {
                alert("Success save watermarks.")
                resetFrame();
                getAllWatermarks();

                saveButton.innerHTML = `<i class="fa-solid fa-floppy-disk"></i>`;
            }, 500);
        }
    });

    const getAllWatermarks = async () => {
        const storedWatermarks = JSON.parse(localStorage.getItem('tbl_generate_img'));
        let data = '';

        if (storedWatermarks) {
            storedWatermarks.forEach((watermark, index) => {
                data += `     
                <div class="box-list text-center col-sm-2">
                    <button class="btn-remove-list" data-id="${watermark.id}">X</button>
                    <p>
                        custom <br>
                        watermark ${watermark.id}
                    </p>
                </div>
            `;
            });

            document.querySelector('#listItem').innerHTML = data;
        }

        // Add event listeners to each button after rendering
        document.querySelectorAll('.btn-remove-list').forEach(button => {
            button.addEventListener('click', async (e) => {
                const id = parseInt(e.target.getAttribute('data-id'), 10); // Convert to number

                let x = confirm('Are you sure you want to remove this?');

                if (!x) {
                    return;
                }

                // Retrieve existing data from LocalStorage
                let existingData = localStorage.getItem('tbl_generate_img');
                let watermarksArray = existingData ? JSON.parse(existingData) : [];

                // Filter the array to remove the item with the specified ID
                watermarksArray = watermarksArray.filter(watermark => watermark.id !== id);

                // Save the updated array back to LocalStorage
                localStorage.setItem('tbl_generate_img', JSON.stringify(watermarksArray));

                alert("Success remove watermarks.");
                getAllWatermarks();
            });
        });
    };
////////////////////////// TWEAKING MOUSE DRAG
getAllWatermarks();

canvas.addEventListener('wheel', (e) => {
    if (e.ctrlKey) {
        e.preventDefault();
        zoomLevel += e.deltaY > 0 ? -0.1 : 0.1;
        zoomLevel = Math.min(Math.max(zoomLevel, 0.1), 3);
        drawImages();
    }
});

function getImageAt(x, y) {
    for (let i = 0; i < images.length; i++) {
        const image = images[i];
        const scaledWidth = image.img.width * zoomLevel;
        const scaledHeight = image.img.height * zoomLevel;

        if (x >= image.x && x <= image.x + scaledWidth &&
            y >= image.y && y <= image.y + scaledHeight) {
            return i;
        }
    }
    return null;
}

let isDragging = false;

canvas.addEventListener('mousedown', (e) => {
    const rect = canvas.getBoundingClientRect();
    const mouseX = (e.clientX - rect.left);
    const mouseY = (e.clientY - rect.top);

    selectedIndex = getImageAt(mouseX, mouseY);
    if (selectedIndex !== null) {
        isDragging = true;
        const selectedImage = images[selectedIndex];
        const scaledWidth = selectedImage.img.width * zoomLevel;
        const scaledHeight = selectedImage.img.height * zoomLevel;

        // Calculate offset using scaled dimensions
        offsetX = mouseX - selectedImage.x;
        offsetY = mouseY - selectedImage.y;

        canvas.style.cursor = 'grabbing'; // Change cursor on drag
    } else {
        selectedIndex = null;
        canvas.style.cursor = 'default'; // Reset cursor if no image selected
    }
});

canvas.addEventListener('mousemove', (e) => {
    if (isDragging && selectedIndex !== null) {
        const rect = canvas.getBoundingClientRect();
        const mouseX = (e.clientX - rect.left);
        const mouseY = (e.clientY - rect.top);

        // Update the selected image's position
        const selectedImage = images[selectedIndex];
        selectedImage.x = mouseX - offsetX;
        selectedImage.y = mouseY - offsetY;

        drawImages();
    }
});

canvas.addEventListener('mouseup', () => {
    isDragging = false;
    selectedIndex = null;
    canvas.style.cursor = 'default'; // Reset cursor after dragging
});

canvas.addEventListener('mouseleave', () => {
    isDragging = false;
    selectedIndex = null;
    canvas.style.cursor = 'default'; // Reset cursor if mouse leaves canvas
});

function drawImages() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.globalAlpha = opacitySlider.value / 100;

    images.forEach((image) => {
        ctx.save();

        // Apply zoom scaling
        const scaledWidth = image.img.width * zoomLevel;
        const scaledHeight = image.img.height * zoomLevel;

        ctx.translate(image.x + scaledWidth / 2, image.y + scaledHeight / 2);
        ctx.rotate(image.rotation * Math.PI / 180);

        // Draw the scaled image
        ctx.drawImage(image.img, -scaledWidth / 2, -scaledHeight / 2, scaledWidth, scaledHeight);
        ctx.restore();
    });
}

opacitySlider.addEventListener('input', function () {
    percentageDisplay.textContent = `${opacitySlider.value}%`;
    drawImages();
});

</script>