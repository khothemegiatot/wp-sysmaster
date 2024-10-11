<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("nav-tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" nav-tab-active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " nav-tab-active";
    }
</script>

<style>
    .tab-content {
        margin-top: 20px;
    }
</style>