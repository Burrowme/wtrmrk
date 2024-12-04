<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-1 text-center p-2">
                    <p><a href="#" class="btn btn-success active tab-button" data-tab="tab1">CREATE</a></p>
                    <p><a href="#" class="btn btn-success tab-button" data-tab="tab2">FORTIFY</a></p>
                    <p><a href="#" class="tab-button" data-tab="tab3"><i class="fa-solid fa-gear text-success" style="font-size: 68px;color:#000;"></i></a></p>
                </div>
                <div class="col-md-11">

                    <div id="tab1" class="tab-content active">
                        <?php include('tab1.php'); ?>
                    </div>
                    <div id="tab2" class="tab-content">
                        <?php include('tab2.php'); ?>
                    </div>
                    <div id="tab3" class="tab-content">
                        <?php include('tab3.php'); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to the clicked button and the corresponding content
            button.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
</script>