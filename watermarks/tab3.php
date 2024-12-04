<div class="d-flex align-items-start" style="padding: 35px;padding-top: 0px!important;">
    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <button class="nav-link active" id="v-pills-help-tab" data-bs-toggle="pill" data-bs-target="#v-pills-help" type="button" role="tab" aria-controls="v-pills-help" aria-selected="true">Help</button>
        <button class="nav-link" id="v-pills-fonts-tab" data-bs-toggle="pill" data-bs-target="#v-pills-fonts" type="button" role="tab" aria-controls="v-pills-fonts" aria-selected="false">Fonts</button>
        <button class="nav-link" id="v-pills-about-tab" data-bs-toggle="pill" data-bs-target="#v-pills-about" type="button" role="tab" aria-controls="v-pills-about" aria-selected="false">About</button>
    </div>
    <div class="tab-content" style="display: block;" id="v-pills-tabContent" style="padding: 47px;">
        <div class="tab-pane fade show active" id="v-pills-help" role="tabpanel" aria-labelledby="v-pills-help-tab" style="padding: 35px;padding-top: 0px!important;">
            <div class="card">
                <div class="card-body P-3">
                    <?php include('settings/help.php'); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="v-pills-fonts" role="tabpanel" aria-labelledby="v-pills-fonts-tab" style="padding: 35px;padding-top: 0px!important;">
            <div class="card">
                <div class="card-body P-3">
                    <?php include('settings/font.php'); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="v-pills-about" role="tabpanel" aria-labelledby="v-pills-about-tab" style="padding: 35px;padding-top: 0px!important;">
            <div class="card">
                <div class="card-body P-3">
                    <?php include('settings/about.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>