<?php
if (isset($_POST["agregar-marketing"])) {
    $config->set("googleDataStudioId", isset($_POST["m-google-id"]) ? $funciones->antihack_mysqli($_POST["m-google-id"]) : '');
    $config->set("googleAnalytics", isset($_POST["m-google-analytics"]) ? $funciones->antihack_mysqli($_POST["m-google-analytics"]) : '');
    $config->set("hubspot", isset($_POST["m-hubspot"]) ? $funciones->antihack_mysqli($_POST["m-hubspot"]) : '');
    $config->set("mailrelay", isset($_POST["m-mailrelay"]) ? $funciones->antihack_mysqli($_POST["m-mailrelay"]) : '');
    $config->set("onesignal", isset($_POST["m-onesignal"]) ? $funciones->antihack_mysqli($_POST["m-onesignal"]) : '');
    $config->set("facebookPixel", isset($_POST["m-facebook-pixel"]) ? $funciones->antihack_mysqli($_POST["m-facebook-pixel"]) : '');
    $config->set("facebookAccessToken", isset($_POST["m-facebook-access-token"]) ? $funciones->antihack_mysqli($_POST["m-facebook-access-token"]) : '');
    $error = $config->addMarketing();
    if ($error) {
        $funciones->headerMove(URL_ADMIN . '/index.php?op=configuracion&accion=modificar&tab=marketing-tab');
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
    }
}
?>
<div>
    <form method="post" action="<?= URL_ADMIN ?>/index.php?op=configuracion&accion=modificar&tab=marketing-tab">
        <div class="row">
            <label class="col-md-12 mt-10">
                Google Data Studio ID:<br />
                <input type="text" class="form-control" name="m-google-id" value="<?= $marketingData['data']["google_data_studio_id"] ? $marketingData['data']["google_data_studio_id"] : '' ?>" />
            </label>
            <label class="col-md-12 mt-10">
                Google Analytics:<br />
                <input type="text" class="form-control" name="m-google-analytics" value="<?= $marketingData['data']["google_analytics"] ? $marketingData['data']["google_analytics"] : '' ?>" />
            </label>
            <label class="col-md-12 mt-10">
                Hubspot:<br />
                <input type="text" class="form-control" name="m-hubspot" value="<?= $marketingData['data']["hubspot"] ? $marketingData['data']["hubspot"] : '' ?>" />
            </label>
            <label class="col-md-12 mt-10">
                Mailrelay:<br />
                <input type="text" class="form-control" name="m-mailrelay" value="<?= $marketingData['data']["mailrelay"] ? $marketingData['data']["mailrelay"] : '' ?>" />
            </label>
            <label class="col-md-12 mt-10">
                OneSignal:<br />
                <input type="text" class="form-control" name="m-onesignal" value="<?= $marketingData['data']["onesignal"] ? $marketingData['data']["onesignal"] : '' ?>" />
            </label>
            <label class="col-md-12 mt-10">
                Facebook Pixel:<br />
                <input type="text" class="form-control" name="m-facebook-pixel" value="<?= $marketingData['data']["facebook_pixel"] ? $marketingData['data']["facebook_pixel"] : '' ?>" />
            </label>
            <label class="col-md-12 mt-10">
                Facebook Access Token:<br />
                <input type="text" class="form-control" name="m-facebook-access-token" value="<?= $marketingData['data']["facebook_access_token"] ? $marketingData['data']["facebook_access_token"] : '' ?>" />
            </label>
            <div class="col-md-12  mt-20">
                <button class="btn btn-primary btn-block" type="submit" name="agregar-marketing">Guardar cambios</button>
            </div>
        </div>
    </form>

</div>