<style>
    body {
        margin: 20px;
        background: #EEFFF8;
    }

    .view-screen-wt {
        background: #4CAF50;
        height: 626px;
        border-radius: 6px;
        padding: 10px;
    }

    .conatainer-list.mt-4.p-4 {
        height: 300px;
        border: 3px solid #4CAF50;
        border-radius: 6px;
        overflow-x: hidden;
    }

    .box-list.text-center {
        background: #4CAF50;
        width: 120px;
        padding: 10px;
        border-radius: 6px;
    }

    button.btn-remove-list {
        position: relative;
        right: -40px;
        width: 24px;
        height: 26px;
        font-size: 12px;
    }

    .conatainer-list.mt-4.p-4.row {
        gap: 10px;
        justify-content: center;
    }

    .slider-container {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    .percentage {
        margin-left: 10px;
        font-weight: bold;
    }

    .tab-button.active {
        background-color: #010101;
        color: white;
        border-color: #070809;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    div#listItem,
    div#listItemImg {
        max-height: 189px;
    }

    #imageCanvas {
        cursor: grab;
        border: 1px solid black;
    }

    .controls {
        display: flex;
        flex-direction: column;
    }

    .view-screen-wt {
        max-height: 530px;
    }

    div#image-controls {
        overflow-x: scroll;
        max-width: 235px;
        margin: auto;
        position: relative;
        top: 9px;
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        padding-left: 10px;
    }

    #colorButtons {
        margin: 10px 0;
    }

    .form-control {
        border: 2px solid #4CAF50;
    }

    #colorButtons button {
        padding: 5px 5px;
        color: transparent;
        cursor: pointer;
        border: 2px solid white;
        margin: 5px;
        transition: background-color 0.3s;
    }

    #blackButton {
        background-color: black;
    }

    #whiteButton {
        background-color: white;
        color: black;
    }

    #greenButton {
        background-color: #4CAF50;
    }

    .spinner-border.text-dark {
        width: 20px;
        height: 21px;
    }

    /* tab 1 */
    canvas#canvas_img {
        max-width: 473px;
        max-height: 363px;
        width: 100%;
        height: 100%;
        margin: auto;
        display: block;
        position: relative;
        top: 12px;

    }

    div#center-img-ctrl {
        position: relative;
        top: 61px;
    }

    .btn-dl-img {
        display: flex;
        flex-direction: column;
        width: 75px;
        float: right;
        gap: 5px;
    }

    div#slider {
        display: flex;
        justify-content: space-between;
        position: relative;
        top: 237px;
    }

    button#prev {
        position: relative;
        left: -10px;
        z-index: 9999;
    }

    button#next {
        position: relative;
        right: -11px;
        z-index: 9999;
    }

    #fontModal {
        display: none;
        position: fixed;
        top: 50%;
        left: 45%;
        transform: translate(-50%, -50%);
        border: 1px solid #ccc;
        padding: 37px;
        background: white;
        z-index: 9999;
    }

    button#closeModal {
        position: absolute;
        top: 0;
        right: -17px;
        top: -14px;
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    #splash {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
    }

    #loadingBar {
        width: 300px;
        height: 30px;
        border: 3px solid #4CAF50;
        border-radius: 2px;
        overflow: hidden;
        background-color: #e0e0e0;
        position: relative;
    }

    #progress {
        height: 100%;
        width: 0;
        background-color: #4CAF50;
        transition: width 0.2s;
    }

    #percentage {
        position: absolute;
        /* Center the text */
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.2em;
        color: #333;
    }

    /* fonts */
    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: #fff !important;
        background-color: #000 !important
    }

    .nav-link:focus,
    .nav-link {
        color: #000 !important
    }

    #fileList {
        margin-top: 10px;
        width: 80%;
    }

    #fileList li {
        list-style-type: none;
        margin: 5px 0;
    }

    ul#fontList span {
        font-size: 26px;
    }

    ul#fontList li {
        display: flex;
        justify-content: space-between;
        gap: 52px;
    }

    ul#fontList {
        padding: 0px;
    }

    #fontList {
        display: flex;
        /* Use flexbox for layout */
        flex-wrap: wrap;
        /* Allow wrapping of items */
        list-style-type: none;
        /* Remove default list styling */
        padding: 0;
        /* Remove padding */
    }

    #fontList li {
        flex: 0 0 50%;
        /* Each item takes up 50% of the row (2 columns) */
        box-sizing: border-box;
        /* Include padding in width calculation */
        padding: 10px;
        /* Add some padding */
        display: flex;
        /* Allow flex properties for inner elements */
        justify-content: space-between;
        /* Space between text and button */
    }

    button.btn-remove-list {
        background: transparent;
        border-color: transparent;
        border-radius: 6px;
        font-size: 15px;
        font-weight: bold;
    }

    button.btn-remove-list:hover {
        font-size: 16px;
    }

    .box-list.text-center.col-sm-2 p {
        margin-top: 10px;
    }

    button#closeModal {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
        border-radius: 50%;
        height: 33px;
        width: 36px;
    }

    button#closeModal:hover {
        font-size: 12px;
    }
</style>