<div id="adobe-dc-view" style="width: 100%; height: 600px;"></div>
<script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
<script type="text/javascript">
    // https://console.adobe.io/home
    document.addEventListener("adobe_dc_view_sdk.ready", function(){
        var adobeDCView = new AdobeDC.View({clientId: "f51f8e3bd64c4de386b4ac5e5122a348", divId: "adobe-dc-view",locale: "fr-FR"});
        adobeDCView.previewFile({
            content:{location: {url: "<?php echo $path; ?>"}},
            metaData:{fileName: "<?php echo $book; ?>"}
        }, { dockPageControls: true, showDownloadPDF: false});
    });
</script>