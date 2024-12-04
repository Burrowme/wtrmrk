<div class="row d-flex justify-content-center align-item-center">
    <div class="col-md-5 p-5">
        <!-- controls view -->
        <div class="contols-view text-center">
            <form id="uploadForm">
                <button type="button" class="btn btn-success p-2 mb-2" id="uploadBtnImg"><i class="fa-solid fa-cloud-arrow-up"></i>&nbsp;Select Images To Watermarks</button>
                <input
                    type="file"
                    id="upload_img"
                    accept="image/*"
                    multiple
                    style="display: none" />
                <br>
                <br>
                <input type="text" class="form-control text-center" id="watermarkText" required placeholder="Write watermark here">

            </form>
        </div>

        <div class="conatainer-text mt-4 text-center">
            <h2>AMPLITUDE</h2>

            <p>Adjust enough for desired intensity of watermark,<br>
                The watermark must be visible to the human eye but have little to no contrast against the overall images</p>

            <div id="splash">
                <div id="">
                    <div id=""></div>
                    <div id=""></div> <!-- Centered percentage -->
                </div>
            </div>
        </div>
        <div class="conatainer-list mt-4 p-4 row" id="listItemImg">
        </div>
    </div>
    <div class="col-md-7 p-5">
        <!-- screen view --><!---------------------------------------------------------------------------------------->
        <div class="view-screen-wt">
            <button id="showBefore">Before</button>
            <button id="showAfter">After</button>
            <button id="reset" style="float: right;" data-bs-toggle="tooltip" data-bs-placement="top" title="Clear All"><i class="fa-solid fa-eraser"></i></button>
            <div id="slider">
                <button id="prev" data-bs-toggle="tooltip" data-bs-placement="top" title="Previous"><i class="fa-solid fa-arrow-left"></i></button>
                <button id="next" data-bs-toggle="tooltip" data-bs-placement="top" title="Next"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <canvas id="canvas_img" width="650" height="450" style="background: transparent;"></canvas>

            <div class="row">
                <div class="col-md-4 text-center">
                </div>
                <div class="col-md-4 text-center">
                    <div id="center-img-ctrl">
                        
                        <button id="zoomInImg" data-bs-toggle="tooltip" data-bs-placement="top" title="Zoom In"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                        <button id="zoomOutImg" data-bs-toggle="tooltip" data-bs-placement="top" title="Zoom Out"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
                        <button id="fontButton" data-bs-toggle="tooltip" data-bs-placement="top" title="Change Watermarks Fonts"><i class="fa-solid fa-font"></i></button>

                        <div id="overlay"></div>
                        <div id="fontModal">
                            <h2>Select a Font</h2>
                            <select id="fontStyle" class="form-select">
                                <option value="">-- Choose a font --</option>
                            </select>
                            <br><br>
                            <h2>Pick a Font Color</h2>
                            <input type="color" id="colorPicker" />
                            <button id="closeModal">X</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="btn-dl-img">
                        <button id="copyAll" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy Watermarks to all images"><i class="fa-solid fa-copy"></i></span>
                        </button>

                        <button id="saveImg" data-bs-toggle="tooltip" data-bs-placement="top" title="Save File"><i class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!---------------------------------------------------------------------------------------->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    
    let rotationAngle = 0;
    let scale = 1;
    let originalImages = [];
    let currentImageIndex = 0;
    let watermarkImg = new Image();
    let watermarkText = "";
    let fontStyle = "Arial";
    let showWatermarked = false;
    let file_error = 0;
    let textColor = "";
    let positionData = "fullCoverage";

    const percentageElement = document.getElementById('percentage');
    const progressElement = document.getElementById('progress');
    const splashElement = document.getElementById('splash');


    document.getElementById("uploadBtnImg").addEventListener("click", function() {
        document.getElementById("upload_img").click();
    });

    document.getElementById("upload_img").addEventListener("change", function(event) {
        const files = event.target.files;

        const file = upload.files[0];
        const count = parseInt(copyCountInput.value) || 1; // Default to 1 if input is empty


        document.getElementById("uploadBtnImg").innerHTML = `<div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;

        setTimeout(() => {
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        originalImages.push(img);
                        if (i === 0) {
                            redrawCanvas(positionData);
                        }
                    };
                };
                reader.readAsDataURL(files[i]);
                file_error++;
            }
            document.getElementById("uploadBtnImg").innerHTML = `<i class="fa-solid fa-check"></i>&nbsp; Select Images To Watermarks`
        }, 500);

    });



    document.getElementById("zoomOutImg").addEventListener("click", function() {
        scale *= 1.2;
        redrawCanvas(positionData);
    });

    document.getElementById("zoomInImg").addEventListener("click", function() {
        scale *= 0.8;
        redrawCanvas(positionData);
    });

    document.getElementById("fontStyle").addEventListener("change", function() {

        if (file_error == 0) {
            alert("Upload first image before adding fonts.")
            return;
        }

        fontStyle = this.value;
        redrawCanvas(positionData);
    });

    document.getElementById("reset").addEventListener("click", function() {
        // Reset all variables
        rotationAngle = 0;
        scale = 1;
        originalImages = [];
        currentImageIndex = 0;
        watermarkText = "";
        watermarkImg.src = "";
        showWatermarked = false;

        // Clear the canvas
        const canvas = document.getElementById("canvas_img");
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        document.getElementById("watermarkText").value = "";
        const specificPercent = 0; // Change this value as needed
        percentageElement.textContent = `${specificPercent}%`;
        progressElement.style.width = `${specificPercent}%`;
    });


    document.getElementById("watermarkText").addEventListener("input", function() {

        if (file_error == 0) {
            alert("Upload first image before adding text.")
            this.value = "";
            return;
        }
        watermarkText = this.value || "";
        showWatermarked = true;
        redrawCanvas(positionData);
    });

    document.getElementById("showBefore").addEventListener("click", function() {
        showWatermarked = false;
        redrawCanvas(positionData);
    });

    document.getElementById("showAfter").addEventListener("click", function() {
        showWatermarked = true;
        redrawCanvas(positionData);
    });

    document.getElementById("prev").addEventListener("click", function() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            redrawCanvas(positionData);
        }
    });

    document.getElementById("next").addEventListener("click", function() {
        if (currentImageIndex < originalImages.length - 1) {
            currentImageIndex++;
            redrawCanvas(positionData);
        }
    });

    document.getElementById("saveImg").addEventListener("click", function() {
        if (file_error === 0) {
            alert("Upload first image before saving the image.");
            return;
        }
        redrawCanvas(positionData); // Ensure canvas is drawn with the watermark
        const canvas = document.getElementById("canvas_img");
        const link = document.createElement("a");
        link.download = `watermarked-image-${currentImageIndex + 1}.png`;
        link.href = canvas.toDataURL();
        link.click();
    });


    document.getElementById("copyAll").addEventListener("click", async function() {
        if (file_error === 0) {
            alert("Upload first image before copying all watermarks.");
            return;
        }

        redrawCanvas(positionData);
///////////////////////////ZIP FILE FIXINGGGGGG/////////////////////////////////
const zip = new JSZip();
const watermarkTransparency = 0.3; // Transparency factor (0.0 to 1.0)

// Function to add text watermark with overlay algorithm
function addTextWatermark(ctx, canvas, img, positionData) {
    const baseFontSize = Math.min(canvas.width, canvas.height) / 15;
    ctx.font = `${baseFontSize}px ${fontStyle}`;
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";

    const textColorToUse = textColor || getContrastYIQ(getAverageColor(img));
    const text = watermarkText || "";

    // Create a temporary canvas for the text watermark
    const tempCanvas = document.createElement("canvas");
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;
    const tempCtx = tempCanvas.getContext("2d");

    // Draw text on the temporary canvas
    tempCtx.font = ctx.font;
    tempCtx.textAlign = ctx.textAlign;
    tempCtx.textBaseline = ctx.textBaseline;
    tempCtx.fillStyle = textColorToUse;

    if (positionData === "center") {
        tempCtx.fillText(text, canvas.width / 2, canvas.height / 2);
    } else if (positionData === "corners") {
        const padding = 50;
        tempCtx.fillText(text, padding, baseFontSize); // Top-left
        tempCtx.fillText(text, canvas.width - padding, baseFontSize); // Top-right
        tempCtx.fillText(text, padding, canvas.height - padding); // Bottom-left
        tempCtx.fillText(text, canvas.width - padding, canvas.height - padding); // Bottom-right
    } else if (positionData === "fullCoverage") {
        const rows = 6;
        const spacingY = canvas.height / (rows + 1);
        for (let i = 0; i < rows; i++) {
            tempCtx.fillText(text, canvas.width / 2, (i + 1) * spacingY);
        }
    }

    // Extract pixel data of the text watermark
    const textImageData = tempCtx.getImageData(0, 0, canvas.width, canvas.height);
    const baseImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const blendedImageData = ctx.createImageData(baseImageData);

    // Blend text watermark pixels with transparency
    for (let i = 0; i < textImageData.data.length; i += 4) {
        const alpha = (textImageData.data[i + 3] / 255) * watermarkTransparency; // Scaled alpha
        if (alpha > 0) {
            const overlayColor = [
                textImageData.data[i],
                textImageData.data[i + 1],
                textImageData.data[i + 2],
            ];

            const baseColor = [
                baseImageData.data[i],
                baseImageData.data[i + 1],
                baseImageData.data[i + 2],
            ];

            // Apply overlay blend mode
            const blendedColor = overlayBlend(baseColor, overlayColor);

            // Mix the blended color with the original base color based on transparency
            blendedImageData.data[i] = (1 - alpha) * baseColor[0] + alpha * blendedColor[0];
            blendedImageData.data[i + 1] = (1 - alpha) * baseColor[1] + alpha * blendedColor[1];
            blendedImageData.data[i + 2] = (1 - alpha) * baseColor[2] + alpha * blendedColor[2];
            blendedImageData.data[i + 3] = baseImageData.data[i + 3]; // Preserve original alpha
        } else {
            // Keep original base pixel
            blendedImageData.data[i] = baseImageData.data[i];
            blendedImageData.data[i + 1] = baseImageData.data[i + 1];
            blendedImageData.data[i + 2] = baseImageData.data[i + 2];
            blendedImageData.data[i + 3] = baseImageData.data[i + 3];
        }
    }

    // Draw blended text watermark onto the main canvas
    ctx.putImageData(blendedImageData, 0, 0);
}

const promises = originalImages.map((img, index) => {
    return new Promise((resolve) => {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        canvas.width = img.width * scale;
        canvas.height = img.height * scale;

        // Draw the original image
        ctx.drawImage(img, 0, 0, img.width * scale, img.height * scale);

        if (showWatermarked) {
            // ** Image Watermark Settings **
            const watermarkScale = 1.5;
            const watermarkWidth = watermarkImg.width * watermarkScale;
            const watermarkHeight = watermarkImg.height * watermarkScale;
            const watermarkX = (canvas.width - watermarkWidth) / 2;
            const watermarkY = (canvas.height - watermarkHeight) / 2;

            // Create a temporary canvas for the watermark image
            const watermarkCanvas = document.createElement("canvas");
            const watermarkCtx = watermarkCanvas.getContext("2d");
            watermarkCanvas.width = canvas.width;
            watermarkCanvas.height = canvas.height;

            // Draw the watermark image on the temporary canvas
            watermarkCtx.drawImage(
                watermarkImg,
                watermarkX,
                watermarkY,
                watermarkWidth,
                watermarkHeight
            );

            // Extract pixel data
            const watermarkImageData = watermarkCtx.getImageData(0, 0, watermarkCanvas.width, watermarkCanvas.height);
            const baseImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const blendedImageData = ctx.createImageData(baseImageData);

            // Blend watermark pixels with transparency
            for (let i = 0; i < watermarkImageData.data.length; i += 4) {
                const alpha = (watermarkImageData.data[i + 3] / 255) * watermarkTransparency; // Scaled alpha
                if (alpha > 0) {
                    const overlayColor = [
                        watermarkImageData.data[i],
                        watermarkImageData.data[i + 1],
                        watermarkImageData.data[i + 2],
                    ];

                    const baseColor = [
                        baseImageData.data[i],
                        baseImageData.data[i + 1],
                        baseImageData.data[i + 2],
                    ];

                    // Apply overlay blend mode
                    const blendedColor = overlayBlend(baseColor, overlayColor);

                    // Mix the blended color with the original base color based on transparency
                    blendedImageData.data[i] = (1 - alpha) * baseColor[0] + alpha * blendedColor[0];
                    blendedImageData.data[i + 1] = (1 - alpha) * baseColor[1] + alpha * blendedColor[1];
                    blendedImageData.data[i + 2] = (1 - alpha) * baseColor[2] + alpha * blendedColor[2];
                    blendedImageData.data[i + 3] = baseImageData.data[i + 3]; // Preserve original alpha
                } else {
                    // Keep original base pixel
                    blendedImageData.data[i] = baseImageData.data[i];
                    blendedImageData.data[i + 1] = baseImageData.data[i + 1];
                    blendedImageData.data[i + 2] = baseImageData.data[i + 2];
                    blendedImageData.data[i + 3] = baseImageData.data[i + 3];
                }
            }

            // Draw blended image watermark onto the main canvas
            ctx.putImageData(blendedImageData, 0, 0);

            // Add text watermark with the same overlay logic
            addTextWatermark(ctx, canvas, img, positionData);

            // Reset transformations
            ctx.resetTransform();
        }

        // Add canvas image to ZIP
        canvas.toBlob((blob) => {
            zip.file(`watermarked-image-${index + 1}.png`, blob);
            resolve();
        });
    });
});

await Promise.all(promises);

// Generate the ZIP file and trigger download
zip.generateAsync({ type: "blob" }).then((content) => {
    const link = document.createElement("a");
    link.href = URL.createObjectURL(content);
    link.download = "watermarked-images.zip";
    link.click();
});



    });
////////////////////////////////////////////////////////////////////////////////////////
function getContrastYIQ(rgb) {
    const [r, g, b] = rgb;
    const yiq = (r * 299 + g * 587 + b * 114) / 1000;
    return yiq >= 128 ? "black" : "white";
}

function getAverageColor(img) {
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    canvas.width = img.width;
    canvas.height = img.height;
    ctx.drawImage(img, 0, 0);
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    let r = 0, g = 0, b = 0;

    for (let i = 0; i < data.length; i += 4) {
        r += data[i];
        g += data[i + 1];
        b += data[i + 2];
    }
    const pixelCount = data.length / 4;
    return [
        Math.floor(r / pixelCount),
        Math.floor(g / pixelCount),
        Math.floor(b / pixelCount),
    ];
}

document.getElementById('colorPicker').addEventListener('input', (e) => {
    textColor = e.target.value; // Update the text color
    if (file_error > 0) { // Redraw if an image is uploaded
        redrawCanvas(positionData);
    }
});


function overlayBlend(sourceColor, overlayColor) {
    const blendedColor = [0, 0, 0]; // Array to hold the blended color

    // Apply overlay blending for each channel
    for (let i = 0; i < 3; i++) { // Loop through RGB channels
        const source = sourceColor[i];
        const overlay = overlayColor[i];

        // Use the overlay blending formula
        blendedColor[i] = (source < 128) 
            ? (2 * source * overlay) / 255 
            : (255 - (2 * (255 - source) * (255 - overlay)) / 255);
    }
    
    return blendedColor;
}

function redrawCanvas(positionData) {
    const canvas = document.getElementById("canvas_img");
    const ctx = canvas.getContext("2d");
    if (originalImages.length === 0) return;

    const img = originalImages[currentImageIndex];
    canvas.width = img.width * scale;
    canvas.height = img.height * scale;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 0, 0, img.width * scale, img.height * scale); // Draw the original image

    if (showWatermarked) {
        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.rotate((rotationAngle * Math.PI) / 180);

        // ** Image Watermark Settings **
        const watermarkScale = 1.5;
        const watermarkWidth = watermarkImg.width * watermarkScale;
        const watermarkHeight = watermarkImg.height * watermarkScale;
        const watermarkX = (canvas.width - watermarkWidth) / 2;
        const watermarkY = (canvas.height - watermarkHeight) / 2;

        // Transparency factor (0.0 to 1.0, where 1.0 is fully opaque)
        const watermarkTransparency = 0.3; // Adjust this value for subtlety

        // Create a temporary canvas for the watermark image
        const watermarkCanvas = document.createElement("canvas");
        const watermarkCtx = watermarkCanvas.getContext("2d");
        watermarkCanvas.width = canvas.width;
        watermarkCanvas.height = canvas.height;

        // Draw the watermark image on the temporary canvas
        watermarkCtx.drawImage(
            watermarkImg,
            watermarkX,
            watermarkY,
            watermarkWidth,
            watermarkHeight
        );

        // Extract pixel data
        const watermarkImageData = watermarkCtx.getImageData(0, 0, watermarkCanvas.width, watermarkCanvas.height);
        const baseImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const blendedImageData = ctx.createImageData(baseImageData);

        // Blend watermark pixels with transparency
        for (let i = 0; i < watermarkImageData.data.length; i += 4) {
            const alpha = (watermarkImageData.data[i + 3] / 255) * watermarkTransparency; // Scaled alpha
            if (alpha > 0) {
                const overlayColor = [
                    watermarkImageData.data[i],
                    watermarkImageData.data[i + 1],
                    watermarkImageData.data[i + 2],
                ];

                const baseColor = [
                    baseImageData.data[i],
                    baseImageData.data[i + 1],
                    baseImageData.data[i + 2],
                ];

                // Apply overlay blend mode
                const blendedColor = overlayBlend(baseColor, overlayColor);

                // Mix the blended color with the original base color based on transparency
                blendedImageData.data[i] = (1 - alpha) * baseColor[0] + alpha * blendedColor[0];
                blendedImageData.data[i + 1] = (1 - alpha) * baseColor[1] + alpha * blendedColor[1];
                blendedImageData.data[i + 2] = (1 - alpha) * baseColor[2] + alpha * blendedColor[2];
                blendedImageData.data[i + 3] = baseImageData.data[i + 3]; // Preserve original alpha
            } else {
                // Keep original base pixel
                blendedImageData.data[i] = baseImageData.data[i];
                blendedImageData.data[i + 1] = baseImageData.data[i + 1];
                blendedImageData.data[i + 2] = baseImageData.data[i + 2];
                blendedImageData.data[i + 3] = baseImageData.data[i + 3];
            }
        }

        // Draw blended image watermark onto the main canvas
        ctx.putImageData(blendedImageData, 0, 0);

        // ** Text Watermark **
        const baseFontSize = Math.min(canvas.width, canvas.height) / 15;
        ctx.font = `${baseFontSize}px ${fontStyle}`;
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";

        const textColorToUse = textColor || getContrastYIQ(getAverageColor(img));
        const text = watermarkText || "";

        const textCanvas = document.createElement("canvas");
        const textCtx = textCanvas.getContext("2d");
        textCanvas.width = canvas.width;
        textCanvas.height = canvas.height;

        textCtx.font = `${baseFontSize}px ${fontStyle}`;
        textCtx.textAlign = "center";
        textCtx.textBaseline = "middle";
        textCtx.fillStyle = textColorToUse;

        if (positionData === 'center') {
            textCtx.fillText(text, textCanvas.width / 2, textCanvas.height / 2);
        } else if (positionData === 'corners') {
            const padding = 50;
            textCtx.fillText(text, padding, baseFontSize); // Top-left
            textCtx.fillText(text, textCanvas.width - padding, baseFontSize); // Top-right
            textCtx.fillText(text, padding, textCanvas.height - padding); // Bottom-left
            textCtx.fillText(text, textCanvas.width - padding, textCanvas.height - padding); // Bottom-right
        } else if (positionData === 'fullCoverage') {
            const rows = 6;
            const spacingY = textCanvas.height / (rows + 1);
            for (let i = 0; i < rows; i++) {
                textCtx.fillText(text, textCanvas.width / 2, (i + 1) * spacingY);
            }
        }

        // Blend text watermark
        const textOverlayImageData = textCtx.getImageData(0, 0, textCanvas.width, textCanvas.height);
        const blendedTextData = ctx.createImageData(baseImageData);

        for (let i = 0; i < textOverlayImageData.data.length; i += 4) {
            const alpha = textOverlayImageData.data[i + 3] / 255;
            if (alpha > 0) {
                const overlayColor = [
                    textOverlayImageData.data[i],
                    textOverlayImageData.data[i + 1],
                    textOverlayImageData.data[i + 2],
                ];

                const baseColor = [
                    blendedImageData.data[i],
                    blendedImageData.data[i + 1],
                    blendedImageData.data[i + 2],
                ];

                const blendedColor = overlayBlend(baseColor, overlayColor);
                blendedTextData.data[i] = blendedColor[0];
                blendedTextData.data[i + 1] = blendedColor[1];
                blendedTextData.data[i + 2] = blendedColor[2];
                blendedTextData.data[i + 3] = blendedImageData.data[i + 3];
            } else {
                blendedTextData.data[i] = blendedImageData.data[i];
                blendedTextData.data[i + 1] = blendedImageData.data[i + 1];
                blendedTextData.data[i + 2] = blendedImageData.data[i + 2];
                blendedTextData.data[i + 3] = blendedImageData.data[i + 3];
            }
        }

        // Put final blended result back to the canvas
        ctx.putImageData(blendedTextData, 0, 0);

        ctx.resetTransform(); // Reset transformations
    }
}


/////////////////////////////////////////////////////////////////////

    const getAllWatermarksWithText = async () => {

        const storedWatermarks = JSON.parse(localStorage.getItem('tbl_generate_img'));
        let data = '';

        if (storedWatermarks) {
            storedWatermarks.forEach((watermark, index) => {
                data += `
                    <div class="box-list text-center col-sm-2 d-flex d-flex align-items-center">
                        <p class="btn-add-list" data-id="${watermark.id}" style="cursor: pointer;">
                            custom <br> watermark ${watermark.id}
                        </p>
                    </div>
                `;
            });

            data += `
                <div class="box-list text-center col-sm-2 d-flex d-flex align-items-center justify-content-center">
                    <p class="default-positioning" data-id="center" style="cursor: pointer;">
                        Type 1 <br> watermark 
                    </p>
                </div>
            `;

            data += `
                <div class="box-list text-center col-sm-2 d-flex d-flex align-items-center justify-content-center">
                    <p class="default-positioning" data-id="corners" style="cursor: pointer; text-align: center;">
                        Type 2 <br> watermark 
                    </p>
                </div>
            `;

            data += `
                <div class="box-list text-center col-sm-2 d-flex d-flex align-items-center justify-content-center">
                    <p class="default-positioning" data-id="fullCoverage" style="cursor: pointer;">
                        Type 3 <br> watermark 
                    </p>
                </div>
            `;

            document.querySelector('#listItemImg').innerHTML = data;
        }


        document.querySelectorAll(".default-positioning").forEach(button => {
            button.addEventListener('click', async (e) => {

                if (file_error == 0) {
                    alert("Upload first image before adding watermarks.");
                    return;
                }

                const positioning = e.target.getAttribute('data-id');

                positionData = positioning;


                redrawCanvas(positionData);
            });
        });


        // Add event listeners to each button after rendering
        document.querySelectorAll('.btn-add-list').forEach(button => {
            button.addEventListener('click', async (e) => {
                const id = e.target.getAttribute('data-id');

                if (file_error == 0) {
                    alert("Upload first image before adding watermarks.");
                    return;
                }

                // Retrieve existing data from LocalStorage
                let existingData = localStorage.getItem('tbl_generate_img');
                let watermarksArray = existingData ? JSON.parse(existingData) : [];

                // Filter the array to remove the item with the specified ID
                watermarksArray = watermarksArray.filter(watermark => watermark.id !== parseInt(id));

                // Ensure there is at least one watermark left
                if (watermarksArray.length === 0) {
                    console.error("No watermarks left after filtering.");
                    return;
                }

                // Log the first watermark for debugging
                console.log(watermarksArray[0]);

                // Add img watermarks
                watermarkImg.src = watermarksArray[0].image; // Ensure image is correctly formatted
                watermarkImg.onload = () => {
                    showWatermarked = true;
                    redrawCanvas(positionData);
                };

                // Add alpha percent
                const specificPercent = watermarksArray[0].opacity; // Ensure opacity is correctly defined
                percentageElement.textContent = `${specificPercent}%`;
                progressElement.style.width = `${specificPercent}%`;
            });
        });
    };
/////////////////////////////////////////////////////// watermark img testing ground

    getAllWatermarksWithText();


    const fonts_items = [];

    document.getElementById('fontButton').addEventListener('click', () => {
        const modal = document.getElementById('fontModal');
        const overlay = document.getElementById('overlay');
        modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
        overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';

        // Populate the font dropdown
        const select = document.getElementById('fontStyle');
        select.innerHTML = '<option value="">-- Choose a font --</option>'; // Reset options
        fonts_items.forEach(font => {
            const option = document.createElement('option');
            option.value = font;
            option.textContent = font;
            select.appendChild(option);
        });
    });

    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('fontModal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    });

    // Close modal when clicking on overlay
    document.getElementById('overlay').addEventListener('click', () => {
        document.getElementById('fontModal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    });


    async function loadFontsEditorView(fonts) {
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

    async function loadFontListEditorView() {
        const storedWatermarks = JSON.parse(localStorage.getItem('tbl_font'));
        const fontList = document.getElementById("fontList");

        if (!fontList) {
            console.error("Font list element not found!");
            return;
        }

        fontList.innerHTML = '';

        await loadFontsEditorView(storedWatermarks); // Load fonts first

        if (storedWatermarks) {
            storedWatermarks.forEach(async (font, index) => {

                const fontName = font.fontFile.replace(/\.[^/.]+$/, ""); // Cleaned font name

                fonts_items.push(fontName);
            });
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => {
            loadFontListEditorView();
        }, 1000);
    });
</script>